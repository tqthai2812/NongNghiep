@extends('user.layouts.default')

@section('title', 'Login')
@push('page_specific_css')

@endpush

@section('content')
<div id="wp-content" class="container py-4" style="margin-top: 57px;">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đăng nhập</li>
        </ol>
    </nav>

    <div class="row border rounded-5 p-3 bg-white shadow box-area">
        <div class="col">

            @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="row">
                <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #097829cc;">
                    <div class="featured-image mb-3">
                        <img src="{{ asset('assets/img/auth/image.png') }}" class="img-fluid" style="width: 250px;">
                    </div>
                    <div class="d-flex justify-content-center align-items-center flex-column left-text">
                        <p class="text-white fs-2" style="font-weight: 600;">Đăng Nhập</p>
                        <small class="text-white text-wrap text-center" style="width: 17rem;">Đăng nhập để trải nghiệm tốt hơn.</small>
                    </div>
                </div>

                <div class="col-md-6 right-box">
                    <div class="row align-items-center p-3">
                        <div class="header-text mb-4">
                            <h2>Xin Chào</h2>
                            <p>Chúng tôi rất vui khi có bạn trở lại.</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <input id="email" type="email"
                                    class="form-control form-control-lg bg-light fs-6 @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}"
                                    required autofocus placeholder="Email">
                                @error('email')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-1">
                                <input id="password" type="password"
                                    class="form-control form-control-lg bg-light fs-6 @error('password') is-invalid @enderror"
                                    name="password" required
                                    placeholder="Mật khẩu" autocomplete="current-password">
                                @error('password')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-3 d-flex justify-content-between">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label for="remember_me" class="form-check-label text-secondary">
                                        <small>Ghi nhớ tôi</small>
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                <div class="forgot">
                                    <small><a href="{{ route('password.request') }}">Quên mật khẩu?</a></small>
                                </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-lg w-100 fs-6 text-white" style="background: #097829cc;">
                                    Đăng nhập
                                </button>
                            </div>

                            <div class="row">
                                <small>Bạn chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_specific_js')
@endpush