<div wire:ignore.self class="modal fade" id="modal_output" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-sm" style="border-radius: 12px; background-color: #2b2f3a;">

            <!-- HEADER -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title fw-bold">
                    {{ $isEdit ? 'Ubah Luaran' : 'Tambah Luaran Baru' }}
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">

                    <!-- TIPE SKEMA -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Pilih Tipe Skema
                        </label>

                        <select wire:model="scheme_type_id"
                                class="form-select text-white bg-dark border-secondary @error('scheme_type_id') is-invalid @enderror">
                            <option value="">-- Pilih Tipe Skema --</option>
                            @foreach($schemeTypes as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>

                        @error('scheme_type_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Nama Luaran / Keluaran
                        </label>

                        <input type="text"
                               wire:model="name"
                               class="form-control text-white bg-dark border-secondary @error('name') is-invalid @enderror"
                               placeholder="Contoh: Publikasi Jurnal Internasional Terindeks Scopus">

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



