<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF Token cho các yêu cầu AJAX nếu cần --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'TQTshop')</title>

    {{-- CSS Libraries --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <script src="https://kit.fontawesome.com/950843fd38.js" crossorigin="anonymous"></script>

    {{-- Custom CSS --}}
    <link href="{{ asset('assets/css/user/layouts.css') }}?v={{ time() }}" rel="stylesheet">

    @stack('page_specific_css')
</head>

<body>
    <header id="header" class="py-2" style="position: fixed; top: 0; right: 0; left: 0; z-index: 1000; background: rgba(9, 120, 41, 0.8); height: 57px;">
        <div id="header-main" class="row mx-auto" style="width: 90%;">
            <div id="logo" class="col-2 d-flex justify-content-center align-items-center">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/img/logoheader.png') }}" alt="Logo" class="object-fit-cover" style="width: 170px;">
                </a>
            </div>

            <div id="search" class="col-6 d-flex justify-content-between row">
                <div class="ms-4 col-3 border border-0 text-white rounded-pill bg-dark bg-opacity-50 dropdown d-flex align-items-center" style="width: 142px;">
                    <div class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" style="cursor: pointer;">
                        <i class="fa-solid fa-indent me-3"></i>
                        <p class="m-0">Danh Mục</p>
                    </div>
                    <ul class="dropdown-menu" style="min-width: 200px;">
                        {{-- Laravel Handle Categories --}}
                        @if(isset($categories) && $categories->count() > 0)
                        @foreach($categories as $category)
                        <li>
                            <a href="#" class="dropdown-item py-2">
                                {{ $category->name }}
                            </a>
                        </li>
                        @endforeach
                        @else
                        <li><a class="dropdown-item py-2" href="#">Không có danh mục</a></li>
                        @endif
                    </ul>
                </div>

                <form id="search-form" class="border rounded-pill col-9 row bg-body" action="{{-- route('search') --}}" method="GET">
                    <input id="input_search" class="rounded-pill p-2 col-11 border-0" type="text" name="search" placeholder="Nhập sản phẩm cần tìm" value="{{ request('search') }}" style="outline: none;">
                    <button class="col-1 m-0 border-0 bg-transparent fa-solid fa-magnifying-glass" type="submit"></button>
                </form>
            </div>

            <div id="icon-shop" class="col-4 d-flex justify-content-between align-items-center ps-4">
                <a href="{{ route('cart.index') }}"
                    class="text-decoration-none icon-shop-index d-flex border-0 text-white rounded-pill bg-dark justify-content-center align-items-center"
                    style="width: 134px; height: 40px;">
                    <i class="fa-solid fa-cart-shopping me-2"></i>
                    <p class="m-0">Giỏ Hàng</p>
                </a>
                @guest
                <div class="text-white me-3">
                    <a href="{{ route('login') }}" class="text-white text-decoration-none">Đăng nhập</a>
                    <span class="mx-1">|</span>
                    <a href="{{ route('register') }}" class="text-white text-decoration-none">Đăng ký</a>
                </div>
                @else
                <div class="nav-item dropstart d-flex align-items-center">
                    <a class="nav-link dropdown-toggle d-flex align-items-center text-white"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown">

                        {{-- Avatar --}}
                        <img src="{{asset('storage/' . Auth::user()->avatar ?? 'avatars/default.png')}}"
                            alt="avatar"
                            width="35"
                            height="35"
                            class="rounded-circle me-2 object-fit-cover">

                        {{-- Tên --}}
                        <span>{{ Auth::user()->name }}</span>
                    </a>

                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{-- url('/user/info') --}}">Thông tin</a>
                        <a class="dropdown-item" href="{{ route('user.order_history') }}">Lịch sử đơn hàng</a>
                        <a class="dropdown-item"
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Đăng xuất
                        </a>
                        <form id="logout-form"
                            action="{{ route('logout') }}"
                            method="POST"
                            class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
                @endguest

            </div>
        </div>
    </header>

    {{-- Content --}}
    @yield('content')

    <footer class="text-light pt-5" style="background-color: #1b8a3a;">
        <div class="container">
            <div class="row">
                <div class="d-flex col-12 justify-content-between">
                    <div>
                        <h6 class="text-light">Hệ thống TQT Shop trên toàn quốc</h6>
                        <p style="color: #ded9d9;">Bao gồm Cửa hàng TQT Shop, Trung tâm Cây giống, T.Studio, S.Studio, TQT Brand Store</p>
                    </div>
                    <div><a href="#" class="border border-0 rounded-pill text-decoration-none py-2 px-3" style="color: #097829; background-color: #fff;">Xem danh sách cửa hàng</a></div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col">
                    <h6 class="mb-3">KẾT NỐI VỚI TQT SHOP</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="text-light"><img src="{{ asset('assets/img/imgfooter/facebook_icon_8543190720.svg') }}" alt=""></a>
                        <a href="#" class="text-light"><img src="{{ asset('assets/img/imgfooter/zalo_icon_8cbef61812.svg') }}" alt=""></a>
                        <a href="#" class="text-light"><img src="{{ asset('assets/img/imgfooter/tiktok_icon_faabbeeb61.svg') }}" alt=""></a>
                        <a href="#" class="text-light"><img src="{{ asset('assets/img/imgfooter/youtube_icon_b492d61ba5.svg') }}" alt=""></a>
                    </div>
                    <h5 class="mt-4">Tổng đài miễn phí</h5>
                    <p>
                    <div>Tư vấn mua hàng (Miễn phí)</div>
                    <div><strong>1800.6601</strong> (Nhánh 1)</div>
                    </p>
                    <p>
                    <div>Góp ý, khiếu nại</div>
                    <div><strong>1800.6616</strong> (8h00 - 22h00)</div>
                    </p>
                    <p>
                    <div>Hỗ trợ kỹ thuật</div>
                    <div><strong>Gặp chuyên gia ngay!</strong></div>
                    </p>
                </div>
                <div class="col">
                    <h6 class="mb-3">VỀ CHÚNG TÔI</h6>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Giới thiệu công ty</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Quy chế hoạt động</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Dự án Doanh nghiệp</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Tin tức khuyến mãi</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Giới thiệu cây giống đổi trả</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Hướng dẫn mua hàng & thanh toán
                            online</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Đại lý uỷ quyền và TTBH uỷ quyền
                            của Apple</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Tra cứu hoá đơn điện tử</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Tra cứu bảo hành</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Câu hỏi thường gặp</a></p>
                </div>
                <div class="col">
                    <h6 class="mb-3">CHÍNH SÁCH</h6>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Chính sách bảo hành</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Chính sách đổi trả</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Chính sách bảo mật</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Chính sách trả góp</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Chính sách khui hộp sản phẩm</a>
                    </p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Chính sách giao hàng & lắp
                            đặt</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Chính sách mạng lưới TQT</a>
                    </p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Chính sách thu thập & xử lý dữ
                            liệu cá nhân</a></p>
                    <p class="mb-2"><a class="text-decoration-none text-light" href="#">Quy định về hỗ trợ kỹ thuật &
                            sao lưu dữ liệu</a></p>
                </div>
                <div class="col-md-2">
                    <h6 class="mb-3">HỖ TRỢ THANH TOÁN</h6>
                    <div class="row g-1 mb-2">
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/visa1.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/mastercard2.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/jcb3.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/amex4.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/vnpay5.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/zalopay6.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/napas7.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/kredivo8.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/momo9.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/foxpay10.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/alepay11.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/muadee12.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/homepaylater13.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/applepay14.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/samsungpay15.svg') }}" alt=""></div>
                        <div class="col-md-3"><img class="w-100" src="{{ asset('assets/img/imgfooter/googlepay16.svg') }}" alt=""></div>
                    </div>
                    <h6 class="mb-3 mt-4">Chứng nhận</h6>
                    <div class="row g-1">
                        <div class="col-md-4"><img class="w-100" src="{{ asset('assets/img/imgfooter/dmca17.svg') }}" alt=""></div>
                        <div class="col-md-4"><img class="w-100" src="{{ asset('assets/img/imgfooter/img18.svg') }}" alt=""></div>
                        <div class="col-md-4"><img class="w-100" src="{{ asset('assets/img/imgfooter/imgft19.svg') }}" alt=""></div>
                    </div>
                </div>
            </div>
            <div class="text-center py-3 border-top mt-3">
                <p>&copy; 2025 Công Ty Cổ Phần Bán Vật Tư Nông Nghiệp. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    @stack('page_specific_js')
</body>

</html>