<x-app-layout>
    <x-slot name="header">{{ __('Profile') }}</x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow rounded-lg sm:rounded-lg border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow rounded-lg border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow rounded-lg border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
