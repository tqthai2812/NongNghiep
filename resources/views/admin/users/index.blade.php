@extends('admin.layouts.master')

@section('title', 'Danh sách người dùng')

@section('page_specific_css')
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.css" rel="stylesheet">
<style>
    .table-actions .material-icons {
        font-size: 20px;
        cursor: pointer;
    }

    /* DataTable Controls */
    .dt-search,
    .dt-length {
        display: none;
    }

    .datatable-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .badge-role {
        font-weight: 500;
        padding: 5px 10px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        {{-- Thông báo Lỗi (Ví dụ: Tự xóa chính mình) --}}
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mt-3" role="alert" style="background-color: #fdeaea; color: #d93025; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="material-icons me-2">error_outline</i>
                <span class="fw-bold">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Thông báo Thành công --}}
        @if(session('success'))
        <div class=" alert alert-success alert-dismissible fade show border-0 shadow-sm mt-3" role="alert" style="background-color: #e6f4ea; color: #1e8e3e; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="material-icons me-2">check_circle_outline</i>
                <span class="fw-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="card card-plain">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mt-0 text-dark fw-bold">Quản Lý Người Dùng Hệ Thống</h4>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success d-flex align-items-center">
                    <i class="material-icons me-1">add</i>
                    Thêm người dùng
                </a>
            </div>

            <div class="card-body">
                <div class="row g-2 mb-3 filter-bar">
                    <div class="col-md-3">
                        <input type="text" id="userSearch" class="form-control" placeholder="Tìm tên, email, số điện thoại...">
                    </div>
                    <div class="col-md-2">
                        <select id="filterRole" class="form-select">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin">Quản trị viên (Admin)</option>
                            <option value="customer">Khách hàng (Customer)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="filterStatus" class="form-select">
                            <option value="">Trạng thái</option>
                            <option value="Hoạt động">Đang hoạt động</option>
                            <option value="Khóa">Bị khóa</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="changeLength" class="form-select">
                            <option value="10" selected>10 dòng/trang</option>
                            <option value="25">25 dòng/trang</option>
                            <option value="50">50 dòng/trang</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="userTable" class="table table-hover align-middle">
                        <thead class="text-secondary bg-light">
                            <tr>
                                <th>Người dùng</th>
                                <th>Liên hệ</th>
                                <th class="text-center">Vai trò</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Ngày tạo</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3" style="width: 90px; height: 90px; border-radius: 50%; overflow: hidden; object-fit: cover;">
                                            <img src="{{ asset('storage/' . $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name)) }}" class="me-3 img-cover" alt="{{ $user->name }}">
                                        </div>
                                        <div>
                                            <div class=" fw-bold">{{ $user->name }}
                                            </div>
                                            <div class="text-muted small">ID: #{{ $user->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small"><i class="material-icons info-icon" style="font-size: 14px;">email</i> {{ $user->email }}</div>
                                    <div class="small text-muted"><i class="material-icons info-icon" style="font-size: 14px;">phone</i> {{ $user->phone_number ?? 'Chưa cập nhật' }}</div>
                                </td>
                                <td class="text-center">
                                    @if($user->role == 'admin')
                                    <span class="badge bg-light text-primary border border-primary badge-role">Admin</span>
                                    @else
                                    <span class="badge bg-light text-dark border badge-role">Customer</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->is_active)
                                    <span class="text-success d-flex align-items-center justify-content-center">
                                        <i class="material-icons small me-1" style="font-size: 18px;">check_circle</i> Hoạt động
                                    </span>
                                    @else
                                    <span class="text-danger d-flex align-items-center justify-content-center">
                                        <i class="material-icons small me-1" style="font-size: 18px;">block</i> Khóa
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center text-muted small">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="table-actions text-end">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-primary me-2"><i class="material-icons">edit</i></a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                        method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger" style="background: none; border: none; padding: 0;">
                                            <i class="material-icons">delete_outline</i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="pagination-container" class="datatable-footer"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        // Tự động ẩn các thông báo sau 4 giây để giao diện gọn gàng
        setTimeout(function() {
            $(".alert").fadeOut(800, function() {
                $(this).remove();
            });
        }, 4000);
    });

    $(document).ready(function() {
        // Khởi tạo DataTable đồng nhất cấu hình
        var table = $('#userTable').DataTable({
            "dom": '<"top"rt><"datatable-footer"ip><"clear">',
            "pageLength": 10,
            "ordering": true, // User có thể bật ordering
            "order": [
                [0, 'desc']
            ], // Mặc định user mới nhất lên đầu
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json"
            }
        });

        // Xử lý tìm kiếm Custom
        $('#userSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Lọc theo Vai trò
        $('#filterRole').on('change', function() {
            table.column(2).search(this.value).draw();
        });

        // Lọc theo Trạng thái
        $('#filterStatus').on('change', function() {
            table.column(3).search(this.value).draw();
        });

        // Thay đổi độ dài trang
        $('#changeLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        // Di chuyển pagination
        $('.datatable-footer').appendTo('#pagination-container');
    });
</script>
@endpush