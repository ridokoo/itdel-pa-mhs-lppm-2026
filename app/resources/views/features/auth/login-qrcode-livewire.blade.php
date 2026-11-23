<div class="card-body">
    <a href="" class="text-nowrap logo-img text-center mb-4 d-block w-100">
        <img src="/img/logo-dark-text.png" class="dark-logo" style="height: 72px;" alt="Logo-dark" />
    </a>

    <div class="position-relative text-center">
        <p class="mb-0 fs-4 px-3 d-inline-block bg-body text-dark z-index-5 position-relative">
            Masuk dengan Scan QRCode
        </p>
        <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
    </div>

    <div class="mb-4"></div>

    <form wire:submit.prevent="submit">
        <!-- Elemen tersembunyi untuk binding Livewire -->
        <input type="hidden" wire:model="systemId" id="systemIdInput">
        <input type="hidden" wire:model="info" id="infoInput">

        <div>
            <div class="text-center mb-3">
                Scan QRCode berikut menggunakan aplikasi
                <a class="text-primary"
                    href="https://play.google.com/store/apps/details?id=org.delcom.diitdel" target="_blank">
                    Di ITDel
                </a>
            </div>
            @if ($loginQRCode != null)
                <div>
                    <div class="mb-4 text-center">
                        <img src="{{ $loginQRCode }}" alt="QR Code" class="img-fluid rounded-2"
                            style="max-width: 100%; width: 200px;">
                    </div>
                </div>
            @elseif($isSetup)
                <div class="mb-4">
                    @include('components.loading')
                </div>
            @else
                <div>
                    <div class="alert alert-warning">
                        QRCode Login sudah kedaluwarsa, silahkan membuat ulang!
                    </div>

                    <div class="mb-4 text-center">
                        <a href="{{ route('auth.login.qrcode') }}" class="btn btn-primary w-100 py-8 rounded-2">
                            Buat Ulang QRCode Login
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center justify-content-center">
            <p class="mb-0 fw-medium">Ingin menggunakan kredensial akun?</p>
            <a class="text-primary fw-medium ms-2" href="{{ route('auth.login') }}">Masuk disini</a>
        </div>
    </form>
</div>

@section('others-js')
    <script src="/assets/vendor/crypto-js-4.2.0/crypto-js.js"></script>
    <script src="/scripts/auth/login-qrcode-livewire-v1.js"></script>
    <script>
        document.addEventListener("livewire:initialized", () => {
            let components = Livewire.all();
            if (components.length === 0) {
                window.alert("No Livewire components found.");
                return;
            }

            let livewire = components[0].$wire;
            let checkTimeout = null; // simpan ID timeout

            function checkLoginQRCode() {
                livewire.check();

                // simpan ID timeout
                checkTimeout = setTimeout(checkLoginQRCode, 3000);
            }

            Livewire.on("stopCheckLoginQRCode", () => {
                if (checkTimeout) {
                    clearTimeout(checkTimeout);
                    checkTimeout = null;
                }
            });

            // mulai polling
            checkLoginQRCode();
        });
    </script>
@endsection



