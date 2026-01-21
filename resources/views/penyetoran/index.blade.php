@extends('layouts.app')

@section('title', 'Penyetoran Harian')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="ti tabler-milk me-2 text-primary"></i>Penyetoran Harian
            </h4>
            <p class="text-muted mb-0">Pilih pos penampungan untuk mengelola data penyetoran</p>
        </div>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalManagePos">
            <i class="ti tabler-plus me-1"></i> Tambah Pos
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti tabler-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($posList->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="ti tabler-building-warehouse" style="font-size: 120px; opacity: 0.15;"></i>
                <h4 class="text-muted mb-3 mt-4">Belum ada Pos Penyetoran</h4>
                <p class="text-muted mb-4">Silakan tambahkan pos penyetoran terlebih dahulu</p>
                <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalManagePos">
                    <i class="ti tabler-plus me-2"></i> Tambah Pos Penyetoran
                </button>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($posList as $pos)
            <div class="col-md-6 col-xl-4 mb-4">
                <div class="card h-100 shadow-sm hover-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="avatar bg-primary rounded">
                                <i class="ti tabler-building-warehouse fs-3 text-white"></i>
                            </div>
                            <span class="badge bg-{{ $pos->is_active ? 'success' : 'secondary' }}">
                                {{ $pos->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        
                        <h5 class="card-title mb-2">{{ $pos->nama_pos }}</h5>
                        <p class="text-muted mb-3">
                            <i class="ti tabler-map-pin me-1"></i>{{ $pos->lokasi ?? '-' }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-muted">Total Peternak</small>
                            <span class="badge bg-primary px-3 py-2">
                                <i class="ti tabler-users me-1"></i>{{ $pos->peternak_aktif_count }}
                            </span>
                        </div>
                        
                        <a href="{{ route('penyetoran.show', $pos->id) }}" class="btn btn-primary w-100">
                            <i class="ti tabler-clipboard-text me-1"></i> Kelola Penyetoran
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- MODAL TAMBAH POS --}}
<div class="modal fade" id="modalManagePos" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">
                    <i class="ti tabler-plus me-2"></i>Tambah Pos Penampungan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAddPos">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pos <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_pos" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti tabler-check me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('formAddPos')?.addEventListener('submit', function(e) {
    e.preventDefault();
    fetch('{{ route("pos.storeAjax") }}', {
        method: 'POST',
        headers: { 
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: new FormData(this)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.hover-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15) !important;
}
.avatar {
    width: 3.5rem;
    height: 3.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush