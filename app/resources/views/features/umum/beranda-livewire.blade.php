@push('scripts')
    <!-- Global stylesheets -->
    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/images/icons/phosphor/styles.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" id="stylesheet" rel="stylesheet" type="text/css" />
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{ asset('assets/demo/demo_configurator.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script src="{{ asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/demo/pages/dashboard.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/streamgraph.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/sparklines.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/lines.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/areas.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/donuts.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/bars.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/progress.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/heatmaps.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/pies.js') }}"></script>
    <script src="{{ asset('assets/demo/charts/pages/dashboard/bullets.js') }}"></script>
    <!-- /theme JS files -->
@endpush
<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-body bg-primary bg-opacity-10 border-0 shadow-none mb-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-pill overflow-hidden me-3">
                        <img alt="profile" width="40" height="40"
                            src="{{ $authUser->photo ?? asset('assets/images/demo/users/face11.jpg') }}"
                            class="rounded-pill">
                    </div>
                    <h5 class="fw-semibold mb-0 fs-5">{{ $authUser->name ?? '' }}</h5>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@section('others-js')
    <script>
        function updateServerTime() {
            // Simpan waktu server awal sebagai reference
            if (!window.serverStartTime) {
                window.serverStartTime = new Date("{{ $waktuServer }}");
                window.clientStartTime = new Date();
            }

            // Hitung selisih waktu antara sekarang dengan saat page load
            const now = new Date();
            const elapsed = now - window.clientStartTime; // milidetik yang telah berlalu

            // Tambahkan selisih waktu ke waktu server awal
            const currentServerTime = new Date(window.serverStartTime.getTime() + elapsed);

            const options = {
                timeZone: 'Asia/Jakarta',
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };

            const formatter = new Intl.DateTimeFormat('id-ID', options);
            document.getElementById('server-time').innerHTML = formatter.format(currentServerTime).replace("pukul",
                "<br/>");
        }

        // Update setiap detik
        setInterval(updateServerTime, 1000);

        // Initial update
        updateServerTime();
    </script>
@endsection



