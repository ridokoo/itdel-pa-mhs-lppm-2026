<div>

    <style>

        .page-header{
            background-color:#ffffff!important;
        }

        .page-title{
            color:#1a1a1a!important;
        }

        .card{
            background-color:#ffffff!important;
            border:1px solid #e5e7eb!important;
            box-shadow:0 4px 12px rgba(0,0,0,0.04)!important;
            border-radius:12px!important;
        }

        .card-body{
            background-color:#ffffff!important;
        }

        .form-control,
        .form-select{
            background-color:#ffffff!important;
            color:#374151!important;
            border-color:#e5e7eb!important;
            border-radius:8px!important;
            font-size:14px!important;
        }

        .form-control:focus,
        .form-select:focus{
            background-color:#ffffff!important;
            color:#374151!important;
            border-color:#0070C0!important;
            box-shadow:0 0 0 0.2rem rgba(32,201,151,.15)!important;
        }

        .form-label{
            color:#374151!important;
            font-weight:600!important;
            font-size:13.5px!important;
        }

        .btn-primary{
            background-color:#0070C0!important;
            border-color:#0070C0!important;
            color:#ffffff!important;
            border-radius:8px!important;
        }

        .btn-primary:hover{
            background-color:#1bad82!important;
        }

        .btn-light{
            background-color:#f9fafb!important;
            border-color:#e5e7eb!important;
            color:#374151!important;
            border-radius:8px!important;
        }

        .table thead th{
            background-color:#f8f9fa!important;
            color:#444!important;
            border-bottom:2px solid #eee!important;
            font-weight:700!important;
            text-align:center!important;
        }

        .table tbody tr{
            background-color:#ffffff!important;
            color:#374151!important;
        }

        .table tbody tr:hover{
            background-color:#f0fdf4!important;
        }

        /* SWEET ALERT */

        .swal2-popup{
            width:22rem!important;
            padding:1.8rem!important;
            border-radius:20px!important;
            background:#ffffff!important;
            color:#333333!important;
        }

        .swal2-title{
            font-size:1.7rem!important;
            font-weight:700!important;
            color:#1f2937!important;
            margin-top:12px!important;
        }

        .swal2-html-container{
            display:block!important;
            text-align:center!important;
            font-size:15px!important;
            color:#6b7280!important;
            line-height:1.6!important;
            margin-top:8px!important;
            margin-bottom:1rem!important;
        }

        .swal2-confirm{
            background:#0070C0!important;
            border:none!important;
            border-radius:10px!important;
            padding:10px 24px!important;
            font-weight:600!important;
            box-shadow:none!important;
        }

        .swal2-cancel{
            border-radius:10px!important;
            padding:10px 24px!important;
            font-weight:600!important;
        }

        .swal2-icon{
            width:95px!important;
            height:95px!important;
            margin:10px auto 12px auto!important;
            border-width:4px!important;
        }

        .swal2-icon.swal2-success{
            border-color:#0070C0!important;
            color:#0070C0!important;
        }

        .swal2-icon.swal2-error{
            border-color:#ef4444!important;
            color:#ef4444!important;
        }

        .swal2-timer-progress-bar{
            background:#0070C0!important;
        }

    </style>

    <!-- HEADER -->

    <div class="page-header border-bottom mb-4 bg-white">

        <div class="page-header-content d-lg-flex align-items-center">

            <div class="d-flex py-2">

                <h4 class="page-title mb-0 text-dark fw-bold">

                    <i class="ph-bold ph-calendar me-2"
                       style="color:#0070C0;"></i>

                    @if($showForm && $isEdit)
                        Edit Periode Proposal
                    @elseif($showForm)
                        Tambah Periode Proposal
                    @else
                        Kelola Periode Proposal
                    @endif

                </h4>

            </div>

            <div class="ms-lg-auto">

                @if(!$showForm)

                    <button wire:click="create"
                            class="btn btn-primary shadow-sm px-3">

                        <i class="ph-bold ph-plus-circle me-2"></i>
                        Tambah Periode Proposal

                    </button>

                @else

                    <button wire:click="resetFields"
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

            <div class="card border-0 shadow-sm">

                <div class="table-responsive">

                    <table class="table table-hover bg-white mb-0">

                        <thead>
                            <tr>
                                <th>Tahun</th>
                                <th>Nama Periode</th>
                                <th>Durasi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($periods as $p)

                            <tr class="align-middle">

                                <td>{{ $p->year }}</td>

                                <td class="fw-bold text-dark">
                                    {{ $p->name }}
                                </td>

                                <td>

                                    {{ optional($p->start_date)->format('d M Y') }}
                                    -
                                    {{ optional($p->end_date)->format('d M Y') }}

                                </td>

                                <td class="text-center">

                                    @if($p->is_active)

                                        <span class="badge bg-success">
                                            Aktif
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Non Aktif
                                        </span>

                                    @endif

                                </td>

                                <td class="text-center">

                                    <div class="d-inline-flex gap-1">

                                        <button wire:click="edit('{{ $p->id }}')"
                                                class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-transparent">

                                            <i class="ph-bold ph-pencil-line"></i>

                                        </button>

                                        <button onclick="confirmDelete('{{ $p->id }}')"
                                                class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-transparent">

                                            <i class="ph-bold ph-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td colspan="5"
                                    class="text-center py-5 text-muted">

                                    <i class="ph-bold ph-calendar-x fs-2 d-block mb-2 opacity-25"></i>

                                    Belum ada data periode proposal.

                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @else

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">

                        <div class="row g-3">

                            <div class="col-md-4">

                                <label class="form-label">
                                    Tahun
                                </label>

                                <input type="text"
                                       wire:model="year"
                                       class="form-control @error('year') is-invalid @enderror">

                                @error('year')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-8">

                                <label class="form-label">
                                    Nama Periode
                                </label>

                                <input type="text"
                                       wire:model="name"
                                       class="form-control @error('name') is-invalid @enderror">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Tanggal Mulai
                                </label>

                                <input type="date"
                                       wire:model="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror">

                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Tanggal Selesai
                                </label>

                                <input type="date"
                                       wire:model="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror">

                                @error('end_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <div class="col-md-6">

                                <label class="form-label">
                                    Status
                                </label>

                                <select wire:model="is_active"
                                        class="form-select">

                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>

                                </select>

                            </div>

                        </div>

                        <div class="border-top pt-3 mt-4 d-flex justify-content-end gap-2">

                            <button type="button"
                                    wire:click="resetFields"
                                    class="btn btn-link text-muted fw-bold text-decoration-none">

                                Cancel

                            </button>

                            <button type="submit"
                                    class="btn btn-primary px-4 shadow-sm">

                                <i class="ph-bold ph-floppy-disk me-2"></i>

                                {{ $isEdit ? 'Update' : 'Simpan Data' }}

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



