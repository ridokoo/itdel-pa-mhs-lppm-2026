<div>
    <div class="page-header border-0 mb-3">
        <div class="d-flex align-items-center">
            <h4 class="page-title mb-0">Kelola Status Tugas Reviewer</h4>
            <button type="button" class="btn btn-primary ms-auto rounded-pill" wire:click="create">
                <i class="ph-plus me-2"></i> Tambah Status
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th style="width: 50px;">ID</th>
                        <th>Nama Status Tugas</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statuses as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td><span
                                    class="badge bg-indigo bg-opacity-10 text-indigo fw-bold">{{ strtoupper($s->name) }}</span>
                            </td>
                            <td class="text-center">
                                <button wire:click="edit({{ $s->id }})"
                                    class="btn btn-sm btn-light text-primary"><i class="ph-pencil"></i></button>
                                <button wire:click="deleteConfirm({{ $s->id }})"
                                    class="btn btn-sm btn-light text-danger"><i class="ph-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODALS --}}
    @include('features.master-data.reviewer-status.modals.form')
    @include('features.master-data.reviewer-status.modals.delete')
</div>



