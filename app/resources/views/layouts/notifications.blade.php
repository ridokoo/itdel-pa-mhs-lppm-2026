<div class="offcanvas offcanvas-end bg-dark text-white" tabindex="-1" id="notifications"
    aria-labelledby="notificationsLabel">
    <div class="offcanvas-header border-bottom border-secondary py-2">
        <h5 class="offcanvas-title py-2" id="notificationsLabel">Pemberitahuan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body p-0">
        @if (auth()->check())
            @php
                try {
                    $notifService = app(\App\Services\NotificationService::class);
                    $allNotifs = collect($notifService->getNotifications(auth()->id()) ?? []);
                } catch (\Exception $e) {
                    $allNotifs = collect([]);
                }
            @endphp

            @forelse($allNotifs as $n)
                @php
                    // LOGIKA ASLI ANDA (Tetap dipertahankan)
                    $n = is_array($n) ? $n : (array) $n;
                    $d = isset($n['data']) && is_array($n['data']) ? $n['data'] : (array) ($n['data'] ?? $n);
                    $nId = $n['id'] ?? null;
                    $isRead = !empty($n['read_at']); // Penentu apakah sudah dibaca atau belum
                    $createdAt = isset($n['created_at'])
                        ? \Carbon\Carbon::parse($n['created_at'])->diffForHumans()
                        : '-';
                @endphp

                <!-- Container: Jika sudah dibaca, beri opacity-60 agar terlihat seperti riwayat -->
                <div
                    class="p-3 border-bottom border-secondary {{ $isRead ? 'opacity-60' : 'bg-primary bg-opacity-10 border-start border-start-width-3 border-start-info' }}">
                    <div class="d-flex align-items-start">
                        <!-- Icon: Berubah warna jika sudah dibaca -->
                        <div
                            class="{{ $isRead ? 'bg-secondary' : 'bg-indigo' }} bg-opacity-20 text-indigo rounded-pill p-2 me-3 flex-shrink-0">
                            <i class="{{ $d['icon'] ?? 'ph-bell' }} ph-2x"></i>
                        </div>

                        <div class="flex-fill overflow-hidden">
                            <div class="fw-bold {{ $isRead ? 'text-muted' : 'text-info' }} small">
                                {{ $d['title'] ?? 'Pemberitahuan Sistem' }}
                            </div>
                            <div class="fs-sm text-white-50 mt-1">
                                {{ $d['message'] ?? 'Ada pembaruan baru untuk Anda.' }}
                            </div>

                            <!-- TOMBOL LIHAT DETAIL (Dibuat agar tidak hilang meskipun sudah dibaca) -->
                            @if ($nId)
                                <div class="mt-2">
                                    <a href="{{ route('notification.open', ['id' => $nId]) }}"
                                        class="btn {{ $isRead ? 'btn-outline-secondary opacity-50' : 'btn-primary' }} btn-xs rounded-pill px-3">
                                        <i class="ph-arrow-circle-right me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            @endif

                            <div class="fs-xs text-muted mt-2">{{ $createdAt }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center text-muted">
                    <i class="ph-bell-slash ph-3x mb-3 opacity-20 d-block"></i>
                    <p class="small">Tidak ada pemberitahuan baru.</p>
                </div>
            @endforelse
        @endif
    </div>
</div>



