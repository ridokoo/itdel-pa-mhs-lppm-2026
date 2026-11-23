<div class="container-fluid">
    {{-- Breadcrumb --}}
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">
                        <i class="ti ti-settings"></i>
                        Pengaturan Akun
                    </h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none"
                                    href="#">Beranda</a></li>
                            <li class="breadcrumb-item" aria-current="page">Pengaturan Akun</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img alt="bg-breadcrumb" class="img-fluid mb-n4" src="/assets/images/breadcrumb/ChatBc.png">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Konten --}}
    <div class="card mt-3">
        <div class="card-header bg-info-subtle">
            <h4 class="mb-0"><i class="ti ti-user"></i>&nbsp;Umum</h4><span>Perbarui informasi umum akun Anda</span>
        </div>
        <div class="card-body px-4 py-3">

            <ul class="list-group mt-3">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ti ti-user"></i><strong class="ms-2">Nama Lengkap</strong><br />
                            <small class="text-muted">{{ $auth->name }}</small>
                        </div>
                        <div>
                            <button class="btn btn-outline-secondary" type="button" disabled>
                                Ubah
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="list-group mt-3">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{-- Gunakan ikon gender yang sesuai --}}
                            <i class="ti ti-gender-intersex"></i><strong class="ms-2">Jenis Kelamin</strong><br />
                            <small class="text-muted">
                                @if (strtoupper($auth->gender_en ?? '') == 'L')
                                    Laki-laki
                                @elseif(strtoupper($auth->gender_en ?? '') == 'P')
                                    Perempuan
                                @else
                                    {{ $auth->gender_en ?? '[!] Belum ditambahkan' }}
                                @endif
                            </small>
                        </div>
                        <div>
                            {{-- Biasanya data gender dari API bersifat Read-Only (tidak bisa diubah dari aplikasi lokal) --}}
                            <button class="btn btn-outline-secondary" type="button" disabled>
                                Ubah
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="list-group mt-3">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            {{-- Gunakan ikon gender yang sesuai --}}
                            <i class="ti ti-gender-intersex"></i><strong class="ms-2">Email</strong><br />
                                <small class="text-muted">{{ $auth->email }}</small>
                        </div>
                        <div>
                            {{-- Biasanya data gender dari API bersifat Read-Only (tidak bisa diubah dari aplikasi lokal) --}}
                            <button class="btn btn-outline-secondary" type="button" disabled>
                                Ubah
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="list-group mt-3">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ti ti-brand-whatsapp"></i><strong class="ms-2">Whatsapp</strong><br />
                            <small class="text-muted">{{ $auth->whatsapp ?? '[!] Belum ditambahkan' }}</small>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#editWhatsappModal">
                                Ubah
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

            <ul class="list-group mt-3">
                <li class="list-group-item">
                    <i class="ti ti-photo"></i>
                    <strong class="ms-2">Foto Profil</strong>
                </li>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <img class="rounded-circle" alt="photo profile" src="{{ $auth->photo }}"
                                style="max-width: 48px; max-height: 48px;">
                            <button class="btn btn-sm btn-outline-info ms-3" type="button" data-bs-toggle="modal"
                                data-bs-target="#examplePhotoModal">
                                Contoh Foto Profil
                            </button>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" type="button" wire:click="prepareEditPhoto()">
                                Ubah
                            </button>
                        </div>
                    </div>
                </li>
            </ul>

        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-info-subtle">
            <h4 class="mb-0">
                <i class="ti ti-lock"></i>&nbsp;
                Pribadi
            </h4>
            <span>
                Perbarui informasi pribadi akun Anda
            </span>
        </div>
        <div class="card-body px-4 py-3">
            <ul class="list-group">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="ti ti-key"></i><strong class="ms-2">Kata Sandi</strong><br />
                            <small class="text-muted">
                                Terakhir diperbarui:
                                {{ date('d F Y - H:i', strtotime($auth->password_updated_at ?? '-')) }}
                            </small>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                data-bs-target="#editPasswordModal">
                                Ubah
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-info-subtle">
            <h4 class="mb-0">
                <i class="ti ti-settings"></i>
                &nbsp;Lainnya
            </h4>
            <span>
                Tindakan lainnya yang mungkin kamu butuhkan
            </span>
        </div>
        <div class="card-body px-4 py-3">
            <ul class="list-group mt-3">
                <ul class="list-group">
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-danger">
                                <i class="ti ti-trash"></i>
                                <strong class="ms-2">
                                    Hapus Akun Saya
                                </strong>
                            </div>
                            <div>
                                <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal"
                                    data-bs-target="#deleteAccountModal">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </ul>
        </div>
    </div>

    @include('features.profile.modals.edit-whatsapp')
    @include('features.profile.modals.edit-password')
    @include('features.profile.modals.example-photo')
    @include('features.profile.modals.edit-photo')
    @include('features.profile.modals.delete-account')
</div>

@section('others-css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/croppie-2.6.5/croppie.css') }}">
@endsection

@section('others-js')
    <script src="{{ asset('assets/vendor/croppie-2.6.5/croppie.min.js') }}"></script>
    <script>
        document.addEventListener("livewire:initialized", () => {
            Livewire.on("clickSelectPhoto", () => {
                $('#upload').click();
            });

            let croppieInstance = null;

            // Event ketika file dipilih
            $('#upload').on('change', function() {
                const file = this.files[0];
                if (!file) return;

                // Validasi ukuran file
                if (file.size > 5 * 1024 * 1024) {
                    alert('Ukuran file maksimal 5MB');
                    return;
                }

                $('#crop-container').removeClass('d-none');
                $('#result-section').addClass('d-none');

                const reader = new FileReader();
                reader.onload = function(e) {
                    // Hancurkan instance sebelumnya jika ada
                    if (croppieInstance) {
                        croppieInstance.destroy();
                        croppieInstance = null;
                    }

                    // Inisialisasi Croppie baru
                    croppieInstance = new Croppie(document.getElementById('croppie'), {
                        viewport: {
                            width: 256,
                            height: 256,
                            type: 'square'
                        },
                        boundary: {
                            width: 300,
                            height: 300
                        },
                        enableExif: true,
                        enableOrientation: true
                    });

                    // Bind gambar
                    croppieInstance.bind({
                        url: e.target.result
                    });
                };
                reader.readAsDataURL(file);
            });

            // Tombol Crop
            $('#crop-btn').on('click', function() {
                if (croppieInstance) {
                    croppieInstance.result({
                        type: 'base64',
                        size: 'viewport',
                        format: 'jpeg',
                        quality: 0.9
                    }).then(function(base64) {
                        $('#preview').attr('src', base64);
                        $('#cropped-image').val(base64);
                        $('#result-section').removeClass('d-none');
                        $('#crop-container').addClass('d-none');
                        $('#upload').val('');

                        // Kirim data ke Livewire
                        @this.photo = base64;
                    }).catch(function(error) {
                        console.error('Error cropping:', error);
                        alert('Terjadi error saat cropping gambar');
                    });
                }
            });

            // Tombol Reset
            $('#reset-btn').on('click', function() {
                $('#upload').val('');
                $('#crop-container').addClass('d-none');
                $('#result-section').addClass('d-none');

                if (croppieInstance) {
                    croppieInstance.destroy();
                    croppieInstance = null;
                }

                // Clear container
                $('#croppie').html('');
            });
        });
    </script>
@endsection



