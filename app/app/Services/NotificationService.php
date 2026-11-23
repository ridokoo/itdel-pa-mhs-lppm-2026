<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.notification.url', 'http://localhost:8001');
        $this->apiKey  = config('services.notification.key') ?? '';  // ← baris 16
    }

    public function send(int $userId, string $title, string $message, array $options = []): bool
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept'    => 'application/json',
            ])->post("{$this->baseUrl}/api/notifications/send", [
                'user_id'     => $userId,
                'title'       => $title,
                'message'     => $message,
                'url'         => $options['url'] ?? '#',
                'icon'        => $options['icon'] ?? 'ph-bell',
                'proposal_id' => $options['proposal_id'] ?? null,
                'event_type'  => $options['event_type'] ?? 'general',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            // Kalau notif service down, main app tetap jalan
            Log::warning('Notification service tidak tersedia: ' . $e->getMessage());
            return false;
        }
    }

    public function sendBulk(array $userIds, string $title, string $message, array $options = []): bool
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->post("{$this->baseUrl}/api/notifications/send-bulk", [
                'user_ids'    => $userIds,
                'title'       => $title,
                'message'     => $message,
                'url'         => $options['url'] ?? '#',
                'icon'        => $options['icon'] ?? 'ph-bell',
                'proposal_id' => $options['proposal_id'] ?? null,
                'event_type'  => $options['event_type'] ?? 'general',
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::warning('Notification service tidak tersedia: ' . $e->getMessage());
            return false;
        }
    }

    public function getNotifications(int $userId): array
    {
        if (!$userId) {
            return [];
        }

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->get("{$this->baseUrl}/api/notifications/{$userId}");

            if ($response->successful()) {
                return $response->json('data', []);
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function markRead(int $userId): bool
    {
        if (!$userId) return 0;

        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->post("{$this->baseUrl}/api/notifications/read", [
                'user_id' => $userId,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getCount(int $userId): int
    {
        try {
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
            ])->get("{$this->baseUrl}/api/notifications/{$userId}");

            return $response->json('count', 0);
        } catch (\Exception $e) {
            return 0;
        }
    }
}
