<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        // Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_barang', 'like', '%' . $request->search . '%')
                  ->orWhere('kode', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
            });
        }

        // Filter jumlah
        if ($request->filled('min_jumlah')) {
            $query->where('jumlah', '>=', $request->min_jumlah);
        }

        // Sorting
        $sort = $request->get('sort', 'nama_barang'); // default sort by nama_barang
        $direction = $request->get('direction', 'asc'); // default ascending
        $allowedSorts = ['kode', 'nama_barang', 'harga_satuan', 'jumlah', 'created_at'];
        if (!in_array($sort, $allowedSorts)) $sort = 'nama_barang';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';

        $barangs = $query->orderBy($sort, $direction)->paginate(10)->appends($request->query());

        return view('barang.index', compact('barangs', 'sort', 'direction'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:barangs,kode',
            'nama_barang' => 'required',
            'deskripsi' => 'nullable',
            'harga_satuan' => 'required|numeric',
            'jumlah' => 'required|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['foto'] = 'uploads/' . $filename;
        }

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode' => 'required|unique:barangs,kode,' . $barang->id,
            'nama_barang' => 'required',
            'deskripsi' => 'nullable',
            'harga_satuan' => 'required|numeric',
            'jumlah' => 'required|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($barang->foto && file_exists(public_path($barang->foto))) {
                unlink(public_path($barang->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['foto'] = 'uploads/' . $filename;
        }

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto && file_exists(public_path($barang->foto))) {
            unlink(public_path($barang->foto));
        }

        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
