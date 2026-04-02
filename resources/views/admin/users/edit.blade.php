@extends('admin.layouts.master')

@section('title', 'Chỉnh sửa người dùng')

@push('styles')

<link rel="stylesheet" href="{{ asset('assets/css/admin/users/edit.css') }}">

@endpush

@section('content')
<form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">Thông tin cá nhân: {{ $user->name }}</h4>

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
                        @error('avatar')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                        @enderror
                    </div>

                </div>

                <div class="form-row">
                    <div class="input-group-text"><span class="required-star">*</span>Họ và tên</div>
                    <div class="form-floating flex-grow-1">
                        <input name="name" type="text" class="form-control" id="userName" value="{{ old('name', $user->name) }}" required>
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
                            <div class="input-group-text"><span class="required-star">*</span>Email</div>
                            <div class="form-floating flex-grow-1">
                                <input name="email" type="email" class="form-control" id="userEmail" value="{{ old('email', $user->email) }}" required>
                                <label for="userEmail">Địa chỉ email</label>
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
                            <div class="input-group-text">Số điện thoại</div>
                            <div class="form-floating flex-grow-1">
                                <input name="phone_number" type="text" class="form-control" id="userPhone" value="{{ old('phone_number', $user->phone_number) }}">
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
                            <div class="input-group-text">Mật khẩu mới</div>
                            <div class="form-floating flex-grow-1">
                                <input name="password" type="password" class="form-control" id="userPass" placeholder="Password">
                                <label for="userPass">Để trống nếu không đổi mật khẩu</label>
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

                {{-- BUTTON --}}
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.users.index') }}"
                            class="btn btn-secondary me-2 shadow-sm p-2">
                            Huỷ
                        </a>
                        <button type="submit"
                            class="btn btn-save shadow-sm">
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/users/edit.js') }}"></script>
@endpush