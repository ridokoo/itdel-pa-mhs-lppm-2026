<div>
<style>
    .page-header { background-color:#ffffff; }
    .page-title { color:#1a1a1a; }
    .card { background-color:#ffffff; border:1px solid #e5e7eb; border-radius:14px; box-shadow:0 4px 14px rgba(0,0,0,0.04); }
    .card-body { background-color:#ffffff; }
    .form-label { color:#374151; font-weight:600; font-size:13.5px; margin-bottom:6px; }
    .form-control, .form-select { background-color:#ffffff; color:#374151; border:1px solid #e5e7eb; border-radius:10px; font-size:14px; padding:10px 14px; transition:all .2s ease; }
    .form-control::placeholder { color:#9ca3af; }
    .form-control:focus, .form-select:focus { border-color:#0070C0 !important; box-shadow:0 0 0 .2rem rgba(0,112,192,.15) !important; }
    .btn-primary { background-color:#0070C0 !important; border-color:#0070C0 !important; border-radius:10px; font-weight:600; transition:all .2s ease; }
    .btn-primary:hover { background-color:#005a9e !important; border-color:#005a9e !important; }
    .btn-light { background-color:#f9fafb; border-color:#e5e7eb; border-radius:10px; }
    .table thead th { background-color:#f8f9fa; border-bottom:2px solid #eeeeee; font-weight:700; color:#374151; padding:14px; }
    .table tbody td { padding:14px; vertical-align:middle; }
    .table tbody tr { transition:all .2s ease; }
    .table tbody tr:hover { background-color:#f0f7ff; }
    .swal2-popup { width:22rem !important; padding:1.8rem !important; border-radius:20px !important; background:#ffffff !important; color:#333333 !important; }
    .swal2-title { font-size:1.7rem !important; font-weight:700 !important; color:#1f2937 !important; }
    .swal2-confirm { background-color:#0070C0 !important; border:none !important; border-radius:10px !important; padding:10px 24px !important; font-weight:600 !important; }
    .swal2-cancel { border-radius:10px !important; padding:10px 24px !important; font-weight:600 !important; }
    .swal2-timer-progress-bar { background-color:#0070C0 !important; }
</style>

    <!-- HEADER -->
    <div class="page-header border-bottom mb-4">
        <div class="page-header-content d-flex align-items-center">
            <div class="d-flex py-2">
                <h4 class="page-title mb-0 fw-bold">
                    <i class="ph-bold ph-clock-counter-clockwise me-2 text-primary"></i>
                    @if($showForm && $isEdit)
                        Edit Status Riwayat Proposal
                    @elseif($showForm)
                        Tambah Status Riwayat Proposal
                    @else
                        Kelola Status Riwayat Proposal
                    @endif
                </h4>
            </div>
            <div class="ms-auto">
                @if(!$showForm)
                    <button wire:click="showCreate" class="btn btn-primary shadow-sm px-3">
                        <i class="ph-bold ph-plus-circle me-2"></i>
                        Tambah Status Riwayat Proposal
                    </button>
                @else
                    <button wire:click="resetFields" class="btn btn-light border px-3">
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
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>

                                <th class="text-center" style="width:60px;">
                                    No
                                </th>

                                <th class="text-center">
                                    Proposal
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                                <th class="text-center">
                                    Catatan
                                </th>

                                <th class="text-center">
                                    Tanggal
                                </th>

                                <th class="text-center" style="width:120px;">
                                    Aksi
                                </th>

                            </tr>
                        </thead>

                        <tbody>

                            @forelse($histories as $item)

                                <tr class="align-middle"
                                    wire:key="history-{{ $item->id }}">

                                    {{-- NO --}}
                                    <td class="text-muted">
                                        {{ $loop->iteration }}
                                    </td>

                                    {{-- PROPOSAL --}}
                                    <td class="fw-semibold text-dark">
                                        {{ $item->proposal->title ?? '-' }}
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="fw-semibold text-dark">
                                        {{ $item->proposalStatus->name ?? '-' }}
                                    </td>

                                    {{-- CATATAN --}}
                                    <td>

                                        @if($item->note)

                                            <span style="font-size:13px; color:#374151;">
                                                {{ $item->note }}
                                            </span>

                                        @else

                                            <span class="text-muted fst-italic">
                                                -
                                            </span>

                                        @endif

                                    </td>

                                    {{-- TANGGAL --}}
                                    <td>

                                        <span style="font-size:13px;"
                                            class="text-dark">

                                            {{ $item->created_at?->format('d M Y') }}

                                        </span>

                                        <br>

                                        <small class="text-muted">
                                            {{ $item->created_at?->format('H:i') }}
                                        </small>

                                    </td>

                                    {{-- AKSI --}}
                                    <td class="text-center">

                                        <div class="d-inline-flex gap-1">

                                            <button wire:click="showEdit('{{ $item->id }}')"
                                                    class="btn btn-outline-primary btn-sm rounded-pill border-0"
                                                    title="Edit">

                                                <i class="ph-bold ph-pencil"></i>

                                            </button>

                                            <button type="button"
                                                    onclick="confirmDelete('{{ $item->id }}')"
                                                    class="btn btn-outline-danger btn-sm rounded-pill border-0"
                                                    title="Hapus">

                                                <i class="ph-bold ph-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6"
                                        class="text-center py-5 text-muted">

                                        <i class="ph-bold ph-clock-counter-clockwise fs-2 d-block mb-2 opacity-25"></i>

                                        Belum ada data history.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </div>

        @else

            <!-- FORM -->
            <div class="card">
                <div class="card-body p-4">
                    <form wire:submit.prevent="{{ $isEdit ? 'confirmUpdate' : 'store' }}">

                        <div class="mb-4">
                            <label class="form-label">Proposal</label>
                            <select wire:model="proposal_id"
                                class="form-select @error('proposal_id') is-invalid @enderror">
                                <option value="">-- Pilih Proposal --</option>
                                @foreach($proposals as $p)
                                    <option value="{{ $p->id }}">{{ $p->title }}</option>
                                @endforeach
                            </select>
                            @error('proposal_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Status Proposal</label>
                            <select wire:model="proposal_status_id"
                                class="form-select @error('proposal_status_id') is-invalid @enderror">
                                <option value="">-- Pilih Status --</option>
                                @foreach($statuses as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('proposal_status_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                            <textarea wire:model="note" rows="3"
                                class="form-control @error('note') is-invalid @enderror"
                                placeholder="Tambahkan catatan perubahan status..."></textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="border-top pt-3 mt-2 d-flex justify-content-end gap-2">
                            <button type="button" wire:click="resetFields"
                                class="btn btn-link text-muted fw-bold text-decoration-none">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary px-4 shadow-sm"
                                wire:loading.attr="disabled"
                                wire:target="store, update">
                                <span wire:loading.remove wire:target="store, update">
                                    <i class="ph-bold ph-floppy-disk me-2"></i>
                                    {{ $isEdit ? 'Update' : 'Simpan Data' }}
                                </span>
                                <span wire:loading wire:target="store, update">
                                    Menyimpan...
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
    window.confirmDelete = (id) => {
        Swal.fire({
            title: 'Hapus data?',
            html: `<div>Data yang dihapus tidak dapat dikembalikan.</div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) { $wire.delete(id); }
        });
    };

    $wire.on('triggerUpdateConfirm', () => {
        Swal.fire({
            title: 'Update data?',
            html: `<div>Perubahan data akan disimpan.</div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0070C0',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Update',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) { $wire.update(); }
        });
    });

    Livewire.on('swal:modal', (event) => {
        let data = event[0] ?? event;
        Swal.fire({
            title: data.title ?? 'Info',
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



