<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // AJAX: daftar barang per supplier
   public function barangs($supplierId)
{
    $supplier = Supplier::findOrFail($supplierId);

    // Ambil id, nama, harga_jual
    $barangs = $supplier->barangs()
        ->select('barangs.id', 'barangs.nama', 'barangs.harga_jual') 
        ->get()
        // ubah key harga_jual jadi harga, supaya frontend gak perlu ubah
        ->map(function($b) {
            return [
                'id'    => $b->id,
                'nama'  => $b->nama,
                'harga' => $b->harga_jual,
            ];
        });

    return response()->json($barangs);
}

    // AJAX: data untuk modal Edit
    public function edit(Supplier $supplier)
    {
        // langsung balikin JSON
        return response()->json($supplier);
    }

    // Simpan supplier baru
public function store(Request $r)
{
    $data = $r->validate([
        'nama'=>'required',
        'kontak'=>'nullable',
        'alamat'=>'nullable',
    ]);
    Supplier::create($data);

    // <-- flash dulu
    session()->flash('success','Supplier berhasil ditambahkan.');

    if ($r->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil ditambahkan.'
        ]);
    }

    return redirect()->route('master.index');
}

    // Update supplier
    public function update(Request $r, Supplier $supplier)
    {
        $data = $r->validate([
            'nama'   => 'required|string|max:255',
            'kontak' => 'nullable|string|max:50',
            'alamat' => 'nullable|string',
        ]);

        $supplier->update($data);

        if ($r->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil diperbarui.'
            ]);
        }

        return redirect()
            ->route('master.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    // Hapus supplier
    public function destroy(Request $r, Supplier $supplier)
    {
        $supplier->delete();

        if ($r->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil dihapus.'
            ]);
        }

        return back()->with('success', 'Supplier berhasil dihapus.');
    }
}
