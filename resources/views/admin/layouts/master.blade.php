<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>@yield('title', 'CMS Dashboard')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

    <style>
        /* Sửa lỗi dropdown menu trong Sidebar khi dùng BS5 collapse */
        #sidebar ul.collapse.show {
            display: block;
        }

        /* Căn chỉnh lại dropdown toggle icon */
        .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.255em;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
            position: absolute;
            right: 10px;
            top: 20px;
        }

        /* Chỉnh lại padding cho nav-link để icon không bị lệch */
        .navbar-nav .nav-link {
            padding-right: 0;
            padding-left: 0;
            display: flex;
            align-items: center;
        }

        .navbar-nav .material-icons {
            margin-right: 5px;
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="wrapper">

        <div class="body-overlay"></div>

        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><img src="{{ asset('assets/img/logo.png') }}" class="img-fluid" /><span>Trần Quốc Thái</span></h3>
            </div>
            <ul class="list-unstyled components">
                <li class="active">
                    <a href="{{ route('admin.dashboard') }}" class="dashboard"><i class="material-icons">dashboard</i><span>Bản điều khiển</span></a>
                </li>

                <div class="small-screen navbar-display">
                    <li class="dropdown d-lg-none d-md-block d-xl-none d-sm-block">
                        <a href="#homeSubmenu0" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                            <i class="material-icons">notifications</i><span> 4 notification</span></a>
                        <ul class="collapse list-unstyled menu" id="homeSubmenu0">
                            <li><a href="#">You have 5 new messages</a></li>
                            <li><a href="#">You're now friend with Mike</a></li>
                            <li><a href="#">Wish Mary on her birthday!</a></li>
                            <li><a href="#">5 warnings in Server Console</a></li>
                        </ul>
                    </li>

                    <li class="d-lg-none d-md-block d-xl-none d-sm-block">
                        <a href="#"><i class="material-icons">person</i><span>user</span></a>
                    </li>

                    <li class="d-lg-none d-md-block d-xl-none d-sm-block">
                        <a href="#"><i class="material-icons">settings</i><span>setting</span></a>
                    </li>
                </div>

                <li class="dropdown">
                    <a href="#homeSubmenu1" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">person</i><span>Người dùng</span></a>
                    <ul class="collapse list-unstyled menu" id="homeSubmenu1">
                        <li><a href="{{ route('admin.users.index') }}">Tất cả người dùng</a></li>
                        <li><a href="{{ route('admin.users.create') }}">Thêm người dùng</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu2" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">layers</i><span>Sản Phẩm</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu2">
                        <li><a href="{{ route('admin.products.index') }}">Tất cả sản phẩm</a></li>
                        <li><a href="{{ route('admin.products.create') }}">Thêm sản phẩm</a></li>
                        <li><a href="#">Chương trình giảm giá</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu3" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">equalizer</i><span>Danh mục</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu3">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu4" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">extension</i><span>Bài viết</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu4">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu5" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">border_color</i><span>Đơn hàng</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu5">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu6" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">grid_on</i><span>Nhà cung cấp</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu6">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu7" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">content_copy</i><span>Phương thức giao hàng</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu7">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="#pageSubmenu8" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="material-icons">content_copy</i><span>Liên hệ và phản hồi</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu8">
                        <li><a href="#">Page 1</a></li>
                        <li><a href="#">Page 2</a></li>
                        <li><a href="#">Page 3</a></li>
                    </ul>
                </li>

            </ul>
        </nav>

        <div id="content">

            <div class="top-navbar">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">

                        <button type="button" id="sidebarCollapse" class="d-xl-block d-lg-block d-md-mone d-none">
                            <span class="material-icons">arrow_back_ios</span>
                        </button>

                        <a class="navbar-brand" href="#"> Bảng điều khiển </a>

                        <button class="d-inline-block d-lg-none ml-auto more-button" type="button"
                            data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="material-icons">more_vert</span>
                        </button>

                        <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none"
                            id="navbarSupportedContent">
                            <ul class="nav navbar-nav ms-auto">
                                <li class="dropdown nav-item active">
                                    <a href="#" class="nav-link" data-bs-toggle="dropdown">
                                        <span class="material-icons">notifications</span>
                                        <span class="notification">4</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="#" class="dropdown-item">You have 5 new messages</a></li>
                                        <li><a href="#" class="dropdown-item">You're now friend with Mike</a></li>
                                        <li><a href="#" class="dropdown-item">Wish Mary on her birthday!</a></li>
                                        <li><a href="#" class="dropdown-item">5 warnings in Server Console</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <span class="material-icons">person</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <span class="material-icons">settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="main-content">
                @yield('content')
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <nav class="d-flex">
                                    <ul class="m-0 p-0">
                                        <li><a href="#">Home</a></li>
                                        <li><a href="#">Company</a></li>
                                        <li><a href="#">Portfolio</a></li>
                                        <li><a href="#">Blog</a></li>
                                    </ul>
                                </nav>
                            </div>
                            <div class="col-md-6">
                                <p class="copyright d-flex justify-content-end"> &copy 2026 Design by
                                    <a href="#"> Trần Quốc Thái </a> BootStrap Admin Dashboard
                                </p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            // Lấy các phần tử cần thiết
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapse');
            const moreButtons = document.querySelectorAll('.more-button');
            const bodyOverlay = document.querySelector('.body-overlay');

            // Xử lý sự kiện click cho nút toggle sidebar (Desktop)
            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    content.classList.toggle('active');
                });
            }

            // Xử lý sự kiện click cho nút 3 chấm (Mobile)
            moreButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    sidebar.classList.toggle('show-nav');
                    bodyOverlay.classList.toggle('show-nav');
                });
            });

            // Xử lý sự kiện click vào overlay (để đóng menu mobile)
            if (bodyOverlay) {
                bodyOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('show-nav');
                    bodyOverlay.classList.remove('show-nav');
                });
            }
        });
    </script>

    @stack('scripts')

</body>

</html>