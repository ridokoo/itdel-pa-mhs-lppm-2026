<div wire:ignore.self class="modal fade" id="modal_hapus_scheme_type" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm text-center" style="border-radius: 12px; background-color: #2b2f3a;">

            <div class="modal-body pt-4">

                <!-- ICON -->
                <div class="mb-3">
                    <i class="ph-warning-circle ph-4x" style="color: #dc3545;"></i>
                </div>

                <!-- TITLE -->
                <h5 class="mb-1 text-white fw-bold">
                    Apakah data dihapus?
                </h5>

                <!-- DESC -->
                <p class="text-light opacity-75">
                    Data ini mungkin sudah digunakan dalam usulan proposal.
                </p>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer justify-content-center border-0 pb-4">

                <button type="button"
                        class="btn btn-light px-3"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="button"
                        wire:click="destroy"
                        class="btn text-white px-3"
                        style="background-color: #dc3545; border-color: #dc3545;">
                    Ok
                </button>

            </div>

        </div>
    </div>
</div>



