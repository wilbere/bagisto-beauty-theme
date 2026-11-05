<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.layouts.my-account')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="account" />
        @endSection
    @endif

    <div class="w-full md:max-w-md mx-auto">
        <x-shop::layouts.account.navigation />
    </div>
</x-shop::layouts.account>
