@extends('layouts.app')

@section('title', 'Manajemen Artikel')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Manajemen Artikel</h4>
        <a href="{{ route('articles.create') }}" class="btn btn-primary">
            <i class="ti tabler-plus me-1"></i> Tambah Artikel
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti tabler-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 100px;">Gambar</th>
                            <th>Judul</th>
                            <th style="width: 120px;">Author</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 150px;">Tanggal</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $index => $article)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" 
                                     class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <strong>{{ $article->title }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($article->excerpt, 60) }}</small>
                            </td>
                            <td>{{ $article->author->name }}</td>
                            <td>
                                <span class="badge bg-{{ $article->is_published ? 'success' : 'secondary' }}">
                                    {{ $article->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $article->created_at->locale('id')->isoFormat('D MMM Y') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('articles.edit', $article) }}" class="btn btn-sm btn-warning">
                                    <i class="ti tabler-edit"></i>
                                </a>
                                <form action="{{ route('articles.destroy', $article) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Hapus artikel ini?')">
                                        <i class="ti tabler-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Belum ada artikel
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection