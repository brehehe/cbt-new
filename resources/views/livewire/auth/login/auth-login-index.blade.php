<div>
    <div id="login-universitas-app"
            data-company="{{ json_encode($company) }}"
            data-is-credentials="{{ json_encode($is_credentials) }}"
            data-credentials="{{ json_encode($credentials) }}"
            data-app-windows="{{ $company->app_windows ? Storage::url($company->app_windows) : '' }}"
            data-app-mac="{{ $company->app_mac ? Storage::url($company->app_mac) : '' }}"
            data-app-android="{{ $company->app_android ? Storage::url($company->app_android) : '' }}"
            data-app-ios="{{ $company->app_ios ? Storage::url($company->app_ios) : '' }}"
    ></div>
    @push('scripts')
        @vite(['resources/js/login-react.jsx'])
    @endpush
</div>