<div>
    <form wire:submit.prevent="editPhoto">
        <div wire:ignore.self class="modal fade" id="editPhotoModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
                <div class="modal-content">
                    {{-- Modal Header --}}
                    <div class="modal-header modal-colored-header bg-primary text-white sticky-top">
                        <h4 class="modal-title text-white">
                            Sesuaikan Foto Profil
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">
                        <!-- Input File -->
                        <div class="mb-3 text-center">
                            <input type="file" id="upload" accept="image/*" class="d-none">
                            <label for="upload" class="btn btn-primary">Pilih Foto</label>
                            @error('photo')
                                <div>
                                    <span class="text-danger">Gagal mengupload foto</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Container Crop -->
                        <div id="crop-container" class="d-none mb-3">
                            <div id="croppie" class="mx-auto"></div>
                            <div class="text-center mt-3">
                                {{-- <button type="button" id="reset-btn" class="btn btn-outline-dark me-2">Reset</button> --}}
                                <button type="button" id="crop-btn" class="btn btn-success ms-2">
                                    Crop Photo
                                </button>
                            </div>
                        </div>

                        <input type="hidden" id="cropped-image" wire:model="photo">

                        <!-- Hasil -->
                        <div id="result-section" class="d-none text-center">
                            <h5>Hasil Crop:</h5>
                            <img id="preview" class="img-thumbnail mt-2" src="" alt="Preview">
                            @error('photo')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i>
                                    Simpan
                                </button>
                            </div>
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
    </form>
</div>



