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
            'kode_peternak' => [
                'required',
                'string',
                'max:50',
                'unique:peternak,kode_peternak,' . $peternak->id,
                'regex:/^[A-Z0-9-]+$/',
            ],
            'nama_peternak' => 'required|string|max:255',
            'is_active' => 'nullable',
        ]);

        // Convert is_active to boolean
        $isActive = true;
        if ($request->has('is_active')) {
            $isActive = $request->is_active == '1' || $request->is_active === true;
        }

        $peternak->kode_peternak = strtoupper($request->kode_peternak);
        $peternak->nama_peternak = $request->nama_peternak;
        $peternak->is_active = $isActive;
        $peternak->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data peternak berhasil diupdate!',
                'data' => $peternak
            ]);
        }

        return redirect()->route('peternak.index')
            ->with('success', 'Data peternak berhasil diupdate!');
    }

    public function destroy(Peternak $peternak)
    {
        try {
            // Hapus semua data penyetoran terkait terlebih dahulu
            $peternak->penyetoranHarian()->delete();
            
            // Hapus peternak
            $peternak->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data peternak dan semua data penyetoran berhasil dihapus!'
                ]);
            }

            return redirect()->route('peternak.index')
                ->with('success', 'Data peternak dan semua data penyetoran berhasil dihapus!');
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
            ->orderBy('kode_peternak')
            ->get(['id', 'kode_peternak', 'nama_peternak', 'is_active']);

        return response()->json($peternakList);
    }
}