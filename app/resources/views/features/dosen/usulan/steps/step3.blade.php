{{-- ============================================================ --}}
{{-- STEP 3: Luaran & Substansi (REVISI) --}}
{{-- ============================================================ --}}
@if ($currentStep == 3)
    <div class="card border-0 shadow-sm p-4">
        <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">3.1 Target Luaran Proposal</h6>

        @foreach ($outputTargetTypes as $type)
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-sm mb-0">
                    <thead>
                        <tr class="table-active">
                            <th colspan="3" class="fw-bold text-indigo py-2">
                                <i class="ph-star-four me-2"></i> LUARAN {{ strtoupper($type->name) }}
                            </th>
                            <th class="text-end py-2">
                                @if (!$isReadOnly)
                                    <button type="button" wire:click="addOutputRow({{ $type->id }})"
                                        class="btn btn-primary btn-xs rounded-pill px-3 shadow-sm">
                                        <i class="ph-plus me-1"></i> Tambah
                                    </button>
                                @endif
                            </th>
                        </tr>
                        <tr class="bg-light text-center small fw-bold">
                            <th scope="col" style="width: 200px;">Kategori</th>
                            <th scope="col" style="width: 250px;">Detail Luaran</th> {{-- GANTI STATUS JADI DETAIL --}}
                            <th scope="col">Keterangan</th>
                            <th scope="col" style="width: 50px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $hasItem = false; @endphp
                        @foreach ($outputTargets as $index => $row)
                            @if ($row['output_target_type_id'] == $type->id)
                                @php $hasItem = true; @endphp
                                <tr wire:key="output-row-{{ $index }}">
                                    {{-- Kolom Kategori --}}
                                    <td>
                                        <select
                                            wire:model.live="outputTargets.{{ $index }}.output_target_category_id"
                                            class="form-select form-select-sm border-0 bg-dark text-white">
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach ($categoriesByType[$type->id] ?? [] as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- Kolom Detail Luaran (Cascading Dropdown) --}}
                                    <td>
                                        <select
                                            wire:model.live="outputTargets.{{ $index }}.output_target_detail_id"
                                            class="form-select form-select-sm border-0 bg-dark text-white"
                                            {{ empty($row['output_target_category_id']) ? 'disabled' : '' }}>
                                            <option value="">-- Pilih Detail (Sinta/Scopus/dll) --</option>
                                            @if (!empty($row['output_target_category_id']))
                                                @foreach ($detailsByCategory[$row['output_target_category_id']] ?? [] as $det)
                                                    <option value="{{ $det->id }}">{{ $det->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>

                                    {{-- Kolom Keterangan --}}
                                    <td>
                                        <input type="text"
                                            wire:model.blur="outputTargets.{{ $index }}.description"
                                            class="form-control form-control-sm border-0 bg-transparent"
                                            placeholder="Nama jurnal/konferensi/produk...">
                                    </td>

                                    <td class="text-center">
                                        @if (!$isReadOnly)
                                            <button type="button" wire:click="removeOutputRow({{ $index }})"
                                                class="btn btn-sm text-danger p-0">
                                                <i class="ph-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        @if (!$hasItem)
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted small italic">
                                    Belum ada data {{ strtolower($type->name) }}. Klik tombol Tambah.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach

{{-- FOOTER NAVIGASI --}}
<div class="d-flex justify-content-end mt-4 gap-2 border-top pt-3">
    <button type="button" id="btn_back_step2" name="btn_back_step2" wire:click="nextStep(2)"
        class="btn btn-light rounded-pill px-4 shadow-sm">
        <i class="ph-arrow-left me-2" aria-hidden="true"></i> Kembali
    </button>
    <button type="button" id="btn_next_step4" name="btn_next_step4" wire:click="nextStep(4)"
        class="btn btn-primary rounded-pill px-4 shadow-sm">
        Lanjut ke RAB <i class="ph-arrow-right ms-2" aria-hidden="true"></i>
    </button>
</div>
</div>
@endif



