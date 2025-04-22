<script>
    import { onMount, tick } from "svelte";
    import ProcessSkeleton from "../Shared/Skeletons/ProcessSkeleton.svelte";
    let loading = true;

    export let images = [];
    console.log(images);
    onMount(async () => {
        setTimeout(async () => {
            loading = false;
            await tick();
        }, 1500);
    });
</script>

<div
    class="relative min-h-screen bg-gradient-to-b from-black via-indigo-900 to-black flex flex-col items-center justify-center p-6 overflow-hidden text-white font-sans"
>
    <!-- Lapisan bintang -->
    <div
        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-20 mix-blend-screen"
    ></div>

    <!-- Lapisan nebula -->
    <div
        class="absolute w-[60vw] h-[60vw] top-1/4 left-1/4 bg-gradient-to-br from-purple-500 via-pink-500 to-transparent rounded-full blur-3xl opacity-20 mix-blend-screen animate-pulse"
    ></div>

    {#if loading}
        <ProcessSkeleton />
    {:else}
        <h1
            class="text-3xl md:text-4xl font-bold mb-8 text-center tracking-wide drop-shadow text-white"
        >
            📸 Photobooth
        </h1>

        <div class="grid grid-cols-5 gap-4 w-full max-w-7xl mx-auto">
            <div class="col-span-2 space-y-4">
                <div
                    class="w-full aspect-video bg-white/40 rounded-xl shadow-2xl border-4 border-white/20"
                >
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
                                    </div>
                                {/each}
                            </div>
                        </div>
                    {/if}
                    <h6 class="text-center">photobooth</h6>
                </div>
            </div>
            <div class="col-span-3 space-y-4">
                <div
                    class="w-full aspect-video bg-white/40 rounded-xl shadow-2xl border-4 border-white/20"
                ></div>
                <div class="flex gap-4">
                    <div class="h-10 w-full bg-white/40 rounded"></div>
                    <div class="h-10 w-full bg-white/40 rounded"></div>
                </div>
            </div>
        </div>
    {/if}
</div>
