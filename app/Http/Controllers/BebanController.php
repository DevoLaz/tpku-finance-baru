<?php

namespace App\Http\Controllers;

use App\Models\Beban;
use App\Models\Kategori;
use App\Models\ArusKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;
use App\Exports\BebansExport; // --- DITAMBAHKAN ---
use Maatwebsite\Excel\Facades\Excel; // --- DITAMBAHKAN ---

class BebanController extends Controller
{
    // ... (fungsi index, create, store, destroy, exportPdf tetap sama) ...
    public function index(Request $request)
    {
        $query = Beban::query()->with('kategori');

        // Menambahkan filter
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bebans = $query->latest()->paginate(15)->withQueryString();
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('beban.index', compact('bebans', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('beban.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'nama_beban' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $buktiPath = null;
            if ($request->hasFile('bukti')) {
                $buktiPath = $request->file('bukti')->store('bukti_beban', 'public_uploads');
            }

            $beban = Beban::create([
                'tanggal' => $validatedData['tanggal'],
                'nama' => $validatedData['nama_beban'],
                'jumlah' => $validatedData['jumlah'],
                'keterangan' => $validatedData['keterangan'],
                'bukti' => $buktiPath,
                'kategori_id' => $validatedData['kategori_id'],
            ]);

            ArusKas::create([
                'tanggal' => $beban->tanggal,
                'jumlah' => $beban->jumlah,
                'tipe' => 'keluar',
                'deskripsi' => $beban->nama,
                'referensi_id' => $beban->id,
                'referensi_tipe' => Beban::class,
            ]);

            DB::commit();
            return redirect()->route('beban.index')->with('success', 'Beban berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($buktiPath)) {
                Storage::delete($buktiPath);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Beban $beban)
    {
        DB::beginTransaction();
        try {
            if ($beban->bukti) {
                Storage::delete($beban->bukti);
            }

            ArusKas::where('referensi_tipe', Beban::class)
                   ->where('referensi_id', $beban->id)
                   ->delete();

            $beban->delete();
            DB::commit();
            return redirect()->route('beban.index')->with('success', 'Beban berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus beban: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        $query = Beban::query()->with('kategori');

        $dari = $request->input('dari');
        $sampai = $request->input('sampai');
        $kategori_id = $request->input('kategori_id');

        // Apply filters
        if ($dari) {
            $query->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai) {
            $query->whereDate('tanggal', '<=', $sampai);
        }
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }

        $bebans = $query->latest()->get();
        $totalBeban = $bebans->sum('jumlah');
        $kategoriNama = $kategori_id ? Kategori::find($kategori_id)->nama_kategori : 'Semua Kategori';

        // Generate PDF
        $pdf = PDF::loadView('beban.pdf', compact('bebans', 'totalBeban', 'dari', 'sampai', 'kategoriNama'));

        $fileName = 'laporan-beban-' . date('Y-m-d') . '.pdf';

        return $pdf->download($fileName);
    }
    
    // --- FUNGSI BARU UNTUK EXPORT EXCEL --- //
    public function exportExcel(Request $request)
    {
        $query = Beban::query()->with('kategori');

        // Apply filters
        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $bebans = $query->latest()->get();
        $totalBeban = $bebans->sum('jumlah');
        
        $fileName = 'laporan-beban-operasional-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new BebansExport($bebans, $totalBeban), $fileName);
    }
    // --- SELESAI FUNGSI BARU --- //
}