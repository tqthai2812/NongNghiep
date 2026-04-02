@extends('admin.layouts.master')

@section('title', 'Tạo người dùng mới')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/users/create.css') }}">
@endpush

@section('content')
<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">Thông tin cá nhân</h4>
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
                        @error('avatar')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                        @enderror
                    </div>

                </div>

                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>Họ và tên
                    </div>
                    <div class="form-floating flex-grow-1">
                        <input name="name" type="text" class="form-control" id="userName" placeholder="Nhập họ tên" required value="{{ old('name') }}">
                        <label for="userName">Tên hiển thị trên hệ thống</label>
                        @error('name')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                        @enderror
                    </div>

                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-row">
                            <div class="input-group-text">
                                <span class="required-star">*</span>Email
                            </div>
                            <div class="form-floating flex-grow-1">
                                <input name="email" type="email" class="form-control" id="userEmail" placeholder="name@example.com" required value="{{ old('email') }}">
                                <label for="userEmail">Địa chỉ email (Dùng để đăng nhập)</label>
                                @error('email')
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
                                Số điện thoại
                            </div>
                            <div class="form-floating flex-grow-1">
                                <input name="phone_number" type="text" class="form-control" id="userPhone" placeholder="090..." value="{{ old('phone_number') }}">
                                <label for="userPhone">Số điện thoại liên lạc</label>
                                @error('phone_number')
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
                                @error('password')
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
                                <span class="required-star">*</span>Vai trò
                            </div>
                            <div class="form-floating flex-grow-1">
                                <select class="form-select material-select" id="roleSelect" name="role" required>
                                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Khách hàng (Customer)</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Quản trị viên (Admin)</option>
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
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ old('is_active') ? 'checked' : '' }} value="1">
                            <label class="form-check-label" for="isActive">Kích hoạt tài khoản ngay</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.users.index') }}"
                            class="btn btn-secondary me-2 shadow-sm p-2">
                            Huỷ
                        </a>
                        <button type="submit"
                            class="btn btn-save shadow-sm">
                            Thêm người dùng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/users/create.js') }}"></script>
@endpush