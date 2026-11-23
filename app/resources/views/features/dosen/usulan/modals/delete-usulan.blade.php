<div wire:ignore.self class="modal fade" id="modal_hapus_usulan" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center pt-4">
                <div class="text-danger mb-3">
                    <i class="ph-warning-circle ph-4x"></i>
                </div>
                <h5 class="mb-1">Hapus Usulan?</h5>
                <p class="text-muted small">Tindakan ini akan menghapus data draf usulan beserta anggotanya.</p>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                {{-- PENTING: Tombol ini harus memanggil fungsi destroy --}}
                <button type="button" wire:click="destroy" class="btn btn-danger">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>



