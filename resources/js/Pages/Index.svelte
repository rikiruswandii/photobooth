<script>
    import { onMount } from "svelte";
    import { fade } from "svelte/transition";
    import { flip } from "svelte/animate";
    import HomeSkeleton from "../Shared/Skeletons/HomeSkeleton.svelte";
    import SutterSoundFile from "../../assets/sounds/camera-shutter-199580.mp3";
    import ClockSoundFile from "../../assets/sounds/slow-cinematic-clock-ticking-tension-323080.mp3";

    let loading = true;

    let errorMessage = '';

    let video;
    let canvas;
    let images = [];

    let frames = [1, 2, 3, 4];
    let selectedFrame = 1;

    $: if (selectedFrame !== null) {
        console.log("Frame yang dipilih:", selectedFrame);
    }

    function startCamera() {
        navigator.mediaDevices
            .getUserMedia({ video: true, audio: false })
            .then((stream) => {
                video.srcObject = stream;
                video.play();
                errorMessage = '';
            })
            .catch((err) => {
                console.error("Gagal mengakses kamera:", err);
                errorMessage = 'Kamera tidak tersedia atau akses ditolak.';
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

    async function captureImage() {
        showCountdown = true;

        // 🔁 Hitung mundur
        for (let i = defaultCountdown; i > 0; i--) {
            currentCountdown = i;

            // 🔊 Atur ulang dan mainkan suara countdown dari awal
            countdownSound.pause();
            countdownSound.currentTime = 0;
            countdownSound.play();

            await new Promise((res) => setTimeout(res, 1000));
        }

        // 🛑 Hentikan suara countdown saat selesai
        countdownSound.pause();
        countdownSound.currentTime = 0;

        showCountdown = false;

        // 🔊 Suara shutter
        shutterSound.currentTime = 0;
        await shutterSound.play();

        // 📸 Ambil gambar
        const context = canvas.getContext("2d");
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0);

        const img = new Image();
        img.src = canvas.toDataURL("image/png");
        images = [...images, img.src].slice(-4);
    }

    onMount(() => {
        setTimeout(() => {
            loading = false;
            startCamera();
        }, 1500);
        return () => {
            if (video && video.srcObject) {
                video.srcObject.getTracks().forEach((track) => track.stop());
            }
        };
    });
</script>

<div class="min-h-screen bg-gradient-to-br from-purple-500 via-pink-400 to-blue-500 flex flex-col items-center justify-center p-6 text-white font-sans">
    {#if loading}
        <HomeSkeleton />
    {:else}
        <h1 class="text-3xl md:text-4xl font-bold mb-6 text-center tracking-wide drop-shadow">
            📸 Photobooth
        </h1>

        <div class="flex justify-start items-start gap-4 mb-4">
            <!-- Dropdown Frame -->
            <select
                id="frame"
                name="frame"
                bind:value={selectedFrame}
                class="block w-full rounded border-gray-300 shadow-sm focus:ring-amber-500 focus:border-amber-500 h-10 bg-white text-black"
            >
                {#each frames as frame}
                    <option value={frame}>Frame {frame}</option>
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

        <div class="flex flex-col md:flex-row gap-8 w-full max-w-5xl">
            <!-- Kamera -->
            <div class="flex-1 space-y-4">
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

                <canvas bind:this={canvas} class="hidden"></canvas>

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
            <div
                class={images.length === 0
                    ? "hidden"
                    : "flex-1 transition-width duration-300 ease"}
            >
                <div
                    class={images.length === 0
                        ? "hidden"
                        : "grid grid-cols-1 gap-4 bg-white/30 backdrop-blur-md rounded-xl p-4 shadow-xl border border-white/40"}
                >
                    {#each images as img (img)}
                        <div animate:flip>
                            <img
                                src={img}
                                alt="Captured"
                                class="w-1/2 mx-auto rounded-lg shadow-lg transform transition duration-500 hover:scale-105 hover:shadow-2xl"
                                transition:fade={{ duration: 500 }}
                            />
                        </div>
                    {/each}
                </div>
            </div>
        </div>
    {/if}
</div>
