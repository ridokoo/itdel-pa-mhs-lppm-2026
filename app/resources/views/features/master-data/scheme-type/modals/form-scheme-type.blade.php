<div wire:ignore.self class="modal fade" id="modal_scheme_type" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-sm" style="border-radius: 12px; background-color: #2b2f3a;">

            <!-- HEADER -->
            <div class="modal-header text-white border-0" style="background-color: #0070C0;">
                <h6 class="modal-title fw-bold">
                    {{ $isEdit ? 'Ubah Tipe Skema' : 'Tambah Tipe Skema' }}
                </h6>
                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal"
                        wire:click="resetFields"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">

                    <!-- KELOMPOK -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Pilih Kelompok Skema
                        </label>

                        <select wire:model="scheme_group_id"
                                class="form-select text-white bg-dark border-secondary @error('scheme_group_id') is-invalid @enderror">
                            <option value="">-- Pilih Kelompok --</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>

                        @error('scheme_group_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- NAMA -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-white">
                            Nama Tipe Skema
                        </label>

                        <input type="text"
                               wire:model="name"
                               class="form-control text-white bg-dark border-secondary @error('name') is-invalid @enderror"
                               placeholder="Contoh: Penelitian Dosen Pemula">

                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- TKT -->
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">TKT Minimal</label>
                                <input type="number"
                                       wire:model="tkt_min"
                                       class="form-control text-white bg-dark border-secondary @error('tkt_min') is-invalid @enderror"
                                       min="1" max="9">

                                @error('tkt_min')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-white">TKT Maksimal</label>
                                <input type="number"
                                       wire:model="tkt_max"
                                       class="form-control text-white bg-dark border-secondary @error('tkt_max') is-invalid @enderror"
                                       min="1" max="9">

                                @error('tkt_max')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
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
                        {{ $isEdit ? 'Updatee' : 'Simpan Data' }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>



