<div wire:ignore.self class="modal fade" id="modal_rev_status" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="modal-content">
            <div class="modal-header {{ $isEdit ? 'bg-info' : 'bg-primary' }} text-white border-0">
                <h6 class="modal-title">{{ $isEdit ? 'Ubah Status' : 'Tambah Status' }}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    wire:click="resetFields"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Status</label>
                    <input type="text" wire:model="name" class="form-control" placeholder="Contoh: Assigned">
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn {{ $isEdit ? 'btn-info' : 'btn-primary' }} w-100">Simpan</button>
            </div>
        </form>
    </div>
</div>



