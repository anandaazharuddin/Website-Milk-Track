<?php

namespace App\Http\Controllers;

use App\Models\PenyetoranHarian;
use App\Models\PosPenyetoran;
use App\Models\Peternak;
use App\Models\Article;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal dari request, default hari ini
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $tanggalCarbon = Carbon::parse($tanggal);

        // Ambil artikel yang dipublish dalam 7 hari terakhir
        $articles = Article::where('is_published', true)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->latest()
            ->get();

        // Ambil data 2 pos penyetoran
        $pos1 = PosPenyetoran::where('id', 1)->first();
        $pos2 = PosPenyetoran::where('id', 2)->first();

        // Ambil data peternak dan penyetoran untuk Pos 1 (hari yang dipilih)
        // Ambil SEMUA peternak aktif (tidak dibatasi)
        $peternakPos1 = Peternak::where('pos_id', 1)
            ->where('is_active', true)
            ->orderBy('nama_peternak')
            ->get();

        $penyetoranDataPos1 = PenyetoranHarian::where('pos_id', 1)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('peternak_id');

        // Build data array untuk Pos 1
        $dataPos1 = [];
        $totalVolumePagiPos1 = 0;
        $totalVolumeSorePos1 = 0;

        foreach ($peternakPos1 as $peternak) {
            $penyetoran = $penyetoranDataPos1->get($peternak->id);
            
            $volumePagi = $penyetoran->volume_pagi ?? 0;
            $volumeSore = $penyetoran->volume_sore ?? 0;
            
            $dataPos1[] = [
                'peternak_id' => $peternak->id,
                'kode_peternak' => $peternak->kode_peternak,
                'nama_peternak' => $peternak->nama_peternak,
                'volume_pagi' => $volumePagi,
                'bj_pagi' => $penyetoran->bj_pagi ?? null,
                'volume_sore' => $volumeSore,
                'bj_sore' => $penyetoran->bj_sore ?? null,
                'penyetoran_id' => $penyetoran->id ?? null,
            ];
            
            $totalVolumePagiPos1 += $volumePagi;
            $totalVolumeSorePos1 += $volumeSore;
        }

        // Ambil data peternak dan penyetoran untuk Pos 2 (hari yang dipilih)
        // Ambil SEMUA peternak aktif (tidak dibatasi)
        $peternakPos2 = Peternak::where('pos_id', 2)
            ->where('is_active', true)
            ->orderBy('nama_peternak')
            ->get();

        $penyetoranDataPos2 = PenyetoranHarian::where('pos_id', 2)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('peternak_id');

        // Build data array untuk Pos 2
        $dataPos2 = [];
        $totalVolumePagiPos2 = 0;
        $totalVolumeSorePos2 = 0;

        foreach ($peternakPos2 as $peternak) {
            $penyetoran = $penyetoranDataPos2->get($peternak->id);
            
            $volumePagi = $penyetoran->volume_pagi ?? 0;
            $volumeSore = $penyetoran->volume_sore ?? 0;
            
            $dataPos2[] = [
                'peternak_id' => $peternak->id,
                'kode_peternak' => $peternak->kode_peternak,
                'nama_peternak' => $peternak->nama_peternak,
                'volume_pagi' => $volumePagi,
                'bj_pagi' => $penyetoran->bj_pagi ?? null,
                'volume_sore' => $volumeSore,
                'bj_sore' => $penyetoran->bj_sore ?? null,
                'penyetoran_id' => $penyetoran->id ?? null,
            ];
            
            $totalVolumePagiPos2 += $volumePagi;
            $totalVolumeSorePos2 += $volumeSore;
        }

        $totalVolumePos1 = $totalVolumePagiPos1 + $totalVolumeSorePos1;
        $totalVolumePos2 = $totalVolumePagiPos2 + $totalVolumeSorePos2;

        return view('welcome', compact(
            'pos1', 
            'pos2', 
            'dataPos1', 
            'dataPos2',
            'tanggal',
            'tanggalCarbon',
            'totalVolumePagiPos1',
            'totalVolumeSorePos1',
            'totalVolumePos1',
            'totalVolumePagiPos2',
            'totalVolumeSorePos2',
            'totalVolumePos2',
            'articles'
        ));
    }
}
