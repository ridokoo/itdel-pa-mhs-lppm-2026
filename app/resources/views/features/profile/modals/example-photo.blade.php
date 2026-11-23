<div>
    <div wire:ignore.self class="modal fade" id="examplePhotoModal" tabindex="-1" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                {{-- Modal Header --}}
                <div class="modal-header modal-colored-header bg-primary text-white sticky-top">
                    <h4 class="modal-title text-white">
                        Contoh Foto Profil
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- Modal Body --}}
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img class="img-thumbnail" alt="Contoh Foto Profil" src="/assets/img/user/example.png"
                            style="max-width: 250px; max-height: 250px;" />
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-footer sticky-bottom bg-light">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>



