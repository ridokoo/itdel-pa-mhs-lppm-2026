<div wire:ignore.self class="modal fade" id="modal_prodi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-sm" style="border-radius: 12px; background-color: #2b2f3a;">

            <!-- HEADER -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title fw-bold">
                    {{ $isEdit ? 'Edit Program Studi' : 'Tambah Prodi Baru' }}
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">

                    <!-- SELECT FAKULTAS -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Pilih Fakultas
                        </label>

                        <select wire:model="faculty_id"
                                class="form-select text-white bg-dark border-secondary @error('faculty_id') is-invalid @enderror">
                            <option value="">-- Pilih Fakultas --</option>
                            @foreach ($faculties as $f)
                                <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>

                        @error('faculty_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- NAMA PRODI -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Nama Program Studi
                        </label>

                        <input type="text"
                               wire:model="name"
                               class="form-control text-white bg-dark border-secondary @error('name') is-invalid @enderror"
                               placeholder="Contoh: S1 Informatika">

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



