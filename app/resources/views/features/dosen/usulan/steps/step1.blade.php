{{-- ============================================================ --}}
{{-- STEP 1: Judul & Identitas Usulan --}}
{{-- ============================================================ --}}
@if ($currentStep == 1)
    <div class="card border-0 shadow-sm p-4">
        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">1.1 Identitas Usulan</h6>

        <div class="row mb-3">
            <div class="col-md-12 mb-3">
                <label class="form-label small fw-bold" for="title">Judul Usulan *</label>
                <textarea id="title" name="title" wire:model.blur="title"
                    class="form-control @error('title') is-invalid @enderror"
                    {{ $isReadOnly ? 'disabled' : '' }}
                    placeholder="Masukkan judul usulan secara lengkap"></textarea>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label fw-bold small text-muted" for="summary">Ringkasan / Abstrak</label>
                <textarea id="summary" name="summary" wire:model.blur="summary"
                    class="form-control" rows="3" {{ $isReadOnly ? 'disabled' : '' }}
                    placeholder="Gambarkan singkat inti dari usulan Anda"></textarea>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label small fw-bold" for="target_tkt_id">Target TKT Saat Ini *</label>
                <select id="target_tkt_id" name="target_tkt_id" wire:model.live="target_tkt_id"
                    {{ $isReadOnly ? 'disabled' : '' }}
                    class="form-select @error('target_tkt_id') is-invalid @enderror">
                    <option value="">-- Pilih TKT --</option>
                    @foreach ($targetTkts as $tkt)
                        <option value="{{ $tkt->id }}">Level {{ $tkt->level }} - {{ $tkt->name }}</option>
                    @endforeach
                </select>
                @error('target_tkt_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label small fw-bold" for="duration_months">Durasi Usulan (Tahun) *</label>
                <input type="number" id="duration_months" name="duration_months" wire:model.blur="duration_months"
                    class="form-control" {{ $isReadOnly ? 'disabled' : '' }} placeholder="Contoh: 1">
            </div>
        </div>

        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">1.2 Pemilihan Program
            {{ $categoryId == 4 ? 'Penelitian' : 'Pengabdian' }}</h6>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold" for="categoryId">Kategori</label>
                <select id="categoryId" name="categoryId" wire:model="categoryId" class="form-select bg-light" disabled>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold" for="scheme_group_id">Kelompok Skema *</label>
                <select id="scheme_group_id" name="scheme_group_id" wire:model.live="scheme_group_id"
                    {{ $isReadOnly ? 'disabled' : '' }}
                    class="form-select @error('scheme_group_id') is-invalid @enderror">
                    <option value="">-- Pilih Kelompok --</option>
                    @foreach ($schemeGroups as $sg)
                        <option value="{{ $sg->id }}">{{ $sg->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold" for="scheme_type_id">Tipe Skema *</label>
                <select id="scheme_type_id" name="scheme_type_id" wire:model.live="scheme_type_id"
                    {{ $isReadOnly ? 'disabled' : '' }}
                    class="form-select @error('scheme_type_id') is-invalid @enderror">
                    <option value="">-- Pilih --</option>
                    @foreach ($schemeTypes as $st)
                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label fw-semibold" for="scope_id">Bidang Fokus *</label>
                <select id="scope_id" name="scope_id" wire:model.live="scope_id"
                    {{ $isReadOnly ? 'disabled' : '' }}
                    class="form-select @error('scope_id') is-invalid @enderror">
                    <option value="">-- Pilih Bidang Fokus --</option>
                    @foreach ($scopes as $scope)
                        <option value="{{ $scope->id }}">{{ $scope->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-4 gap-2 border-top pt-3">
            <a href="{{ route('dosen.usulan.index', ['categoryId' => $categoryId]) }}"
                class="btn btn-light rounded-pill px-4">Kembali</a>

            <button wire:click="nextStep(2)" class="btn btn-primary rounded-pill px-4">
                Lanjut ke Personil <i class="ph-arrow-right ms-2"></i>
            </button>
        </div>
    </div>
@endif



