<div wire:ignore.self class="modal fade" id="modal_scope" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-sm" style="border-radius: 12px; background-color: #2b2f3a;">

            <!-- HEADER -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title fw-bold">
                    {{ $isEdit ? 'Ubah Ruang Lingkup' : 'Tambah Ruang Lingkup Baru' }}
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">

                    <!-- INPUT -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Nama Bidang / Ruang Lingkup
                        </label>

                        <input type="text"
                               wire:model="name"
                               class="form-control text-white bg-dark border-secondary @error('name') is-invalid @enderror"
                               placeholder="Contoh: Energi Baru dan Terbarukan">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-link text-secondary"
                            data-bs-dismiss="modal"
                            wire:click="resetFields">
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



