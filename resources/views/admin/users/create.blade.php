@extends('admin.layouts.master')

@section('title', 'Tạo người dùng mới')

@push('styles')
<style>
    /* Giữ nguyên các style bạn đã định nghĩa ở trang sản phẩm để đảm bảo đồng nhất */
    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
    }

    :root {
        --google-blue: #1a73e8;
        --border-color: #dadce0;
    }

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

    /* Tùy chỉnh Floating Label */
    .form-floating>label {
        color: #757575;
        padding-left: 12px;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #1a73e8;
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

    /* Style riêng cho Avatar User (Hình tròn thay vì hình vuông sản phẩm) */
    .image-box {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        /* Tròn */
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

    .image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

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
        opacity: 0;
        transition: .2s;
        cursor: pointer;
        z-index: 10;
    }

    .image-box:hover .delete-btn {
        opacity: 1;
    }

    .material-select {
        border-radius: 5 !important;
        padding-left: 12px !important;
        transition: border-bottom 0.2s;
    }
</style>
@endpush

@section('content')
<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">Thông tin cá nhân</h4>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="form-row">
                    <div class="input-group-text">
                        Ảnh đại diện
                    </div>
                    <div class="form-floating flex-grow-1">
                        <div class="image-box" id="avatarBox" onclick="triggerUpload()">
                            <input type="file" name="avatar" accept="image/*" hidden onchange="handleUpload(event)">
                            <div id="uploadPlaceholder">
                                <span class="material-icons" style="font-size: 40px; color: #999;">account_circle</span><br>
                                <span style="font-size: 12px;">Tải ảnh</span>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Định dạng: JPG, PNG. Tối đa 2MB.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Họ và tên
                    </div>
                    <div class="form-floating flex-grow-1">
                        <input name="name" type="text" class="form-control" id="userName" placeholder="Nhập họ tên" required>
                        <label for="userName">Tên hiển thị trên hệ thống</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">
                                <span class="required-star">*</span>Email
                            </div>
                            <div class="form-floating flex-grow-1">
                                <input name="email" type="email" class="form-control" id="userEmail" placeholder="name@example.com" required>
                                <label for="userEmail">Địa chỉ email (Dùng để đăng nhập)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">
                                Số điện thoại
                            </div>
                            <div class="form-floating flex-grow-1">
                                <input name="phone_number" type="text" class="form-control" id="userPhone" placeholder="090...">
                                <label for="userPhone">Số điện thoại liên lạc</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card p-4">
                <h4 class="card-title mb-4">Tài khoản & Phân quyền</h4>

                <div class="row">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">
                                <span class="required-star">*</span>Mật khẩu
                            </div>
                            <div class="form-floating flex-grow-1">
                                <input name="password" type="password" class="form-control" id="userPass" placeholder="Password" required>
                                <label for="userPass">Mật khẩu (Ít nhất 6 ký tự)</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">
                                <span class="required-star">*</span>Vai trò
                            </div>
                            <div class="form-floating flex-grow-1">
                                <select class="form-select material-select" id="roleSelect" name="role" required>
                                    <option value="customer" selected>Khách hàng (Customer)</option>
                                    <option value="admin">Quản trị viên (Admin)</option>
                                </select>
                                <label for="roleSelect">Cấp quyền truy cập</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group-text">Trạng thái</div>
                    <div class="flex-grow-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" checked value="1">
                            <label class="form-check-label" for="isActive">Kích hoạt tài khoản ngay</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-link text-muted me-3">Hủy bỏ</a>
                        <button type="submit" class="btn btn-save shadow-sm">
                            <i class="material-icons style=" vertical-align: middle; font-size: 18px;">person_add</i> Lưu người dùng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    function triggerUpload() {
        document.querySelector('input[name="avatar"]').click();
    }

    function handleUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const box = document.getElementById('avatarBox');
            const placeholder = document.getElementById('uploadPlaceholder');

            // Xóa ảnh cũ nếu có
            const oldImg = box.querySelector('img');
            if (oldImg) oldImg.remove();

            // Ẩn placeholder
            placeholder.style.display = 'none';

            // Thêm ảnh mới
            const img = document.createElement('img');
            img.src = e.target.result;
            box.appendChild(img);

            // Thêm nút xóa nếu chưa có
            if (!box.querySelector('.delete-btn')) {
                const deleteBtn = document.createElement('div');
                deleteBtn.className = 'delete-btn';
                deleteBtn.innerHTML = '<span class="material-icons">close</span>';
                deleteBtn.onclick = (e) => {
                    e.stopPropagation();
                    img.remove();
                    placeholder.style.display = 'block';
                    deleteBtn.remove();
                    document.querySelector('input[name="avatar"]').value = "";
                };
                box.appendChild(deleteBtn);
            }
        };
        reader.readAsDataURL(file);
    }
</script>
@endpush