<div wire:ignore.self class="modal fade" id="modal_hapus_rev_status" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center pt-4">
                <i class="ph-warning-circle ph-4x text-danger mb-3"></i>
                <h5>Hapus Status?</h5>
            </div>
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>

                {{-- KUNCI: Harus memanggil fungsi destroy --}}
                <button type="button" wire:click="destroy" class="btn btn-danger">
                    <span wire:loading wire:target="destroy" class="spinner-border spinner-border-sm me-1"></span>
                    Ya, Hapus Sekarang
                </button>
            </div>
        </div>
    </div>
</div>



