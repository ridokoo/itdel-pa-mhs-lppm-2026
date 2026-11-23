<div class="p-4">
    <style>
        .text-laporan-akhir { color: #7c3aed !important; }
        .bg-laporan-akhir { background-color: #7c3aed !important; color: white; }
        .btn-laporan-akhir { background-color: #7c3aed; border-color: #7c3aed; color: white; }
        .btn-laporan-akhir:hover { background-color: #6d28d9; }
    </style>

    {{-- HEADER --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold mb-1 text-laporan-akhir">
                        {{ $isEdit ? 'Edit' : 'Buat' }} Laporan Akhir
                    </h5>
                    <p class="text-muted mb-0 small">{{ $proposal->title }}</p>
                </div>
                <a href="{{ route('dosen.laporan-akhir.index') }}"
                    class="btn btn-sm btn-outline-secondary rounded-pill">
                    <i class="ph-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if($isReadOnly)
    <div class="alert alert-warning rounded-3 mb-3">
        <i class="ph-lock me-2"></i>
        Laporan sudah diajukan dan tidak dapat diubah. Hubungi reviewer jika perlu revisi.
    </div>
    @endif

    {{-- SUMMARY ANGGARAN --}}
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-muted small mb-1">Total Anggaran</div>
                <div class="fw-bold fs-6">Rp {{ number_format($totalBudget, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-muted small mb-1">Total Terpakai</div>
                <div class="fw-bold fs-6 text-success">Rp {{ number_format($totalSpentAmount, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="text-muted small mb-1">Persentase</div>
                <div class="fw-bold fs-6 {{ $spentPercentage > 100 ? 'text-danger' : 'text-primary' }}">
                    {{ $spentPercentage }}%
                </div>
            </div>
        </div>
    </div>

    {{-- REALISASI ANGGARAN --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-bottom">
            <h6 class="fw-bold mb-0"><i class="ph-money me-2"></i> Realisasi Anggaran</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
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
                        @forelse($budgets as $i => $row)
                        <tr>
                            <td>{{ $row['component'] }}</td>
                            <td>{{ $row['item_name'] }}</td>
                            <td><span class="badge bg-secondary">{{ $row['category_name'] }}</span></td>
                            <td class="text-center">{{ $row['volume'] }}</td>
                            <td class="text-center">{{ $row['unit'] }}</td>
                            <td class="text-end">Rp {{ number_format($row['unit_price'], 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($row['total_plan'], 0, ',', '.') }}</td>
                            <td>
                                @if(!$isReadOnly)
                                    <input type="number"
                                        class="form-control form-control-sm text-end"
                                        wire:model.live="budgets.{{ $i }}.amount_spent"
                                        min="0" placeholder="0" />
                                @else
                                    <span class="d-block text-end">Rp {{ number_format($row['amount_spent'], 0, ',', '.') }}</span>
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
    </div>

    {{-- CAPAIAN LUARAN --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-bottom">
            <h6 class="fw-bold mb-0"><i class="ph-target me-2"></i> Capaian Luaran Akhir</h6>
        </div>
        <div class="card-body">
            @forelse($outputs as $i => $row)
            <div class="border rounded-3 p-3 mb-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="badge bg-primary">{{ $row['type_name'] }}</span>
                    <span class="badge bg-secondary">{{ $row['category_name'] }}</span>
                    <span class="text-muted small ms-auto">{{ $row['description'] }}</span>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Status Capaian <span class="text-danger">*</span></label>
                        <select wire:model="outputs.{{ $i }}.output_target_status_id"
                            class="form-select form-select-sm"
                            {{ $isReadOnly ? 'disabled' : '' }}>
                            <option value="">-- Pilih Status --</option>
                            @foreach($outputTargetStatuses as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Keterangan Capaian <span class="text-danger">*</span></label>
                        <textarea wire:model="outputs.{{ $i }}.achievement_description"
                            class="form-control form-control-sm" rows="2"
                            placeholder="Jelaskan capaian luaran ini..."
                            {{ $isReadOnly ? 'disabled' : '' }}></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Bukti Pendukung (opsional)</label>
                        @if($row['existing_evidence_file'])
                            <div class="mb-1">
                                <a href="{{ Storage::url($row['existing_evidence_file']) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success rounded-pill">
                                    <i class="ph-eye me-1"></i> Lihat File
                                </a>
                            </div>
                        @endif
                        @if(!$isReadOnly)
                            <input type="file" wire:model="outputs.{{ $i }}.evidence_file"
                                class="form-control form-control-sm" accept=".pdf,.doc,.docx,.jpg,.png" />
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info rounded-3">
                <i class="ph-info me-2"></i> Tidak ada luaran terdaftar.
            </div>
            @endforelse
        </div>
    </div>

    {{-- DOKUMEN PENDUKUNG --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-header bg-white border-bottom">
            <h6 class="fw-bold mb-0"><i class="ph-files me-2"></i> Dokumen Pendukung</h6>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <label class="form-label fw-semibold">Rencana Integrasi Hasil ke Pembelajaran <span class="text-danger">*</span></label>
                <textarea wire:model="integration_plan" class="form-control" rows="4"
                    placeholder="Jelaskan bagaimana hasil penelitian akan diintegrasikan..."
                    {{ $isReadOnly ? 'disabled' : '' }}></textarea>
            </div>

            <div class="row g-3">

                {{-- ===== Bukti Pengeluaran ===== --}}
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100 d-flex flex-column">
                        <div class="fw-semibold mb-1">
                            <i class="ph-receipt text-warning me-1"></i> Bukti Pengeluaran (100%)
                        </div>
                        <div class="text-muted small mb-3">Upload bukti pengeluaran lunas (.pdf, .zip)</div>

                        @if($existing_expense_proof_file)
                            <div class="alert alert-success py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                <i class="ph-check-circle text-success"></i>
                                <div class="small">
                                    <div class="fw-semibold">File terunggah</div>
                                    <div class="text-muted" style="font-size:11px;">{{ basename($existing_expense_proof_file) }}</div>
                                </div>
                                <a href="{{ Storage::url($existing_expense_proof_file) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success rounded-pill ms-auto px-2 py-0">
                                    <i class="ph-eye"></i>
                                </a>
                            </div>
                        @else
                            <div class="alert alert-secondary py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                <i class="ph-warning text-muted"></i>
                                <div class="small text-muted">Belum ada file</div>
                            </div>
                        @endif

                        @if(!$isReadOnly)
                        <div class="mt-auto">
                            <input type="file" wire:model="expense_proof_file"
                                class="form-control form-control-sm" accept=".pdf,.zip" />
                            @if($expense_proof_file)
                            <div class="d-flex align-items-center gap-2 mt-2 text-success small">
                                <i class="ph-check-circle"></i>
                                <span>File siap: <strong>{{ $expense_proof_file->getClientOriginalName() }}</strong></span>
                            </div>
                            @endif
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
                        <div class="text-muted small mb-3">Upload logbook lengkap (.pdf, .doc, .docx)</div>

                        @if($existing_logbook_file)
                            <div class="alert alert-success py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                <i class="ph-check-circle text-success"></i>
                                <div class="small">
                                    <div class="fw-semibold">File terunggah</div>
                                    <div class="text-muted" style="font-size:11px;">{{ basename($existing_logbook_file) }}</div>
                                </div>
                                <a href="{{ Storage::url($existing_logbook_file) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success rounded-pill ms-auto px-2 py-0">
                                    <i class="ph-eye"></i>
                                </a>
                            </div>
                        @else
                            <div class="alert alert-secondary py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                <i class="ph-warning text-muted"></i>
                                <div class="small text-muted">Belum ada file</div>
                            </div>
                        @endif

                        @if(!$isReadOnly)
                        <div class="mt-auto">
                            <input type="file" wire:model="logbook_file"
                                class="form-control form-control-sm" accept=".pdf,.doc,.docx" />
                            @if($logbook_file)
                            <div class="d-flex align-items-center gap-2 mt-2 text-success small">
                                <i class="ph-check-circle"></i>
                                <span>File siap: <strong>{{ $logbook_file->getClientOriginalName() }}</strong></span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ===== Laporan Akhir ===== --}}
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100 d-flex flex-column">
                        <div class="fw-semibold mb-1">
                            <i class="ph-file-text text-primary me-1"></i> Dokumen Laporan Akhir
                        </div>
                        <div class="text-muted small mb-3">Upload dokumen laporan akhir lengkap (.pdf, .doc, .docx)</div>

                        @if($existing_final_report_file)
                            <div class="alert alert-success py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                <i class="ph-check-circle text-success"></i>
                                <div class="small">
                                    <div class="fw-semibold">File terunggah</div>
                                    <div class="text-muted" style="font-size:11px;">{{ basename($existing_final_report_file) }}</div>
                                </div>
                                <a href="{{ Storage::url($existing_final_report_file) }}" target="_blank"
                                    class="btn btn-sm btn-outline-success rounded-pill ms-auto px-2 py-0">
                                    <i class="ph-eye"></i>
                                </a>
                            </div>
                        @else
                            <div class="alert alert-secondary py-2 px-3 rounded-3 d-flex align-items-center gap-2 mb-2">
                                <i class="ph-warning text-muted"></i>
                                <div class="small text-muted">Belum ada file</div>
                            </div>
                        @endif

                        @if(!$isReadOnly)
                        <div class="mt-auto">
                            <input type="file" wire:model="final_report_file"
                                class="form-control form-control-sm" accept=".pdf,.doc,.docx" />
                            @if($final_report_file)
                            <div class="d-flex align-items-center gap-2 mt-2 text-success small">
                                <i class="ph-check-circle"></i>
                                <span>File siap: <strong>{{ $final_report_file->getClientOriginalName() }}</strong></span>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- TOMBOL AKSI --}}
    @if(!$isReadOnly)
    <div class="card border-0 shadow-sm">
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <a href="{{ route('dosen.laporan-akhir.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="ph-arrow-left me-1"></i> Batal
            </a>
            <div class="d-flex gap-2">
                <button wire:click="saveDraft"
                    wire:loading.attr="disabled" wire:target="saveDraft"
                    class="btn btn-outline-primary rounded-pill px-4">
                    <span wire:loading.remove wire:target="saveDraft">
                        <i class="ph-floppy-disk me-1"></i> Simpan Draft
                    </span>
                    <span wire:loading wire:target="saveDraft">
                        <span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...
                    </span>
                </button>

                <button wire:click="submit"
                    wire:loading.attr="disabled" wire:target="submit"
                    wire:confirm="Yakin ingin mengajukan laporan akhir? Data tidak dapat diubah setelah diajukan."
                    class="btn btn-laporan-akhir rounded-pill px-4 fw-bold">
                    <span wire:loading.remove wire:target="submit">
                        <i class="ph-paper-plane-right me-1"></i> Ajukan Laporan Akhir
                    </span>
                    <span wire:loading wire:target="submit">
                        <span class="spinner-border spinner-border-sm me-1"></span> Mengajukan...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
