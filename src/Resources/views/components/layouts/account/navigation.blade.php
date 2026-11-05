@php
    $customer = auth()->guard('customer')->user();
@endphp

<div class="w-full md:w-80 md:min-w-[280px] lg:min-w-[320px] flex-shrink-0 flex flex-col gap-6">
    <!-- Account Profile Card -->
    <div class="flex items-center gap-4 rounded-2xl border border-[#ede8dc] bg-white p-5 shadow-xs">
        <div class="relative h-14 w-14 flex-shrink-0 overflow-hidden rounded-full border border-[#ede8dc] bg-[#fdfbf7]">
            <img
                src="{{ $customer->image_url ?? bagisto_asset('images/user-placeholder.png') }}"
                class="h-full w-full object-cover"
                alt="{{ trans('shop::app.components.layouts.account.profile-image') }}"
            >
        </div>

        <div class="min-w-0 flex-1" v-pre>
            <p class="font-serif text-lg font-bold text-[#18181b] tracking-tight truncate">
                {{ trans('shop::app.components.layouts.account.greeting', ['name' => $customer->first_name]) }}
            </p>

            <p class="text-xs text-[#71717a] truncate font-sans">
                {{ $customer->email }}
            </p>
        </div>
    </div>

    <!-- Account Navigation Menus -->
    @foreach (menu()->getItems('customer') as $menuItem)
        <div>
            <!-- Section Header -->
            <div class="select-none px-1 pb-2.5">
                <p class="text-xs font-bold uppercase tracking-wider text-[#9e8464]">
                    {{ $menuItem->getName() }}
                </p>
            </div>

            <!-- Navigation Links Card -->
            @if ($menuItem->haveChildren())
                <div class="overflow-hidden rounded-2xl border border-[#ede8dc] bg-white shadow-xs divide-y divide-[#ede8dc]">
                    @foreach ($menuItem->getChildren() as $subMenuItem)
                        <a
                            href="{{ $subMenuItem->getUrl() }}"
                            class="group flex items-center justify-between px-5 py-3.5 text-sm transition-all {{ $subMenuItem->isActive() ? 'bg-[#fdfbf7] text-[#9e8464] font-semibold border-l-4 border-l-[#9e8464]' : 'text-[#52525b] hover:text-[#18181b] hover:bg-[#fdfbf7]/60' }}"
                        >
                            <span class="flex items-center gap-3 truncate">
                                <span class="{{ $subMenuItem->getIcon() }} text-lg transition-colors {{ $subMenuItem->isActive() ? 'text-[#9e8464]' : 'text-[#a1a1aa] group-hover:text-[#9e8464]' }}"></span>
                                <span class="truncate">{{ $subMenuItem->getName() }}</span>
                            </span>

                            <span class="icon-arrow-right rtl:icon-arrow-left text-xs transition-transform group-hover:translate-x-0.5 {{ $subMenuItem->isActive() ? 'text-[#9e8464]' : 'text-[#a1a1aa]' }}"></span>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    <!-- Logout Action Button -->
    <div class="pt-2">
        <form
            method="POST"
            action="{{ route('shop.customer.session.destroy') }}"
            id="customerLogoutNav"
        >
            @method('DELETE')
            @csrf
            <button
                type="submit"
                class="w-full flex items-center justify-center gap-2 rounded-full border border-[#ede8dc] bg-white px-5 py-3 text-xs font-semibold uppercase tracking-wider text-[#71717a] shadow-xs transition-all hover:border-[#9e8464] hover:text-[#9e8464] hover:bg-[#fdfbf7] cursor-pointer"
            >
                <span class="icon-cancel text-base"></span>
                <span>@lang('shop::app.components.layouts.header.desktop.bottom.logout')</span>
            </button>
        </form>
    </div>
</div>
