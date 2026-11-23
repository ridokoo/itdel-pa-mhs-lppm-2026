<div class="py-3" style="margin-left: -2.5rem; margin-right: -2.5rem;">
    <style>
        .text-racing-green {
            color: #158771 !important;
        }

        .bg-racing-green {
            background-color: #158771 !important;
            color: white;
        }

        .btn-racing-green {
            background-color: #158771;
            border-color: #158771;
            color: white;
        }

        .btn-racing-green:hover {
            background-color: #116d5b;
        }

        .step-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }
    </style>

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold mb-1 text-racing-green">Form Laporan Kemajuan</h5>
                    <p class="text-muted mb-0 small">{{ $proposal->title }}</p>
                </div>
                <a href="{{ route('dosen.laporan-kemajuan.index', ['categoryId' => $proposal->category_id]) }}"
                    class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="ph-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- STEP INDICATOR --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex gap-2 flex-wrap">
                @foreach ([1 => 'Realisasi Anggaran', 2 => 'Capaian Luaran', 3 => 'Dokumen', 4 => 'Konfirmasi'] as $s => $label)
                    <button wire:click="nextStep({{ $s }})"
                        class="btn btn-sm {{ $currentStep == $s ? 'btn-racing-green' : 'btn-outline-secondary' }} rounded-pill px-3">
                        <span class="me-1">{{ $s }}</span> {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ================================================================
         STEP 1: REALISASI ANGGARAN
    ================================================================ --}}
    @if ($currentStep == 1)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="fw-bold mb-0 text-racing-green">
                    <i class="ph-money me-2"></i> Step 1 — Realisasi Anggaran
                </h6>
            </div>
            <div class="card-body">

                {{-- Summary Cards --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Total Anggaran</div>
                            <div class="fw-bold fs-6">Rp {{ number_format($totalBudget, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Total Terpakai</div>
                            <div class="fw-bold fs-6 text-success">Rp
                                {{ number_format($totalSpentAmount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Persentase</div>
                            <div class="fw-bold fs-6 {{ $spentPercentage > 100 ? 'text-danger' : 'text-primary' }}">
                                {{ $spentPercentage }}%
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-4">
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar {{ $spentPercentage > 100 ? 'bg-danger' : 'bg-success' }}"
                            style="width: {{ min($spentPercentage, 100) }}%"></div>
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle">
                        <thead class="table-light">
                            <tr class="small text-uppercase text-muted">
                                <th>Komponen</th>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th class="text-center">Volume</th>
                                <th class="text-center">Satuan</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Rencana (Rp)</th>
                                <th class="text-end" style="min-width:160px;">Realisasi (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($budgetRealizations as $id => $row)
                                <tr>
                                    <td>{{ $row['component'] }}</td>
                                    <td>{{ $row['item_name'] }}</td>
                                    <td><span class="badge bg-secondary">{{ $row['category_name'] }}</span></td>
                                    <td class="text-center">{{ $row['volume'] }}</td>
                                    <td class="text-center">{{ $row['unit'] }}</td>
                                    <td class="text-end">Rp {{ number_format($row['unit_price'], 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($row['total_plan'], 0, ',', '.') }}</td>
                                    <td>
                                        @if (!$isReadOnly)
                                            <input type="number" class="form-control form-control-sm text-end"
                                                wire:model.live="budgetRealizations.{{ $id }}.amount_spent"
                                                min="0" max="{{ $row['total_plan'] }}" placeholder="0" />
                                        @else
                                            <span class="text-end d-block">Rp
                                                {{ number_format($row['amount_spent'], 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-3">Tidak ada item anggaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="6" class="text-end">Total</td>
                                <td class="text-end">Rp {{ number_format($totalBudget, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($totalSpentAmount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <button wire:click="nextStep(2)" class="btn btn-racing-green rounded-pill px-4">
                    Simpan & Lanjut <i class="ph-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- ================================================================
         STEP 2: CAPAIAN LUARAN
    ================================================================ --}}
    @if ($currentStep == 2)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="fw-bold mb-0 text-racing-green">
                    <i class="ph-target me-2"></i> Step 2 — Capaian Luaran
                </h6>
            </div>
            <div class="card-body">
                @forelse($outputRealizations as $i => $row)
                    <div class="border rounded-3 p-3 mb-3">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge bg-primary">{{ $row['type_name'] }}</span>
                            <span class="badge bg-secondary">{{ $row['category_name'] }}</span>
                            <span class="text-muted small ms-auto">{{ $row['description'] }}</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Status Capaian <span
                                        class="text-danger">*</span></label>
                                <select wire:model="outputRealizations.{{ $i }}.output_target_status_id"
                                    class="form-select form-select-sm" {{ $isReadOnly ? 'disabled' : '' }}>
                                    <option value="">-- Pilih Status --</option>
                                    @foreach ($outputTargetStatuses as $st)
                                        <option value="{{ $st['id'] }}">{{ $st['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold">Keterangan Pencapaian</label>
                                <textarea wire:model="outputRealizations.{{ $i }}.achievement_description"
                                    class="form-control form-control-sm" rows="2" placeholder="Contoh: Paper sudah submit ke jurnal Sinta 2..."
                                    {{ $isReadOnly ? 'disabled' : '' }}></textarea>
                            </div>
                            @if ($row['existing_evidence_file'])
                                <div class="col-12">
                                    <a href="{{ Storage::url($row['existing_evidence_file']) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success rounded-pill">
                                        <i class="ph-file me-1"></i> Lihat Bukti Sebelumnya
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info rounded-3">
                        <i class="ph-info me-2"></i> Tidak ada luaran terdaftar pada proposal ini.
                    </div>
                @endforelse
            </div>
            <div class="card-footer bg-white d-flex justify-content-between">
                <button wire:click="nextStep(1)" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="ph-arrow-left me-1"></i> Kembali
                </button>
                <button wire:click="nextStep(3)" class="btn btn-racing-green rounded-pill px-4">
                    Simpan & Lanjut <i class="ph-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
    @endif

    {{-- ================================================================
         STEP 3: DOKUMEN
    ================================================================ --}}
    @if ($currentStep == 3)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="fw-bold mb-0 text-racing-green">
                    <i class="ph-files me-2"></i> Step 3 — Dokumen Pendukung
                </h6>
            </div>
            <div class="card-body">

                <div class="mb-4">
                    <label class="form-label fw-semibold">Rencana Integrasi ke Pembelajaran</label>
                    <textarea wire:model="integration_plan" class="form-control" rows="4"
                        placeholder="Jelaskan bagaimana hasil penelitian akan diintegrasikan ke pembelajaran..."
                        {{ $isReadOnly ? 'disabled' : '' }}></textarea>
                </div>

                <div class="row g-3">

                    {{-- ===== Bukti Pengeluaran ===== --}}
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100 d-flex flex-column">
                            <div class="fw-semibold mb-1">
                                <i class="ph-receipt text-warning me-1"></i> Bukti Pengeluaran
                            </div>
                            <div class="text-muted small mb-3">Upload kwitansi / bukti transfer (.pdf, .zip)</div>

                            @if ($existing_expense_proof)
                                <div
                                    class="alert alert-success py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                    <i class="ph-check-circle text-success fs-5"></i>
                                    <div class="small">
                                        <div class="fw-semibold">File terunggah</div>
                                        <div class="text-muted" style="font-size:11px;">
                                            {{ basename($existing_expense_proof) }}</div>
                                    </div>
                                    <a href="{{ Storage::url($existing_expense_proof) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success rounded-pill ms-auto px-2 py-0">
                                        <i class="ph-eye"></i>
                                    </a>
                                </div>
                            @else
                                <div
                                    class="alert alert-secondary py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                    <i class="ph-warning text-muted fs-5"></i>
                                    <div class="small text-muted">Belum ada file</div>
                                </div>
                            @endif

                            @if (!$isReadOnly)
                                <div class="mt-auto">
                                    <input type="file" wire:model="expense_proof_file"
                                        class="form-control form-control-sm" accept=".pdf,.zip" />



                                @if ($expense_proof_file)
                                    <div class="d-flex align-items-center gap-2 mt-2 text-success small">
                                            <i class="ph-check-circle fs-6"></i>
                                            <span>File siap:
                                                <strong>{{ $expense_proof_file->getClientOriginalName() }}</strong></span>
                                        </div>
                                    @endif

                                    @error('expense_proof_file')
                                        <div class="text-danger small mt-1">
                                            <i class="ph-warning-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ===== Logbook ===== --}}
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100 d-flex flex-column">
                            <div class="fw-semibold mb-1">
                                <i class="ph-notebook text-info me-1"></i> Catatan Harian (Logbook)
                            </div>
                            <div class="text-muted small mb-3">Upload logbook kegiatan (.pdf, .doc, .docx)</div>

                            @if ($existing_logbook)
                                <div
                                    class="alert alert-success py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                    <i class="ph-check-circle text-success fs-5"></i>
                                    <div class="small">
                                        <div class="fw-semibold">File terunggah</div>
                                        <div class="text-muted" style="font-size:11px;">
                                            {{ basename($existing_logbook) }}</div>
                                    </div>
                                    <a href="{{ Storage::url($existing_logbook) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success rounded-pill ms-auto px-2 py-0">
                                        <i class="ph-eye"></i>
                                    </a>
                                </div>
                            @else
                                <div
                                    class="alert alert-secondary py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                    <i class="ph-warning text-muted fs-5"></i>
                                    <div class="small text-muted">Belum ada file</div>
                                </div>
                            @endif

                            @if (!$isReadOnly)
                                <div class="mt-auto">
                                    <input type="file" wire:model="logbook_file"
                                        class="form-control form-control-sm" accept=".pdf,.doc,.docx" />



                                    @if ($logbook_file)
                                            <div class="d-flex align-items-center gap-2 mt-2 text-success small">
                                            <i class="ph-check-circle fs-6"></i>
                                            <span>File siap:
                                                <strong>{{ $logbook_file->getClientOriginalName() }}</strong></span>
                                        </div>
                                    @endif

                                    @error('logbook_file')
                                        <div class="text-danger small mt-1">
                                            <i class="ph-warning-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ===== Dokumen Laporan ===== --}}
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 h-100 d-flex flex-column">
                            <div class="fw-semibold mb-1">
                                <i class="ph-file-text text-primary me-1"></i> Dokumen Laporan Kemajuan
                            </div>
                            <div class="text-muted small mb-3">Upload dokumen laporan lengkap (.pdf, .doc, .docx)</div>

                            @if ($existing_progress_report)
                                <div
                                    class="alert alert-success py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                    <i class="ph-check-circle text-success fs-5"></i>
                                    <div class="small">
                                        <div class="fw-semibold">File terunggah</div>
                                        <div class="text-muted" style="font-size:11px;">
                                            {{ basename($existing_progress_report) }}</div>
                                    </div>
                                    <a href="{{ Storage::url($existing_progress_report) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success rounded-pill ms-auto px-2 py-0">
                                        <i class="ph-eye"></i>
                                    </a>
                                </div>
                            @else
                                <div
                                    class="alert alert-secondary py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                    <i class="ph-warning text-muted fs-5"></i>
                                    <div class="small text-muted">Belum ada file</div>
                                </div>
                            @endif

                            @if (!$isReadOnly)
                                <div class="mt-auto">
                                    <input type="file" wire:model="progress_report_file"
                                        class="form-control form-control-sm" accept=".pdf,.doc,.docx" />

                                    

                                    @if ($progress_report_file)
                                       <div class="d-flex align-items-center gap-2 mt-2 text-success small">
                                            <i class="ph-check-circle fs-6"></i>
                                            <span>File siap:
                                                <strong>{{ $progress_report_file->getClientOriginalName() }}</strong></span>
                                        </div>
                                    @endif

                                    @error('progress_report_file')
                                        <div class="text-danger small mt-1">
                                            <i class="ph-warning-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <button wire:click="nextStep(2)" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="ph-arrow-left me-1"></i> Kembali
                </button>

                <div>
                    <button wire:click="nextStep(4)" class="btn btn-racing-green rounded-pill px-4"
                        wire:loading.attr="disabled" wire:target="nextStep">
                        <span wire:loading.remove wire:target="nextStep">
                            Simpan & Lanjut <i class="ph-arrow-right ms-1"></i>
                        </span>
                        <span wire:loading wire:target="nextStep">
                            <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ================================================================
     STEP 4: KONFIRMASI
================================================================ --}}
    @if ($currentStep == 4)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="fw-bold mb-0 text-racing-green">
                    <i class="ph-paper-plane-right me-2"></i> Step 4 — Konfirmasi & Kirim
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info rounded-3">
                    <i class="ph-info me-2"></i>
                    Periksa kembali seluruh data sebelum mengajukan. Laporan yang sudah diajukan tidak dapat diubah.
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3">
                            <div class="text-muted small mb-1">Judul Proposal</div>
                            <div class="fw-semibold">{{ $proposal->title }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Total Terpakai</div>
                            <div class="fw-bold text-success">Rp {{ number_format($totalSpentAmount, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Realisasi</div>
                            <div class="fw-bold text-primary">{{ $spentPercentage }}%</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Jumlah Luaran</div>
                            <div class="fw-bold">{{ count($outputRealizations) }} item</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Bukti Pengeluaran</div>
                            <div class="fw-bold">
                                {!! $existing_expense_proof
                                    ? '<span class="text-success">✅ Terunggah</span>'
                                    : '<span class="text-danger">❌ Belum Ada</span>' !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Dokumen Laporan</div>
                            <div class="fw-bold">
                                {!! $existing_progress_report
                                    ? '<span class="text-success">✅ Terunggah</span>'
                                    : '<span class="text-danger">❌ Belum Ada</span>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (!$isReadOnly)
                <div class="card-footer bg-white d-flex justify-content-between">
                    <button wire:click="nextStep(3)" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="ph-arrow-left me-1"></i> Kembali Edit
                    </button>

                    <button wire:click="finalSubmit" wire:loading.attr="disabled" wire:target="finalSubmit"
                        wire:confirm="Yakin ingin mengajukan laporan ini? Data tidak dapat diubah setelah diajukan."
                        class="btn btn-success rounded-pill px-4 fw-bold">
                        <span wire:loading.remove wire:target="finalSubmit">
                            <i class="ph-paper-plane-right me-1"></i> Ajukan Laporan
                        </span>
                        <span wire:loading wire:target="finalSubmit">
                            <span class="spinner-border spinner-border-sm me-1"></span> Mengajukan...
                        </span>
                    </button>
                </div>
            @endif
        </div>
    @endif

</div>
