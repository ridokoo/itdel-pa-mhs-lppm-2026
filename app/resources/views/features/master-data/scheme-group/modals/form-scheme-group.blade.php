<div wire:ignore.self class="modal fade" id="modal_scheme_group" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-sm" style="border-radius: 12px; background-color: #2b2f3a;">

            <!-- HEADER -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title fw-bold">
                    {{ $isEdit ? 'Ubah Kelompok Skema' : 'Tambah Kelompok Skema' }}
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">

                    <!-- KATEGORI -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">Pilih Kategori</label>

                        <select wire:model="category_id"
                                class="form-select text-white bg-dark border-secondary @error('category_id') is-invalid @enderror">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Nama Kelompok Skema
                        </label>

                        <input type="text"
                               wire:model="name"
                               class="form-control text-white bg-dark border-secondary @error('name') is-invalid @enderror"
                               placeholder="Contoh: Skema Penelitian Dasar">

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



