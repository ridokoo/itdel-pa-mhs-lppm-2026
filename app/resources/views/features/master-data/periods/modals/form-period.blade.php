<div wire:ignore.self class="modal fade" id="modal_period" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 shadow-sm" style="border-radius: 12px;">

            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    Form Periode Proposal
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Tahun</label>
                        <input type="text" wire:model="year" class="form-control">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama Label Periode</label>
                        <input type="text" wire:model="name" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" wire:model="start_date" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" wire:model="end_date" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Status Periode Aktif</label>
                        <select wire:model="is_active" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Non Aktif</option>
                        </select>
                    </div>

                </div>

            </div>

            <div class="modal-footer border-0">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>

                <button class="btn btn-primary"
                        wire:click="{{ $isEdit ? 'update' : 'store' }}">
                    Simpan Data
                </button>
            </div>

        </div>
    </div>
</div>



