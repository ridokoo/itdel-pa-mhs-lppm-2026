<div wire:ignore.self class="modal fade" id="modal_index" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #2b2f3a;">

            <!-- Header -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title">
                    {{ $isEdit ? 'Ubah Indeks' : 'Tambah Indeks Baru' }}
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">

                    <!-- Nama Indeks -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Nama Indeks
                        </label>
                        <input type="text"
                               wire:model="name"
                               class="form-control text-white bg-dark border-secondary @error('name') is-invalid @enderror"
                               placeholder="Contoh: Scopus, SINTA, atau Google Scholar">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Base URL -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Base URL (Opsional)
                        </label>
                        <input type="text"
                               wire:model="base_url"
                               class="form-control text-white bg-dark border-secondary @error('base_url') is-invalid @enderror"
                               placeholder="Contoh: https://www.scopus.com/authid/detail.uri?authorId=">

                        <div class="form-text text-light opacity-75">
                            Gunakan tautan dasar untuk mempermudah akses profil nantinya.
                        </div>

                        @error('base_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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



