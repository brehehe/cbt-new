@section('title', 'Dashboard')

<div>
    @if (Auth::user()->hasRole('Mahasiswa'))
        @if (isset($userProfile) && $userProfile)
            @include('livewire.admin.dashboard.partials.user-profile-section')
        @endif
    @else
        <div id="admin-dashboard-app"
             data-user-profile="{{ json_encode($userProfile) }}"
        ></div>

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            @vite(['resources/js/dashboard-react.jsx'])
        @endpush
    @endif
</div>