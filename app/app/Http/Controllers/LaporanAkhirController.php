<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Livewire\Dosen\LaporanAkhir\IndexLivewire;
use App\Livewire\Dosen\LaporanAkhir\FormLivewire;

class LaporanAkhirController extends Controller
{
    public function index(Request $request, $categoryId = null)
    {
        return view('app.dosen.akhir.index', [ // Memanggil Wrap
            'title'      => 'Laporan Akhir',
            'component'  => IndexLivewire::class, // Gunakan Class, dikirim sebagai $component
            'categoryId' => $categoryId,
        ]);
    }

    public function form(Request $request, $proposalId, $idLaporan = null)
    {
        return view('app.dosen.akhir.form', [ // Memanggil Wrap
            'title'       => $idLaporan ? 'Edit Laporan Akhir' : 'Buat Laporan Akhir',
            'component'   => FormLivewire::class, // Gunakan Class, dikirim sebagai $component
            'proposal_id' => $proposalId,
            'id_laporan'  => $idLaporan,
        ]);
    }
}



