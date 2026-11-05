<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.orders.title')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="orders" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="flex-1 min-w-0 w-full">
        <div class="mb-6 flex items-center justify-between border-b border-[#ede8dc] pb-5">
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7] md:hidden shadow-xs transition-colors"
                    href="{{ route('shop.customers.account.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-lg"></span>
                </a>

                <h1 class="font-serif text-2xl font-bold tracking-tight text-[#18181b] md:text-3xl">
                    @lang('shop::app.customers.account.orders.title')
                </h1>
            </div>
        </div>

        {!! view_render_event('bagisto.shop.customers.account.orders.list.before') !!}

        <!-- For Desktop View -->
        <div class="max-md:hidden">
            <x-shop::datagrid
                :src="route('shop.customers.account.orders.index')"
                :shimmerColumns="5"
            />
        </div>

        <!-- For Mobile View -->
        <div class="md:hidden">
            <x-shop::datagrid :src="route('shop.customers.account.orders.index')">
                <!-- Datagrid Header -->
                <template #header="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <div class="hidden"></div>
                </template>

                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-shop::shimmer.customers.account.orders />
                    </template>
    
                    <template v-else>
                        <template v-for="record in available.records">
                            <div class="mb-4 w-full rounded-2xl border border-[#ede8dc] bg-white p-5 shadow-xs transition-all hover:border-[#9e8464] last:mb-0">
                                <a :href="record.actions[0].url">
                                    <div class="flex items-start justify-between">
                                        <div class="text-sm font-semibold text-[#18181b]">
                                            @lang('shop::app.customers.account.orders.order-id'): #@{{ record.increment_id }}
    
                                            <p class="mt-1 text-xs font-normal text-[#71717a]">
                                                @{{ record.created_at }}
                                            </p>
                                        </div>
    
                                        <div v-html="record.status"></div>
                                    </div>
        
                                    <div class="mt-4 flex items-baseline justify-between border-t border-[#ede8dc] pt-3 text-xs font-normal text-[#71717a]">
                                        <span>@lang('shop::app.customers.account.orders.subtotal')</span>
    
                                        <p class="font-serif text-lg font-bold text-[#18181b]">
                                            @{{ record.grand_total }}
                                        </p>
                                    </div>
                                </a>
                            </div>
                        </template>
                    </template>
                </template>
            </x-shop::datagrid>
        </div>
    
        {!! view_render_event('bagisto.shop.customers.account.orders.list.after') !!}
    </div>
</x-shop::layouts.account>
