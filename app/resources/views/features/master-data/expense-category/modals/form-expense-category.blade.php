<div wire:ignore.self class="modal fade" id="modal_expense" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #2b2f3a;">

            <!-- Header -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title">
                    {{ $isEdit ? 'Ubah Kategori Biaya' : 'Tambah Kategori Biaya Baru' }}
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">

                    <!-- Nama -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">Nama Kategori</label>
                        <input type="text"
                               wire:model="name"
                               class="form-control text-white bg-dark border-secondary @error('name') is-invalid @enderror"
                               placeholder="Contoh: Honorarium / Bahan Habis Pakai">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">Deskripsi (Opsional)</label>
                        <textarea wire:model="description"
                                  class="form-control text-white bg-dark border-secondary"
                                  rows="2"></textarea>
                    </div>

                    <!-- Persentase & Urutan -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">Min %</label>
                                <input type="number"
                                       wire:model="percentage_min"
                                       class="form-control text-white bg-dark border-secondary"
                                       step="0.01">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">Max %</label>
                                <input type="number"
                                       wire:model="percentage_max"
                                       class="form-control text-white bg-dark border-secondary"
                                       step="0.01">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">No Urut</label>
                                <input type="number"
                                       wire:model="display_order"
                                       class="form-control text-white bg-dark border-secondary">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-link text-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                            class="btn text-white"
                            style="background-color: #0070C0; border-color: #0070C0;">
                        {{ $isEdit ? 'Update' : 'Simpan Data' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>



