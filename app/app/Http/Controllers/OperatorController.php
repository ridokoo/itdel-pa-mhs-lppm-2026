<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Livewire\Master\FakultasLivewire;
use App\Livewire\Reviewer\ReviewerManagerLivewire;


class OperatorController extends Controller
{
    public function fakultas(Request $request)
    {
        // Pastikan hanya operator yang masuk (Extra Security)
        if ($request->auth->role != 'operator') {
            abort(403, 'Anda bukan operator!');
        }

        $data = [
            'auth' => $request->auth, // Kirim data API ke tampilan
            'title' => 'Manajemen Fakultas',
            'livewire' => FakultasLivewire::getComponentName(), // Gunakan helper Anda
        ];

        return view('app.operator.fakultas', $data);
    }

    public function prodi(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Program Studi',
            'livewire' => \App\Livewire\Master\ProdiLivewire::getComponentName(),
        ];
        return view('app.operator.prodi', $data);
    }

    public function jabatanFungsional(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Jabatan Fungsional',
            'livewire' => \App\Livewire\Master\JabatanFungsionalLivewire::getComponentName(),
        ];
        return view('app.operator.jabatan-fungsional', $data);
    }

    public function proposalStatusHistory(Request $request)
    {
        $data = [
            'auth'    => $request->auth,
            'title'   => 'History Status Proposal',
            'livewire' => \App\Livewire\Master\ProposalStatusHistoryLivewire::getComponentName(),
        ];
        return view('app.operator.proposal-status-history', $data);
    }
    public function kategori(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Kategori Kegiatan',
            'livewire' => \App\Livewire\Master\CategoryLivewire::getComponentName(),
        ];
        return view('app.operator.kategori', $data);
    }

    public function kelompokSkema(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Kelompok Skema',
            'livewire' => \App\Livewire\Master\SchemeGroupLivewire::getComponentName(),
        ];
        return view('app.operator.kelompok-skema', $data);
    }

    public function schemeType(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Tipe Skema Kegiatan',
            'livewire' => \App\Livewire\Master\SchemeTypeLivewire::getComponentName(),
        ];
        return view('app.operator.tipe-skema', $data);
    }

    public function indeks(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Daftar Indeks Database',
            'livewire' => \App\Livewire\Master\DatabaseIndexLivewire::getComponentName(),
        ];
        return view('app.operator.indeks', $data);
    }

    public function proposalStatus(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Status Proposal',
            'livewire' => \App\Livewire\Master\ProposalStatusLivewire::getComponentName(),
        ];
        return view('app.operator.status-proposal', $data);
    }

    public function memberStatus(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Status Anggota Proposal',
            'livewire' => \App\Livewire\Master\MemberStatusLivewire::getComponentName(),
        ];
        return view('app.operator.member-status', $data);
    }

    public function reviewer(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Reviewer',
            // Sekarang ini tidak akan error lagi
            'livewire' => ReviewerManagerLivewire::getComponentName(),
        ];

        return view('app.operator.reviewer', $data);
    }

    public function scopes(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Ruang Lingkup',
            'livewire' => \App\Livewire\Master\ScopeLivewire::getComponentName(),
        ];
        return view('app.operator.scopes', $data);
    }

    public function monitoringUsulan(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Monitoring Usulan Proposal',
            'livewire' => \App\Livewire\Operator\ProposalMonitoringLivewire::getComponentName(),
        ];
        return view('app.operator.monitoring-proposal', $data);
    }

    public function proposalPeriod(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Periode Proposal',
            'livewire' => \App\Livewire\Master\ProposalPeriodLivewire::getComponentName(),
        ];
        return view('app.operator.proposalperiod', $data);
    }

    public function kriteriaSkema(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Kriteria Skema',
            'livewire' => \App\Livewire\Master\SchemeCriteriaLivewire::getComponentName(),
        ];
        return view('app.operator.skemakriteria', $data);
    }

    public function luaranSkema(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Luaran Skema',
            'livewire' => \App\Livewire\Master\SchemeOutputLivewire::getComponentName(),
        ];
        return view('app.operator.luaran-skema', $data);
    }

    public function kategoriBiaya(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Kategori Biaya (RAB)',
            'livewire' => \App\Livewire\Master\ExpenseCategoryLivewire::getComponentName(),
        ];
        return view('app.operator.kategori-biaya', $data);
    }

    public function outputTargetType(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Jenis Target Luaran',
            'livewire' => \App\Livewire\Master\OutputTargetTypeLivewire::getComponentName(),
        ];
        return view('app.operator.output-target-type', $data);
    }

    public function outputTargetCategory(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Kategori Target Luaran',
            'livewire' => \App\Livewire\Master\OutputTargetCategoryLivewire::getComponentName(),
        ];
        return view('app.operator.output-target-category', $data);
    }

    public function outputTargetDetail(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Detail Jenis Luaran',
            'livewire' => \App\Livewire\Master\OutputTargetDetailLivewire::getComponentName(),
        ];
        return view('app.operator.output-target-detail', $data);
    }

    public function outputTargetStatus(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Status Capaian Luaran',
            'livewire' => \App\Livewire\Master\OutputTargetStatusLivewire::getComponentName(),
        ];
        return view('app.operator.output-target-status', $data);
    }

    public function targetTkt(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Manajemen Level TKT',
            'livewire' => \App\Livewire\Master\TargetTktLivewire::getComponentName(),
        ];
        return view('app.operator.target-tkt', $data);
    }

    public function penugasanReviewer(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Penugasan Reviewer (Plotting)',
            'livewire' => \App\Livewire\Operator\AssignReviewerLivewire::getComponentName(),
        ];
        return view('app.operator.assign-reviewer', $data);
    }

    public function reviewerStatus(Request $request)
    {
        $data = [
            'auth' => $request->auth,
            'title' => 'Master Status Reviewer',
            'livewire' => \App\Livewire\Master\ReviewerStatusLivewire::getComponentName(),
        ];
        return view('app.operator.reviewer-status', $data);
    }
    public function dashboard(Request $request)
    {
        $data = [
            'auth'           => $request->auth,
            'title'          => 'Dashboard Operator',
            'total_fakultas' => \App\Models\Faculty::count(),
            'total_prodi'    => \App\Models\StudyProgram::count(),
            'total_indeks'   => \App\Models\DatabaseIndex::where('is_active', 1)->count(),
            'total_kontak'   => \App\Models\Contact::count(),
        ];

        return view('app.operator.dashboard', $data);
    }
    public function statusFinalUsulan()
    {
        return view('features.operator.status-final-usulan.index');
    }
}
