<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.index.add-address')
    </x-slot>
    
    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="addresses" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="flex-1 min-w-0 w-full">
        <div class="flex items-center justify-between border-b border-[#ede8dc] pb-5">
            <div class="flex items-center gap-3">
                <!-- Back Button -->
                <a
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-[#ede8dc] bg-white text-[#18181b] hover:border-[#9e8464] hover:bg-[#fdfbf7] md:hidden shadow-xs transition-colors"
                    href="{{ route('shop.customers.account.index') }}"
                >
                    <span class="icon-arrow-left rtl:icon-arrow-right text-lg"></span>
                </a>
    
                <h1 class="font-serif text-2xl font-bold tracking-tight text-[#18181b] md:text-3xl">
                    @lang('shop::app.customers.account.addresses.index.title')
                </h1>
            </div>

            <a
                href="{{ route('shop.customers.account.addresses.create') }}"
                class="inline-flex items-center justify-center rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold tracking-wide text-white transition-all hover:bg-[#9e8464] shadow-xs active:scale-[0.99]"
            >
                @lang('shop::app.customers.account.addresses.index.add-address') 
            </a>
        </div>

        @if (! $addresses->isEmpty())
            <!-- Address Information -->

            {!! view_render_event('bagisto.shop.customers.account.addresses.list.before', ['addresses' => $addresses]) !!}

            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach ($addresses as $address)
                    <div class="rounded-2xl border border-[#ede8dc] bg-white p-6 shadow-xs transition-all hover:border-[#9e8464] flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="font-serif text-lg font-bold text-[#18181b]" v-pre>
                                        {{ $address->first_name }} {{ $address->last_name }}

                                        @if ($address->company_name)
                                            <span class="text-sm font-normal text-[#71717a]">({{ $address->company_name }})</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="flex items-center gap-3">
                                    @if ($address->default_address)
                                        <span class="inline-flex items-center rounded-full bg-[#9e8464]/10 px-3 py-1 text-xs font-semibold text-[#9e8464] border border-[#9e8464]/30">
                                            @lang('shop::app.customers.account.addresses.index.default-address') 
                                        </span>
                                    @endif

                                    <!-- Dropdown Actions -->
                                    <x-shop::dropdown position="bottom-{{ core()->getCurrentLocale()->direction === 'ltr' ? 'right' : 'left' }}">
                                        <x-slot:toggle>
                                            <button 
                                                class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-transparent text-[#71717a] transition-all hover:border-[#ede8dc] hover:bg-[#fdfbf7] hover:text-[#18181b]" 
                                                aria-label="More Options"
                                            >
                                                <span class="icon-more text-xl"></span>
                                            </button>
                                        </x-slot>

                                        <x-slot:menu class="rounded-xl border border-[#ede8dc] bg-white p-1 shadow-lg">
                                            <x-shop::dropdown.menu.item class="rounded-lg hover:bg-[#fdfbf7]">
                                                <a href="{{ route('shop.customers.account.addresses.edit', $address->id) }}" class="block w-full px-3 py-1.5 text-xs font-medium text-[#18181b]">
                                                    @lang('shop::app.customers.account.addresses.index.edit')
                                                </a>    
                                            </x-shop::dropdown.menu.item>

                                            <x-shop::dropdown.menu.item class="rounded-lg hover:bg-[#fdfbf7]">
                                                <form
                                                    method="POST"
                                                    ref="addressDelete"
                                                    action="{{ route('shop.customers.account.addresses.delete', $address->id) }}"
                                                >
                                                    @method('DELETE')
                                                    @csrf
                                                </form>

                                                <button 
                                                    type="button"
                                                    class="block w-full px-3 py-1.5 text-left text-xs font-medium text-red-600 hover:text-red-700 cursor-pointer"
                                                    @click="$emitter.emit('open-confirm-modal', {
                                                        agree: () => {
                                                            $refs['addressDelete'].submit()
                                                        }
                                                    })"
                                                >
                                                    @lang('shop::app.customers.account.addresses.index.delete')
                                                </button>
                                            </x-shop::dropdown.menu.item>

                                            @if (! $address->default_address)
                                                <x-shop::dropdown.menu.item class="rounded-lg hover:bg-[#fdfbf7]">
                                                    <form
                                                        method="POST"
                                                        ref="setAsDefault"
                                                        action="{{ route('shop.customers.account.addresses.update.default', $address->id) }}"
                                                    >
                                                        @method('PATCH')
                                                        @csrf

                                                    </form>

                                                    <button 
                                                        type="button"
                                                        class="block w-full px-3 py-1.5 text-left text-xs font-medium text-[#9e8464] hover:text-[#18181b] cursor-pointer"
                                                        @click="$emitter.emit('open-confirm-modal', {
                                                            agree: () => {
                                                                $refs['setAsDefault'].submit()
                                                            }
                                                        })"
                                                    >
                                                        @lang('shop::app.customers.account.addresses.index.set-as-default')
                                                    </button>
                                                </x-shop::dropdown.menu.item>
                                            @endif
                                        </x-slot>
                                    </x-shop::dropdown>
                                </div>
                            </div>

                            <p class="mt-4 text-sm leading-relaxed text-[#71717a]" v-pre>
                                {{ $address->address }}<br>
                                {{ $address->city }}, {{ $address->state }}, {{ $address->country }}<br>
                                <span class="font-medium text-[#18181b]">{{ $address->postcode }}</span>
                            </p>
                        </div>
                    </div>    
                @endforeach
            </div>

            {!! view_render_event('bagisto.shop.customers.account.addresses.list.after', ['addresses' => $addresses]) !!}

        @else
            <!-- Address Empty Page -->
            <div class="m-auto grid w-full place-content-center items-center justify-items-center py-24 text-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-[#fdfbf7] border border-[#ede8dc] text-[#9e8464] mb-4">
                    <span class="icon-location text-3xl"></span>
                </div>
                
                <h3 class="font-serif text-xl font-bold text-[#18181b]">
                    @lang('shop::app.customers.account.addresses.index.empty-address')
                </h3>

                <p class="mt-1 text-sm text-[#71717a] max-w-sm">
                    Agrega tu primera dirección para agilizar tus futuras compras.
                </p>

                <a
                    href="{{ route('shop.customers.account.addresses.create') }}"
                    class="mt-6 inline-flex items-center justify-center rounded-full bg-[#18181b] px-6 py-2.5 text-xs font-semibold tracking-wide text-white transition-all hover:bg-[#9e8464] shadow-xs"
                >
                    @lang('shop::app.customers.account.addresses.index.add-address') 
                </a>
            </div>    
        @endif
    </div>
</x-shop::layouts.account>
