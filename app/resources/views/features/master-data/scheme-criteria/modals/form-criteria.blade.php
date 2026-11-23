<div wire:ignore.self class="modal fade" id="modal_criteria" tabindex="-1">
    <div class="modal-dialog modal-lg"> {{-- Gunakan modal-lg karena field banyak --}}
        <div class="modal-content">
            <div class="modal-header {{ $isEdit ? 'bg-info' : 'bg-primary' }} text-white border-0">
                <h6 class="modal-title">{{ $isEdit ? 'Ubah Aturan Skema' : 'Set Aturan Skema Baru' }}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
                <div class="modal-body">
                    {{-- 1. Pilih Skema --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">Target Tipe Skema</label>
                        <select wire:model="scheme_type_id"
                            class="form-select @error('scheme_type_id') is-invalid @enderror">
                            <option value="">-- Pilih Tipe Skema --</option>
                            @foreach ($schemeTypes as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                        @error('scheme_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    {{-- 2. Aturan Anggaran --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Anggaran Minimal (Rp)</label>
                            <input type="text" wire:model.live="min_budget" class="form-control" placeholder="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Anggaran Maksimal (Rp) *</label>
                            <input type="text" wire:model.live="max_budget"
                                class="form-control @error('max_budget') is-invalid @enderror" placeholder="10.000.000">
                        </div>
                    </div>

                    {{-- 3. Aturan Anggota --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Min. Anggota (Orang)</label>
                            <input type="number" wire:model="min_members" class="form-control" placeholder="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Max. Anggota (Orang) *</label>
                            <input type="number" wire:model="max_members" class="form-control">
                        </div>
                    </div>

                    <hr>

                    {{-- 4. Syarat Akademik --}}
                    <h6 class="fw-bold small text-muted text-uppercase mb-3">Syarat Kelayakan Pengusul</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Jabatan Fungsional Minimal</label>
                            <select wire:model="min_functional_position_id" class="form-select">
                                <option value="">-- Tanpa Minimal --</option>
                                @foreach ($functionalPositions as $fp)
                                    <option value="{{ $fp->id }}">{{ $fp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold d-block">Pendidikan yang Diizinkan</label>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" wire:model="allow_s2" id="s2">
                                <label class="form-check-label" for="s2">S2 (Magister)</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" wire:model="allow_s3" id="s3">
                                <label class="form-check-label" for="s3">S3 (Doktor)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn {{ $isEdit ? 'btn-info' : 'btn-primary' }}">
                        {{ $isEdit ? 'Perbarui Aturan' : 'Simpan Aturan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



