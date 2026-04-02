@extends('admin.layouts.master')

@section('title', 'Bảng điều khiển quản trị')

@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header">
                <div class="icon icon-success">
                    <span class="material-icons">attach_money</span>
                </div>
            </div>
            <div class="card-content">
                <p class="category"><strong>Doanh thu</strong></p>
                <h3 class="card-title">{{ number_format($totalRevenue, 0, ',', '.') }}đ</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> Tổng doanh thu hoàn thành
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header">
                <div class="icon icon-rose">
                    <span class="material-icons">shopping_cart</span>
                </div>
            </div>
            <div class="card-content">
                <p class="category"><strong>Đơn hàng mới</strong></p>
                <h3 class="card-title">{{ $pendingOrdersCount }}</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">local_offer</i> Cần xác nhận ngay
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header">
                <div class="icon icon-warning">
                    <span class="material-icons">inventory_2</span>
                </div>
            </div>
            <div class="card-content">
                <p class="category"><strong>Sắp hết hàng</strong></p>
                <h3 class="card-title">{{ $lowStockCount }}</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons text-danger">warning</i> Số loại sản phẩm < 10 đơn vị
                        </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
                <div class="card-header">
                    <div class="icon icon-info">
                        <span class="material-icons">people</span>
                    </div>
                </div>
                <div class="card-content">
                    <p class="category"><strong>Khách hàng</strong></p>
                    <h3 class="card-title">{{ $totalCustomers }}</h3>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">update</i> Tổng số người dùng hệ thống
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info">
                    <h4 class="card-title">Tăng trưởng người dùng mới</h4>
                    <p class="category">Số lượng tài khoản đăng ký trong 7 ngày qua</p>
                </div>
                <div class="card-content">
                    <canvas id="userGrowthChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-warning">
                    <h4 class="card-title">Thống kê Đánh giá (Sao)</h4>
                    <p class="category">Chất lượng sản phẩm theo đánh giá khách hàng</p>
                </div>
                <div class="card-content" style="display: flex; justify-content: center; padding: 20px;">
                    <div style="width: 70%; max-width: 350px;">
                        <canvas id="reviewPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success">
                    <h4 class="card-title">Cơ cấu Sản phẩm</h4>
                    <p class="category">Tỷ trọng sản phẩm theo từng danh mục</p>
                </div>
                <div class="card-content" style="display: flex; justify-content: center; padding: 20px;">
                    <div style="width: 70%; max-width: 350px;">
                        <canvas id="categoryPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 col-md-12">
            <div class="card" style="min-height: 485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Đơn hàng vừa đặt</h4>
                    <p class="category">Danh sách các giao dịch mới nhất trên hệ thống</p>
                </div>
                <div class="card-content table-responsive">
                    <table class="table table-hover">
                        <thead class="text-primary">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                                <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                <td>
                                    @if($order->status == 'pending')
                                    <span class="label label-warning">Chờ xử lý</span>
                                    @elseif($order->status == 'shipping')
                                    <span class="label label-info">Đang giao</span>
                                    @elseif($order->status == 'completed')
                                    <span class="label label-success">Hoàn thành</span>
                                    @else
                                    <span class="label label-danger">Đã hủy</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Chưa có đơn hàng nào.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12">
            <div class="card" style="min-height: 485px">
                <div class="card-header card-header-text">
                    <h4 class="card-title">Nhật ký kho hàng</h4>
                    <p class="category">Biến động nhập xuất sản phẩm gần đây</p>
                </div>
                <div class="card-content">
                    <div class="streamline">
                        @forelse($inventoryLogs as $log)
                        <div class="sl-item {{ $log->type == 'in' ? 'sl-success' : ($log->type == 'out' ? 'sl-danger' : 'sl-primary') }}">
                            <div class="sl-content">
                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                <p>
                                    <strong>{{ $log->user->user_name ?? 'Admin' }}</strong>
                                    @if($log->type == 'in') đã nhập thêm @elseif($log->type == 'out') đã xuất @else đã điều chỉnh @endif
                                    <span class="text-info">{{ abs($log->quantity) }}</span>
                                    sản phẩm mã #{{ $log->package_id }}
                                </p>
                                <small>Lý do: {{ $log->reason }}</small>
                            </div>
                        </div>
                        @empty
                        <p class="text-center">Chưa có hoạt động kho nào.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Lấy dữ liệu từ Backend (PHP) chuyển sang JavaScript
            const labels = @json($chartLabels);
            const data = @json($chartData);

            // Cấu hình vẽ biểu đồ
            const ctx = document.getElementById('userGrowthChart').getContext('2d');
            const userGrowthChart = new Chart(ctx, {
                type: 'line', // Biểu đồ dạng đường
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Người dùng mới',
                        data: data,
                        backgroundColor: 'rgba(0, 188, 212, 0.2)', // Màu nền mờ (khớp với theme info)
                        borderColor: 'rgba(0, 188, 212, 1)', // Màu đường kẻ
                        borderWidth: 2,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: 'rgba(0, 188, 212, 1)',
                        pointRadius: 4,
                        fill: true, // Điền màu dưới đường kẻ
                        tension: 0.3 // Làm cong đường nối cho mượt
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Chỉ hiển thị số nguyên (vì người dùng không thể là số thập phân)
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Ẩn chú thích để giao diện gọn hơn
                        }
                    }
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ---- BIỂU ĐỒ 1: ĐÁNH GIÁ (SAO) ----
            const reviewCtx = document.getElementById('reviewPieChart').getContext('2d');
            new Chart(reviewCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($reviewLabels),
                    datasets: [{
                        data: @json($reviewData),
                        backgroundColor: [
                            '#4caf50', // 5 sao - Xanh lá
                            '#8bc34a', // 4 sao - Xanh nhạt
                            '#ffeb3b', // 3 sao - Vàng
                            '#ff9800', // 2 sao - Cam
                            '#f44336' // 1 sao - Đỏ
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // ---- BIỂU ĐỒ 2: DANH MỤC SẢN PHẨM ----
            const categoryCtx = document.getElementById('categoryPieChart').getContext('2d');

            // Mảng màu sinh động cho các danh mục
            const categoryColors = [
                '#e91e63', '#9c27b0', '#3f51b5', '#00bcd4',
                '#009688', '#ff5722', '#795548', '#607d8b'
            ];

            new Chart(categoryCtx, {
                type: 'pie', // Có thể đổi thành doughnut nếu thích
                data: {
                    labels: @json($categoryLabels),
                    datasets: [{
                        data: @json($categoryData),
                        backgroundColor: categoryColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    @endpush