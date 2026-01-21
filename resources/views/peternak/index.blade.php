<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold">Data Peternak</h4>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
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
                            <th>Pos</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peternakList as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->kode_peternak }}</strong></td>
                                <td>{{ $item->nama_peternak }}</td>
                                <td>{{ $item->pos->nama_pos ?? '-' }}</td>
                                <td>{{ Str::limit($item->alamat, 30) }}</td>
                                <td>{{ $item->no_hp }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $item->is_active ? 'success' : 'danger' }}">
                                        {{ $item->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-icon btn-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditPeternak"
                                            data-id="{{ $item->id }}"
                                            data-kode="{{ $item->kode_peternak }}"
                                            data-nama="{{ $item->nama_peternak }}"
                                            data-alamat="{{ $item->alamat }}"
                                            data-telepon="{{ $item->no_hp }}"
                                            data-status="{{ $item->is_active ? '1' : '0' }}">
                                        <i class="ti tabler-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-icon btn-danger" 
                                            onclick="deletePeternak({{ $item->id }}, '{{ $item->nama_peternak }}')">
                                        <i class="ti tabler-trash"></i>
                                    </button>
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

        <!-- Modal Edit Peternak -->
        <div class="modal fade" id="modalEditPeternak" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Peternak</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="formEditPeternak">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <input type="hidden" id="edit_peternak_id">
                            
                            <div class="mb-3">
                                <label class="form-label">Kode Peternak</label>
                                <input type="text" class="form-control" id="edit_kode_peternak" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="edit_nama_peternak" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" id="edit_alamat" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" id="edit_no_telepon">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" id="edit_status">
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Edit Peternak - Populate Modal
        document.getElementById('modalEditPeternak').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('edit_peternak_id').value = button.getAttribute('data-id');
            document.getElementById('edit_kode_peternak').value = button.getAttribute('data-kode');
            document.getElementById('edit_nama_peternak').value = button.getAttribute('data-nama');
            document.getElementById('edit_alamat').value = button.getAttribute('data-alamat') || '';
            document.getElementById('edit_no_telepon').value = button.getAttribute('data-telepon') || '';
            document.getElementById('edit_status').value = button.getAttribute('data-status');
        });

        // Submit Edit Form
        document.getElementById('formEditPeternak').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const peternakId = document.getElementById('edit_peternak_id').value;
            const formData = {
                nama_peternak: document.getElementById('edit_nama_peternak').value,
                alamat: document.getElementById('edit_alamat').value,
                no_hp: document.getElementById('edit_no_telepon').value,
                is_active: document.getElementById('edit_status').value == '1',
                _token: '{{ csrf_token() }}',
                _method: 'PUT'
            };

            fetch(`/peternak/${peternakId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal mengupdate data: ' + (data.message || 'Terjadi kesalahan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate data');
            });
        });

        // Delete Peternak
        function deletePeternak(id, nama) {
            if (confirm(`Yakin ingin menghapus peternak "${nama}"?`)) {
                fetch(`/peternak/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus data: ' + (data.message || 'Terjadi kesalahan'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data');
                });
            }
        }
    </script>
    @endpush
</x-app-layout>