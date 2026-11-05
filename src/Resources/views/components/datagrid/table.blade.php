@props([
    'isMultiRow'     => false,
    'shimmerColumns' => 6,
])

<v-datagrid-table
    :is-loading="isLoading"
    :available="available"
    :applied="applied"
    @selectAll="selectAll"
    @sort="sort"
    @actionSuccess="get"
    @changePage="changePage"
>
    {{ $slot }}
</v-datagrid-table>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-datagrid-table-template"
    >
        <div class="w-full overflow-x-auto rounded-2xl border border-[#ede8dc] bg-white shadow-xs">
            <div class="table-responsive grid w-full overflow-hidden">
                <slot
                    name="header"
                    :is-loading="isLoading"
                    :available="available"
                    :applied="applied"
                    :select-all="selectAll"
                    :sort="sort"
                    :perform-action="performAction"
                >
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.head
                            :columns="$shimmerColumns"
                            :isMultiRow="$isMultiRow"
                        />
                    </template>

                    <template v-else>
                        <div
                            class="row grid items-center gap-2.5 border-b border-[#ede8dc] bg-[#fdfbf7] px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-[#71717a] max-md:p-4"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Mass Actions -->
                            <p v-if="available.massActions.length">
                                <label for="mass_action_select_all_records">
                                    <input
                                        type="checkbox"
                                        name="mass_action_select_all_records"
                                        id="mass_action_select_all_records"
                                        class="peer hidden"
                                        :checked="['all', 'partial'].includes(applied.massActions.meta.mode)"
                                        @change="selectAll"
                                    >

                                    <span
                                        class="icon-uncheck cursor-pointer rounded-md text-2xl text-[#a1a1aa]"
                                        :class="[
                                            applied.massActions.meta.mode === 'all' ? 'peer-checked:icon-check-box peer-checked:text-[#9e8464]' : (
                                                applied.massActions.meta.mode === 'partial' ? 'peer-checked:icon-check-box peer-checked:text-[#9e8464]' : ''
                                            ),
                                        ]"
                                    >
                                    </span>
                                </label>
                            </p>

                            <!-- Columns -->
                            <template v-for="column in available.columns">
                                <p
                                    class="flex items-center gap-1.5 transition-colors hover:text-[#18181b]"
                                    :class="{'cursor-pointer select-none': column.sortable}"
                                    @click="sort(column)"
                                    v-if="column.visibility"
                                >
                                    @{{ column.label }}

                                    <i
                                        class="align-text-bottom text-base text-[#9e8464]"
                                        :class="[applied.sort.order === 'asc' ? 'icon-arrow-down': 'icon-arrow-up']"
                                        v-if="column.index == applied.sort.column"
                                    ></i>
                                </p>
                            </template>

                            <!-- Actions -->
                            <p
                                class="place-self-end text-right max-md:place-self-auto"
                                v-if="available.actions.length"
                            >
                                @lang('shop::app.components.datagrid.table.actions')
                            </p>
                        </div>
                    </template>
                </slot>

                <slot
                    name="body"
                    :is-loading="isLoading"
                    :available="available"
                    :applied="applied"
                    :select-all="selectAll"
                    :sort="sort"
                    :perform-action="performAction"
                >
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.body
                            :columns="$shimmerColumns"
                            :isMultiRow="$isMultiRow"
                        />
                    </template>

                    <template v-else>
                        <template v-if="available.records.length">
                            <div
                                class="row grid items-center gap-2.5 border-b border-[#ede8dc] bg-white px-6 py-4 text-sm font-medium text-[#18181b] transition-all hover:bg-[#fdfbf7]/60 max-md:p-4 max-md:text-xs last:border-b-0"
                                v-for="record in available.records"
                                :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                            >
                                <!-- Mass Actions -->
                                <p v-if="available.massActions.length">
                                    <label :for="`mass_action_select_record_${record[available.meta.primary_column]}`">
                                        <input
                                            type="checkbox"
                                            :name="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                            :value="record[available.meta.primary_column]"
                                            :id="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                            class="peer hidden"
                                            v-model="applied.massActions.indices"
                                        >

                                        <span class="icon-uncheck peer-checked:icon-check-box cursor-pointer rounded-md text-2xl text-[#a1a1aa] peer-checked:text-[#9e8464]">
                                        </span>
                                    </label>
                                </p>

                                <!-- Columns -->
                                <template v-for="column in available.columns">
                                    <p
                                        class="break-words font-medium"
                                        v-html="record[column.index]"
                                        v-if="column.visibility"
                                    >
                                    </p>
                                </template>
                                
                                <!-- Actions -->
                                <p v-if="available.actions.length" class="place-self-end">
                                    <span
                                        class="float-right flex h-8 w-8 cursor-pointer items-center justify-center rounded-full text-lg text-[#71717a] transition-all hover:bg-[#fdfbf7] hover:text-[#9e8464] border border-transparent hover:border-[#ede8dc]"
                                        :class="action.icon"
                                        v-for="action in record.actions"
                                        @click="performAction(action)"
                                    >
                                        @{{ ! action.icon ? action.title : '' }}
                                    </span>
                                </p>
                            </div>
                        </template>

                        <template v-else>
                            <div class="row grid border-b-0 px-6 py-12 text-center text-sm text-[#71717a]">
                                <p>
                                    @lang('shop::app.components.datagrid.table.no-records-available')
                                </p>
                            </div>
                        </template>
                    </template>
                </slot>

                <slot
                    name="footer"
                    :available="available"
                    :applied="applied"
                    :change-page="changePage"
                >
                    <template v-if="isLoading">
                        <x-shop::shimmer.datagrid.table.footer />
                    </template>

                    <template v-else>
                        <!-- Information Panel -->
                        <div v-if="$parent.available.records.length" class="flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-[#ede8dc] bg-[#fdfbf7]/50 px-6 py-4 max-md:p-4">
                            <p class="text-xs font-medium text-[#71717a]">
                                @{{ "@lang('shop::app.components.datagrid.table.showing')".replace(':firstItem', $parent.available.meta.from) }}
                                @{{ "@lang('shop::app.components.datagrid.table.to')".replace(':lastItem', $parent.available.meta.to) }}
                                @{{ "@lang('shop::app.components.datagrid.table.of')".replace(':total', $parent.available.meta.total) }}
                            </p>

                            <!-- Pagination -->
                            <nav aria-label="@lang('shop::app.components.datagrid.table.page-navigation')">
                                <ul class="inline-flex items-center overflow-hidden rounded-full border border-[#ede8dc] bg-white shadow-xs">
                                    <li @click="changePage('previous')">
                                        <a
                                            href="javascript:void(0);"
                                            class="flex h-8 w-8 items-center justify-center text-[#71717a] transition-colors hover:bg-[#fdfbf7] hover:text-[#18181b]"
                                            aria-label="@lang('shop::app.components.datagrid.table.previous-page')"
                                        >
                                            <span class="icon-arrow-left rtl:icon-arrow-right text-lg"></span>
                                        </a>
                                    </li>

                                    <li>
                                        <input
                                            type="text"
                                            :value="$parent.available.meta.current_page"
                                            class="h-8 w-12 border-x border-[#ede8dc] bg-[#fdfbf7] text-center text-xs font-semibold text-[#18181b] focus:outline-hidden"
                                            @change="changePage(parseInt($event.target.value))"
                                            aria-label="@lang('shop::app.components.datagrid.table.page-number')"
                                        >
                                    </li>

                                    <li @click="changePage('next')">
                                        <a
                                            href="javascript:void(0);"
                                            class="flex h-8 w-8 items-center justify-center text-[#71717a] transition-colors hover:bg-[#fdfbf7] hover:text-[#18181b]"
                                            aria-label="@lang('shop::app.components.datagrid.table.next-page')"
                                        >
                                            <span class="icon-arrow-right rtl:icon-arrow-left text-lg"></span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>
                </slot>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-datagrid-table', {
            template: '#v-datagrid-table-template',

            props: ['isLoading', 'available', 'applied'],

            emits: ['selectAll', 'sort', 'actionSuccess', 'changePage'],

            computed: {
                gridsCount() {
                    let count = this.available.columns.filter((column) => column.visibility).length;

                    if (this.available.actions.length) {
                        ++count;
                    }

                    if (this.available.massActions.length) {
                        ++count;
                    }

                    return count;
                },
            },

            methods: {
                /**
                 * Change Page.
                 *
                 * @param {string|integer} directionOrPageNumber
                 * @returns {void}
                 */
                 changePage(directionOrPageNumber) {
                    let newPage;

                    if (typeof directionOrPageNumber === 'string') {
                        if (directionOrPageNumber === 'previous') {
                            newPage = this.available.meta.current_page - 1;
                        } else if (directionOrPageNumber === 'next') {
                            newPage = this.available.meta.current_page + 1;
                        } else {
                            console.warn('Invalid Direction Provided : ' + directionOrPageNumber);

                            return;
                        }
                    }  else if (typeof directionOrPageNumber === 'number') {
                        newPage = directionOrPageNumber;
                    } else {
                        console.warn('Invalid Input Provided: ' + directionOrPageNumber);

                        return;
                    }

                    /**
                     * Check if the `newPage` is within the valid range.
                     */
                    if (newPage >= 1 && newPage <= this.available.meta.last_page) {
                        this.$emit('changePage', newPage);
                    } else {
                        console.warn('Invalid Page Provided: ' + newPage);
                    }
                },

                /**
                 * Select all records in the datagrid.
                 *
                 * @returns {void}
                 */
                selectAll() {
                    this.$emit('selectAll');
                },

                /**
                 * Perform a sorting operation on the specified column.
                 *
                 * @param {object} column
                 * @returns {void}
                 */
                sort(column) {
                    this.$emit('sort', column);
                },

                /**
                 * Perform the specified action.
                 *
                 * @param {object} action
                 * @returns {void}
                 */
                performAction(action) {
                    const method = action.method.toLowerCase();

                    switch (method) {
                        case 'get':
                            window.location.href = action.url;

                            break;

                        case 'post':
                        case 'put':
                        case 'patch':
                        case 'delete':
                            this.$emitter.emit('open-confirm-modal', {
                                agree: () => {
                                    this.$axios[method](action.url)
                                        .then(response => {
                                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                            this.$emit('actionSuccess', response.data);
                                        })
                                        .catch((error) => {
                                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                                            this.$emit('actionError', error.response.data);
                                        });
                                }
                            });

                            break;

                        default:
                            console.error('Method not supported.');

                            break;
                    }
                },
            },
        });
    </script>
@endpushOnce
