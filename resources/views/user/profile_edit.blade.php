@extends('user.layouts.default')

@section('title', 'Chỉnh sửa thông tin cá nhân')

@push('page_specific_css')

@endpush

@section('content')

<div id="wp-content" class="bg-body-tertiary" style="margin-top: 57px;">
    <div class="container mt-4 pb-3 pt-3">
        <div class="row">
            @include('user.layouts.profile')

            <div class="col-md-10">
                <div class="card border-0 shadow-sm p-4">
                    <div class="border-bottom pb-3 mb-4">
                        <h5 class="mb-1 fw-normal fs-5">Hồ Sơ Của Tôi</h5>
                        <p class="text-muted small mb-0">Quản lý thông tin hồ sơ để bảo mật tài khoản</p>
                    </div>
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <div class="row">
                            <div class="col-lg-8 border-end">

                                <div class="row mb-4 align-items-center">
                                    <label class="col-sm-3 text-end text-muted">Tên</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}">
                                    </div>
                                </div>

                                <div class="row mb-4 align-items-center">
                                    <label class="col-sm-3 text-end text-muted">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}">
                                    </div>
                                </div>

                                <div class="row mb-4 align-items-center">
                                    <label class="col-sm-3 text-end text-muted">Số điện thoại</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', Auth::user()->phone_number) }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: #097829cc; border: none;">Lưu</button>
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-4 d-flex flex-column align-items-center pt-4">
                                <div class="mb-3">
                                    {{-- Hiển thị ảnh hiện tại, nếu không có dùng ảnh mặc định --}}
                                    <img id="preview-avatar"
                                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://via.placeholder.com/100' }}"
                                        class="rounded-circle img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>

                                {{-- Input file ẩn (Đã có name="avatar" ở code cũ của bạn, chuẩn rồi) --}}
                                <input type="file" name="avatar" id="avatar-input" class="d-none" accept="image/jpeg, image/png, image/jpg">

                                <button type="button" class="btn btn-outline-secondary btn-sm mb-3 px-3"
                                    onclick="document.getElementById('avatar-input').click()">Chọn Ảnh</button>

                                <div class="text-muted small text-center">
                                    Dung lượng file tối đa 1 MB<br>Định dạng: .JPEG, .PNG
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_specific_js')
<script>
    // Preview ảnh khi chọn file
    document.getElementById('avatar-input').onchange = evt => {
        const [file] = document.getElementById('avatar-input').files
        if (file) {
            document.getElementById('preview-avatar').src = URL.createObjectURL(file)
        }
    }
</script>
@endpush