<?php

namespace App\Http\Controllers;

use App\Models\PenyetoranHarian;
use App\Models\Peternak;
use App\Models\PosPenyetoran;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PenyetoranHarianController extends Controller
{
    // Konstanta validasi
    const BJ_PAGI_MIN = 1023;
    const BJ_SORE_MIN = 1022;
    const SUHU_MIN = 30;

    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = PosPenyetoran::active()->withCount('peternakAktif');
        
        if ($user->pos_id) {
            $query->where('id', $user->pos_id);
        }
        
        $posList = $query->orderBy('nama_pos')->get();
        
        if ($posList->count() === 1) {
            return redirect()->route('penyetoran.show', $posList->first()->id);
        }
        
        return view('penyetoran.index', compact('posList'));
    }

    public function show(Request $request, $posId)
    {
        $user = auth()->user();
        
        if (!$user->canAccessPos($posId)) {
            abort(403, 'Anda tidak memiliki akses ke pos ini');
        }
        
        $pos = PosPenyetoran::findOrFail($posId);
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $tanggalCarbon = Carbon::parse($tanggal);

        $peternakList = Peternak::where('pos_id', $posId)
            ->where('is_active', true)
            ->orderBy('nama_peternak')
            ->get();

        $penyetoranData = PenyetoranHarian::where('pos_id', $posId)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('peternak_id');

        $data = $peternakList->map(function ($peternak) use ($penyetoranData) {
            $penyetoran = $penyetoranData->get($peternak->id);
            return [
                'peternak_id' => $peternak->id,
                'nama_peternak' => $peternak->nama_peternak,
                'kode_peternak' => $peternak->kode_peternak,
                'volume_pagi' => $penyetoran->volume_pagi ?? null,
                'bj_pagi' => $penyetoran->bj_pagi ?? null,
                'volume_sore' => $penyetoran->volume_sore ?? null,
                'bj_sore' => $penyetoran->bj_sore ?? null,
                'catatan' => $penyetoran->catatan ?? null,
                'penyetoran_id' => $penyetoran->id ?? null,
            ];
        });

        $totalVolumePagi = $data->sum('volume_pagi');
        $totalVolumeSore = $data->sum('volume_sore');
        $totalVolume = $totalVolumePagi + $totalVolumeSore;

        return view('penyetoran.show', compact(
            'pos',
            'data',
            'tanggal',
            'tanggalCarbon',
            'totalVolumePagi',
            'totalVolumeSore',
            'totalVolume'
        ));
    }

    public function updateCell(Request $request)
    {
        $request->validate([
            'peternak_id' => 'required|exists:peternak,id',
            'tanggal' => 'required|date',
            'field' => 'required|in:volume_pagi,bj_pagi,volume_sore,bj_sore',
            'value' => 'nullable',
        ]);

        $peternak = Peternak::findOrFail($request->peternak_id);
        
        if (!auth()->user()->canAccessPos($peternak->pos_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pos ini',
            ], 403);
        }
        
        $penyetoran = PenyetoranHarian::firstOrNew([
            'peternak_id' => $request->peternak_id,
            'tanggal' => $request->tanggal,
        ]);

        if (!$penyetoran->exists) {
            $penyetoran->pos_id = $peternak->pos_id;
            $penyetoran->created_by = auth()->id();
        }

        $value = $request->value;
        $field = $request->field;
        
        // ========== VALIDASI BJ ==========
        if (in_array($field, ['bj_pagi', 'bj_sore'])) {
            // Remove non-numeric
            $value = $value ? (int) str_replace(['.', ',', ' '], '', $value) : null;
            
            // Validasi 4 digit
            if ($value && strlen((string)$value) !== 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'BJ harus 4 digit (contoh: 1023 untuk BJ pagi, 1022 untuk BJ sore)',
                ], 422);
            }
            
            // Validasi minimum BJ berdasarkan waktu
            if ($field === 'bj_pagi' && $value < self::BJ_PAGI_MIN) {
                return response()->json([
                    'success' => false,
                    'message' => 'BJ Pagi minimal ' . self::BJ_PAGI_MIN . ' (1.023). Data tidak dapat disimpan!',
                ], 422);
            }
            
            if ($field === 'bj_sore' && $value < self::BJ_SORE_MIN) {
                return response()->json([
                    'success' => false,
                    'message' => 'BJ Sore minimal ' . self::BJ_SORE_MIN . ' (1.022). Data tidak dapat disimpan!',
                ], 422);
            }
        }

        $penyetoran->{$field} = $value;
        $penyetoran->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data' => $penyetoran,
        ]);
    }

    public function destroy($id)
    {
        $penyetoran = PenyetoranHarian::findOrFail($id);
        
        if (!auth()->user()->canAccessPos($penyetoran->pos_id)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pos ini',
            ], 403);
        }
        
        $penyetoran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }

    public function export(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $posId = $request->get('pos_id');

        if (!$posId) {
            return back()->with('error', 'Pilih pos terlebih dahulu!');
        }

        if (!auth()->user()->canAccessPos($posId)) {
            abort(403, 'Anda tidak memiliki akses ke pos ini');
        }

        $pos = PosPenyetoran::findOrFail($posId);
        $tanggalCarbon = Carbon::parse($tanggal);

        $peternakList = Peternak::where('pos_id', $pos->id)
            ->where('is_active', true)
            ->orderBy('nama_peternak')
            ->get();

        $penyetoranData = PenyetoranHarian::where('pos_id', $pos->id)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('peternak_id');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $row = 1;
        $sheet->setCellValue("A{$row}", 'FORM DATA SETORAN PETERNAK');
        $sheet->mergeCells("A{$row}:G{$row}");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        $row += 2;
        $sheet->setCellValue("A{$row}", 'Nama Vendor:');
        $sheet->setCellValue("B{$row}", 'KUD KARANGPLOSO');
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Kode Vendor:');
        $sheet->setCellValue("B{$row}", 'KRP');
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Nomor Vendor:');
        $sheet->setCellValue("B{$row}", '22');
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Alamat:');
        $sheet->setCellValue("B{$row}", 'Karangploso');
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Pos Penampungan:');
        $sheet->setCellValue("B{$row}", $pos->nama_pos);
        
        $totalPagi = $penyetoranData->sum('volume_pagi');
        $totalSore = $penyetoranData->sum('volume_sore');
        
        $sheet->setCellValue("E{$row}", 'Jumlah Susu Pagi:');
        $sheet->setCellValue("F{$row}", number_format($totalPagi, 2) . ' L');
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Area Penampungan:');
        $sheet->setCellValue("B{$row}", $pos->lokasi ?? $pos->nama_pos);
        $sheet->setCellValue("E{$row}", 'Jumlah Susu Sore:');
        $sheet->setCellValue("F{$row}", number_format($totalSore, 2) . ' L');
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Pos Penampungan Berpendingin:');
        $sheet->setCellValue("B{$row}", $pos->nama_pos);
        $sheet->setCellValue("E{$row}", 'Total Susu:');
        $sheet->setCellValue("F{$row}", number_format($totalPagi + $totalSore, 2) . ' L');
        $sheet->getStyle("F{$row}")->getFont()->setBold(true);
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Tanggal:');
        $sheet->setCellValue("B{$row}", $tanggalCarbon->isoFormat('D MMMM Y'));
        
        $row++;
        $sheet->setCellValue("A{$row}", 'Kode Penampungan:');
        $sheet->setCellValue("B{$row}", 'Boro PS 2024');
        
        $row += 2;
        $headerRow = $row;
        $headers = ['No', 'Kode Peternak', 'Nama', 'Volume Pagi (L)', 'BJ Pagi', 'Volume Sore (L)', 'BJ Sore', 'Total (L)'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}{$row}", $header);
            $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true);
            $sheet->getStyle("{$col}{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4472C4');
            $sheet->getStyle("{$col}{$row}")->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle("{$col}{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }

        $row++;
        $no = 1;
        foreach ($peternakList as $peternak) {
            $penyetoran = $penyetoranData->get($peternak->id);
            
            if (!$penyetoran) continue;
            
            $volumePagi = $penyetoran->volume_pagi ?? 0;
            $volumeSore = $penyetoran->volume_sore ?? 0;
            $totalVolume = $volumePagi + $volumeSore;
            
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValue("B{$row}", $peternak->kode_peternak);
            $sheet->setCellValue("C{$row}", $peternak->nama_peternak);
            $sheet->setCellValue("D{$row}", $volumePagi);
            $sheet->setCellValue("E{$row}", $penyetoran->bj_pagi ? number_format($penyetoran->bj_pagi / 1000, 3) : '');
            $sheet->setCellValue("F{$row}", $volumeSore);
            $sheet->setCellValue("G{$row}", $penyetoran->bj_sore ? number_format($penyetoran->bj_sore / 1000, 3) : '');
            $sheet->setCellValue("H{$row}", $totalVolume);
            
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("D{$row}:H{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Highlight Total column dengan background hijau muda
            $sheet->getStyle("H{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('C6EFCE');
            $sheet->getStyle("H{$row}")->getFont()->setBold(true);
            
            $row++;
        }

        $lastRow = $row - 1;
        $sheet->getStyle("A{$headerRow}:H{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Setoran_' . $pos->nama_pos . '_' . $tanggalCarbon->format('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$filename}\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}