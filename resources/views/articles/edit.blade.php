@extends('layouts.app')

@section('title', 'Edit Artikel')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold">Edit Artikel</h4>
        <a href="{{ route('articles.index') }}" class="btn btn-secondary">
            <i class="ti tabler-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('articles.update', $article) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-medium">Judul Artikel <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           name="title" value="{{ old('title', $article->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Ringkasan/Excerpt <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                              name="excerpt" rows="3" required>{{ old('excerpt', $article->excerpt) }}</textarea>
                    @error('excerpt')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Konten Artikel <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              name="content" rows="10" required>{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Gambar Artikel</label>
                    
                    @if($article->image)
                        <div class="mb-2">
                            <img src="{{ $article->image_url }}" class="img-thumbnail" 
                                 style="max-width: 300px; max-height: 200px;">
                        </div>
                    @endif
                    
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           name="image" accept="image/*" id="imageInput">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <div class="mt-2" id="imagePreview"></div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_published" 
                           id="isPublished" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label fw-medium" for="isPublished">
                        Publikasikan Artikel
                    </label>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti tabler-check me-1"></i> Update Artikel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <strong>Preview Gambar Baru:</strong><br>
                <img src="${e.target.result}" class="img-thumbnail mt-2" 
                     style="max-width: 300px; max-height: 200px;">
            `;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});
</script>
@endpush