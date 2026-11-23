<div wire:ignore.self class="modal fade" id="modal_tkt" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #2b2f3a;">

            <!-- HEADER -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title">
                    {{ $isEdit ? 'Ubah Level TKT' : 'Tambah Level TKT Baru' }}
                </h6>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields">
                </button>
            </div>

            <!-- FORM -->
            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">

                    <div class="row">
                        <!-- LEVEL -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">
                                    Level (1-9)
                                </label>

                                <input type="number"
                                       wire:model.defer="level"
                                       class="form-control text-white bg-dark border-secondary @error('level') is-invalid @enderror"
                                       min="1" max="9">

                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- NAMA -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">
                                    Nama Label
                                </label>

                                <input type="text"
                                       wire:model.defer="name"
                                       class="form-control text-white bg-dark border-secondary @error('name') is-invalid @enderror"
                                       placeholder="Contoh: TKT 1">

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- DESKRIPSI -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Deskripsi Detail
                        </label>

                        <textarea wire:model.defer="description"
                                  class="form-control text-white bg-dark border-secondary"
                                  rows="3"
                                  placeholder="Penjelasan indikator TKT..."></textarea>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn btn-link text-secondary"
                            data-bs-dismiss="modal">
                        Batal
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



