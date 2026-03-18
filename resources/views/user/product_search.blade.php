@extends('user.layouts.default')

@section('title', 'Tìm kiếm sản phẩm')

@push('page_specific_css')
<style>
    /* Sử dụng tiền tố sp- (Shop Project) để tránh trùng lặp */
    :root {
        --sp-primary-color: #ee4d2d;
        --sp-bg-gray: #f5f5f5;
        --sp-border-color: #e5e5e5;
    }

    body {
        background-color: var(--sp-bg-gray);
    }

    /* Sidebar Custom Classes */
    .sp-filter-section {
        background: transparent;
        padding-right: 15px;
    }

    .sp-filter-group-title {
        font-weight: 500;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        text-transform: uppercase;
        color: #333;
    }

    .sp-category-item {
        margin-bottom: 8px;
    }

    .sp-checkbox-custom {
        cursor: pointer;
        font-size: 0.85rem;
    }

    /* Price Filter */
    .sp-price-input-group {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
    }

    .sp-input-field {
        font-size: 0.8rem !important;
        border-radius: 2px !important;
    }

    .sp-btn-apply {
        background-color: var(--sp-primary-color);
        color: white;
        border: none;
        width: 100%;
        padding: 6px;
        font-size: 0.8rem;
        border-radius: 2px;
        transition: opacity 0.2s;
    }

    .sp-btn-apply:hover {
        opacity: 0.9;
        color: white;
    }

    /* Rating Section */
    .sp-rating-container {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .sp-rating-row {
        display: flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .sp-rating-row:hover {
        background-color: #e8e8e8;
    }

    .sp-rating-row.is-active {
        background-color: #ebebeb;
    }

    .sp-star-icon {
        color: #ffce3d;
        font-size: 0.85rem;
        margin-right: 2px;
    }

    .sp-star-empty {
        color: #ccc;
    }

    .sp-rating-text {
        font-size: 0.8rem;
        margin-left: 8px;
        color: #333;
    }

    /* Product Card */
    .sp-product-item {
        background: white;
        border: 1px solid transparent;
        transition: all 0.2s;
        height: 100%;
        border-radius: 2px;
    }

    .sp-product-item:hover {
        border-color: var(--sp-primary-color);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .sp-product-img {
        width: 100%;
        aspect-ratio: 1/1;
        object-fit: cover;
    }

    .sp-product-info {
        padding: 8px;
    }

    .sp-product-name {
        font-size: 0.8rem;
        line-height: 1.2;
        height: 2.4rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 8px;
    }

    .sp-product-price {
        color: var(--sp-primary-color);
        font-weight: 500;
        font-size: 1rem;
    }

    .sp-discount-label {
        background: #ffe9e4;
        color: var(--sp-primary-color);
        font-size: 0.7rem;
        padding: 1px 4px;
    }

    /* Layout Grid 5 columns */
    @media (min-width: 1200px) {
        .sp-col-5 {
            flex: 0 0 auto;
            width: 20%;
        }
    }
</style>
@endpush

@section('content')
<div id="wp-content" class="bg-body-tertiary" style="margin-top: 57px;">
    <div class="container py-5">

        <form action="{{ route('search') }}" method="GET" class="row">

            @if(request('query'))
            <input type="hidden" name="query" value="{{ request('query') }}">
            @endif

            <aside class="col-lg-3 col-xl-2 sp-filter-section">

                <div class="mb-4">
                    <h6 class="sp-filter-group-title"><i class="bi bi-funnel me-2"></i>Danh mục</h6>
                    @foreach($categories as $category)
                    <div class="sp-category-item">
                        <div class="form-check">
                            <input class="form-check-input shadow-none"
                                type="checkbox"
                                name="categories[]"
                                value="{{ $category->id }}"
                                id="sp-cat-{{ $category->id }}"
                                {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}
                                onchange="this.form.submit()">
                            <label class="form-check-label sp-checkbox-custom" for="sp-cat-{{ $category->id }}">
                                {{ $category->name }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>

                <hr class="my-3">

                <div class="mb-4">
                    <h6 class="sp-filter-group-title">Khoảng Giá</h6>
                    <div class="sp-price-input-group">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control sp-input-field shadow-none" placeholder="Từ">
                        <span class="text-muted small">-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control sp-input-field shadow-none" placeholder="Đến">
                    </div>
                    <button type="submit" class="sp-btn-apply">ÁP DỤNG</button>
                </div>

                <hr class="my-3">

                <div class="mb-4">
                    <h6 class="sp-filter-group-title">Đánh Giá</h6>
                    <div class="sp-rating-container">
                        <input type="hidden" name="rating" id="ratingInput" value="{{ request('rating') }}">

                        @for($stars = 5; $stars >= 1; $stars--)
                        <div class="sp-rating-row {{ request('rating') == $stars ? 'is-active' : '' }}" onclick="selectRating('{{ $stars }}')">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi {{ $i <= $stars ? 'bi-star-fill sp-star-icon' : 'bi-star sp-star-icon sp-star-empty' }}"></i>
                                @endfor
                                @if($stars < 5)
                                    <span class="sp-rating-text">trở lên</span>
                                    @endif
                        </div>
                        @endfor
                    </div>
                </div>
            </aside>

            <main class="col-lg-9 col-xl-10">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-normal">
                        @if(request('query'))
                        Kết quả tìm kiếm cho từ khoá '<span class="text-danger">{{ request('query') }}</span>'
                        @else
                        Tất cả sản phẩm
                        @endif
                    </h5>
                    <span class="text-muted">{{ $products->total() }} kết quả</span>
                </div>

                <div class="row g-2">
                    @forelse($products as $product)
                    <div class="col-6 col-md-4 sp-col-5 mb-3">
                        <a href="/product/{{ $product->id }}" class="text-decoration-none text-dark">
                            <div class="sp-product-item">
                                <img src="{{ asset('storage/' . ($product->primaryImage->image_url ?? 'default.jpg')) }}" class="sp-product-img" alt="{{ $product->name }}">
                                <div class="sp-product-info d-flex flex-column" style="height: calc(100% - 200px);">
                                    <div class="sp-product-name">{{ $product->name }}</div>
                                    <div class="mt-auto">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="sp-product-price">{{ number_format($product->min_price, 0, ',', '.') }}₫</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2 align-items-center">
                                            <div class="small text-warning" style="font-size: 0.75rem;">
                                                ★ {{ number_format($product->avg_rating, 1) }}
                                            </div>
                                            <span class="small text-muted">Đã bán {{ $product->total_sold }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <img src="{{ asset('assets/img/empty-search.png') }}" width="150" alt="Not found">
                        <h5 class="text-muted mt-3">Không tìm thấy sản phẩm nào phù hợp</h5>
                    </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </main>

        </form>
    </div>
</div>
@endsection

@push('page_specific_js')
<script>
    // Script hỗ trợ việc click vào phần Đánh giá (Rating) thì tự động submit form
    function selectRating(stars) {
        document.getElementById('ratingInput').value = stars;
        // Bỏ active cũ, gán active mới cho UI
        document.querySelectorAll('.sp-rating-row').forEach(el => el.classList.remove('is-active'));
        event.currentTarget.classList.add('is-active');
        // Tự động submit form
        document.getElementById('ratingInput').closest('form').submit();
    }
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush