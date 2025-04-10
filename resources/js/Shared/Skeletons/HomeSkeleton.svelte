<!-- HomeSkeleton.svelte -->
<div class="w-full max-w-5xl space-y-6 animate-pulse">
    <!-- Judul -->
    <!-- <div class="h-10 bg-white/50 rounded w-1/2 mx-auto"></div> -->

    <!-- Dropdown Skeleton -->
    <div class="flex gap-4">
        <div class="h-10 w-full bg-white/40 rounded"></div>
        <div class="h-10 w-full bg-white/40 rounded"></div>
    </div>

    <!-- Konten utama: kamera & galeri -->
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Kamera -->
        <div class="flex-1 space-y-4">
            <div class="w-full aspect-video bg-white/40 rounded-xl shadow-2xl border-4 border-white/20"></div>
            <div class="h-12 w-40 bg-white/60 mx-auto rounded-full"></div>
        </div>

        <!-- Galeri -->
        <!-- <div class="flex-1 grid grid-cols-1 gap-4 bg-white/30 backdrop-blur-md rounded-xl p-4 shadow-xl border border-white/40">
            {#each Array(3) as _}
                <div class="w-1/2 mx-auto h-32 bg-white/50 rounded-lg shadow"></div>
            {/each}
        </div> -->
    </div>
</div>
