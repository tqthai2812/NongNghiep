<style>
    .user-sidebar a {
        text-decoration: none;
    }

    .user-sidebar-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .user-sidebar-name {
        font-size: 14px;
    }

    .user-sidebar-edit {
        cursor: pointer;
    }

    .user-sidebar-link {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        color: #333;
        font-weight: 500;
    }

    .user-sidebar-link:hover {
        color: #ee4d2d;
    }

    .user-sidebar-item {
        display: block;
        padding: 6px 0;
        color: #555;
        padding-left: 10px;
    }

    .user-sidebar-item:hover {
        color: #ee4d2d;
    }

    .user-sidebar-item.active {
        color: #ee4d2d;
        font-weight: 500;
    }

    .user-sidebar-submenu {
        padding-left: 20px;
    }

    /* xoay icon */
    .user-sidebar-arrow {
        transition: 0.3s;
    }

    .user-sidebar-link[aria-expanded="true"] .user-sidebar-arrow {
        transform: rotate(180deg);
    }
</style>


@php
$isAccountPage = request()->routeIs(
'user.profile',
'user.address',
'user.password',
'user.info'
);
@endphp


<div class="col-md-2">
    <div class="user-sidebar">

        <!-- profile -->
        <div class="user-sidebar-profile d-flex align-items-center mb-4">
            <img class="user-sidebar-avatar"
                src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://i.pravatar.cc/100' }}">

            <div class="ms-3">
                <div class="user-sidebar-name fw-bold">
                    {{ Auth::user()->name ?? 'Khách hàng' }}
                </div>

                <small class="user-sidebar-edit text-muted">
                    <i class="fa-solid fa-user-pen"></i> Sửa Hồ Sơ
                </small>
            </div>
        </div>


        <!-- menu -->
        <a class="user-sidebar-link"
            data-bs-toggle="collapse"
            href="#userAccountMenu"
            aria-expanded="{{ $isAccountPage ? 'true' : 'false' }}">

            <span>
                <i class="fa-solid fa-user me-2"></i>
                Tài Khoản Của Tôi
            </span>

            <i class="fa-solid fa-chevron-down user-sidebar-arrow"></i>
        </a>


        <!-- submenu -->
        <div class="collapse {{ $isAccountPage ? 'show' : '' }} user-sidebar-submenu" id="userAccountMenu">

            <a href="{{ route('user.profile') }}"
                class="user-sidebar-item {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                Hồ Sơ
            </a>

            <a href="{{ route('user.address') }}"
                class="user-sidebar-item {{ request()->routeIs('user.address') ? 'active' : '' }}">
                Địa Chỉ
            </a>

            <a href="#"
                class="user-sidebar-item {{ request()->routeIs('user.password') ? 'active' : '' }}">
                Đổi Mật Khẩu
            </a>

        </div>


        <!-- orders -->
        <a href="{{ route('user.order_history') }}"
            class="user-sidebar-link {{ request()->routeIs('user.order_history') ? 'active' : '' }}">

            <span>
                <i class="fa-solid fa-clipboard-list me-2"></i>
                Đơn Mua
            </span>
        </a>

    </div>
</div>