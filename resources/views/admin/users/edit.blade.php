@extends('admin.layouts.master')

@section('title', 'Chỉnh sửa người dùng')

@push('styles')
<style>
    /* Giữ nguyên CSS từ trang Create */
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

    .image-box {
        width: 120px;
        height: 120px;
        border-radius: 50%;
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
<form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">Thông tin cá nhân: {{ $user->name }}</h4>

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
                    <div class="input-group-text">Ảnh đại diện</div>
                    <div class="form-floating flex-grow-1">
                        <div class="image-box" id="avatarBox" onclick="triggerUpload()">
                            <input type="file" name="avatar" accept="image/*" hidden onchange="handleUpload(event)">

                            {{-- Hiển thị ảnh cũ nếu có --}}
                            @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" id="currentImg">
                            <div class="delete-btn" onclick="removeImage(event)">
                                <span class="material-icons">close</span>
                            </div>
                            <div id="uploadPlaceholder" style="display: none;">
                                <span class="material-icons" style="font-size: 40px; color: #999;">account_circle</span><br>
                                <span style="font-size: 12px;">Tải ảnh</span>
                            </div>
                            @else
                            <div id="uploadPlaceholder">
                                <span class="material-icons" style="font-size: 40px; color: #999;">account_circle</span><br>
                                <span style="font-size: 12px;">Tải ảnh</span>
                            </div>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">Để trống nếu không muốn thay đổi ảnh.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="input-group-text"><span class="required-star">*</span>Họ và tên</div>
                    <div class="form-floating flex-grow-1">
                        <input name="name" type="text" class="form-control" id="userName" value="{{ old('name', $user->name) }}" required>
                        <label for="userName">Tên hiển thị trên hệ thống</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text"><span class="required-star">*</span>Email</div>
                            <div class="form-floating flex-grow-1">
                                <input name="email" type="email" class="form-control" id="userEmail" value="{{ old('email', $user->email) }}" required>
                                <label for="userEmail">Địa chỉ email</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">Số điện thoại</div>
                            <div class="form-floating flex-grow-1">
                                <input name="phone_number" type="text" class="form-control" id="userPhone" value="{{ old('phone_number', $user->phone_number) }}">
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
                            <div class="input-group-text">Mật khẩu mới</div>
                            <div class="form-floating flex-grow-1">
                                <input name="password" type="password" class="form-control" id="userPass" placeholder="Password">
                                <label for="userPass">Để trống nếu không đổi mật khẩu</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text"><span class="required-star">*</span>Vai trò</div>
                            <div class="form-floating flex-grow-1">
                                <select class="form-select material-select" id="roleSelect" name="role" required>
                                    <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Khách hàng (Customer)</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
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
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $user->is_active ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="isActive">Tài khoản đang hoạt động</label>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-link text-muted me-3">Hủy bỏ</a>
                        <button type="submit" class="btn btn-save shadow-sm">
                            <i class="material-icons" style="vertical-align: middle; font-size: 18px;">save</i> Cập nhật thông tin
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

            // Xóa ảnh hiện tại (nếu có)
            const oldImgs = box.querySelectorAll('img');
            oldImgs.forEach(img => img.remove());

            placeholder.style.display = 'none';

            const img = document.createElement('img');
            img.src = e.target.result;
            box.appendChild(img);

            if (!box.querySelector('.delete-btn')) {
                const deleteBtn = document.createElement('div');
                deleteBtn.className = 'delete-btn';
                deleteBtn.innerHTML = '<span class="material-icons">close</span>';
                deleteBtn.onclick = (e) => removeImage(e);
                box.appendChild(deleteBtn);
            }
        };
        reader.readAsDataURL(file);
    }

    function removeImage(e) {
        e.stopPropagation();
        const box = document.getElementById('avatarBox');
        const placeholder = document.getElementById('uploadPlaceholder');

        const imgs = box.querySelectorAll('img');
        imgs.forEach(img => img.remove());

        placeholder.style.display = 'block';
        const deleteBtn = box.querySelector('.delete-btn');
        if (deleteBtn) deleteBtn.remove();

        document.querySelector('input[name="avatar"]').value = "";
    }
</script>
@endpush