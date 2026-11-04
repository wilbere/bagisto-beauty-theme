<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex items-start gap-8 lg:gap-10">
        <!-- Desktop Filter Shimmer Effect -->
        <div class="w-64 shrink-0 max-lg:hidden">
            <x-shop::shimmer.categories.filters />
        </div>

        <div class="flex-1">
            <!-- Desktop Toolbar Shimmer Effect -->
            <div class="mb-6 flex items-center justify-between border-b border-[#eae5db]/60 pb-4 max-md:hidden">
                <span class="shimmer h-9 w-36 rounded-full"></span>
                <span class="shimmer h-9 w-28 rounded-full"></span>
            </div>

            <!-- Product Card Grid Shimmer -->
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <x-shop::shimmer.products.cards.grid count="12" />
            </div>
        </div>
    </div>
</div>
