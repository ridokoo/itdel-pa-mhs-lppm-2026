    <style>
        /* Mengubah warna switch saat aktif menjadi HIJAU */
        .custom-switch .form-check-input:checked {
            background-color: #198754 !important;
            /* Hijau Success */
            border-color: #198754 !important;
        }

        /* Mengubah warna switch saat tidak aktif menjadi PUTIH/ABU */
        .custom-switch .form-check-input {
            background-color: #ffffff;
            border: 2px solid #dee2e6;
            width: 50px;
            height: 25px;
            cursor: pointer;
        }
    </style>

    {{-- ============================================================ --}}
    {{-- STEP 7: Konfirmasi & Review Akhir --}}
    {{-- ============================================================ --}}
    @if ($currentStep == 7)
        @php
            $isAllApproved = true;
            $dbMembers = collect([]);
            if ($id_usulan) {
                $proposalAsli = \App\Models\Proposal::with(['proposalMembers.user', 'leader'])->find($id_usulan);
                if ($proposalAsli) {
                    $dbMembers = $proposalAsli->proposalMembers;
                    foreach ($dbMembers as $m) {
                        if ($m->member_status_id != 2) {
                            $isAllApproved = false;
                        }
                    }
                }
            } else {
                $isAllApproved = false;
            }
            $ketuaUser = $proposalAsli?->leader ?? $localUser;

            // --- LOGIKA VALIDASI DATABASE ---
            $minRequired = $this->getMinMemberLimit();
            $currentMemberCount = count($addedDosen) + count($addedStaff);
            $isMemberCountValid = $currentMemberCount >= $minRequired;
        @endphp

        <div class="card border-0 shadow-sm p-4">

            {{-- HEADER --}}
            <div class="text-center mb-4">
                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-2"
                    style="width:64px;height:64px;">
                    <i class="ph-check-circle ph-3x text-success"></i>
                </div>
                <h4 class="fw-bold mb-1">Review Final Usulan</h4>
                <p class="text-muted small mb-0">Periksa kembali seluruh rincian data sebelum pengiriman final ke LPPM.
                </p>
            </div>

            {{-- WARNING JIKA BELUM LENGKAP --}}
            @if (!$isAllApproved || !$existing_proposal || !$isMemberCountValid)
                <div class="alert alert-danger border-danger border-opacity-25 small py-2 mb-4 rounded-3">
                    <i class="ph-warning-circle me-2"></i>
                    <strong>Submit terkunci:</strong>
                    <ul class="mb-0 mt-1">
                        @if (!$isMemberCountValid)
                            <li>Jumlah anggota (Dosen/Staff) belum memenuhi syarat skema (Minimal:
                                <b>{{ $minRequired }}
                                    orang</b>).
                            </li>
                        @endif
                        @if (!$isAllApproved)
                            <li>Seluruh anggota tim belum menyetujui undangan.</li>
                        @endif
                        @if (!$existing_proposal)
                            <li>Berkas proposal final belum diunggah di Step 6.</li>
                        @endif
                    </ul>
                </div>
            @endif

            {{-- I. IDENTITAS USULAN --}}
            <div class="mb-4 p-3 rounded-3 border border-primary border-opacity-25 bg-primary bg-opacity-10">
                <h6 class="fw-bold text-primary mb-3"><i class="ph-info me-2"></i> I. Identitas Usulan</h6>
                <table class="table table-sm table-borderless small mb-0">
                    <tr>
                        <td class="fw-bold text-nowrap" style="width:200px">Judul Usulan</td>
                        <td class="text-dark">: {{ $title }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Ringkasan / Abstrak</td>
                        <td class="text-dark">: {{ $summary ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Skema & Bidang Fokus</td>
                        <td>:
                            <span
                                class="badge bg-indigo">{{ collect($schemeTypes)->firstWhere('id', $scheme_type_id)?->name }}</span>
                            <i class="ph-caret-right mx-1 small"></i>
                            <span
                                class="badge bg-primary">{{ collect($scopes)->firstWhere('id', $scope_id)?->name }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Target TKT & Durasi</td>
                        <td class="text-dark">: Level
                            {{ collect($targetTkts)->firstWhere('id', $target_tkt_id)?->level }} |
                            {{ $duration_months }} Tahun</td>
                    </tr>
                    <tr>
                        <td class="fw-bold text-success">Total Anggaran</td>
                        <td>: <span class="fw-bold text-success">Rp {{ $total_budget }}</span></td>
                    </tr>
                </table>
            </div>

            {{-- II. PERSONIL TIM --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-2"><i class="ph-users-three me-2"></i> II. Susunan Personil Tim</h6>
                <div class="table-responsive border rounded-3 shadow-sm overflow-hidden">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase text-center">
                                <th class="text-start ps-3 py-2">Nama Lengkap</th>
                                <th class="py-2">Peran</th>
                                <th class="py-2">Status Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <tr class="bg-primary bg-opacity-5">
                                <td class="ps-3 py-2 fw-bold text-primary">{{ strtoupper($ketuaUser->name) }}</td>
                                <td class="text-center text-dark">Ketua Pengusul</td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill px-3 py-2">PENGUSUL</span>
                                </td>
                            </tr>
                            @foreach ($dbMembers as $m)
                                <tr>
                                    <td class="ps-3 py-2 text-dark">{{ $m->user->name }}</td>
                                    <td class="text-center text-dark">Anggota Tim</td>
                                    <td class="text-center">
                                        @if ($m->member_status_id == 2)
                                            <span class="badge bg-success rounded-pill px-3 py-2"
                                                style="min-width: 100px;">SETUJU</span>
                                        @elseif ($m->member_status_id == 3)
                                            <span class="badge bg-danger rounded-pill px-3 py-2"
                                                style="min-width: 100px;">MENOLAK</span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2"
                                                style="min-width: 100px;">MENUNGGU</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- III. TARGET LUARAN --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-2"><i class="ph-star-four me-2"></i> III. Target Luaran</h6>
                <div class="table-responsive border rounded-3 shadow-sm">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light small text-center text-uppercase">
                            <tr>
                                <th class="py-2" style="width: 200px;">Kategori</th>
                                <th class="py-2" style="width: 250px;">Detail Target</th> {{-- GANTI HEADER --}}
                                <th class="py-2">Keterangan Tambahan</th>
                            </tr>
                        </thead>
                        <tbody class="small text-center">
                            @forelse ($outputTargets as $out)
                                @if (!empty($out['output_target_type_id']))
                                    <tr>
                                        <td class="fw-bold text-indigo">
                                            {{ \App\Models\OutputTargetCategory::find($out['output_target_category_id'])?->name ?? '-' }}
                                        </td>
                                        <td class="fw-bold text-success"> {{-- MENAMPILKAN DETAIL --}}
{{ $out['output_target_detail_id'] ? \App\Models\OutputTargetDetail::find($out['output_target_detail_id'])?->name ?? '-' : '-' }}
                                        </td>
                                        <td class="text-dark">{{ $out['description'] ?: '-' }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada data luaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- IV. RENCANA ANGGARAN BIAYA (RAB) --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-2"><i class="ph-money me-2"></i> IV. Rincian Anggaran (RAB)</h6>
                <div class="table-responsive border rounded-3 shadow-sm">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light small text-center text-uppercase">
                            <tr>
                                <th class="text-start ps-3 py-2">Barang / Komponen</th>
                                <th class="py-2">Volume</th>
                                <th class="text-end py-2">Harga Satuan</th>
                                <th class="text-end pe-3 py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody class="small text-dark">
                            @foreach ($budgetItems as $item)
                                <tr>
                                    <td class="ps-3">{{ $item['item_name'] }}<br><small
                                            class="text-muted">{{ $item['component'] }}</small></td>
                                    <td class="text-center">{{ $item['volume'] }}</td>
                                    <td class="text-end">Rp
                                        {{ number_format((float) str_replace('.', '', $item['unit_price']), 0, ',', '.') }}
                                    </td>
                                    <td class="text-end pe-3 fw-bold">Rp
                                        {{ number_format($item['total'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">TOTAL KESELURUHAN RAB</td>
                                <td class="text-end pe-3 fw-bold text-warning">Rp
                                    {{ number_format($totalRAB, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- V. DATA MITRA --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-2"><i class="ph-handshake me-2"></i> V. Data Mitra Penelitian/PKM
                </h6>
                <div class="table-responsive border rounded-3 shadow-sm">
                    <table class="table table-sm table-hover align-middle mb-0 small">
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th class="ps-3 py-2">Nama Mitra</th>
                                <th class="py-2">Institusi</th>
                                <th class="py-2">Peran</th>
                            </tr>
                        </thead>
                        <tbody class="text-dark">
                            @forelse ($addedMitra as $m)
                                <tr>
                                    <td class="ps-3">{{ $m['name'] }}</td>
                                    <td>{{ $m['institution'] }}</td>
                                    <td>{{ $m['role_description'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Tidak ada data mitra
                                        (Opsional)
                                        .</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- VI. BERKAS PROPOSAL FINAL (SEKARANG PALING TERAKHIR) --}}
            <div class="mb-4">
                <h6 class="fw-bold text-primary mb-2"><i class="ph-file-arrow-up me-2"></i> VI. Berkas Proposal Final
                </h6>
                <div class="p-3 border rounded-3 bg-body-tertiary d-flex align-items-center">
                    @if ($existing_proposal)
                        <div class="d-flex align-items-center gap-3 w-100">
                            <i class="ph-file-pdf ph-4x text-danger flex-shrink-0"></i>
                            <div class="flex-fill overflow-hidden">
                                <div class="fw-bold text-dark">File Proposal Terunggah:</div>
                                <div class="text-muted small mb-2 text-truncate">{{ basename($existing_proposal) }}
                                </div>
                                <a href="{{ asset('storage/' . $existing_proposal) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary rounded-pill shadow-none px-3">
                                    <i class="ph-eye me-1"></i> Lihat Dokumen Proposal
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-danger fw-bold small">
                            <i class="ph-warning-circle me-1"></i> Berkas proposal belum diunggah! Silakan kembali ke
                            Step
                            6.
                        </div>
                    @endif
                </div>
            </div>

            {{-- VII. PERNYATAAN INTEGRITAS --}}
            <div class="card border-0 shadow-sm mb-4 {{ $isAgreed ? 'bg-success bg-opacity-10' : 'bg-white' }}"
                style="transition: 0.3s;">
                <div class="card-body p-4">
                    <div class="form-check form-switch custom-switch d-flex align-items-center mb-0">
                        <input class="form-check-input shadow-none" type="checkbox" role="switch" id="checkAgreed"
                            wire:model.live="isAgreed">

                        <label class="form-check-label ms-3 fw-bold {{ $isAgreed ? 'text-success' : 'text-muted' }}"
                            for="checkAgreed" style="cursor: pointer; font-size: 1rem;">
                            Saya menyatakan bahwa seluruh data yang diisikan sudah benar dan sesuai dengan panduan.
                            Saya bertanggung jawab penuh atas keabsahan dokumen yang diunggah.
                        </label>
                    </div>
                </div>
            </div>

            {{-- NAVIGATION --}}
            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <a href="{{ route('dosen.usulan.index', ['categoryId' => $categoryId]) }}"
                    class="btn btn-light rounded-pill px-4">
                    <i class="ph-arrow-left me-2"></i> Kembali ke Daftar
                </a>

                @if (!$isReadOnly && $isLeader)
                    <div class="d-flex gap-2">
                        <button wire:click="nextStep(6)" class="btn btn-light rounded-pill px-4">
                            <i class="ph-arrow-left me-2"></i> Kembali
                        </button>
                        <button wire:click="saveUsulan" class="btn btn-info rounded-pill px-4">
                            <i class="ph-floppy-disk me-2"></i> Simpan Draft
                        </button>
                        <button wire:click="finalSubmit" @if (!$isAllApproved || !$existing_proposal || !$isAgreed || !$isMemberCountValid) disabled @endif
                            class="btn {{ $isAllApproved && $existing_proposal && $isAgreed && $isMemberCountValid ? 'btn-success' : 'btn-secondary' }} rounded-pill px-4 shadow-sm">
                            <i class="ph-paper-plane-tilt me-2"></i> Kirim Usulan Final
                        </button>
                    </div>
                @endif

                @if ($isReadOnly)
                    <span class="badge bg-secondary py-2 px-3 rounded-pill">
                        <i class="ph-eye me-1"></i> Mode Lihat — Tidak dapat melakukan aksi
                    </span>
                @endif
            </div>

        </div>
    @endif



