@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="mb-4 fw-bold">
        <i class="ti tabler-dashboard me-2"></i>Dashboard
    </h4>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Penyetoran Hari Ini</p>
                            <h3 class="mb-0 text-primary">{{ number_format($totalPenyetoran, 2) }} L</h3>
                        </div>
                        <div class="avatar bg-primary rounded">
                            <i class="ti tabler-droplet fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Rata-rata BJ</p>
                            <h3 class="mb-0 text-info">{{ $rataRataBeratJenis ?? '-' }}</h3>
                        </div>
                        <div class="avatar bg-info rounded">
                            <i class="ti tabler-chart-line fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Peternak Aktif</p>
                            <h3 class="mb-0 text-success">{{ $totalPeternak }}</h3>
                        </div>
                        <div class="avatar bg-success rounded">
                            <i class="ti tabler-users fs-3 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifications --}}
    @if(count($notifikasi) > 0)
    <div class="row mb-4">
        <div class="col-12">
            @foreach($notifikasi as $notif)
            <div class="alert alert-{{ $notif['type'] }} alert-dismissible fade show" role="alert">
                <i class="{{ $notif['icon'] }} me-2"></i>{{ $notif['message'] }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Charts & Tables --}}
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="mb-0">Grafik Penyetoran</h5>
                    <select class="form-select w-auto" id="periodeSelect">
                        <option value="hari" {{ $periode == 'hari' ? 'selected' : '' }}>Harian</option>
                        <option value="minggu" {{ $periode == 'minggu' ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulan" {{ $periode == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="chartPenyetoran"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Top 5 Peternak Minggu Ini</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th class="text-end">Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topPeternak as $item)
                                <tr>
                                    <td>{{ $item->peternak->nama_peternak }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item->total_volume, 2) }} L</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Belum ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Per Pos (Super Admin Only) --}}
    @if(count($dataPerPos) > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Data Per Pos Hari Ini</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pos</th>
                                    <th>Lokasi</th>
                                    <th class="text-center">Peternak Aktif</th>
                                    <th class="text-end">Total Volume</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dataPerPos as $item)
                                <tr>
                                    <td class="fw-bold">{{ $item['pos']->nama_pos }}</td>
                                    <td>{{ $item['pos']->lokasi ?? '-' }}</td>
                                    <td class="text-center">{{ $item['total_peternak'] }}</td>
                                    <td class="text-end fw-bold">{{ number_format($item['total_volume'], 2) }} L</td>
                                    <td class="text-center">
                                        <a href="{{ route('penyetoran.show', $item['pos']->id) }}" class="btn btn-sm btn-primary">
                                            <i class="ti tabler-eye me-1"></i>Lihat
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.getElementById('periodeSelect')?.addEventListener('change', function() {
    window.location.href = '?periode=' + this.value;
});

const ctx = document.getElementById('chartPenyetoran');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($grafikData->pluck('label_formatted')) !!},
            datasets: [{
                label: 'Volume (L)',
                data: {!! json_encode($grafikData->pluck('total_volume')) !!},
                backgroundColor: 'rgba(105, 108, 255, 0.8)',
                borderColor: 'rgba(105, 108, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
</script>
@endpush

@push('styles')
<style>
#chartPenyetoran {
    height: 300px;
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