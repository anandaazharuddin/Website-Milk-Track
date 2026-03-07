@extends('layouts.app')

@section('title', 'Dashboard Peternak')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    @if(isset($error))
    <div class="alert alert-danger">
        <i class="ti tabler-alert-circle me-2"></i>{{ $error }}
    </div>
    @else
    
    {{-- Header Info Peternak --}}
    <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-4 text-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar avatar-lg bg-white bg-opacity-25 rounded-circle me-3">
                            <i class="ti tabler-user fs-2"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold text-white">{{ $peternak->nama_peternak }}</h4>
                            <p class="mb-0 opacity-75">
                                <i class="ti tabler-id me-1"></i>Kode: <strong>{{ $peternak->kode_peternak }}</strong>
                                <span class="mx-2">•</span>
                                <i class="ti tabler-building-warehouse me-1"></i>{{ $peternak->pos->nama_pos ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="badge bg-white bg-opacity-25 px-3 py-2 fs-6">
                        <i class="ti tabler-calendar me-1"></i>
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Periode --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2 px-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="ti tabler-chart-line me-1"></i>Statistik Penyetoran
                </h6>
                <select class="form-select form-select-sm w-auto" id="periodeSelect" onchange="window.location.href='?periode='+this.value">
                    <option value="7_hari" {{ $periode == '7_hari' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="bulan_ini" {{ $periode == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="bulan_lalu" {{ $periode == 'bulan_lalu' ? 'selected' : '' }}>Bulan Lalu</option>
                    <option value="3_bulan" {{ $periode == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="avatar bg-primary rounded">
                            <i class="ti tabler-droplet text-white"></i>
                        </div>
                        <span class="badge bg-primary bg-opacity-10 text-primary">Total</span>
                    </div>
                    <h5 class="mb-0 fw-bold">{{ number_format($totalVolume, 1) }} L</h5>
                    <small class="text-muted">Total Volume</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="avatar bg-info rounded">
                            <i class="ti tabler-chart-bar text-white"></i>
                        </div>
                        <span class="badge bg-info bg-opacity-10 text-info">Avg</span>
                    </div>
                    <h5 class="mb-0 fw-bold">{{ number_format($rataRataVolume, 1) }} L</h5>
                    <small class="text-muted">Rata-rata/Hari</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="avatar bg-success rounded">
                            <i class="ti tabler-scale text-white"></i>
                        </div>
                        <span class="badge bg-success bg-opacity-10 text-success">BJ</span>
                    </div>
                    <h5 class="mb-0 fw-bold">{{ $rataRataBJ ?? '-' }}</h5>
                    <small class="text-muted">Rata-rata BJ</small>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="avatar bg-warning rounded">
                            <i class="ti tabler-calendar-check text-white"></i>
                        </div>
                        <span class="badge bg-warning bg-opacity-10 text-warning">Hari</span>
                    </div>
                    <h5 class="mb-0 fw-bold">{{ $totalHariMenyetor }}</h5>
                    <small class="text-muted">Hari Menyetor</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Penyetoran Hari Ini --}}
    @if($penyetoranHariIni)
    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
        <div class="avatar bg-success rounded me-3">
            <i class="ti tabler-check text-white fs-4"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-1 fw-bold">Penyetoran Hari Ini</h6>
            <p class="mb-0">
                <strong>Pagi:</strong> {{ $penyetoranHariIni->volume_pagi ?? 0 }} L (BJ: {{ $penyetoranHariIni->bj_pagi ? number_format($penyetoranHariIni->bj_pagi / 1000, 3) : '-' }})
                <span class="mx-2">•</span>
                <strong>Sore:</strong> {{ $penyetoranHariIni->volume_sore ?? 0 }} L (BJ: {{ $penyetoranHariIni->bj_sore ? number_format($penyetoranHariIni->bj_sore / 1000, 3) : '-' }})
            </p>
        </div>
        <div class="text-end">
            <h4 class="mb-0 fw-bold text-success">
                {{ number_format(($penyetoranHariIni->volume_pagi ?? 0) + ($penyetoranHariIni->volume_sore ?? 0), 1) }} L
            </h4>
        </div>
    </div>
    @else
    <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4">
        <div class="avatar bg-warning rounded me-3">
            <i class="ti tabler-alert-triangle text-white fs-4"></i>
        </div>
        <div>
            <h6 class="mb-1 fw-bold">Belum Ada Penyetoran Hari Ini</h6>
            <p class="mb-0">Data penyetoran untuk hari ini belum diinput oleh admin.</p>
        </div>
    </div>
    @endif

    {{-- Grafik dan Riwayat --}}
    <div class="row">
        {{-- Grafik Penyetoran --}}
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="ti tabler-chart-line me-2 text-primary"></i>Grafik Penyetoran
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="chartPenyetoran" height="300"></canvas>
                </div>
            </div>
        </div>

        {{-- Riwayat Penyetoran --}}
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="ti tabler-history me-2 text-info"></i>Riwayat Terakhir
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="font-size: 0.75rem; padding: 0.5rem;">Tanggal</th>
                                    <th class="text-center" style="font-size: 0.75rem; padding: 0.5rem;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                <tr>
                                    <td style="font-size: 0.8rem; padding: 0.5rem;">
                                        <div class="fw-bold">{{ $item['tanggal_short'] }}</div>
                                        <small class="text-muted">P: {{ $item['volume_pagi'] }}L | S: {{ $item['volume_sore'] }}L</small>
                                    </td>
                                    <td class="text-center fw-bold" style="font-size: 0.85rem; padding: 0.5rem;">
                                        {{ number_format($item['total_volume'], 1) }} L
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">
                                        <i class="ti tabler-inbox fs-3"></i>
                                        <p class="mb-0 mt-2">Belum ada data</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>

@if(!isset($error))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data untuk grafik
    const grafikData = @json($grafikData);
    
    // Chart Penyetoran
    const ctx = document.getElementById('chartPenyetoran');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: grafikData.map(d => d.tanggal),
            datasets: [
                {
                    label: 'Pagi',
                    data: grafikData.map(d => d.volume_pagi),
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Sore',
                    data: grafikData.map(d => d.volume_sore),
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Volume (Liter)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        title: function(context) {
                            const index = context[0].dataIndex;
                            return grafikData[index].tanggal_lengkap;
                        },
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' L';
                        },
                        afterLabel: function(context) {
                            const index = context.dataIndex;
                            const bjKey = context.dataset.label === 'Pagi' ? 'bj_pagi' : 'bj_sore';
                            return 'BJ: ' + grafikData[index][bjKey];
                        }
                    }
                }
            }
        }
    });
</script>
@endif
@endsection
