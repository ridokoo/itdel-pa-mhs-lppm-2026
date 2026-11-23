<div wire:ignore.self class="modal fade" id="modal_hapus_target_category" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background-color: #2b2f3a;">

            <!-- BODY -->
            <div class="modal-body text-center pt-4 text-white">
                <div class="mb-3">
                    <i class="ph-warning-circle ph-4x text-danger"></i>
                </div>

                <h5 class="mb-1 text-white">Hapus Data?</h5>
                <p class="text-white-50">
                    Data yang dihapus tidak bisa dikembalikan.
                </p>
            </div>

            <!-- FOOTER -->
            <div class="modal-footer justify-content-center border-0 pb-4">
                <button type="button"
                        class="btn btn-outline-light px-3"
                        data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button"
                        wire:click="destroy"
                        class="btn px-3 text-white"
                        style="background-color: #dc3545; border-color: #dc3545;">
                    Hapus
                </button>
            </div>

        </div>
    </div>
</div>



