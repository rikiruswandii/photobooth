<script>
    export let selectedFilter = 'none';
    const filters = [
        { name: "Normal", value: "none" },
        { name: "Grayscale", value: "grayscale(100%)" },
        { name: "Sepia", value: "sepia(100%)" },
        { name: "Invert", value: "invert(100%)" },
        { name: "Brightness+", value: "brightness(150%)" },
        { name: "Contrast+", value: "contrast(150%)" },
        { name: "Blur", value: "blur(2px)" },
    ];
</script>

<div class="flex gap-2 mt-4 flex-wrap">
    {#each filters as filter}
        <button
            on:click={() => (selectedFilter = filter.value)}
            class="px-3 py-1 text-sm rounded border transition
                {selectedFilter === filter.value ? 'bg-pink-500 text-white border-pink-500' : 'hover:bg-pink-500 hover:text-white'}"
        >
            {filter.name}
        </button>
    {/each}
</div>

