<script>
    import { onMount, tick } from "svelte";
    import ProcessSkeleton from "../Shared/Skeletons/ProcessSkeleton.svelte";
    let loading = true;
    let title = "Hallo";
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
            class="text-3xl md:text-4xl font-bold mb-6 text-center tracking-wide drop-shadow"
        >
            📸 Photobooth
        </h1>

        <h1>{title}</h1>
    {/if}
</div>
