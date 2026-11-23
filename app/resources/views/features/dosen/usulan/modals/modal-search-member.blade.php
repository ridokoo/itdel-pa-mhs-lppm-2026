<div wire:ignore.self class="modal fade" id="modal_search_member" tabindex="-1" aria-labelledby="modalSearchMemberTitle"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white border-0">
                <h6 class="modal-title" id="modalSearchMemberTitle">Cari Anggota
                    {{ $searchType == 'dosen' ? 'Dosen' : 'Non-Dosen' }}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-control-feedback form-control-feedback-start mb-3">
                    {{-- Menambahkan Label (Visual-hidden agar tidak merusak UI tapi terbaca Screen Reader) --}}
                    <label for="searchMemberInput" class="visually-hidden">Input Pencarian Anggota</label>

                    {{-- Menambahkan ID dan Name --}}
                    <input type="text" id="searchMemberInput" name="searchMemberInput"
                        wire:model.live.debounce.500ms="searchMember" class="form-control"
                        placeholder="Cari Nama/Username/NIDN">

                    <div class="form-control-feedback-icon">
                        <i class="ph-magnifying-glass text-muted" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="list-group">
                    @forelse($searchResult as $index => $res)
                        <button type="button" id="btn_add_member_{{ $index }}"
                            name="btn_add_member_{{ $index }}" wire:click="addMember({{ json_encode($res) }})"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold">{{ $res['name'] }}</div>
                                <small class="text-muted">{{ $res['alias'] }} |
                                    {{ $res['id_no'] ?? $res['username'] }}</small>
                            </div>
                            <i class="ph-plus-circle text-primary" aria-hidden="true"></i>
                        </button>
                    @empty
                        @if (strlen($searchMember) >= 3)
                            <div class="text-center py-3 text-muted small">Data tidak ditemukan.</div>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>



