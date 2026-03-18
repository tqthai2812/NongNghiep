@extends('admin.layouts.master')

@section('title', 'Danh sách đơn hàng')

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

    /* Custom Select Trạng thái */
    .status-select {
        font-weight: 500;
        border-radius: 20px;
        padding: 4px 30px 4px 12px;
        font-size: 0.875rem;
        cursor: pointer;
        box-shadow: none;
    }

    .status-select:focus {
        box-shadow: none;
    }

    /* Màu sắc trạng thái */
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
        border-color: #ffeeba;
    }

    .status-shipping {
        background-color: #cce5ff;
        color: #004085;
        border-color: #b8daff;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        {{-- Thông báo Lỗi --}}
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
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mt-3" role="alert" style="background-color: #e6f4ea; color: #1e8e3e; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="material-icons me-2">check_circle_outline</i>
                <span class="fw-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="card card-plain">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mt-0 text-dark fw-bold">Quản Lý Đơn Hàng</h4>
                {{-- Nút xuất báo cáo nếu cần --}}
                <button type="button" class="btn btn-outline-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#revenueReportModal">
                    <i class="material-icons me-1">download</i>
                    Báo cáo Doanh thu
                </button>
            </div>

            <div class="card-body">
                <div class="row g-2 mb-3 filter-bar">
                    <div class="col-md-4">
                        <input type="text" id="orderSearch" class="form-control" placeholder="Tìm mã ĐH, tên KH, số điện thoại...">
                    </div>
                    <div class="col-md-3">
                        <select id="filterStatus" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending">Chờ xử lý (Pending)</option>
                            <option value="shipping">Đang giao (Shipping)</option>
                            <option value="completed">Đã hoàn thành (Completed)</option>
                            <option value="cancelled">Đã hủy (Cancelled)</option>
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
                    <table id="orderTable" class="table table-hover align-middle">
                        <thead class="text-secondary bg-light">
                            <tr>
                                <th class="text-center">Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Ngày đặt</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td class="text-center fw-bold text-primary">
                                    #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>
                                    {{-- Giả sử Order có quan hệ user() --}}
                                    <div class="fw-bold">{{ $order->user->name ?? 'Khách vãng lai' }}</div>
                                    <div class="small text-muted"><i class="material-icons info-icon" style="font-size: 14px; vertical-align: middle;">email</i> {{ $order->user->email ?? '' }}</div>
                                </td>
                                <td class="fw-bold text-danger">
                                    {{ number_format($order->total_price, 0, ',', '.') }} VNĐ
                                </td>
                                <td class="text-center" data-search="{{ $order->status }}">
                                    {{-- Form đổi trạng thái trực tiếp --}}
                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="m-0 p-0">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status"
                                            class="form-select status-select status-{{ $order->status }}"
                                            onchange="updateStatusColor(this); this.form.submit();">
                                            <option value="pending" class="bg-white text-dark" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                            <option value="shipping" class="bg-white text-dark" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                            <option value="completed" class="bg-white text-dark" {{ $order->status == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                                            <option value="cancelled" class="bg-white text-dark" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center text-muted small">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="table-actions text-end">
                                    {{-- Nút xem chi tiết đơn hàng --}}
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-info me-2" title="Xem chi tiết">
                                        <i class="material-icons">visibility</i>
                                    </a>

                                    {{-- Nút xóa đơn hàng (Chỉ nên cho phép xóa nếu đã hủy) --}}
                                    <form action="{{ route('admin.orders.destroy', $order->id) }}"
                                        method="POST"
                                        style="display: inline-block;"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này? Dữ liệu sẽ không thể khôi phục!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger" style="background: none; border: none; padding: 0;" {{ $order->status != 'cancelled' ? 'disabled' : '' }} title="Chỉ xóa được khi đơn đã hủy">
                                            <i class="material-icons" style="opacity: {{ $order->status != 'cancelled' ? '0.5' : '1' }}">delete_outline</i>
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

<div class="modal fade" id="revenueReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-light" style="border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center">
                    <i class="material-icons me-2 text-primary">analytics</i> Xuất Báo Cáo Doanh Thu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="revenueReportForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Từ ngày</label>
                            <input type="date" class="form-control" name="start_date" id="reportStartDate">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Đến ngày</label>
                            <input type="date" class="form-control" name="end_date" id="reportEndDate">
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label fw-bold">Trạng thái đơn hàng</label>
                            <select class="form-select" name="status" id="reportStatus">
                                <option value="">Tất cả trạng thái</option>
                                <option value="completed" class="text-success fw-bold">Chỉ đơn Đã hoàn thành (Tính doanh thu)</option>
                                <option value="cancelled" class="text-danger fw-bold">Chỉ đơn Đã hủy</option>
                                <option value="pending">Chờ xử lý</option>
                                <option value="shipping">Đang giao</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light" style="border-radius: 0 0 12px 12px;">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary fw-bold d-flex align-items-center" id="btnDownloadRevenueReport">
                    <i class="material-icons me-1">download</i> Tải File Excel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    $(document).ready(function() {
        // Ẩn thông báo sau 4 giây
        setTimeout(function() {
            $(".alert").fadeOut(800, function() {
                $(this).remove();
            });
        }, 4000);

        // Khởi tạo DataTable
        var table = $('#orderTable').DataTable({
            "dom": '<"top"rt><"datatable-footer"ip><"clear">',
            "pageLength": 10,
            "ordering": true,
            "order": [
                [4, 'desc']
            ], // Mặc định sắp xếp theo ngày đặt mới nhất
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json"
            }
        });

        // Xử lý tìm kiếm Custom
        $('#orderSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Lọc theo Trạng thái (Sử dụng data-search trên thẻ <td>)
        $('#filterStatus').on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            // Cột index 3 là cột Trạng thái
            table.column(3).search(val ? '^' + val + '$' : '', true, false).draw();
        });

        // Thay đổi độ dài trang
        $('#changeLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        // Di chuyển pagination
        $('.datatable-footer').appendTo('#pagination-container');
    });

    // Hàm cập nhật màu nền của Select ngay khi người dùng đổi option (trước khi reload web)
    function updateStatusColor(selectElement) {
        // Xóa class màu cũ
        $(selectElement).removeClass('status-pending status-shipping status-completed status-cancelled');
        // Thêm class màu mới dựa trên value
        $(selectElement).addClass('status-' + $(selectElement).val());
    }

    // Xử lý nút Tải Báo Cáo Doanh Thu (Xuất từ Server)
    $('#btnDownloadRevenueReport').on('click', function() {
        // Lấy dữ liệu từ form
        let startDate = $('#reportStartDate').val();
        let endDate = $('#reportEndDate').val();
        let status = $('#reportStatus').val();

        // Xây dựng URL chứa các tham số bộ lọc
        let url = "{{ route('admin.orders.report.revenue') }}?start_date=" + startDate + "&end_date=" + endDate + "&status=" + status;

        // Đóng Modal
        $('#revenueReportModal').modal('hide');

        // Bắn URL lên trình duyệt để Server trả file Excel về
        window.location.href = url;
    });
</script>
@endpush