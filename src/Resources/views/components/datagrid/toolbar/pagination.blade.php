<x-shop::dropdown position="bottom-left">
    <x-slot:toggle>
        <button class="flex w-full min-w-[76px] cursor-pointer items-center justify-between gap-3 rounded-full border border-[#ede8dc] bg-white px-4 py-2 text-xs font-semibold text-[#18181b] transition-all hover:border-[#9e8464] hover:bg-[#fdfbf7] focus:border-[#9e8464] focus:outline-hidden shadow-xs">
            <span v-text="applied.pagination.perPage"></span>

            <span class="icon-arrow-down text-lg text-[#71717a]"></span>
        </button>
    </x-slot>

    <x-slot:menu class="max-md:py-0! rounded-xl border border-[#ede8dc] bg-white p-1 shadow-lg">
        <x-shop::dropdown.menu.item
            v-for="perPageOption in available.meta.per_page_options"
            v-text="perPageOption"
            @click="changePerPageOption(perPageOption)"
            class="rounded-lg px-3 py-1.5 text-xs text-[#18181b] hover:bg-[#fdfbf7] hover:text-[#9e8464] transition-colors cursor-pointer"
        />
    </x-slot>
</x-shop::dropdown>
