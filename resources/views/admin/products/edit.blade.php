@extends('admin.layouts.master')

@section('title', 'Tạo người dùng mới')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/admin/products/edit.css') }}">
@endpush
@section('content')
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">Thông tin cơ bản</h4>
                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Hình ảnh sản phẩm
                    </div>
                    <div class="image-list" id="imageContainer">

                        @foreach($product->images as $index => $image)
                        <div class="image-box">
                            <img src="{{ asset('storage/' . $image->image_url) }}">

                            <div class="delete-btn"
                                onclick="removeOldImage(event, this, {{ $image->id }})">
                                <span class="material-icons">close</span>
                            </div>

                            @if($index === 0)
                            <div class="cover-badge">Ảnh bìa</div>
                            @error('images.*')
                            <small class="text-danger pt-3 ms-2">
                                {{ $message }}
                            </small>
                            @enderror
                            @endif

                            <input type="hidden" name="old_images[]" value="{{ $image->id }}">
                        </div>
                        @endforeach

                        <!-- Box upload mới -->
                        <div class="image-box" onclick="triggerUpload(this)">
                            <input type="file" name="images[]" accept="image/*" hidden onchange="handleUpload(event, this)">
                            <span>
                                <span class="material-icons">add_photo_alternate</span><br>
                                Thêm ảnh
                            </span>
                        </div>

                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Tên sản phẩm
                    </div>
                    <div class="form-floating flex-grow-1">
                        <input name="name" type="text" class="form-control" id="productName" placeholder="Tên sản phẩm + Thương hiệu + Model" value="{{ old('name', $product->name) }}">
                        <label for="productName">Tên sản phẩm + Thương hiệu + Model + Thông số kỹ thuật</label>
                        @error('name')
                        <small class="text-danger pt-3 ms-2">
                            {{ $message }}
                        </small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Mô tả sản phẩm
                    </div>
                    <div class="form-floating flex-grow-1">
                        <textarea
                            name="description"
                            class="form-control auto-expand"
                            id="productDescription"
                            placeholder="Mô tả sản phẩm"
                            oninput="autoResize(this)">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <small class="text-danger pt-3 ms-2">
                            {{ $message }}
                        </small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">Thông tin chi tiết</h4>
                <div class="row">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">
                                <span class="required-star">*</span>Thương hiệu
                            </div>
                            <div class="form-floating flex-grow-1">
                                <input name="brand" type="text" class="form-control" id="brandName" placeholder="Tên thương hiệu" value="{{ old('brand', $product->brand) }}">
                                <label for="brandName">Thương hiệu</label>
                                @error('brand')
                                <small class="text-danger pt-3 ms-2">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">
                                <span class="required-star">*</span>Danh mục
                            </div>
                            <div class="form-floating flex-grow-1">
                                <select class="form-select material-select" id="categorySelect" name="category_id" aria-label="Chọn ngành hàng">
                                    <option value="" selected disabled hidden></option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <label for="categorySelect">Chọn ngành hàng phù hợp</label>
                                @error('category_id')
                                <small class="text-danger pt-3 ms-2">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">Thông tin bán hàng</h4>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Phân loại sản phẩm
                    </div>
                    <div class="col">
                        <div class="variant-box">
                            <div class="d-flex align-items-center mb-3">
                                <div class="form-floating flex-grow-1" style="display: flex;">
                                    <input type="text" name="package_type_name" id="groupTitleInput" class="variant-title-input form-control" value="{{ old('package_type_name', optional($product->packageTypes->first())->type_name) }}"
                                        oninput="updateTableHeader()" placeholder="Tên phân loại">
                                    <label for="groupTitleInput">Tên phân loại</label>
                                    @error('package_type_name')
                                    <small class="text-danger pt-3 ms-2">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>

                                <div class="form-floating flex-grow-1" style="display: flex;">
                                    <input type="text" name="package_type_unit" id="groupTitleInput2" class="variant-title-input form-control" value="{{ old('package_type_unit', optional($product->packageTypes->first())->packages->first()->unit ?? '') }}"
                                        placeholder="Đơn vị tính">
                                    <label for="groupTitleInput2">Đơn vị tính</label>
                                    @error('package_type_unit')
                                    <small class="text-danger pt-3 ms-2">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>

                            </div>
                            <div class="row g-2" id="variantWrapper">

                                @php
                                $packages = optional($product->packageTypes->first())->packages ?? collect();
                                @endphp

                                @forelse($packages as $pkg)
                                <div class="col-md-6">
                                    <div class="variant-item">
                                        <input
                                            type="text"
                                            name="packages_size[]"
                                            class="inner-input variant-input"
                                            value="{{ $pkg->size }}"
                                            oninput="updateTable(); checkNewField(this)">
                                        <span class="text-muted small ms-auto char-count">
                                            {{ strlen($pkg->size) }}/20
                                        </span>
                                        <i class="bi bi-trash ms-2 text-muted"
                                            onclick="removeField(this)"
                                            style="cursor:pointer"></i>
                                    </div>
                                </div>
                                @empty
                                <!-- Nếu chưa có package -->
                                <div class="col-md-6">
                                    <div class="variant-item">
                                        <input
                                            type="text"
                                            name="packages_size[]"
                                            class="inner-input variant-input"
                                            placeholder="Nhập giá trị"
                                            oninput="updateTable(); checkNewField(this)">
                                        <span class="text-muted small ms-auto char-count">0/20</span>
                                        <i class="bi bi-trash ms-2 text-muted"
                                            onclick="removeField(this)"
                                            style="cursor:pointer"></i>
                                    </div>
                                </div>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row" id="tableSection" style="display: none;">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Danh sách phân loại
                    </div>
                    <div class="col">
                        <div class="batch-edit-bar">
                            <div class="currency-input" style="width: 200px;">
                                <span>₫</span>
                                <input type="number" id="batchPrice" placeholder="Giá">
                            </div>
                            <input type="number" id="batchStock" class="input-table" style="width: 150px"
                                placeholder="Kho hàng">
                            <button class="btn-apply" onclick="applyToAll()">Áp dụng cho tất cả</button>
                        </div>

                        <table class="table-variant">
                            <thead>
                                <tr>
                                    <th id="headerName" style="width: 30%;">Size</th>
                                    <th style="width: 40%;">* Giá</th>
                                    <th style="width: 30%;">* Kho hàng</th>
                                </tr>
                            </thead>
                            <tbody id="variantTableBody">
                                @if($product->packageTypes->first())
                                @foreach($product->packageTypes->first()->packages as $pkg)
                                <tr data-key="{{ $pkg->size }}">
                                    <td class="text-center fw-medium">
                                        {{ $pkg->size }}
                                        <input type="hidden" name="packages[{{ $pkg->size }}][size]" value="{{ $pkg->size }}">
                                    </td>
                                    <td>
                                        <div class="currency-input">
                                            <span>₫</span>
                                            <input type="number"
                                                name="packages[{{ $pkg->size }}][price]"
                                                class="row-price"
                                                value="{{ $pkg->price }}">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number"
                                            name="packages[{{ $pkg->size }}][stock]"
                                            class="input-table row-stock"
                                            value="{{ $pkg->stock }}">
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-link text-muted me-3">Hủy bỏ</a>
                        <button type="submit" class="btn btn-save shadow-sm">Cập nhật sản phẩm</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/products/edit.js') }}"></script>
@endpush