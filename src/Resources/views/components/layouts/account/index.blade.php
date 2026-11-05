<x-shop::layouts :has-feature="false">
    <!-- Page Title -->
    <x-slot:title>
        {{ $title ?? '' }}
    </x-slot>

    <!-- Page Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <x-shop::layouts.account.breadcrumb />

        <div class="mt-4 flex flex-col md:flex-row items-start gap-8 lg:gap-10">
            {{ $slot }}
        </div>
    </div>
</x-shop::layouts>
