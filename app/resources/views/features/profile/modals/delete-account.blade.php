<div>
    <form wire:submit.prevent="deleteAccount">
        <div wire:ignore.self class="modal fade" id="deleteAccountModal" tabindex="-1" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    {{-- Modal Header --}}
                    <div class="modal-header modal-colored-header bg-danger text-white sticky-top">
                        <h4 class="modal-title text-white">
                            Hapus Akun
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="modal-body">
                        <div class="alert alert-danger text-danger" role="alert">
                            Saya dengan sadar mengambil tindakan untuk menghapus akun saya.
                        </div>
                        <div class="mt-3">
                            <label>Konfirmasi Kata Sandi</label>
                            <input class="form-control @error('deleteAccountPassword') is-invalid @enderror"
                                type="password" wire:model="deleteAccountPassword">
                            @error('deleteAccountPassword')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label>Konfirmasi Kode OTP</label>
                            <input class="form-control @error('deleteAccountKode') is-invalid @enderror" type="number"
                                wire:model="deleteAccountKode">
                            @error('deleteAccountKode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="modal-footer sticky-bottom bg-light">
                        <button type="submit" class="btn btn-outline-danger" wire:loading.attr="disabled">
                            <span wire:loading wire:target="delete"
                                class="spinner-border spinner-border-sm me-1"></span>
                            <span wire:loading.remove wire:target="delete">Hapus</span>
                            <span wire:loading wire:target="delete">Menghapus...</span>
                        </button>
                    </div>

                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </form>
</div>



