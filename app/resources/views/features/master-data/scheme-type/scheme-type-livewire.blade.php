<div>

    <style>

        :root{
            --primary-green:#0070C0;
            --dark-green:#1bad82;
            --light-green:#f0fdfa;

            --text-dark:#1f2937;
            --text-muted:#6b7280;

            --border-color:#e5e7eb;
            --danger:#ef4444;
        }

        .page-header{
            background:#ffffff !important;
            border-bottom:1px solid var(--border-color) !important;
        }

        .page-title{
            color:var(--text-dark) !important;
            letter-spacing:-0.02em;
        }

        .card{
            background:#ffffff !important;
            border:1px solid var(--border-color) !important;
            border-radius:16px !important;
            box-shadow:0 4px 16px rgba(0,0,0,0.05) !important;
            overflow:hidden;
        }

        .card-body{
            background:#ffffff !important;
        }

        .card-form-accent{
            border-top:4px solid var(--primary-green) !important;
        }

        .form-label{
            color:var(--text-dark) !important;
            font-weight:600 !important;
            margin-bottom:.55rem !important;
        }

        .form-control,
        .form-select{
            background:#ffffff !important;
            color:var(--text-dark) !important;

            border:1px solid var(--border-color) !important;
            border-radius:10px !important;

            padding:12px 14px !important;

            transition:all .2s ease-in-out !important;
        }

        .form-control:focus,
        .form-select:focus{
            border-color:var(--primary-green) !important;
            box-shadow:0 0 0 4px rgba(32,201,151,.12) !important;
        }

        .btn-primary{
            background:var(--primary-green) !important;
            border-color:var(--primary-green) !important;
            color:#ffffff !important;
            border-radius:10px !important;
            font-weight:600 !important;
            padding:11px 18px !important;
        }

        .btn-primary:hover{
            background:var(--dark-green) !important;
            border-color:var(--dark-green) !important;
        }

        .btn-light{
            background:#f9fafb !important;
            border:1px solid var(--border-color) !important;
            border-radius:10px !important;
        }

        .table thead th{
            background:#f9fafb !important;
            color:var(--text-muted) !important;
            text-transform:uppercase;
            font-size:11px !important;
            letter-spacing:.05em;
            border-bottom:1px solid var(--border-color) !important;
            padding:15px !important;
        }

        .table tbody td{
            padding:15px !important;
            vertical-align:middle !important;
        }

        .table tbody tr:hover{
            background:var(--light-green) !important;
        }

        .swal2-popup{
            width:22rem !important;
            padding:1.8rem !important;
            border-radius:20px !important;
            background:#ffffff !important;
            color:#333333 !important;
        }

        .swal2-title{
            font-size:1.7rem !important;
            font-weight:700 !important;
            color:#1f2937 !important;
            margin-top:12px !important;
        }

        .swal2-html-container{
            text-align:center !important;
            font-size:15px !important;
            color:#6b7280 !important;
            line-height:1.6 !important;
        }

        .swal2-confirm{
            background:#0070C0 !important;
            border:none !important;
            border-radius:10px !important;
            padding:10px 24px !important;
            font-weight:600 !important;
        }

        .swal2-cancel{
            border-radius:10px !important;
            padding:10px 24px !important;
            font-weight:600 !important;
        }

        .swal2-icon.swal2-success{
            border-color:#0070C0 !important;
            color:#0070C0 !important;
        }

        .swal2-icon.swal2-error{
            border-color:#ef4444 !important;
            color:#ef4444 !important;
        }

        .swal2-icon.swal2-question{
            border-color:#0070C0 !important;
            color:#0070C0 !important;
        }

        .swal2-timer-progress-bar{
            background:#0070C0 !important;
        }

    </style>


    <!-- HEADER -->
    <div class="page-header mb-4">

        <div class="page-header-content d-lg-flex align-items-center px-4">

            <div class="d-flex py-3">

                <h4 class="page-title mb-0 fw-bold">

                    <i class="ph-bold ph-stack me-2"
                       style="color:var(--primary-green);"></i>

                    @if($showForm && $isEdit)
                        Edit Tipe Skema
                    @elseif($showForm)
                        Tambah Tipe Skema
                    @else
                        Kelola Tipe Skema
                    @endif

                </h4>

            </div>

            <div class="ms-lg-auto py-3">

                @if(!$showForm)

                    <button
                        wire:click="showCreate"
                        class="btn btn-primary shadow-sm">

                        <i class="ph-bold ph-plus-circle me-1"></i>
                        Tambah Tipe Skema

                    </button>

                @else

                    <button
                        wire:click="resetFields"
                        class="btn btn-light px-3">

                        <i class="ph-bold ph-arrow-left me-1"></i>
                        Kembali

                    </button>

                @endif

            </div>

        </div>

    </div>


    <!-- CONTENT -->
    <div class="content px-4">

        @if(!$showForm)

            <!-- TABLE -->
            <div class="card border-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th class="text-center" style="width:70px;">
                                        No
                                    </th>

                                    <th class="text-center">
                                        Kelompok Skema
                                    </th>

                                    <th class="text-center">
                                        Nama Tipe Skema
                                    </th>

                                    <th class="text-center">
                                        Range TKT
                                    </th>

                                    <th class="text-center" style="width:130px;">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>

                        <tbody>

                            @forelse($types as $t)

                                <tr wire:key="scheme-type-{{ $t->id }}">

                                    <td class="text-center text-muted">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $t->schemeGroup->name ?? '-' }}
                                    </td>

                                    <td class="fw-bold text-dark">
                                        {{ $t->name }}
                                    </td>

<td class="text-center">

    <span
        style="
            background: linear-gradient(135deg, #eaf4ff, #dceeff);
            color: #0b5ea8;
            border: 1px solid #b8d9f5;
            border-radius: 10px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0, 112, 192, 0.08);
        ">

        <i class="ph-bold ph-chart-line-up"></i>

        TKT {{ $t->tkt_min }} - {{ $t->tkt_max }}

    </span>

</td>

                                    <td class="text-center">

                                        <div class="d-inline-flex gap-2">

                                            <button
                                                wire:click="showEdit({{ $t->id }})"
                                                class="btn btn-outline-primary btn-icon btn-sm rounded-pill border-0">

                                                <i class="ph-bold ph-pencil"></i>

                                            </button>

                                            <button
                                                type="button"
                                                onclick="confirmDelete({{ $t->id }})"
                                                class="btn btn-outline-danger btn-icon btn-sm rounded-pill border-0">

                                                <i class="ph-bold ph-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center py-5 text-muted">

                                        <i class="ph-bold ph-stack fs-1 d-block mb-2 opacity-50"></i>

                                        Belum ada data tipe skema.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @else

            <!-- FORM -->
            <div class="card border-0 card-form-accent">

                <div class="card-body p-4">

                    <form wire:submit.prevent="{{ $isEdit ? 'confirmUpdate' : 'store' }}">

                        <div class="mb-4">

                            <label class="form-label">
                                Kelompok Skema
                            </label>

                            <select
                                wire:model="scheme_group_id"
                                class="form-select @error('scheme_group_id') is-invalid @enderror">

                                <option value="">
                                    Pilih Kelompok Skema
                                </option>

                                @foreach($groups as $g)

                                    <option value="{{ $g->id }}">
                                        {{ $g->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('scheme_group_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="mb-4">

                            <label class="form-label">
                                Nama Tipe Skema
                            </label>

                            <input
                                type="text"
                                wire:model="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama tipe skema">

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    TKT Minimum
                                </label>

                                <input
                                    type="number"
                                    wire:model="tkt_min"
                                    class="form-control @error('tkt_min') is-invalid @enderror">

                                @error('tkt_min')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label">
                                    TKT Maximum
                                </label>

                                <input
                                    type="number"
                                    wire:model="tkt_max"
                                    class="form-control @error('tkt_max') is-invalid @enderror">

                                @error('tkt_max')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        <div class="border-top pt-4 mt-4 d-flex justify-content-end gap-2">

                            <button
                                type="button"
                                wire:click="resetFields"
                                class="btn btn-link text-muted fw-semibold text-decoration-none">

                                Batal

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary px-4 shadow-sm">

                                <i class="ph-bold ph-floppy-disk me-1"></i>

                                {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data' }}

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endif

    </div>


    @script
    <script>

        // DELETE
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

                if (result.isConfirmed) {

                    $wire.delete(id);

                }

            });

        };


        // UPDATE
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

                if (result.isConfirmed) {

                    $wire.update();

                }

            });

        });


        // NOTIFICATION
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



