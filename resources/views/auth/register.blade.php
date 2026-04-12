@extends('user.layouts.default')

@section('title', 'Login')
@push('page_specific_css')

@endpush

@section('content')
<div class="container py-4" style="margin-top: 57px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đăng Ký</li>
        </ol>
    </nav>

    <div class="row border rounded-5 p-3 bg-white shadow box-area">
        <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #097829cc;">
            <div class="featured-image mb-3">
                <img src="{{ asset('assets/img/auth/image.png') }}" class="img-fluid" style="width: 250px;">
            </div>
            <div class="d-flex justify-content-center align-items-center flex-column left-text">
                <p class="text-white fs-2" style="font-weight: 600;">Tạo Tài Khoản</p>
                <small class="text-white text-wrap text-center" style="width: 17rem;">Đăng nhập để trải nghiệm tốt hơn.</small>
            </div>
        </div>

        <div class="col-md-6 right-box">
            <div class="row align-items-center p-0">
                <div class="header-text mb-4">
                    <h2>Tạo tài khoản</h2>
                    <p>Chúng tôi rất vui khi có bạn tham gia.</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <input id="name" type="text"
                            class="form-control form-control-lg bg-light fs-6 @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}"
                            required autofocus placeholder="Họ và tên">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input id="email" type="email"
                            class="form-control form-control-lg bg-light fs-6 @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}"
                            required placeholder="Email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input id="password" type="password"
                            class="form-control form-control-lg bg-light fs-6 @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password"
                            placeholder="Mật khẩu">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input id="password_confirmation" type="password"
                            class="form-control form-control-lg bg-light fs-6"
                            name="password_confirmation" required
                            placeholder="Nhập lại mật khẩu">
                    </div>

                    <div class="input-group mb-3 d-flex justify-content-between">
                        <div class="form-check">
                        </div>
                        <div class="forgot">
                            <small><a href="#">Điều khoản & Điều kiện</a></small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-lg w-100 fs-6 text-white" style="background: #097829cc;">
                            Đăng ký
                        </button>
                    </div>

                    <div class="row">
                        <small class="text-center">
                            Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập ngay</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('page_specific_js')
@endpush