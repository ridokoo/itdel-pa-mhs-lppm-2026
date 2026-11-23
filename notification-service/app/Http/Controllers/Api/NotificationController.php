<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    // ── Kirim notifikasi ke 1 user ──────────────────────────
    public function send(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|integer',
            'title'       => 'required|string',
            'message'     => 'required|string',
            'url'         => 'nullable|string',
            'icon'        => 'nullable|string',
            'event_type'  => 'nullable|string',
            'proposal_id' => 'nullable|integer',
            'roles'       => 'nullable|array',
        ]);

        try {
            // Format data SAMA dengan SystemNotification di main app
            $data = json_encode([
                'title'       => $request->title,
                'message'     => $request->message,
                'url'         => $request->url ?? '#',
                'icon'        => $request->icon ?? 'ph-bell',
                'proposal_id' => $request->proposal_id,
                'event_type'  => $request->event_type ?? 'general',
                'roles'       => $request->roles ?? [],
            ]);

            // Simpan ke tabel notifications
            DB::table('notifications')->insert([
                'id'              => (string) Str::uuid(),
                'type'            => 'App\Notifications\SystemNotification',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id'   => $request->user_id,
                'data'            => $data,
                'read_at'         => null,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // Simpan log
            DB::table('notification_logs')->insert([
                'user_id'     => $request->user_id,
                'title'       => $request->title,
                'message'     => $request->message,
                'channel'     => 'database',
                'status'      => 'sent',
                'event_type'  => $request->event_type,
                'proposal_id' => $request->proposal_id,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dikirim',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── Kirim ke banyak user ────────────────────────────────
    public function sendBulk(Request $request)
    {
        $request->validate([
            'user_ids'    => 'required|array',
            'user_ids.*'  => 'integer',
            'title'       => 'required|string',
            'message'     => 'required|string',
            'url'         => 'nullable|string',
            'icon'        => 'nullable|string',
            'event_type'  => 'nullable|string',
            'proposal_id' => 'nullable|integer',
        ]);

        $sent   = 0;
        $failed = 0;

        foreach ($request->user_ids as $userId) {
            try {
                $data = json_encode([
                    'title'       => $request->title,
                    'message'     => $request->message,
                    'url'         => $request->url ?? '#',
                    'icon'        => $request->icon ?? 'ph-bell',
                    'proposal_id' => $request->proposal_id ?? null,
                    'event_type'  => $request->event_type ?? 'general',
                ]);

                DB::table('notifications')->insert([
                    'id'              => (string) Str::uuid(),
                    'type'            => 'App\Notifications\SystemNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $userId,
                    'data'            => $data,
                    'read_at'         => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                DB::table('notification_logs')->insert([
                    'user_id'     => $userId,
                    'title'       => $request->title,
                    'message'     => $request->message,
                    'channel'     => 'database',
                    'status'      => 'sent',
                    'event_type'  => $request->event_type ?? null,
                    'proposal_id' => $request->proposal_id ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                $sent++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        return response()->json([
            'success'      => true,
            'sent_count'   => $sent,
            'failed_count' => $failed,
        ]);
    }

    // ── Ambil notifikasi milik user ─────────────────────────
    public function getByUser(int $userId)
    {
        $notifications = DB::table('notifications')
            ->where('notifiable_id', $userId)
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->map(function ($n) {
                $data = json_decode($n->data, true);
                return [
                    'id'         => $n->id,
                    'title'      => $data['title'] ?? '',
                    'message'    => $data['message'] ?? '',
                    'url'        => $data['url'] ?? '#',
                    'icon'       => $data['icon'] ?? 'ph-bell',
                    'event_type' => $data['event_type'] ?? '',
                    'read_at'    => $n->read_at,
                    'created_at' => $n->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'count'   => $notifications->count(),
            'data'    => $notifications,
        ]);
    }

    public function markAsReadSingle(string $id)
    {
        DB::table('notifications')
            ->where('id', $id)
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai dibaca'
        ]);
    }

    // ── Tandai semua sudah dibaca ───────────────────────────
    public function markRead(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);

        $updated = DB::table('notifications')
            ->where('notifiable_id', $request->user_id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success'       => true,
            'marked_count'  => $updated,
        ]);
    }
}

