@extends('admin.layouts.master')

@section('title', 'Tạo người dùng mới')

@push('styles')
<style>
    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
    }

    :root {
        --google-blue: #1a73e8;
        --border-color: #dadce0;
    }

    /* .form-label {
        font-weight: 500;
        color: #555;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .form-control,
    .form-select {
        border-radius: 6px;
        border: 1px solid #dee2e6;
        padding: 10px;
    }

    .form-control:focus {
        border-color: #2196F3;
        box-shadow: none;
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        background: #f9f9f9;
        cursor: pointer;
        transition: 0.3s;
    }

    .upload-area:hover {
        border-color: #2196F3;
        background: #f0f7ff;
    }

    .upload-area i {
        font-size: 48px;
        color: #999;
    } */

    .btn-save {
        background-color: #0d4d2b;
        color: white;
        padding: 10px 30px;
        border: none;
    }

    .btn-save:hover {
        background-color: #0a3d22;
        color: white;
    }

    /* Tùy chỉnh Floating Label kiểu Material */
    .form-floating>label {
        color: #757575;
        padding-left: 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1a73e8;
        /* Màu xanh Google */
        box-shadow: none;
        border-width: 2px;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
        color: #1a73e8;
        opacity: 1;
    }

    .required-star {
        color: #d93025;
        /* Màu đỏ cảnh báo */
        margin-right: 4px;
    }

    .input-group-text {
        background: none;
        border: none;
        padding-left: 0;
        font-weight: 500;
        color: #3c4043;
        min-width: 200px;
        text-align: right;
    }

    .form-row {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }

    .image-list {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }

    .image-box {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        border: 2px dashed #cfd8dc;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        transition: .2s;
        background: #fafafa;
        overflow: hidden;
    }

    .image-box:hover {
        border-color: #1a73e8;
        background: #f1f6ff;
    }

    .image-box span {
        text-align: center;
        color: #5f6368;
        font-size: 14px;
    }

    .image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Nút xoá */
    .delete-btn {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(0, 0, 0, 0.6);
        color: #fff;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        opacity: 0;
        transition: .2s;
        cursor: pointer;
        z-index: 10;
    }

    .image-box:hover .delete-btn {
        opacity: 1;
    }

    .cover-badge {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: #1a73e8;
        color: white;
        font-size: 12px;
        text-align: center;
        padding: 2px 0;
    }

    .form-floating>textarea.form-control {
        height: 120px;
        /* Chiều cao mặc định cho khung mô tả */
        resize: none;
        /* Tắt tính năng kéo giãn nếu muốn cố định giao diện */
    }

    /* Đảm bảo gạch chân và label hoạt động mượt mà với textarea */
    .form-floating>textarea.form-control:focus {
        border-bottom: 2px solid #1a73e8;
    }

    .auto-expand {
        min-height: 50px;
        /* Giới hạn chiều cao tối đa là 300px */
        max-height: 300px;
        overflow-y: auto;
        /* Hiện thanh cuộn khi vượt quá max-height */
        resize: none;
        line-height: 1.5;
        transition: border-color 0.2s;
    }

    /* Tùy chỉnh thanh cuộn cho đẹp (Webkit) */
    .auto-expand::-webkit-scrollbar {
        width: 6px;
    }

    .auto-expand::-webkit-scrollbar-thumb {
        background-color: #e0e0e0;
        border-radius: 10px;
    }

    .auto-expand::-webkit-scrollbar-thumb:hover {
        background-color: #bdbdbd;
    }

    /* Loại bỏ viền mặc định của Bootstrap và thêm gạch chân kiểu Material */
    .material-select {
        border-radius: 5 !important;
        padding-left: 12px !important;
        background-position: right 0 center !important;
        /* Đẩy mũi tên sang phải sát lề */
        transition: border-bottom 0.2s ease-in-out;
    }

    .material-select:focus {
        border-bottom: 2px solid #1a73e8 !important;
        box-shadow: none !important;
    }

    /* Hiệu ứng label khi chọn giá trị */
    .form-floating>.form-select~label {
        color: #757575;
    }

    .form-floating>.form-select:focus~label {
        color: #1a73e8;
        opacity: 1;
    }

    /* Phần phân loại sản phẩm */

    .main-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(60, 64, 67, 0.3);
        padding: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Cấu trúc hàng Phân loại */
    .variant-box {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid #eee;
    }

    .variant-item {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        display: flex;
        align-items: center;
        padding: 5px 10px;
        margin-bottom: 10px;
    }

    .inner-input {
        border: none;
        outline: none;
        flex-grow: 1;
        font-size: 14px;
        padding: 5px;
    }

    /* Table Styling - Thêm Border đầy đủ */
    .table-variant {
        border-collapse: collapse;
        width: 100%;
        margin-top: 0;
        border: 1px solid var(--border-color);
    }

    .table-variant th {
        background-color: #f1f3f4;
        font-size: 13px;
        font-weight: 500;
        color: #5f6368;
        border: 1px solid var(--border-color);
        padding: 12px;
        text-align: center;
    }

    .table-variant td {
        border: 1px solid var(--border-color);
        padding: 10px;
        vertical-align: middle;
        background: #fff;
    }

    /* CẬP NHẬT: Border đầy đủ cho ô nhập trong bảng */
    .input-table {
        border: 1px solid var(--border-color);
        /* Thêm viền 4 cạnh */
        border-radius: 4px;
        padding: 8px 12px;
        width: 100%;
        outline: none;
        font-size: 14px;
        transition: all 0.2s;
        background-color: #fff;
    }

    .input-table:focus {
        border-color: var(--google-blue);
        box-shadow: 0 0 0 1px var(--google-blue);
        /* Tạo hiệu ứng viền xanh đậm khi focus */
    }

    /* Input group cho tiền tệ */
    .currency-input {
        display: flex;
        align-items: center;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background: #fff;
    }

    .currency-input span {
        padding: 0 10px;
        color: #70757a;
        border-right: 1px solid var(--border-color);
        font-size: 14px;
    }

    .currency-input input {
        border: none;
        padding: 8px;
        width: 100%;
        outline: none;
        border-radius: 0 4px 4px 0;
    }

    .currency-input:focus-within {
        border-color: var(--google-blue);
    }

    .batch-edit-bar {
        background: #f8f9fa;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-bottom: none;
        display: flex;
        gap: 10px;
        align-items: center;
        border-radius: 4px 4px 0 0;
    }

    .btn-apply {
        background-color: #ee4d2d;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
    }

    .variant-title-input {
        /* border: none;
        border-bottom: 2px solid transparent; */
        /* background: transparent; */
        font-weight: bold;
        width: 200px;
        outline: none;
        font-size: 16px;
    }
</style>
@endpush
@section('content')
<form action="#" method="POST">
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
                                <input type="file" accept="image/*" hidden onchange="handleUpload(event, this)">
                                <span>
                                    <span class="material-icons">add_photo_alternate</span><br>
                                    Thêm ảnh
                                </span>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-2">
                            Ảnh đầu tiên sẽ được sử dụng làm <b>ảnh bìa</b>
                        </small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Tên sản phẩm
                    </div>
                    <div class="form-floating flex-grow-1">
                        <input type="text" class="form-control" id="productName" placeholder="Tên sản phẩm + Thương hiệu + Model">
                        <label for="productName">Tên sản phẩm + Thương hiệu + Model + Thông số kỹ thuật</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Mô tả sản phẩm
                    </div>
                    <div class="form-floating flex-grow-1">
                        <textarea
                            class="form-control auto-expand"
                            id="productDescription"
                            placeholder="Mô tả sản phẩm"
                            oninput="autoResize(this)"></textarea>
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
                                <input type="text" class="form-control" id="brandName" placeholder="Tên thương hiệu">
                                <label for="brandName">Thương hiệu</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">
                                <span class="required-star">*</span>Danh mục
                            </div>
                            <div class="form-floating flex-grow-1">
                                <select class="form-select material-select" id="categorySelect" aria-label="Chọn ngành hàng">
                                    <option value="" selected disabled hidden></option>
                                    <option value="1">Thiết bị điện tử</option>
                                    <option value="2">Thời trang & Phụ kiện</option>
                                    <option value="3">Mỹ phẩm & Làm đẹp</option>
                                    <option value="4">Sức khỏe & Đời sống</option>
                                </select>
                                <label for="categorySelect">Chọn ngành hàng phù hợp</label>
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
                                    <input type="text" id="groupTitleInput" class="variant-title-input form-control" value=""
                                        oninput="updateTableHeader()" placeholder="Tên phân loại">
                                    <label for="groupTitleInput">Tên phân loại</label>
                                </div>

                            </div>
                            <div class="row g-2" id="variantWrapper">
                                <div class="col-md-6">
                                    <div class="variant-item">
                                        <!-- <i class="bi bi-image text-danger me-2"></i> -->
                                        <input type="text" class="inner-input variant-input" placeholder="Nhập giá trị"
                                            oninput="updateTable(); checkNewField(this)">
                                        <span class="text-muted small ms-auto char-count">0/20</span>
                                        <i class="bi bi-trash ms-2 text-muted" onclick="removeField(this)"
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
<script>
    const MAX_IMAGES = 9;

    function triggerUpload(box) {
        if (box.querySelector('img')) return;
        box.querySelector('input').click();
    }

    function handleUpload(event, input) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const box = input.parentElement;
            box.onclick = null;

            box.innerHTML = `
      <img src="${e.target.result}">
      <div class="delete-btn" onclick="removeImage(event, this)">
        <span class="material-icons">close</span>
      </div>
    `;

            if (!document.querySelector('.cover-badge')) {
                const badge = document.createElement('div');
                badge.className = 'cover-badge';
                badge.innerText = 'Ảnh bìa';
                box.appendChild(badge);
            }

            addNewUploadBox();
        };
        reader.readAsDataURL(file);
    }

    function addNewUploadBox() {
        const container = document.getElementById('imageContainer');
        const boxes = container.querySelectorAll('.image-box');

        if (boxes.length >= MAX_IMAGES) return;

        const hasEmpty = [...boxes].some(b => !b.querySelector('img'));
        if (hasEmpty) return;

        const box = document.createElement('div');
        box.className = 'image-box';
        box.onclick = () => triggerUpload(box);

        box.innerHTML = `
    <input type="file" accept="image/*" hidden onchange="handleUpload(event, this)">
    <span>
      <span class="material-icons">add_photo_alternate</span><br>
      Thêm ảnh
    </span>
  `;

        container.appendChild(box);
    }

    function removeImage(event, btn) {
        event.stopPropagation();

        const box = btn.parentElement;
        const container = document.getElementById('imageContainer');
        const wasCover = box.querySelector('.cover-badge');

        box.remove();

        if (wasCover) {
            const nextImage = container.querySelector('.image-box img');
            if (nextImage) {
                const nextBox = nextImage.parentElement;
                const badge = document.createElement('div');
                badge.className = 'cover-badge';
                badge.innerText = 'Ảnh bìa';
                nextBox.appendChild(badge);
            }
        }

        addNewUploadBox();
    }

    function autoResize(textarea) {
        // 1. Reset về auto để tính toán lại chiều cao thực của nội dung
        textarea.style.height = 'auto';

        // 2. Lấy chiều cao nội dung thực tế
        let newHeight = textarea.scrollHeight;

        // 3. Giới hạn trong CSS là 300, 
        // Nếu nội dung nhỏ hơn 300 thì giãn theo nội dung, ngược lại giữ 300
        if (newHeight > 300) {
            textarea.style.height = '300px';
        } else {
            textarea.style.height = newHeight + 'px';
        }
    }

    // Xử lý phân loại sản phẩm
    function updateTableHeader() {
        document.getElementById('headerName').innerText = document.getElementById('groupTitleInput').value || "Phân loại";
    }

    function checkNewField(input) {
        input.parentElement.querySelector('.char-count').innerText = `${input.value.length}/20`;
        const wrapper = document.getElementById('variantWrapper');
        const inputs = wrapper.querySelectorAll('.variant-input');
        if (input === inputs[inputs.length - 1] && input.value.trim() !== "") {
            const newCol = document.createElement('div');
            newCol.className = 'col-md-6';
            newCol.innerHTML = `
                <div class="variant-item">
                    <input type="text" class="inner-input variant-input" placeholder="Nhập" oninput="updateTable(); checkNewField(this)">
                    <span class="text-muted small ms-auto char-count">0/20</span>
                    <i class="bi bi-trash ms-2 text-muted" onclick="removeField(this)" style="cursor:pointer"></i>
                </div>`;
            wrapper.appendChild(newCol);
        }
    }

    function removeField(icon) {
        const wrapper = document.getElementById('variantWrapper');
        if (wrapper.querySelectorAll('.col-md-6').length > 1) {
            icon.closest('.col-md-6').remove();
            updateTable();
        }
    }

    function updateTable() {
        const inputs = document.querySelectorAll('.variant-input');
        const tableBody = document.getElementById('variantTableBody');
        const tableSection = document.getElementById('tableSection');
        const values = Array.from(inputs).map(i => i.value.trim()).filter(v => v !== "");

        if (values.length === 0) {
            tableSection.style.display = 'none';
            return;
        }

        tableSection.style.display = 'flex';
        const oldData = {};
        tableBody.querySelectorAll('tr').forEach(tr => {
            const key = tr.getAttribute('data-key');
            oldData[key] = {
                price: tr.querySelector('.row-price').value,
                stock: tr.querySelector('.row-stock').value
            };
        });

        tableBody.innerHTML = '';
        values.forEach(val => {
            const row = document.createElement('tr');
            row.setAttribute('data-key', val);
            row.innerHTML = `
                <td class="text-center fw-medium">${val}</td>
                <td>
                    <div class="currency-input">
                        <span>₫</span>
                        <input type="number" class="row-price" placeholder="Nhập vào" value="${oldData[val]?.price || ''}">
                    </div>
                </td>
                <td>
                    <input type="number" class="input-table row-stock" placeholder="0" value="${oldData[val]?.stock || '0'}">
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    function applyToAll() {
        const bPrice = document.getElementById('batchPrice').value;
        const bStock = document.getElementById('batchStock').value;
        if (bPrice !== "") document.querySelectorAll('.row-price').forEach(i => i.value = bPrice);
        if (bStock !== "") document.querySelectorAll('.row-stock').forEach(i => i.value = bStock);
    }
</script>
@endpush