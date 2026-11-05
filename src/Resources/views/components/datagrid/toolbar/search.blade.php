<v-datagrid-search
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @search="search"
>
    {{ $slot }}
</v-datagrid-search>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-search-template"
    >
        <slot
            name="search"
            :available="available"
            :applied="applied"
            :search="search"
            :get-searched-values="getSearchedValues"
        >
            <template v-if="isLoading">
                <x-shop::shimmer.datagrid.toolbar.search />
            </template>

            <template v-else>
                <div class="flex w-full items-center gap-3">
                    <!-- Search Panel -->
                    <div class="relative w-full max-w-xs sm:max-w-sm">
                        <input
                            type="text"
                            name="search"
                            :value="getSearchedValues('all')"
                            class="w-full rounded-full border border-[#ede8dc] bg-[#fdfbf7] pl-10 pr-4 py-2 text-xs sm:text-sm text-[#18181b] placeholder-[#a1a1aa] transition-all hover:border-[#9e8464]/50 focus:border-[#9e8464] focus:bg-white focus:outline-hidden shadow-xs"
                            placeholder="@lang('shop::app.components.datagrid.toolbar.search.title')"
                            autocomplete="off"
                            @keyup.enter="search"
                        >

                        <div class="icon-search pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-base text-[#a1a1aa]">
                        </div>
                    </div>

                    <!-- Information Panel -->
                    <div class="max-md:hidden">
                        <p class="text-xs font-medium text-[#71717a] whitespace-nowrap">
                            @{{ "@lang('shop::app.components.datagrid.toolbar.results')".replace(':total', available.meta.total) }}
                        </p>
                    </div>
                </div>
            </template>
        </slot>
    </script>

    <script type="module">
        app.component('v-datagrid-search', {
            template: '#v-datagrid-search-template',

            props: ['isLoading', 'available', 'applied'],

            emits: ['search'],

            data() {
                return {
                    filters: {
                        columns: [],
                    },
                };
            },

            mounted() {
                this.filters.columns = this.applied.filters.columns.filter((column) => column.index === 'all');
            },

            methods: {
                /**
                 * Perform a search operation based on the input value.
                 *
                 * @param {Event} $event
                 * @returns {void}
                 */
                search($event) {
                    let requestedValue = $event.target.value;

                    let appliedColumn = this.filters.columns.find(column => column.index === 'all');

                    if (! requestedValue) {
                        appliedColumn.value = [];

                        this.$emit('search', this.filters);

                        return;
                    }

                    if (appliedColumn) {
                        appliedColumn.value = [requestedValue];
                    } else {
                        this.filters.columns.push({
                            index: 'all',
                            value: [requestedValue]
                        });
                    }

                    this.$emit('search', this.filters);
                },

                /**
                 * Get the searched values for a specific column.
                 *
                 * @param {string} columnIndex
                 * @returns {Array}
                 */
                getSearchedValues(columnIndex) {
                    let appliedColumn = this.filters.columns.find(column => column.index === 'all');

                    return appliedColumn?.value ?? [];
                },
            },
        });
    </script>
@endPushOnce
