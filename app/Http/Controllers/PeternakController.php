<?php

namespace App\Http\Controllers;

use App\Models\Peternak;
use App\Models\PosPenyetoran;
use Illuminate\Http\Request;

class PeternakController extends Controller
{
    public function index(Request $request)
    {
        $query = Peternak::with('pos');
        
        if ($request->has('pos_id') && $request->pos_id) {
            $query->where('pos_id', $request->pos_id);
        }

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_peternak', 'like', "%{$search}%")
                  ->orWhere('nama_peternak', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $peternakList = $query->orderBy('kode_peternak')->paginate(15);
        $posList = PosPenyetoran::active()->orderBy('nama_pos')->get();
        
        return view('peternak.index', compact('peternakList', 'posList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_peternak' => 'required|string|max:255',
            'pos_id' => 'required|exists:pos_penyetoran,id',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Peternak::create([
            'kode_peternak' => Peternak::generateKode($request->pos_id),
            'nama_peternak' => $request->nama_peternak,
            'pos_id' => $request->pos_id,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'is_active' => true,
        ]);

        return redirect()->route('peternak.index')
            ->with('success', 'Peternak berhasil ditambahkan!');
    }

    public function storeAjax(Request $request)
        {
            $request->validate([
                'kode_peternak' => [
                    'required',
                    'string',
                    'max:50',
                    'unique:peternak,kode_peternak', // ← Kode harus unik
                    'regex:/^[A-Z0-9-]+$/', // ← Hanya huruf besar, angka, dan strip
                ],
                'nama_peternak' => 'required|string|max:255',
                'pos_id' => 'required|exists:pos_penyetoran,id',
            ], [
                'kode_peternak.required' => 'Kode peternak wajib diisi',
                'kode_peternak.unique' => 'Kode peternak sudah digunakan',
                'kode_peternak.regex' => 'Kode hanya boleh huruf besar, angka, dan strip (-)',
                'nama_peternak.required' => 'Nama peternak wajib diisi',
            ]);

            // Cek akses pos
            if (!auth()->user()->canAccessPos($request->pos_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke pos ini',
                ], 403);
            }

            $peternak = Peternak::create([
                'kode_peternak' => strtoupper($request->kode_peternak), // ← Auto uppercase
                'nama_peternak' => $request->nama_peternak,
                'pos_id' => $request->pos_id,
                'is_active' => true,
            ]);

            $peternak->load('pos');

            return response()->json([
                'success' => true,
                'message' => 'Peternak berhasil ditambahkan!',
                'data' => $peternak
            ]);
        }

    public function update(Request $request, Peternak $peternak)
    {
        $request->validate([
            'nama_peternak' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'no_hp' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $peternak->update([
            'nama_peternak' => $request->nama_peternak,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'is_active' => $request->is_active ?? true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data peternak berhasil diupdate!'
            ]);
        }

        return redirect()->route('peternak.index')
            ->with('success', 'Data peternak berhasil diupdate!');
    }

    public function destroy(Peternak $peternak)
    {
        try {
            // Cek apakah peternak memiliki data penyetoran
            if ($peternak->penyetoranHarian()->count() > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak dapat menghapus peternak yang sudah memiliki data penyetoran!'
                    ], 400);
                }
                
                return redirect()->route('peternak.index')
                    ->with('error', 'Tidak dapat menghapus peternak yang sudah memiliki data penyetoran!');
            }

            $peternak->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data peternak berhasil dihapus!'
                ]);
            }

            return redirect()->route('peternak.index')
                ->with('success', 'Data peternak berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('peternak.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data!');
        }
    }

    public function getByPos($posId)
    {
        $peternakList = Peternak::where('pos_id', $posId)
            ->where('is_active', true)
            ->orderBy('kode_peternak')
            ->get(['id', 'kode_peternak', 'nama_peternak']);

        return response()->json($peternakList);
    }
}