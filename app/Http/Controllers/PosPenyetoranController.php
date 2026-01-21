<?php

namespace App\Http\Controllers;

use App\Models\PosPenyetoran;
use Illuminate\Http\Request;

class PosPenyetoranController extends Controller
{
    public function index()
    {
        $posList = PosPenyetoran::withCount('peternakAktif')
            ->orderBy('kode_pos')
            ->get();
            
        return view('pos.index', compact('posList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pos' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        PosPenyetoran::create([
            'kode_pos' => PosPenyetoran::generateKode(),
            'nama_pos' => $request->nama_pos,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'is_active' => true,
        ]);

        return redirect()->route('pos.index')
            ->with('success', 'Pos penyetoran berhasil ditambahkan!');
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'nama_pos' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $pos = PosPenyetoran::create([
            'kode_pos' => PosPenyetoran::generateKode(),
            'nama_pos' => $request->nama_pos,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pos penyetoran berhasil ditambahkan!',
            'data' => $pos
        ]);
    }

    public function update(Request $request, PosPenyetoran $pos)
    {
        $request->validate([
            'nama_pos' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $pos->update([
            'nama_pos' => $request->nama_pos,
            'lokasi' => $request->lokasi,
            'keterangan' => $request->keterangan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('pos.index')
            ->with('success', 'Pos penyetoran berhasil diupdate!');
    }

    public function destroy(PosPenyetoran $pos)
    {
        if ($pos->peternak()->count() > 0) {
            return redirect()->route('pos.index')
                ->with('error', 'Tidak dapat menghapus pos yang masih memiliki peternak!');
        }

        $pos->delete();

        return redirect()->route('pos.index')
            ->with('success', 'Pos penyetoran berhasil dihapus!');
    }

    public function getList()
    {
        $posList = PosPenyetoran::active()
            ->orderBy('nama_pos')
            ->get(['id', 'kode_pos', 'nama_pos']);

        return response()->json($posList);
    }
}