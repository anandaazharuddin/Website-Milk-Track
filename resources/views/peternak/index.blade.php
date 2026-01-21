<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Data Peternak</h4>
            <a href="{{ route('peternak.create') }}" class="btn btn-primary">
                <i class="ti tabler-plus me-1"></i> Tambah Peternak
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Jumlah Sapi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peternak as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->kode_peternak }}</strong></td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ Str::limit($item->alamat, 30) }}</td>
                                <td>{{ $item->no_telepon }}</td>
                                <td>{{ $item->jumlah_sapi }} ekor</td>
                                <td>
                                    <span class="badge bg-label-{{ $item->status == 'aktif' ? 'success' : 'danger' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('peternak.edit', $item) }}" class="btn btn-sm btn-icon btn-warning">
                                        <i class="ti tabler-edit"></i>
                                    </a>
                                    <form action="{{ route('peternak.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-icon btn-danger" 
                                                onclick="return confirm('Yakin hapus data ini?')">
                                            <i class="ti tabler-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data peternak</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>