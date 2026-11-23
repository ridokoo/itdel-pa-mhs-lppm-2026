<div>

    <style>

        .page-header{
            background-color:#ffffff !important;
        }

        .page-title{
            color:#1a1a1a !important;
        }

        .card{
            background-color:#ffffff !important;
            border:1px solid #e5e7eb !important;
            box-shadow:0 4px 12px rgba(0,0,0,0.04) !important;
            border-radius:12px !important;
        }

        .card-body{
            background-color:#ffffff !important;
        }

        .form-control,
        .form-select{
            background-color:#ffffff !important;
            color:#374151 !important;
            border-color:#e5e7eb !important;
            border-radius:8px !important;
            font-size:14px !important;
        }

        .form-control::placeholder{
            color:#9ca3af !important;
        }

        .form-control:focus,
        .form-select:focus{
            background-color:#ffffff !important;
            color:#374151 !important;
            border-color:#0070C0 !important;
            box-shadow:0 0 0 0.2rem rgba(32,201,151,0.15) !important;
        }

        .form-label{
            color:#374151 !important;
            font-weight:600 !important;
            font-size:13.5px !important;
        }

        .btn-primary{
            background-color:#0070C0 !important;
            border-color:#0070C0 !important;
            color:#ffffff !important;
            border-radius:8px !important;
        }

        .btn-primary:hover{
            background-color:#1bad82 !important;
        }

        .btn-light{
            background-color:#f9fafb !important;
            border-color:#e5e7eb !important;
            color:#374151 !important;
            border-radius:8px !important;
        }

        .table thead th{
            background-color:#f8f9fa !important;
            color:#444 !important;
            border-bottom:2px solid #eee !important;
            font-weight:700 !important;
            text-align:center !important;
        }

        .table tbody tr{
            background-color:#ffffff !important;
            color:#374151 !important;
        }

        .table tbody tr:hover{
            background-color:#f0fdf4 !important;
        }

        .swal2-icon{
            width:80px !important;
            height:80px !important;
            min-width:80px !important;
            min-height:80px !important;
            border-width:4px !important;
            margin:20px auto 10px !important;
            display:flex !important;
            align-items:center !important;
            justify-content:center !important;
            box-sizing:content-box !important;
        }

        .swal2-icon .swal2-icon-content{
            font-size:50px !important;
            display:flex !important;
            align-items:center !important;
        }

        .swal2-success-ring{
            width:80px !important;
            height:80px !important;
            box-sizing:content-box !important;
        }

        .swal2-icon.swal2-success [class^='swal2-success-line']{
            background-color:#0070C0 !important;
            height:5px !important;
        }

        .swal2-title{
            font-size:1.4rem !important;
            padding-top:10px !important;
            font-weight:700 !important;
            color:#333 !important;
        }

        .swal2-popup{
            border-radius:15px !important;
            padding:1.5rem !important;
            width:22rem !important;
        }

        .swal2-timer-progress-bar{
            background-color:#0070C0 !important;
        }

    </style>


    <!-- HEADER -->
    <div class="page-header border-bottom mb-4 bg-white">

        <div class="page-header-content d-lg-flex align-items-center">

            <div class="d-flex py-2">

                <h4 class="page-title mb-0 text-dark fw-bold">

                    <i class="ph-bold ph-briefcase me-2"
                       style="color:#0070C0;"></i>

                    @if($showForm && $isEdit)
                        Edit Jabatan Fungsional
                    @elseif($showForm)
                        Tambah Jabatan Fungsional
                    @else
                        Kelola Jabatan Fungsional
                    @endif

                </h4>

            </div>

            <div class="ms-lg-auto">

                @if(!$showForm)

                    <button
                        wire:click="showCreate"
                        class="btn btn-primary shadow-sm px-3">

                        <i class="ph-bold ph-plus-circle me-2"></i>
                        Tambah Jabatan

                    </button>

                @else

                    <button
                        wire:click="resetFields"
                        class="btn btn-light border px-3">

                        <i class="ph-bold ph-arrow-left me-2"></i>
                        Kembali

                    </button>

                @endif

            </div>

        </div>

    </div>


    <!-- CONTENT -->
    <div class="content">

        @if(!$showForm)

            <!-- TABLE -->
            <div class="card border-0 shadow-sm">

                <div class="table-responsive">

                    <table class="table table-hover bg-white mb-0">

                        <thead>

                            <tr>

                                <th class="text-center" style="width:60px;">
                                    No
                                </th>

                                <th>Nama Jabatan</th>

                                <th class="text-center" style="width:120px;">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($positions as $p)

                                <tr
                                    class="align-middle"
                                    wire:key="jabatan-{{ $p->id }}">

                                    <td class="text-center text-muted">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td class="fw-bold text-dark">
                                        {{ $p->name }}
                                    </td>

                                    <td class="text-center">

                                        <div class="d-inline-flex gap-1">

                                            <!-- EDIT -->
                                            <button
                                                wire:click="showEdit('{{ $p->id }}')"
                                                class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-transparent"
                                                title="Edit">

                                                <i class="ph-bold ph-pencil-line"></i>

                                            </button>

                                            <!-- DELETE -->
                                            <button
                                                onclick="confirmDelete('{{ $p->id }}')"
                                                class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-transparent"
                                                title="Hapus">

                                                <i class="ph-bold ph-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3"
                                        class="text-center py-5 text-muted">

                                        <i class="ph-bold ph-briefcase fs-2 d-block mb-2 opacity-25"></i>

                                        Belum ada data jabatan fungsional.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @else

            <!-- FORM -->
            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <form wire:submit.prevent="{{ $isEdit ? 'confirmUpdate' : 'store' }}">

                        <div class="mb-4">

                            <label class="form-label">
                                Nama Jabatan Fungsional
                            </label>

                            <input
                                type="text"
                                wire:model="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Contoh: Asisten Ahli, Lektor, Guru Besar">

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        <div class="border-top pt-3 mt-2 d-flex justify-content-end gap-2">

                            <button
                                type="button"
                                wire:click="resetFields"
                                class="btn btn-link text-muted fw-bold text-decoration-none">

                                Cancel

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary px-4 shadow-sm"
                                wire:loading.attr="disabled"
                                wire:target="store, update">

                                <span wire:loading.remove wire:target="store, update">

                                    <i class="ph-bold ph-floppy-disk me-2"></i>

                                    {{ $isEdit ? 'Update' : 'Simpan Data' }}

                                </span>

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endif

    </div>


    @script
    <script>

        // ================= DELETE =================
        window.confirmDelete = (id) => {

            Swal.fire({

                title: 'Hapus data?',

                html: `<div>Data yang dihapus tidak dapat dikembalikan.</div>`,

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',

                reverseButtons: true

            }).then((result) => {

                if(result.isConfirmed){

                    $wire.delete(id);

                }

            });

        };


        // ================= UPDATE =================
        $wire.on('triggerUpdateConfirm', () => {

            Swal.fire({

                title: 'Update data?',

                html: `<div>Perubahan data akan disimpan.</div>`,

                icon: 'question',

                showCancelButton: true,

                confirmButtonColor: '#0070C0',
                cancelButtonColor: '#6b7280',

                confirmButtonText: 'Update',
                cancelButtonText: 'Batal'

            }).then((result) => {

                if(result.isConfirmed){

                    $wire.update();

                }

            });

        });


        // ================= NOTIFICATION =================
        Livewire.on('swal:modal', (event) => {

            let data = event[0] ?? event;

            Swal.fire({

                title: data.title ?? 'Informasi',

                html: `<div>${data.text ?? ''}</div>`,

                icon: data.icon ?? 'info',

                confirmButtonText: 'OK',

                confirmButtonColor: '#0070C0',

                timer: 2500,
                timerProgressBar: true

            });

        });

    </script>
    @endscript

</div>



