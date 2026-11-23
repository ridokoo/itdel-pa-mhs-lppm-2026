{{-- ============================================================ --}}
{{-- STEP 4: RAB --}}
{{-- ============================================================ --}}
@if ($currentStep == 4)
    <div class="card border-0 shadow-sm p-4">
        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">4.1 Rencana Anggaran Biaya (RAB)</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-nowrap align-middle">
                <tbody>
                    @foreach ($expenseCategories as $cat)
                        @php
                            // Ambil logik limit & status lock dari kode lama
                            $limit = $categoryLimits[$cat->id] ?? null;
                            $isLocked = $limit && $limit['spent'] >= $limit['max_rp'] && $limit['max_rp'] > 0;

                            $categorySubTotal = collect($budgetItems)
                                ->where('expense_category_id', $cat->id)
                                ->sum('total');
                        @endphp

                        {{-- Baris Judul Kategori --}}
                        <tr class="table-active">
                            {{-- Colspan disesuaikan dengan 7 kolom total (5 kolom awal) --}}
                            <td colspan="5" class="py-2">
                                <div class="fw-bold text-indigo">
                                    <i class="ph-caret-right me-2"></i> {{ strtoupper($cat->name) }}
                                    {{-- Mengembalikan info Target --}}
                                    <span class="ms-2 badge bg-info text-dark fw-semibold">
    Target: {{ $cat->percentage_min }}% - {{ $cat->percentage_max }}%
</span>
                                </div>
                                {{-- Mengembalikan detail nominal limit dan yang sudah terpakai --}}
                                @if ($limit)
                                    <div class="small mt-1 text-muted">
                                        <span class="me-2">
                                            Limit: <b>Rp {{ number_format($limit['min_rp'], 0, ',', '.') }}</b>
                                            s/d <b>Rp {{ number_format($limit['max_rp'], 0, ',', '.') }}</b>
                                        </span>
                                        <span>
                                            Sudah Terpakai: <b>Rp
                                                {{ number_format($limit['spent'] ?? 0, 0, ',', '.') }}</b>
                                        </span>
                                    </div>
                                @endif
                            </td>
                            {{-- Colspan 2 sisa kolom terakhir untuk aksi tombol tambah --}}
                            <td colspan="2" class="text-end">
                                {{-- Logik tombol Tambah atau Terkunci jika limit penuh --}}
                                @if ($isLocked)
                                    <button type="button"
                                        class="btn btn-secondary btn-xs rounded-pill px-3 shadow-none" disabled>
                                        <i class="ph-lock me-1"></i> Limit Kategori Penuh
                                    </button>
                                @else
                                    @if (!$isReadOnly)
                                        <button type="button" wire:click="addBudgetRow({{ $cat->id }})"
                                            class="btn btn-primary btn-xs rounded-pill px-3">
                                            <i class="ph-plus me-1"></i> Tambah Item
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>

                        {{-- Header Kolom (7 Kolom) --}}
                        <tr class="bg-light extra-small text-center fw-bold text-muted" style="font-size: 0.7rem;">
                            <td style="width: 180px;">KOMPONEN</td>
                            <td style="width: 220px;">NAMA BARANG</td>
                            <td style="width: 70px;">VOL</td>
                            <td style="width: 100px;">SATUAN</td>
                            <td style="width: 140px;">HARGA SATUAN</td>
                            <td style="width: 140px;">TOTAL</td>
                            <td style="width: 40px;">#</td>
                        </tr>

                        @php $hasItem = false; @endphp
                        @foreach ($budgetItems as $index => $item)
                            @if ($item['expense_category_id'] == $cat->id)
                                @php $hasItem = true; @endphp
                                <tr wire:key="rab-row-{{ $index }}">
                                    <td><input type="text"
                                            wire:model.blur="budgetItems.{{ $index }}.component"
                                            class="form-control form-control-sm border-0 bg-transparent"
                                            {{ $isReadOnly ? 'disabled' : '' }}></td>
                                    <td><input type="text"
                                            wire:model.blur="budgetItems.{{ $index }}.item_name"
                                            class="form-control form-control-sm border-0 fw-bold bg-transparent"
                                            {{ $isReadOnly ? 'disabled' : '' }}></td>
                                    <td><input type="number" wire:model.blur="budgetItems.{{ $index }}.volume"
                                            class="form-control form-control-sm border-0 text-center bg-transparent"
                                            {{ $isReadOnly ? 'disabled' : '' }}></td>
                                    <td>
                                        <input type="text" wire:model.blur="budgetItems.{{ $index }}.unit"
                                            class="form-control form-control-sm border-0 text-center bg-transparent"
                                            placeholder="Contoh: Rim, Paket, Orang"
                                            {{ $isReadOnly ? 'disabled' : '' }}>
                                    </td>
                                    <td><input type="text"
                                            wire:model.blur="budgetItems.{{ $index }}.unit_price"
                                            class="form-control form-control-sm border-0 text-end bg-transparent"
                                            {{ $isReadOnly ? 'disabled' : '' }}></td>
                                    <td class="text-end fw-bold">Rp
                                        {{ number_format($item['total'] ?? 0, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if (!$isReadOnly)
                                            <button type="button" wire:click="removeBudgetRow({{ $index }})"
                                                class="btn btn-sm text-danger p-0"><i class="ph-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        {{-- Warning jika belum ada item sama sekali --}}
                        @if (!$hasItem)
                            <tr>
                                <td colspan="7" class="text-center py-2 text-muted small italic">
                                    Belum ada item untuk kelompok ini.
                                </td>
                            </tr>
                        @endif

                        {{-- Baris Sub-Total --}}
                        <tr class="bg-light-subtle">
                            <td colspan="5" class="text-end fw-bold py-2">Sub-Total {{ $cat->name }}:</td>
                            <td class="text-end fw-bold text-primary py-2">Rp
                                {{ number_format($categorySubTotal, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                        <tr style="height: 15px;">
                            <td colspan="7" class="border-0"></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-dark">
                    <tr>
                        <th scope="row" colspan="5" class="text-end fw-bold py-3">TOTAL ANGGARAN KESELURUHAN (RAB)
                        </th>
                        <td class="text-end fw-bold text-yellow py-3">Rp
                            {{ number_format($totalRAB ?? 0, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Logik Ringkasan Validasi & Pagu di Bawah --}}
        @php
            $paguSkema = $this->getSchemeMaxBudget();
            $acuan = $paguSkema > 0 ? $paguSkema : 0;
            $totalPercentage = $acuan > 0 ? ($totalRAB / $acuan) * 100 : 0;
            $isOverBudget = $acuan > 0 && $totalRAB > $acuan;
            $isEmpty = $totalRAB <= 0;
            $noSkema = $paguSkema <= 0;

            // Mengembalikan pengecekan kategori yang tidak valid (melebihi batas %)
            $invalidCategories = collect($categoryLimits)->filter(fn($l) => !$l['is_valid']);
            $canProceed = !$isOverBudget && !$isEmpty && !$noSkema && $invalidCategories->isEmpty();
        @endphp

        {{-- Mengembalikan Warning Kategori Invalid jika ada kategori melewati batas persentase --}}
        @if ($invalidCategories->isNotEmpty())
            <div class="alert alert-warning py-2 small mb-3">
                <i class="ph-warning me-2"></i>
                <strong>Kategori berikut melebihi batas persentase:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($invalidCategories as $catId => $lim)
                        @php $catName = $expenseCategories->firstWhere('id', $catId)?->name ?? "Kategori #{$catId}"; @endphp
                        <li>{{ $catName }} — terpakai Rp {{ number_format($lim['spent'], 0, ',', '.') }}, maks Rp
                            {{ number_format($lim['max_rp'], 0, ',', '.') }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Ringkasan Kuota Pagu Skema Visual --}}
        <div class="card card-body border-0 bg-light mt-2 shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex justify-content-between mb-1 small fw-bold">
                        <span>Pemakaian Kuota Pagu Skema</span>
                        <span
                            class="{{ $isOverBudget ? 'text-danger' : 'text-success' }}">{{ number_format($totalPercentage, 1) }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar {{ $isOverBudget ? 'bg-danger' : 'bg-success' }}"
                            style="width: {{ min($totalPercentage, 100) }}%"></div>
                    </div>
                    @if ($isOverBudget)
                        <small class="text-danger mt-1 d-block fw-bold italic">
                            <i class="ph-warning-circle me-1"></i> Total RAB melebihi batas skema!
                        </small>
                    @endif
                </div>

                <div class="col-md-6">
                    <span class="small text-muted d-block">Realisasi Total RAB:</span>
                    <h4 class="fw-bold mb-0 {{ $isOverBudget ? 'text-danger' : 'text-primary' }}">
                        Rp {{ number_format($totalRAB, 0, ',', '.') }}
                        <small class="text-muted fs-6 fw-normal">/ Rp
                            {{ number_format($paguSkema, 0, ',', '.') }}</small>
                    </h4>
                </div>
            </div>
        </div>

        {{-- Footer Navigasi --}}
        <div class="d-flex justify-content-between mt-4 gap-2 border-top pt-3">
            {{-- Tombol Export --}}
            <button type="button" wire:click="exportRAB" class="btn btn-success rounded-pill px-4">
                <i class="ph-microsoft-excel-logo me-2"></i> Export ke PDF
            </button>

            <div class="d-flex gap-2">
                <button type="button" wire:click="nextStep(3)" class="btn btn-light rounded-pill px-4">
                    <i class="ph-arrow-left me-2"></i> Kembali
                </button>
                <button type="button" wire:click="nextStep(5)"
                    class="btn {{ $canProceed ? 'btn-primary' : 'btn-secondary' }} rounded-pill px-4"
                    @if (!$canProceed) disabled title="Selesaikan RAB sesuai ketentuan batas kuota sebelum melanjutkan" @endif>
                    Lanjut ke Mitra <i class="ph-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

    </div>
@endif



