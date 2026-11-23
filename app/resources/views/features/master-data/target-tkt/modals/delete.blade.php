<div wire:ignore.self class="modal fade" id="modal_hapus_tkt" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center">

            <div class="modal-body pt-4">
                <div class="mb-3">
                    <i class="ph-warning-circle ph-4x text-danger"></i>
                </div>

                <h5 class="mb-1">Apakah Data dihapus?</h5>

                <p class="text-muted small">
                    Data ini tidak dapat dihapus jika sudah digunakan oleh usulan proposal.
                </p>
            </div>

            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button"
                        class="btn btn-light px-3"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button"
                        wire:click="destroy"
                        class="btn btn-danger px-3">
                    Ya, Hapus
                </button>
            </div>

        </div>
    </div>
</div>



