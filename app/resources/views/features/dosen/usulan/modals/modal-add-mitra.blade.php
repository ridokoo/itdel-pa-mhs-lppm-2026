<div wire:ignore.self class="modal fade" id="modal_tambah_mitra" tabindex="-1" aria-labelledby="modal_tambah_mitra_title"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white border-0">
                <h6 class="modal-title" id="modal_tambah_mitra_title">Tambah Data Lengkap Mitra</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="mitra_identity">Nomor Identitas (NIK/Paspor)</label>
                        <input type="text" id="mitra_identity" name="mitra_identity" wire:model="mitra_identity"
                            class="form-control" placeholder="Masukkan No. KTP Mitra">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="mitra_name">Nama Lengkap Mitra</label>
                        <input type="text" id="mitra_name" name="mitra_name" wire:model="mitra_name"
                            class="form-control" placeholder="Masukkan Nama Lengkap Mitra">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold" for="mitra_email">Email</label>
                        <input type="email" id="mitra_email" name="mitra_email" wire:model="mitra_email"
                            class="form-control" placeholder="Masukkan Email Mitra">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold" for="mitra_institution">Institusi / Instansi</label>
                        <input type="text" id="mitra_institution" name="mitra_institution"
                            wire:model="mitra_institution" class="form-control" placeholder="Masukkan Institusi / Instansi">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold" for="mitra_qualification">Kualifikasi / Jabatan</label>
                        <input type="text" id="mitra_qualification" name="mitra_qualification"
                            wire:model="mitra_qualification" class="form-control"
                            placeholder="Contoh: Manager / Direktur">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" for="mitra_address">Alamat Institusi / Domisili</label>
                    <textarea id="mitra_address" name="mitra_address" wire:model="mitra_address" class="form-control" rows="2"
                        placeholder="Alamat lengkap mitra"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold" for="mitra_role">Deskripsi Peran / Kontribusi Mitra</label>
                    <textarea id="mitra_role" name="mitra_role" wire:model="mitra_role" class="form-control" rows="3"
                        placeholder="Jelaskan kontribusi nyata mitra dalam penelitian/pkm ini"></textarea>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-link" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="btn_submit_mitra" name="btn_submit_mitra" wire:click="addMitraToList"
                    class="btn btn-success px-4">
                    <i class="ph-check me-2" aria-hidden="true"></i> Tambahkan ke Daftar
                </button>
            </div>
        </div>
    </div>
</div>



