@extends('admin.layouts.master')

@section('title', 'Tạo người dùng mới')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/admin/products/create.css') }}">
@endpush
@section('content')

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">Thông tin cơ bản</h4>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Hình ảnh sản phẩm
                    </div>
                    <div class="form-floating flex-grow-1">
                        <div class="image-list" id="imageContainer">
                            <div class="image-box" onclick="triggerUpload(this)">
                                <input type="file" name="images[]" accept="image/*" hidden onchange="handleUpload(event, this)">
                                <span>
                                    <span class="material-icons">add_photo_alternate</span><br>
                                    Thêm ảnh
                                </span>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Ảnh đầu tiên sẽ được sử dụng làm <b>ảnh bìa</b>
                        </small>

                        @error('images.*')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Tên sản phẩm
                    </div>
                    <div class="form-floating flex-grow-1">
                        <input name="name" type="text" class="form-control" id="productName" placeholder="Tên sản phẩm + Thương hiệu + Model">
                        <label for="productName">Tên sản phẩm + Thương hiệu + Model + Thông số kỹ thuật</label>
                        @error('name')
                        <small class="text-danger">
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
                            oninput="autoResize(this)"></textarea>
                        @error('description')
                        <small class="text-danger">
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
                                <input name="brand" type="text" class="form-control" id="brandName" placeholder="Tên thương hiệu">
                                <label for="brandName">Thương hiệu</label>
                                @error('brand')
                                <small class="text-danger">
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
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <label for="categorySelect">Chọn ngành hàng phù hợp</label>
                                @error('category_id')
                                <small class="text-danger">
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
                                    <input type="text" name="package_type_name" id="groupTitleInput" class="variant-title-input form-control" value=""
                                        oninput="updateTableHeader()" placeholder="Tên phân loại">
                                    <label for="groupTitleInput">Tên phân loại</label>
                                    @error('package_type_name')
                                    <small class="text-danger pt-3 ms-2">
                                        {{ $message }}
                                    </small>
                                    @enderror
                                </div>

                                <div class="form-floating flex-grow-1" style="display: flex;">
                                    <input type="text" name="package_type_unit" id="groupTitleInput2" class="variant-title-input form-control" value=""
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
                                <div class="col-md-6">
                                    <div class="variant-item">
                                        <!-- <i class="bi bi-image text-danger me-2"></i> -->
                                        <input type="text" name="packages_size[]" class="inner-input variant-input" placeholder="Nhập giá trị"
                                            oninput="updateTable(); checkNewField(this)">
                                        <span class="text-muted small ms-auto char-count">0/20</span>
                                        <i class="bi bi-trash-fill ms-2 text-muted" onclick="removeField(this)"
                                            style="cursor:pointer"></i>
                                    </div>
                                </div>
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
                            <tbody id="variantTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-save shadow-sm">Thêm sản phẩm</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/products/create.js') }}"></script>
@endpush