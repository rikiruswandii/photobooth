<script>
    import { onMount, tick } from "svelte";
    import { fade } from "svelte/transition";
    import { flip } from "svelte/animate";
    import { FontAwesomeIcon } from "@fortawesome/svelte-fontawesome";
    import HomeSkeleton from "../Shared/Skeletons/HomeSkeleton.svelte";
    import SutterSoundFile from "../../assets/sounds/camera-shutter-199580.mp3";
    import ClockSoundFile from "../../assets/sounds/slow-cinematic-clock-ticking-tension-323080.mp3";
    import { faCamera } from "@fortawesome/free-solid-svg-icons";

    let loading = true;

    let errorMessage = "";

    let video;
    let canvas;
    let images = [];

    let photoLayouts = [
        { label: "2 Foto (Horizontal)", count: 2 },
        { label: "3 Foto (Vertical)", count: 3 },
        { label: "4 Foto (Vertical)", count: 4 },
        { label: "4 Foto (2x2 Grid)", count: 4 },
    ];
    let selectedLayout = photoLayouts[0];
    $: maxImages = selectedLayout.count;

    function startCamera() {
        navigator.mediaDevices
            .getUserMedia({ video: true, audio: false })
            .then((stream) => {
                console.log("Stream berhasil:", stream);
                video.srcObject = stream;
                video.play();
                errorMessage = "";
            })
            .catch((err) => {
                console.error("Gagal mengakses kamera:", err);
                errorMessage = "Kamera tidak tersedia atau akses ditolak.";
            });
    }

    let defaultCountdown;
    let delayes = [3, 5, 10];
    let selectedDelay = 3;

    $: defaultCountdown = selectedDelay;

    let currentCountdown = defaultCountdown;
    let showCountdown = false;
    let shutterSound = new Audio(SutterSoundFile);
    let countdownSound = new Audio(ClockSoundFile);

    function playSound(audio) {
        audio.pause();
        audio.currentTime = 0;
        try {
            audio.play();
        } catch (e) {
            console.warn("Gagal memutar audio", e);
        }
    }

    async function captureImage(indexToReplace = null) {
        if (typeof indexToReplace !== "number") {
            indexToReplace = null;
        }
        console.log("Memulai proses pengambilan gambar");
        if (indexToReplace === null && images.length > 0) {
            const confirmClear = window.confirm(
                "Foto yang sudah diambil akan hilang. Lanjutkan?",
            );
            console.log(
                "Konfirmasi untuk menghapus gambar yang ada:",
                confirmClear,
            );
            if (!confirmClear) return;
            images = []; // reset gambar
            console.log("Gambar direset");
        }

        // Pastikan canvas dan video ada dan terpasang
        if (!canvas || !video) {
            console.error("Canvas atau Video tidak ditemukan");
            return;
        }

        // Jika ada indexToReplace, kita hanya akan mengganti gambar pada index tersebut
        if (indexToReplace !== null) {
            console.log(`Mengganti gambar pada indeks: ${indexToReplace}`);
            showCountdown = true;

            for (let j = defaultCountdown; j > 0; j--) {
                currentCountdown = j;
                console.log("Countdown:", currentCountdown);
                playSound(countdownSound);
                await new Promise((res) => setTimeout(res, 1000));
            }

            countdownSound.pause();
            countdownSound.currentTime = 0;
            showCountdown = false;

            playSound(shutterSound);

            const context = canvas.getContext("2d");
            if (!context) {
                console.error("Gagal mendapatkan context canvas");
                return;
            }

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0);

            const newImage = canvas.toDataURL("image/png");

            // Ganti gambar pada indeks tertentu
            console.log("Gambar baru yang diambil:", newImage);
            images[indexToReplace] = newImage;
        } else {
            console.log("Proses mengambil gambar baru dimulai...");
            // Proses untuk mengambil gambar baru jika tidak ada indexToReplace
            for (let i = 0; i < maxImages; i++) {
                console.log(`Mengambil gambar ${i + 1} dari ${maxImages}`);
                showCountdown = true;

                for (let j = defaultCountdown; j > 0; j--) {
                    currentCountdown = j;
                    console.log("Countdown:", currentCountdown);
                    playSound(countdownSound);
                    await new Promise((res) => setTimeout(res, 1000));
                }

                countdownSound.pause();
                countdownSound.currentTime = 0;
                showCountdown = false;

                playSound(shutterSound);

                const context = canvas.getContext("2d");
                if (!context) {
                    console.error("Gagal mendapatkan context canvas");
                    return;
                }

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0);

                const newImage = canvas.toDataURL("image/png");

                // Tambahkan gambar ke array
                console.log("Captured image:", newImage);
                images = [...images, newImage]; // tambahkan gambar ke array

                // Tambahkan jeda antar foto (misalnya 500ms) sebelum mengambil foto berikutnya
                await new Promise((res) => setTimeout(res, 500));
            }
        }
    }

    onMount(async () => {
        setTimeout(async () => {
            loading = false;
            await tick();
            startCamera();
        }, 1500);
        return () => {
            if (video && video.srcObject) {
                video.srcObject.getTracks().forEach((track) => track.stop());
            }
        };
    });
</script>

<div
    class="min-h-screen bg-gradient-to-br from-purple-500 via-pink-400 to-blue-500 flex flex-col items-center justify-center p-6 text-white font-sans"
>
    {#if loading}
        <HomeSkeleton />
    {:else}
        <h1
            class="text-3xl md:text-4xl font-bold mb-6 text-center tracking-wide drop-shadow"
        >
            📸 Photobooth
        </h1>

        <div class="flex justify-start items-start gap-4 mb-4">
            <!-- Ganti dropdown frame -->
            <select
                id="layout"
                name="layout"
                bind:value={selectedLayout}
                class="block w-full rounded border-gray-300 shadow-sm focus:ring-amber-500 focus:border-amber-500 h-10 bg-white text-black"
            >
                {#each photoLayouts as layout}
                    <option value={layout}>{layout.label}</option>
                {/each}
            </select>

            <!-- Dropdown Delay -->
            <select
                id="delay"
                name="delay"
                bind:value={selectedDelay}
                class="block w-full rounded border-gray-300 shadow-sm focus:ring-amber-500 focus:border-amber-500 h-10 bg-white text-black"
            >
                {#each delayes as delay}
                    <option value={delay}>delay {delay}</option>
                {/each}
            </select>
        </div>

        <div class="grid grid-cols-5 gap-4 w-full max-w-7xl mx-auto">
            <!-- Spacer kiri -->
            <div></div>

            <!-- Kamera -->
            <div class="col-span-3 space-y-4">
                <div class="relative w-full aspect-video">
                    <video
                        bind:this={video}
                        autoplay
                        playsinline
                        class="rounded-xl shadow-2xl w-full h-full object-cover border-4 border-white/20"
                    >
                        <track kind="captions" />
                    </video>

                    {#if showCountdown}
                        <div
                            class="absolute inset-0 flex items-center justify-center bg-black/25 z-10 text-white text-6xl font-bold rounded-xl"
                        >
                            {currentCountdown}
                        </div>
                    {/if}

                    {#if errorMessage}
                        <div
                            class="absolute inset-0 flex items-center justify-center bg-black/25 z-10 text-white text-2xl font-bold rounded-xl"
                        >
                            {errorMessage}
                        </div>
                    {/if}
                </div>

                <canvas bind:this={canvas} aria-hidden="true" class="hidden"
                ></canvas>

                <div class="flex justify-center">
                    <button
                        on:click={captureImage}
                        class="bg-white text-purple-700 font-semibold px-6 py-3 rounded-full shadow hover:shadow-xl hover:scale-105 transform transition-all duration-300"
                    >
                        📷 Ambil Foto
                    </button>
                </div>
            </div>

            <!-- Galeri Foto -->
            {#if images.length > 0}
                <div class="space-y-4 transition-width duration-300">
                    <div
                        class="grid grid-cols-1 gap-4 bg-white/30 backdrop-blur-md rounded-xl p-4 shadow-xl border border-white/40"
                    >
                        {#each images as img, index (img)}
                            <div animate:flip class="relative group">
                                <img
                                    src={img}
                                    alt="Captured"
                                    class="w-1/2 mx-auto rounded-lg shadow-lg hover:shadow-2xl"
                                    transition:fade={{ duration: 500 }}
                                />

                                <!-- Wrapper absolute tengah -->
                                <button
                                    on:click={captureImage(index)}
                                    class="absolute cursor-pointer inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/30 rounded-lg"
                                >
                                    <FontAwesomeIcon
                                        icon={faCamera}
                                        class="text-white text-2xl"
                                    />
                                </button>
                            </div>
                        {/each}
                    </div>
                </div>
            {/if}
        </div>
    {/if}
</div>
