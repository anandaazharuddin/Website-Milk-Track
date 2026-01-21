@extends('layouts.app')

@section('title', 'Artikel')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold mb-4">
        <i class="ti tabler-news me-2"></i>Artikel & Informasi
    </h4>

    <div class="row g-3">
        @forelse($articles as $article)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow-sm">
                <img src="{{ $article->image_url }}" class="card-img-top" alt="{{ $article->title }}" 
                     style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $article->title }}</h5>
                    <p class="card-text text-muted flex-grow-1">
                        {{ Str::limit($article->excerpt, 120) }}
                    </p>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted">
                            <i class="ti tabler-user me-1"></i>{{ $article->author->name }}
                        </small>
                        <small class="text-muted">
                            <i class="ti tabler-calendar me-1"></i>
                            {{ $article->created_at->locale('id')->isoFormat('D MMM Y') }}
                        </small>
                    </div>
                    <button class="btn btn-primary btn-sm mt-3 btn-detail-article" data-id="{{ $article->id }}">
                        <i class="ti tabler-eye me-1"></i> Selengkapnya
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="ti tabler-info-circle me-2"></i>
                Belum ada artikel yang dipublikasikan
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- MODAL DETAIL ARTIKEL --}}
<div class="modal fade" id="modalArticleDetail" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <div id="articleDetailContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalDetail = new bootstrap.Modal(document.getElementById('modalArticleDetail'));
    
    document.querySelectorAll('.btn-detail-article').forEach(btn => {
        btn.addEventListener('click', function() {
            const articleId = this.dataset.id;
            
            // Show loading
            document.getElementById('articleDetailContent').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
            
            modalDetail.show();
            
            // Fetch article detail
            fetch(`/articles/${articleId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const article = data.data;
                        
                        document.getElementById('articleDetailContent').innerHTML = `
                            <img src="${article.image_url}" class="img-fluid rounded mb-3" 
                                 style="width: 100%; max-height: 400px; object-fit: cover;">
                            
                            <h3 class="fw-bold mb-3">${article.title}</h3>
                            
                            <div class="d-flex gap-3 mb-3 text-muted">
                                <small>
                                    <i class="ti tabler-user me-1"></i>
                                    <strong>Author:</strong> ${article.author}
                                </small>
                                <small>
                                    <i class="ti tabler-calendar me-1"></i>
                                    <strong>Tanggal:</strong> ${article.created_at}
                                </small>
                            </div>
                            
                            <hr>
                            
                            <div class="article-content" style="line-height: 1.8; text-align: justify;">
                                ${article.content.replace(/\n/g, '<br>')}
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('articleDetailContent').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="ti tabler-alert-circle me-2"></i>
                            Gagal memuat artikel
                        </div>
                    `;
                });
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.article-content {
    font-size: 1rem;
    color: #333;
}

.article-content p {
    margin-bottom: 1rem;
}
</style>
@endpush