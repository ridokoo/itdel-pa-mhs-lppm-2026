<?php
// ============================================================
// FILE 1: app/Livewire/Dosen/MemberInvitationLivewire.php
// ============================================================

namespace App\Livewire\Dosen;

use Livewire\Component;
use App\Models\ProposalMember;
use App\Helper\WithComponentNameHelper;
use App\Services\NotificationService;  // ← HANYA ini, hapus SystemNotification

class MemberInvitationLivewire extends Component
{
    use WithComponentNameHelper;

    public function mount()
    {
        // Mark as read lewat notification service
        if (auth()->check()) {
            app(\App\Services\NotificationService::class)->markRead(auth()->id());
        }
    }

    public function accept($memberId)
    {
        $member   = ProposalMember::findOrFail($memberId);
        $member->update(['member_status_id' => 2]);

        $proposal = $member->proposal;
        $dosen    = $proposal->leader;

        if ($dosen) {
            app(NotificationService::class)->send(
                userId: $dosen->id,
                title: 'Undangan Diterima Anggota Tim',
                message: auth()->user()->name . " menyetujui undangan bergabung dalam usulan '{$proposal->title}'.",
                options: [
                    'url'         => route('dosen.usulan.form', [
                        'categoryId'  => $proposal->category_id,
                        'id_usulan'   => $proposal->id,
                        'currentStep' => 7,
                    ]),
                    'icon'        => 'ph-user-check',
                    'proposal_id' => $proposal->id,
                    'event_type'  => 'member_acceptance',
                    'roles'       => ['dosen'],
                ]
            );
        }

        $this->dispatch('showSuccessAlert', 'Berhasil menyetujui undangan.');
    }

    public function reject($memberId)
    {
        $member   = ProposalMember::findOrFail($memberId);
        $member->update(['member_status_id' => 3]);

        $proposal = $member->proposal;
        $dosen    = $proposal->leader;

        if ($dosen) {
            app(NotificationService::class)->send(
                userId: $dosen->id,
                title: 'Undangan Ditolak Anggota Tim',
                message: auth()->user()->name . " menolak undangan bergabung dalam usulan '{$proposal->title}'.",
                options: [
                    'url'         => route('dosen.usulan.form', [
                        'categoryId'  => $proposal->category_id,
                        'id_usulan'   => $proposal->id,
                        'currentStep' => 7,
                    ]),
                    'icon'        => 'ph-user-minus',
                    'proposal_id' => $proposal->id,
                    'event_type'  => 'member_rejection',
                    'roles'       => ['dosen'],
                ]
            );
        }

        $this->dispatch('showSuccessAlert', 'Undangan ditolak.');
    }

    public function render()
    {
        $invitations = ProposalMember::where('user_id', auth()->id())
            ->with(['proposal.leader', 'proposal.schemeType'])
            ->orderBy('member_status_id', 'asc') // Yang pending muncul di atas
            ->orderByDesc('updated_at')         // Yang terbaru muncul di atas
            ->take(10)                          // Batasi riwayat (misal 10 data)
            ->get();

        return view('features.dosen.usulan.invitation-livewire', [
            'invitations' => $invitations,
        ]);
    }
}



