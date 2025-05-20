<!DOCTYPE html>
<html>
<head>
    <title>Daftar Barang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Daftar Barang</h1>
        <a href="{{ route('barang.create') }}" class="btn btn-success">+ Tambah Barang</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    @endif

    <!-- 🔍 Form Pencarian dan Filter -->
    <form action="{{ route('barang.index') }}" method="GET" class="row row-cols-lg-auto g-3 align-items-center mb-4">
        <div class="col-12">
            <input type="text" name="search" class="form-control" placeholder="Cari nama / kode / deskripsi..." value="{{ request('search') }}">
        </div>
        <div class="col-12">
            <input type="number" name="min_jumlah" class="form-control" placeholder="Jumlah ≥" value="{{ request('min_jumlah') }}">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-outline-primary">Filter</button>
            <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <!-- 📋 Tabel Barang -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    @php
                        function sortLink($field, $label, $sort, $direction) {
                            $icon = '';
                            if ($sort === $field) {
                                $icon = $direction === 'asc' ? '↑' : '↓';
                            }
                            $nextDirection = ($sort === $field && $direction === 'asc') ? 'desc' : 'asc';
                            return '<a href="'.request()->fullUrlWithQuery(['sort' => $field, 'direction' => $nextDirection]).'" class="text-white text-decoration-none">'.$label.' '.$icon.'</a>';
                        }
                    @endphp
                    <th>{!! sortLink('kode', 'Kode', $sort ?? '', $direction ?? '') !!}</th>
                    <th>{!! sortLink('nama_barang', 'Nama', $sort ?? '', $direction ?? '') !!}</th>
                    <th>Deskripsi</th>
                    <th>{!! sortLink('harga_satuan', 'Harga', $sort ?? '', $direction ?? '') !!}</th>
                    <th>{!! sortLink('jumlah', 'Jumlah', $sort ?? '', $direction ?? '') !!}</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                    <tr>
                        <td>{{ $barang->kode }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->deskripsi }}</td>
                        <td>Rp {{ number_format($barang->harga_satuan, 2, ',', '.') }}</td>
                        <td>{{ $barang->jumlah }}</td>
                        <td class="text-center">
                            @if ($barang->foto)
                                <img src="{{ asset($barang->foto) }}" class="img-thumbnail" style="max-width: 80px;">
                            @else
                                <span class="text-muted fst-italic">Tidak ada</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">Tidak ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 🔢 Pagination -->
    <div class="d-flex justify-content-center">
        {{ $barangs->withQueryString()->links('pagination::bootstrap-5') }}
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
