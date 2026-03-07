<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenyetoranHarian;
use App\Models\Peternak;
use App\Models\PosPenyetoran;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // ========== AUTO REDIRECT UNTUK ADMIN POS ==========
        // Jika admin pos (bukan super admin), langsung redirect ke halaman penyetoran pos-nya
        if ($user->isAdmin() && $user->pos_id) {
            return redirect()->route('penyetoran.show', $user->pos_id);
        }
        
        // Jika super admin (pos_id = null), tampilkan dashboard atau redirect ke pilih pos
        if ($user->isAdmin() && !$user->pos_id) {
            return redirect()->route('penyetoran.index');
        }

        // ========== DASHBOARD KHUSUS UNTUK PETERNAK ==========
        if ($user->isPeternak()) {
            return $this->dashboardPeternak($request);
        }

        // ========== DASHBOARD UNTUK ROLE LAIN ==========
        $today = Carbon::today();
        $periode = $request->get('periode', 'minggu'); // default: minggu

        // Filter pos berdasarkan user
        $posQuery = PosPenyetoran::query();
        if ($user->pos_id) {
            $posQuery->where('id', $user->pos_id);
        }
        $posIds = $posQuery->pluck('id')->toArray();

        // Data ringkasan harian
        $penyetoranHariIni = PenyetoranHarian::whereDate('tanggal', $today)
            ->whereIn('pos_id', $posIds)
            ->get();

        $totalVolumePagi = $penyetoranHariIni->sum('volume_pagi');
        $totalVolumeSore = $penyetoranHariIni->sum('volume_sore');
        $totalPenyetoran = $totalVolumePagi + $totalVolumeSore;

        // Rata-rata BJ (pagi dan sore)
        $bjPagiAvg = $penyetoranHariIni->whereNotNull('bj_pagi')->avg('bj_pagi');
        $bjSoreAvg = $penyetoranHariIni->whereNotNull('bj_sore')->avg('bj_sore');
        $rataRataBeratJenis = ($bjPagiAvg + $bjSoreAvg) / 2;
        $rataRataBeratJenis = $rataRataBeratJenis ? number_format($rataRataBeratJenis / 1000, 3) : null;

        // Total peternak aktif
        $totalPeternak = Peternak::whereIn('pos_id', $posIds)
            ->where('is_active', true)
            ->count();

        // Grafik penyetoran berdasarkan periode
        $groupFormat = match ($periode) {
            'hari' => '%Y-%m-%d',
            'bulan' => '%Y-%m',
            default => '%Y-%u', // minggu ke-
        };

        $grafikData = PenyetoranHarian::select(
            DB::raw("DATE_FORMAT(tanggal, '$groupFormat') as label"),
            DB::raw("SUM(volume_pagi + volume_sore) as total_volume"),
            DB::raw("AVG((bj_pagi + bj_sore) / 2) as avg_berat_jenis")
        )
        ->whereIn('pos_id', $posIds)
        ->groupBy('label')
        ->orderBy('label', 'desc')
        ->limit(10)
        ->get();

        // Format label grafik
        $grafikData = $grafikData->map(function ($item) use ($periode) {
            if ($periode === 'hari') {
                $item->label_formatted = Carbon::parse($item->label)->format('d M');
            } elseif ($periode === 'bulan') {
                $item->label_formatted = Carbon::parse($item->label . '-01')->format('M Y');
            } else {
                // Minggu ke-
                $item->label_formatted = 'Week ' . substr($item->label, -2);
            }
            $item->avg_berat_jenis_formatted = $item->avg_berat_jenis 
                ? number_format($item->avg_berat_jenis / 1000, 3) 
                : null;
            return $item;
        })->reverse()->values();

        // Notifikasi & Alert
        $notifikasi = [];

        // Cek BJ di bawah standar
        $bjDibawahStandar = PenyetoranHarian::whereIn('pos_id', $posIds)
            ->whereDate('tanggal', '>=', $today->copy()->subDays(7))
            ->where(function($q) {
                $q->where('bj_pagi', '<', 1023)
                  ->orWhere('bj_sore', '<', 1022);
            })
            ->count();

        if ($bjDibawahStandar > 0) {
            $notifikasi[] = [
                'type' => 'warning',
                'icon' => 'ti-alert-circle',
                'message' => "Ada {$bjDibawahStandar} data dengan BJ di bawah standar dalam 7 hari terakhir."
            ];
        }

        // Cek data tidak lengkap hari ini
        $dataKosong = PenyetoranHarian::whereIn('pos_id', $posIds)
            ->whereDate('tanggal', $today)
            ->where(function($q) {
                $q->whereNull('volume_pagi')
                  ->orWhereNull('volume_sore')
                  ->orWhereNull('bj_pagi')
                  ->orWhereNull('bj_sore');
            })
            ->count();

        if ($dataKosong > 0) {
            $notifikasi[] = [
                'type' => 'info',
                'icon' => 'ti-clipboard-text',
                'message' => "Ada {$dataKosong} data penyetoran yang belum lengkap hari ini."
            ];
        }

        // Top 5 peternak berdasarkan volume minggu ini
        $topPeternak = PenyetoranHarian::select(
            'peternak_id',
            DB::raw('SUM(volume_pagi + volume_sore) as total_volume')
        )
        ->whereIn('pos_id', $posIds)
        ->whereBetween('tanggal', [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()])
        ->groupBy('peternak_id')
        ->orderBy('total_volume', 'desc')
        ->limit(5)
        ->with('peternak:id,nama_peternak,kode_peternak')
        ->get();

        // Data per pos (untuk super admin)
        $dataPerPos = [];
        if (!$user->pos_id) {
            $posList = PosPenyetoran::withCount(['peternakAktif'])->get();
            
            foreach ($posList as $pos) {
                $posData = PenyetoranHarian::where('pos_id', $pos->id)
                    ->whereDate('tanggal', $today)
                    ->get();
                
                $dataPerPos[] = [
                    'pos' => $pos,
                    'total_volume' => $posData->sum('volume_pagi') + $posData->sum('volume_sore'),
                    'total_peternak' => $pos->peternak_aktif_count,
                ];
            }
        }

        return view('dashboard', compact(
            'totalPenyetoran',
            'rataRataBeratJenis',
            'totalPeternak',
            'grafikData',
            'notifikasi',
            'topPeternak',
            'dataPerPos',
            'periode'
        ));
    }

    /**
     * Dashboard khusus untuk role Peternak
     */
    private function dashboardPeternak(Request $request)
    {
        $user = auth()->user();
        
        // Ambil data peternak berdasarkan user_id
        $peternak = Peternak::where('user_id', $user->id)->with('pos')->first();

        // Jika user tidak terkait dengan data peternak
        if (!$peternak) {
            return view('dashboard-peternak', ['peternak' => null, 'error' => 'Data peternak tidak ditemukan. Silakan hubungi admin.']);
        }

        // Periode filter
        $periode = $request->get('periode', 'bulan_ini');
        $tanggalMulai = match($periode) {
            '7_hari' => Carbon::today()->subDays(6),
            'bulan_ini' => Carbon::now()->startOfMonth(),
            'bulan_lalu' => Carbon::now()->subMonth()->startOfMonth(),
            '3_bulan' => Carbon::now()->subMonths(3)->startOfMonth(),
            default => Carbon::now()->startOfMonth(),
        };
        
        $tanggalSelesai = match($periode) {
            'bulan_lalu' => Carbon::now()->subMonth()->endOfMonth(),
            default => Carbon::today(),
        };

        // Statistik penyetoran
        $penyetoranData = PenyetoranHarian::where('peternak_id', $peternak->id)
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])->get();

        $totalVolumePagi = $penyetoranData->sum('volume_pagi');
        $totalVolumeSore = $penyetoranData->sum('volume_sore');
        $totalVolume = $totalVolumePagi + $totalVolumeSore;
        $rataRataVolume = $penyetoranData->count() > 0 ? ($totalVolume / $penyetoranData->count()) : 0;

        // Rata-rata BJ
        $bjPagiAvg = $penyetoranData->whereNotNull('bj_pagi')->avg('bj_pagi');
        $bjSoreAvg = $penyetoranData->whereNotNull('bj_sore')->avg('bj_sore');
        $rataRataBJ = ($bjPagiAvg && $bjSoreAvg) ? number_format((($bjPagiAvg + $bjSoreAvg) / 2) / 1000, 3) : null;

        // Total hari menyetor
        $totalHariMenyetor = $penyetoranData->filter(function($item) {
            return ($item->volume_pagi ?? 0) > 0 || ($item->volume_sore ?? 0) > 0;
        })->count();

        // Penyetoran hari ini
        $penyetoranHariIni = PenyetoranHarian::where('peternak_id', $peternak->id)
            ->whereDate('tanggal', Carbon::today())->first();

        // Grafik data
        $grafikData = PenyetoranHarian::where('peternak_id', $peternak->id)
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal', 'asc')->get()
            ->map(function($item) {
                return [
                    'tanggal' => Carbon::parse($item->tanggal)->format('d/m'),
                    'tanggal_lengkap' => Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y'),
                    'volume_pagi' => $item->volume_pagi ?? 0,
                    'volume_sore' => $item->volume_sore ?? 0,
                    'total_volume' => ($item->volume_pagi ?? 0) + ($item->volume_sore ?? 0),
                    'bj_pagi' => $item->bj_pagi ? number_format($item->bj_pagi / 1000, 3) : '-',
                    'bj_sore' => $item->bj_sore ? number_format($item->bj_sore / 1000, 3) : '-',
                ];
            });

        // Riwayat 10 terakhir
        $riwayat = PenyetoranHarian::where('peternak_id', $peternak->id)
            ->orderBy('tanggal', 'desc')->limit(10)->get()
            ->map(function($item) {
                return [
                    'tanggal' => Carbon::parse($item->tanggal)->locale('id')->isoFormat('dddd, D MMMM Y'),
                    'tanggal_short' => Carbon::parse($item->tanggal)->format('d/m/Y'),
                    'volume_pagi' => $item->volume_pagi ?? 0,
                    'volume_sore' => $item->volume_sore ?? 0,
                    'total_volume' => ($item->volume_pagi ?? 0) + ($item->volume_sore ?? 0),
                    'bj_pagi' => $item->bj_pagi ? number_format($item->bj_pagi / 1000, 3) : '-',
                    'bj_sore' => $item->bj_sore ? number_format($item->bj_sore / 1000, 3) : '-',
                ];
            });

        return view('dashboard-peternak', compact('peternak','totalVolume','totalVolumePagi','totalVolumeSore','rataRataVolume','rataRataBJ','totalHariMenyetor','penyetoranHariIni','grafikData','riwayat','periode'));
    }
}
