@extends('layouts.app')

@section('title', 'Penyetoran - ' . $pos->nama_pos)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Single Card Header --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2 px-3">
            <div class="row align-items-center g-2">
                {{-- Left: Pos Name & Date Info --}}
                <div class="col-lg-6 col-md-12">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <h6 class="mb-0 fw-bold text-primary">
                                <i class="ti tabler-building-warehouse me-1"></i>{{ $pos->nama_pos }}
                            </h6>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                {{ $tanggalCarbon->locale('id')->isoFormat('dddd, D MMMM Y') }}
                            </small>
                        </div>
                        <div class="vr d-none d-lg-block"></div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="prevDate" style="padding: 0.25rem 0.5rem;">
                                <i class="ti tabler-chevron-left"></i>
                            </button>
                            <input type="date" class="form-control form-control-sm border-primary text-center" id="tanggalPicker" value="{{ $tanggal }}" 
                                   style="width: 130px; font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="nextDate" style="padding: 0.25rem 0.5rem;">
                                <i class="ti tabler-chevron-right"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" id="todayBtn" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                Hari Ini
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Right: Action Buttons --}}
                <div class="col-lg-6 col-md-12">
                    <div class="d-flex justify-content-lg-end gap-2">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalManagePeternak" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                            <i class="ti tabler-users-group me-1"></i> Kelola Peternak
                        </button>
                        <button type="button" class="btn btn-success btn-sm" id="btnExport" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                            <i class="ti tabler-file-excel me-1"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 mb-2" role="alert" style="font-size: 0.875rem;">
            <i class="ti tabler-check me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2 mb-2" role="alert" style="font-size: 0.875rem;">
            <i class="ti tabler-alert-circle me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Hidden inputs --}}
    <input type="hidden" id="currentTanggal" value="{{ $tanggal }}">
    <input type="hidden" id="currentPosId" value="{{ $pos->id }}">

    {{-- Table Penyetoran --}}
    <div class="card shadow-sm border-0 mb-2">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0 align-middle" id="tablePenyetoran">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 30px; font-size: 0.7rem; padding: 0.35rem 0.2rem;">No</th>
                            <th style="width: 50px; font-size: 0.7rem; padding: 0.35rem 0.3rem;">Kode</th>
                            <th style="width: 100px; font-size: 0.7rem; padding: 0.35rem 0.3rem;">Peternak</th>
                            <th colspan="2" class="text-center bg-info bg-opacity-10" style="font-size: 0.7rem; padding: 0.35rem 0.2rem;">
                                <i class="ti tabler-sunrise me-1"></i>Pagi
                            </th>
                            <th colspan="2" class="text-center bg-warning bg-opacity-10" style="font-size: 0.7rem; padding: 0.35rem 0.2rem;">
                                <i class="ti tabler-sunset me-1"></i>Sore
                            </th>
                            <th class="text-center bg-success bg-opacity-10" style="width: 90px; font-size: 0.7rem; padding: 0.35rem 0.2rem;">
                                <i class="ti tabler-droplet me-1"></i>Total (L)
                            </th>
                            <th class="text-center" style="width: 50px; font-size: 0.7rem; padding: 0.35rem 0.2rem;">Aksi</th>
                        </tr>
                        <tr class="table-light">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-center bg-info bg-opacity-10" style="width: 110px; padding: 0.25rem;">
                                <small style="font-size: 0.65rem;">Vol (L)</small>
                            </th>
                            <th class="text-center bg-info bg-opacity-10" style="width: 90px; padding: 0.25rem;">
                                <small style="font-size: 0.65rem;">BJ</small>
                            </th>
                            <th class="text-center bg-warning bg-opacity-10" style="width: 110px; padding: 0.25rem;">
                                <small style="font-size: 0.65rem;">Vol (L)</small>
                            </th>
                            <th class="text-center bg-warning bg-opacity-10" style="width: 90px; padding: 0.25rem;">
                                <small style="font-size: 0.65rem;">BJ</small>
                            </th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $index => $item)
                        <tr data-peternak-id="{{ $item['peternak_id'] }}" data-penyetoran-id="{{ $item['penyetoran_id'] }}">
                            <td class="text-center text-muted" style="font-size: 0.7rem; padding: 0.25rem 0.2rem;">{{ $index + 1 }}</td>
                            <td style="padding: 0.25rem 0.3rem;">
                                <code class="text-primary" style="font-size: 0.65rem;">{{ $item['kode_peternak'] ?? '-' }}</code>
                            </td>
                            <td class="fw-medium" style="font-size: 0.75rem; padding: 0.25rem 0.3rem;">{{ $item['nama_peternak'] }}</td>
                            <td class="p-1 editable-cell" data-field="volume_pagi" data-type="number">
                                <span class="cell-display" style="font-size: 0.8rem; font-weight: 600;">{{ $item['volume_pagi'] ? number_format($item['volume_pagi'], 2) : '-' }}</span>
                                <input type="number" class="form-control form-control-sm text-center cell-input d-none" 
                                       value="{{ $item['volume_pagi'] }}" step="0.01" min="0" style="font-size: 0.75rem; padding: 0.3rem;">
                            </td>
                            <td class="p-1 editable-cell" data-field="bj_pagi" data-type="bj" data-waktu="pagi">
                                <span class="cell-display" style="font-size: 0.8rem; font-weight: 600;">{{ $item['bj_pagi'] ? number_format($item['bj_pagi'] / 1000, 3) : '-' }}</span>
                                <input type="text" class="form-control form-control-sm text-center cell-input d-none" 
                                       value="{{ $item['bj_pagi'] }}" maxlength="4" placeholder="1023" style="font-size: 0.75rem; padding: 0.3rem;">
                            </td>
                            <td class="p-1 editable-cell" data-field="volume_sore" data-type="number">
                                <span class="cell-display" style="font-size: 0.8rem; font-weight: 600;">{{ $item['volume_sore'] ? number_format($item['volume_sore'], 2) : '-' }}</span>
                                <input type="number" class="form-control form-control-sm text-center cell-input d-none" 
                                       value="{{ $item['volume_sore'] }}" step="0.01" min="0" style="font-size: 0.75rem; padding: 0.3rem;">
                            </td>
                            <td class="p-1 editable-cell" data-field="bj_sore" data-type="bj" data-waktu="sore">
                                <span class="cell-display" style="font-size: 0.8rem; font-weight: 600;">{{ $item['bj_sore'] ? number_format($item['bj_sore'] / 1000, 3) : '-' }}</span>
                                <input type="text" class="form-control form-control-sm text-center cell-input d-none" 
                                       value="{{ $item['bj_sore'] }}" maxlength="4" placeholder="1022" style="font-size: 0.75rem; padding: 0.3rem;">
                            </td>
                            <td class="text-center p-1 bg-success bg-opacity-10 total-per-row" style="font-size: 0.85rem; font-weight: 700;">
                                <span class="text-success total-value-per-row">
                                    {{ number_format(($item['volume_pagi'] ?? 0) + ($item['volume_sore'] ?? 0), 2) }}
                                </span>
                            </td>
                            <td class="text-center p-1">
                                @if($item['penyetoran_id'])
                                <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                        data-id="{{ $item['penyetoran_id'] }}"
                                        title="Hapus" style="padding: 0.2rem 0.4rem;">
                                    <i class="ti tabler-trash" style="font-size: 0.9rem;"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="ti tabler-users fs-2 d-block mb-2 opacity-25"></i>
                                <p class="mb-2" style="font-size: 0.875rem;">Belum ada peternak di pos ini</p>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalManagePeternak">
                                    <i class="ti tabler-user-plus me-1"></i> Tambah Peternak
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($data->count() > 0)
                    <tfoot class="table-light border-top border-2">
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end" style="font-size: 0.75rem; padding: 0.4rem 0.3rem;">TOTAL:</td>
                            <td class="text-center text-info" id="totalPagi" style="font-size: 0.85rem; padding: 0.4rem 0.2rem;">
                                {{ number_format($totalVolumePagi, 2) }} L
                            </td>
                            <td class="text-center text-muted" style="font-size: 0.7rem; padding: 0.4rem 0.2rem;">-</td>
                            <td class="text-center text-warning" id="totalSore" style="font-size: 0.85rem; padding: 0.4rem 0.2rem;">
                                {{ number_format($totalVolumeSore, 2) }} L
                            </td>
                            <td class="text-center text-muted" style="font-size: 0.7rem; padding: 0.4rem 0.2rem;">-</td>
                            <td class="text-center text-success" id="totalAllInline" style="font-size: 0.85rem; padding: 0.4rem 0.2rem;">
                                {{ number_format($totalVolume, 2) }} L
                            </td>
                            <td class="text-center text-muted" style="font-size: 0.7rem; padding: 0.4rem 0.2rem;">-</td>
                        </tr>
                        <tr class="fw-bold bg-primary bg-opacity-10">
                            <td colspan="3" class="text-end" style="font-size: 0.8rem; padding: 0.5rem 0.3rem;">TOTAL KESELURUHAN:</td>
                            <td colspan="6" class="text-center text-primary" id="totalAll" style="font-size: 0.9rem; padding: 0.5rem 0.2rem;">
                                <i class="ti tabler-droplet me-1"></i>
                                <span class="total-value">{{ number_format($totalVolume, 2) }}</span> Liter
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-info py-1 mb-0" style="font-size: 0.75rem;">
        <i class="ti tabler-info-circle me-1"></i>
        <strong>Cara Input:</strong> Klik cell untuk edit. BJ format 4 digit (1023 = 1.023). 
        <strong class="text-danger">BJ Pagi ≥1023, BJ Sore ≥1022, Suhu ≤30°C</strong>
    </div>
</div>

{{-- MODAL KELOLA PETERNAK --}}
<div class="modal fade" id="modalManagePeternak" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-info">
                <h5 class="modal-title text-white fw-bold">
                    <i class="ti tabler-users-group me-2"></i>Kelola Peternak - {{ $pos->nama_pos }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Form Tambah Peternak --}}
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-header bg-light py-2 border-0">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="ti tabler-user-plus me-1"></i> Tambah Peternak Baru
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="formAddPeternak">
                            <input type="hidden" name="pos_id" value="{{ $pos->id }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">
                                        Kode Peternak <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control text-uppercase" 
                                           name="kode_peternak" 
                                           id="inputKodePeternak"
                                           placeholder="PTK-001"
                                           maxlength="50"
                                           required
                                           autocomplete="off"
                                           style="text-transform: uppercase;">
                                    <small class="text-muted">
                                        <i class="ti tabler-info-circle"></i> 
                                        Contoh: PTK-001, BORO-01
                                    </small>
                                </div>
                                
                                <div class="col-md-8">
                                    <label class="form-label fw-medium">
                                        Nama Peternak <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           name="nama_peternak" 
                                           placeholder="Masukkan nama lengkap peternak"
                                           required
                                           autocomplete="off">
                                </div>
                                
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-info px-4" id="btnSavePeternak">
                                        <i class="ti tabler-check me-1"></i> Simpan Peternak
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Daftar Peternak --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light py-2 border-0 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="ti tabler-list me-1"></i> Daftar Peternak
                        </h6>
                        <span class="badge bg-primary" id="totalPeternakBadge">0 Peternak</span>
                    </div>
                    <div class="card-body p-0">
                        {{-- Mobile-Friendly Card Layout --}}
                        <div class="d-block d-md-none" id="peternakCardsMobile"></div>

                        {{-- Desktop Table Layout --}}
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th style="width: 120px;">Kode</th>
                                        <th>Nama Peternak</th>
                                        <th class="text-center" style="width: 100px;">Status</th>
                                        <th class="text-center" style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="peternakTableBody"></tbody>
                            </table>
                        </div>

                        {{-- Empty State --}}
                        <div class="text-center py-5 d-none" id="emptyState">
                            <i class="ti tabler-users-off" style="font-size: 80px; opacity: 0.15;"></i>
                            <p class="text-muted mt-3">Belum ada peternak di pos ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT PETERNAK --}}
<div class="modal fade" id="modalEditPeternak" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white fw-bold">
                    <i class="ti tabler-edit me-2"></i>Edit Peternak
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditPeternak">
                <div class="modal-body">
                    <input type="hidden" id="edit_peternak_id">
                    <div class="mb-3">
                        <label class="form-label fw-medium">Kode Peternak</label>
                        <input type="text" class="form-control" id="edit_kode_peternak" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Peternak <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama_peternak" required>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="edit_is_active">
                        <label class="form-check-label fw-medium" for="edit_is_active">
                            Status Aktif
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti tabler-check me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI EXPORT --}}
<div class="modal fade" id="modalExportConfirm" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white">
                    <i class="ti tabler-alert-triangle me-2"></i>Konfirmasi Export
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="ti tabler-file-excel" style="font-size: 80px; color: #28a745;"></i>
                </div>
                <h5 class="text-center mb-3">Apakah Anda yakin data sudah benar?</h5>
                <p class="text-center text-muted mb-0">
                    Data yang akan di-export:<br>
                    <strong>{{ $pos->nama_pos }}</strong><br>
                    <strong>{{ $tanggalCarbon->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btnConfirmExport">
                    <i class="ti tabler-check me-1"></i> Ya, Export Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalPicker = document.getElementById('tanggalPicker');
    const posId = document.getElementById('currentPosId').value;
    
    // ========== DATE NAVIGATION ==========
    function goToDate(date) {
        window.location.href = `{{ route('penyetoran.show', $pos->id) }}?tanggal=${date}`;
    }

    tanggalPicker?.addEventListener('change', function() { goToDate(this.value); });
    
    document.getElementById('prevDate')?.addEventListener('click', function() {
        const current = new Date(tanggalPicker.value);
        current.setDate(current.getDate() - 1);
        goToDate(current.toISOString().split('T')[0]);
    });
    
    document.getElementById('nextDate')?.addEventListener('click', function() {
        const current = new Date(tanggalPicker.value);
        current.setDate(current.getDate() + 1);
        goToDate(current.toISOString().split('T')[0]);
    });
    
    document.getElementById('todayBtn')?.addEventListener('click', function() {
        goToDate(new Date().toISOString().split('T')[0]);
    });

    // ========== LOAD PETERNAK LIST ==========
    let peternakData = [];

    function loadPeternakList() {
        const posId = document.getElementById('currentPosId').value;
        
        fetch(`{{ url('peternak/by-pos') }}/${posId}`)
            .then(res => res.json())
            .then(data => {
                peternakData = Array.isArray(data) ? data : [];
                renderPeternakList();
            })
            .catch(err => {
                console.error(err);
                showToast('Error', 'Gagal memuat daftar peternak', 'danger');
            });
    }

    function renderPeternakList() {
        const tableBody = document.getElementById('peternakTableBody');
        const cardsMobile = document.getElementById('peternakCardsMobile');
        const emptyState = document.getElementById('emptyState');
        const totalBadge = document.getElementById('totalPeternakBadge');
        
        if (peternakData.length === 0) {
            tableBody.innerHTML = '';
            cardsMobile.innerHTML = '';
            emptyState.classList.remove('d-none');
            totalBadge.textContent = '0 Peternak';
            return;
        }
        
        emptyState.classList.add('d-none');
        totalBadge.textContent = peternakData.length + ' Peternak';
        
        // Render Desktop Table
        let tableHtml = '';
        peternakData.forEach((p, index) => {
            tableHtml += `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td><code class="text-primary fw-bold">${p.kode_peternak}</code></td>
                    <td class="fw-medium">${p.nama_peternak}</td>
                    <td class="text-center">
                        <span class="badge bg-${p.is_active ? 'success' : 'secondary'}">
                            ${p.is_active ? 'Aktif' : 'Nonaktif'}
                        </span>
                    </td>
                    <td class="text-center">
                        <button type="button" 
                                class="btn btn-sm btn-warning btn-edit-peternak me-1" 
                                data-id="${p.id}"
                                data-kode="${p.kode_peternak}"
                                data-nama="${p.nama_peternak}"
                                data-active="${p.is_active ? 1 : 0}"
                                title="Edit">
                            <i class="ti tabler-edit"></i>
                        </button>
                        <button type="button" 
                                class="btn btn-sm btn-danger btn-delete-peternak" 
                                data-id="${p.id}"
                                title="Hapus">
                            <i class="ti tabler-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        tableBody.innerHTML = tableHtml;
        
        // Render Mobile Cards
        let cardsHtml = '';
        peternakData.forEach((p, index) => {
            cardsHtml += `
                <div class="border-bottom p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-light text-dark">#${index + 1}</span>
                                <code class="text-primary fw-bold">${p.kode_peternak}</code>
                            </div>
                            <h6 class="mb-1 fw-bold">${p.nama_peternak}</h6>
                        </div>
                        <span class="badge bg-${p.is_active ? 'success' : 'secondary'}">
                            ${p.is_active ? 'Aktif' : 'Nonaktif'}
                        </span>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" 
                                class="btn btn-sm btn-warning btn-edit-peternak flex-fill" 
                                data-id="${p.id}"
                                data-kode="${p.kode_peternak}"
                                data-nama="${p.nama_peternak}"
                                data-active="${p.is_active ? 1 : 0}">
                            <i class="ti tabler-edit me-1"></i> Edit
                        </button>
                        <button type="button" 
                                class="btn btn-sm btn-danger btn-delete-peternak" 
                                data-id="${p.id}">
                            <i class="ti tabler-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        cardsMobile.innerHTML = cardsHtml;
        
        attachPeternakEventListeners();
    }

    function attachPeternakEventListeners() {
        document.querySelectorAll('.btn-edit-peternak').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const kode = this.dataset.kode;
                const nama = this.dataset.nama;
                const active = this.dataset.active == 1;
                
                document.getElementById('edit_peternak_id').value = id;
                document.getElementById('edit_kode_peternak').value = kode;
                document.getElementById('edit_nama_peternak').value = nama;
                document.getElementById('edit_is_active').checked = active;
                
                const modal = new bootstrap.Modal(document.getElementById('modalEditPeternak'));
                modal.show();
            });
        });
        
        document.querySelectorAll('.btn-delete-peternak').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Hapus peternak ini?')) return;
                
                const id = this.dataset.id;
                
                fetch(`{{ url('peternak') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Berhasil', data.message, 'success');
                        loadPeternakList();
                    } else {
                        showToast('Error', data.message, 'danger');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('Error', 'Gagal menghapus peternak', 'danger');
                });
            });
        });
    }

    // ========== AUTO UPPERCASE KODE PETERNAK ==========
    document.getElementById('inputKodePeternak')?.addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
        this.value = this.value.replace(/[^A-Z0-9-]/g, '');
    });

    // ========== FORM TAMBAH PETERNAK ==========
    document.getElementById('formAddPeternak')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnSavePeternak');
        const kode = document.querySelector('[name="kode_peternak"]').value;
        const nama = document.querySelector('[name="nama_peternak"]').value;
        
        if (!kode || !nama) {
            showToast('Error', 'Kode dan nama peternak wajib diisi', 'danger');
            return;
        }
        
        if (!/^[A-Z0-9-]+$/.test(kode)) {
            showToast('Error', 'Kode hanya boleh huruf besar, angka, dan strip (-)', 'danger');
            return;
        }
        
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
        
        fetch('{{ route("peternak.storeAjax") }}', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: new FormData(this)
        })
        .then(res => {
            if (res.status === 422) {
                return res.json().then(data => {
                    const errors = data.errors || {};
                    const firstError = Object.values(errors)[0];
                    if (firstError && firstError[0]) {
                        showToast('Error Validasi', firstError[0], 'danger');
                    }
                    throw new Error('Validation failed');
                });
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Berhasil', data.message, 'success');
                this.reset();
                loadPeternakList();
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showToast('Error', data.message || 'Gagal menyimpan', 'danger');
            }
        })
        .catch(err => {
            console.error(err);
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti tabler-check me-1"></i> Simpan Peternak';
        });
    });

    // ========== FORM EDIT PETERNAK ==========
    document.getElementById('formEditPeternak')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('edit_peternak_id').value;
        const nama = document.getElementById('edit_nama_peternak').value;
        const active = document.getElementById('edit_is_active').checked;
        
        fetch(`{{ url('peternak') }}/${id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nama_peternak: nama,
                is_active: active
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Berhasil', data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('modalEditPeternak')).hide();
                loadPeternakList();
                setTimeout(() => window.location.reload(), 1500);
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Error', 'Gagal update peternak', 'danger');
        });
    });

    // ========== LOAD ON MODAL OPEN ==========
    document.getElementById('modalManagePeternak')?.addEventListener('shown.bs.modal', function() {
        loadPeternakList();
    });

    // ========== EDITABLE CELL ==========
    document.querySelectorAll('.editable-cell').forEach(cell => {
        const display = cell.querySelector('.cell-display');
        const input = cell.querySelector('.cell-input');
        
        cell.addEventListener('click', function(e) {
            if (e.target.classList.contains('cell-input')) return;
            
            display.classList.add('d-none');
            input.classList.remove('d-none');
            input.focus();
            input.select();
        });
        
        input.addEventListener('blur', function() {
            saveCell(cell);
        });
        
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.blur();
            }
        });
    });

    function saveCell(cell) {
        const display = cell.querySelector('.cell-display');
        const input = cell.querySelector('.cell-input');
        const row = cell.closest('tr');
        const peternakId = row.dataset.peternakId;
        const field = cell.dataset.field;
        const type = cell.dataset.type;
        const tanggal = document.getElementById('currentTanggal').value;
        
        let value = input.value;
        
        if (type === 'bj' && value) {
            value = value.replace(/\D/g, '');
            
            if (value.length !== 4) {
                showToast('Error', 'BJ harus 4 digit (1023 untuk pagi, 1022 untuk sore)', 'danger');
                input.value = input.defaultValue || '';
                display.classList.remove('d-none');
                input.classList.add('d-none');
                return;
            }
            
            input.value = value;
        }
        
        if (type === 'bj') {
            display.textContent = value ? (parseFloat(value) / 1000).toFixed(3) : '-';
        } else {
            display.textContent = value ? parseFloat(value).toFixed(2) : '-';
        }
        
        display.classList.remove('d-none');
        input.classList.add('d-none');
        
        if (!value) return;
        
        cell.style.backgroundColor = '#ffecb5';
        
        fetch('{{ route("penyetoran.updateCell") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                peternak_id: peternakId,
                tanggal: tanggal,
                field: field,
                value: value
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                cell.style.backgroundColor = '#d4edda';
                row.dataset.penyetoranId = data.data.id;
                
                const actionCell = row.querySelector('td:last-child');
                if (!actionCell.querySelector('.btn-delete')) {
                    actionCell.innerHTML = `
                        <button type="button" class="btn btn-sm btn-danger btn-delete" 
                                data-id="${data.data.id}" title="Hapus" style="padding: 0.2rem 0.4rem;">
                            <i class="ti tabler-trash" style="font-size: 0.9rem;"></i>
                        </button>
                    `;
                    
                    actionCell.querySelector('.btn-delete').addEventListener('click', function() {
                        deleteRow(this);
                    });
                }
                
                setTimeout(() => {
                    cell.style.backgroundColor = '';
                }, 2000);
                
                calculateTotals();
            } else {
                cell.style.backgroundColor = '#f8d7da';
                showToast('Error', data.message || 'Gagal menyimpan', 'danger');
                
                input.value = input.defaultValue || '';
                if (type === 'bj') {
                    display.textContent = input.defaultValue ? (parseFloat(input.defaultValue) / 1000).toFixed(3) : '-';
                } else {
                    display.textContent = input.defaultValue ? parseFloat(input.defaultValue).toFixed(2) : '-';
                }
                
                setTimeout(() => {
                    cell.style.backgroundColor = '';
                }, 3000);
            }
        })
        .catch(err => {
            console.error(err);
            cell.style.backgroundColor = '#f8d7da';
            showToast('Error', 'Terjadi kesalahan', 'danger');
        });
    }

    // ========== DELETE ROW ==========
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            deleteRow(this);
        });
    });

    function deleteRow(btn) {
        if (!confirm('Hapus data penyetoran ini?')) return;
        
        const id = btn.dataset.id;
        
        fetch(`{{ url('penyetoran') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Berhasil', 'Data berhasil dihapus', 'success');
                window.location.reload();
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Error', 'Gagal menghapus data', 'danger');
        });
    }

    // ========== CALCULATE TOTALS ==========
    function calculateTotals() {
        let totalPagi = 0;
        let totalSore = 0;
        
        document.querySelectorAll('tbody tr[data-peternak-id]').forEach(row => {
            const volPagi = parseFloat(row.querySelector('[data-field="volume_pagi"] .cell-input').value) || 0;
            const volSore = parseFloat(row.querySelector('[data-field="volume_sore"] .cell-input').value) || 0;
            const totalRow = volPagi + volSore;
            
            // Update total per row
            const totalCell = row.querySelector('.total-value-per-row');
            if (totalCell) {
                totalCell.textContent = totalRow.toFixed(2);
            }
            
            totalPagi += volPagi;
            totalSore += volSore;
        });
        
        document.getElementById('totalPagi').textContent = totalPagi.toFixed(2) + ' L';
        document.getElementById('totalSore').textContent = totalSore.toFixed(2) + ' L';
        document.getElementById('totalAllInline').textContent = (totalPagi + totalSore).toFixed(2) + ' L';
        document.querySelector('.total-value').textContent = (totalPagi + totalSore).toFixed(2);
    }

    // ========== EXPORT WITH CONFIRMATION ==========
    document.getElementById('btnExport')?.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('modalExportConfirm'));
        modal.show();
    });

    document.getElementById('btnConfirmExport')?.addEventListener('click', function() {
        const tanggal = document.getElementById('tanggalPicker').value;
        window.location.href = `{{ route('penyetoran.export') }}?pos_id=${posId}&tanggal=${tanggal}`;
        bootstrap.Modal.getInstance(document.getElementById('modalExportConfirm')).hide();
    });

    // ========== TOAST NOTIFICATION ==========
    function showToast(title, message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>
                    <small>${message}</small>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        document.body.appendChild(toast);
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', () => {
            toast.remove();
        });
    }
});
</script>
@endpush

@push('styles')
<style>
/* ========== FIX SIDEBAR RESPONSIVENESS ========== */
.layout-page {
    transition: padding-left 0.3s ease, margin-left 0.3s ease;
}

/* Desktop - Sidebar Expanded */
@media (min-width: 1200px) {
    .layout-menu-expanded .layout-page {
        padding-left: 260px !important;
    }
    
    .layout-menu-collapsed .layout-page {
        padding-left: 80px !important;
    }
}

/* Tablet - Overlay Mode */
@media (min-width: 768px) and (max-width: 1199px) {
    .layout-page {
        padding-left: 0 !important;
        margin-left: 0 !important;
    }
    
    .layout-menu {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .layout-menu-expanded .layout-menu {
        transform: translateX(0);
    }
}

/* Mobile - Full Overlay */
@media (max-width: 767px) {
    .layout-page {
        padding-left: 0 !important;
        margin-left: 0 !important;
        width: 100% !important;
    }
    
    .content-wrapper {
        padding: 0.5rem !important;
    }
}

/* ========== COMPACT TABLE LAYOUT ========== */
.editable-cell {
    cursor: pointer;
    transition: background-color 0.2s;
}

.editable-cell:hover {
    background-color: #f8f9fa;
}

.cell-display {
    display: block;
    padding: 0.3rem;
    min-height: 1.5rem;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
}

.table-sm th, .table-sm td {
    padding: 0.3rem 0.25rem;
}

code {
    font-size: 0.65rem;
    padding: 0.1rem 0.25rem;
    border-radius: 0.2rem;
    background-color: #f0f2f5;
}

/* Fix untuk icon Tabler */
.ti {
    font-family: 'tabler-icons' !important;
    speak: none;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ========== MOBILE OPTIMIZATIONS ========== */
@media (max-width: 767px) {
    /* Hide desktop view, show mobile cards */
    #peternakCardsMobile {
        display: block !important;
    }
    
    .table-responsive.d-md-block {
        display: none !important;
    }
    
    /* Compact header on mobile */
    .card-body.py-2 {
        padding: 0.5rem !important;
    }
    
    /* Smaller font for table */
    .table-responsive {
        font-size: 0.7rem;
    }
    
    /* Mobile: Kolom kode & nama kecil */
    th:nth-child(2), td:nth-child(2),
    th:nth-child(3), td:nth-child(3) {
        font-size: 0.6rem !important;
        padding: 0.15rem 0.2rem !important;
        max-width: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Kode peternak extra small */
    td:nth-child(2) code {
        font-size: 0.55rem !important;
        padding: 0.05rem 0.15rem !important;
    }
    
    /* Volume & BJ lebih visible di mobile */
    th:nth-child(4), td:nth-child(4),
    th:nth-child(5), td:nth-child(5),
    th:nth-child(6), td:nth-child(6),
    th:nth-child(7), td:nth-child(7) {
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        padding: 0.3rem 0.25rem !important;
    }
    
    /* Total column bigger */
    th:nth-child(8), td:nth-child(8) {
        font-size: 0.9rem !important;
        font-weight: 800 !important;
        padding: 0.3rem !important;
    }
    
    /* Compact button spacing */
    .btn-sm {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
    }
    
    /* Stack action buttons on mobile */
    .col-lg-6.col-md-12 .d-flex {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .col-lg-6.col-md-12 .d-flex .btn {
        width: 100%;
    }
    
    /* Horizontal scroll for table */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        max-width: 100vw;
    }
    
    /* Table min width untuk scroll horizontal yang smooth */
    #tablePenyetoran {
        min-width: 600px;
        width: 100%;
    }
    
    /* Make total column sticky on mobile */
    .total-per-row {
        position: sticky;
        right: 50px;
        background-color: #f0fdf4 !important;
        z-index: 10;
        box-shadow: -2px 0 4px rgba(0,0,0,0.05);
    }
    
    /* Sticky action column */
    tbody td:last-child,
    thead th:last-child,
    tfoot td:last-child {
        position: sticky;
        right: 0;
        background-color: white;
        z-index: 10;
        box-shadow: -2px 0 4px rgba(0,0,0,0.05);
    }
    
    thead th:last-child {
        background-color: #f8f9fa;
    }
    
    tfoot td:last-child {
        background-color: #f8f9fa;
    }
}

/* ========== TABLET OPTIMIZATIONS ========== */
@media (min-width: 768px) and (max-width: 991px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    th, td {
        padding: 0.4rem 0.3rem !important;
    }
    
    .btn-sm {
        padding: 0.3rem 0.6rem !important;
        font-size: 0.8rem !important;
    }
}

/* Desktop - Hide mobile cards */
@media (min-width: 768px) {
    #peternakCardsMobile {
        display: none !important;
    }
}

/* ========== COMPACT MODE ========== */
.container-xxl.container-p-y {
    padding: 1rem 1.5rem !important;
}

@media (max-width: 767px) {
    .container-xxl.container-p-y {
        padding: 0.5rem !important;
    }
    
    .card {
        margin-bottom: 0.75rem !important;
    }
    
    .alert {
        margin-bottom: 0.5rem !important;
        padding: 0.5rem !important;
        font-size: 0.8rem !important;
    }
}

/* ========== SMOOTH TRANSITIONS ========== */
* {
    transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
}

/* Prevent content jump when sidebar opens */
.layout-wrapper {
    overflow-x: hidden;
}

/* Fix overlay z-index */
.layout-overlay {
    z-index: 9;
}

.layout-menu {
    z-index: 10;
}
</style>
@endpush