@extends('admin.layouts.master')

@section('title', 'Danh sách sản phẩm')

@section('page_specific_css')
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.css" rel="stylesheet">
<style>
    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .package-img {
        width: 35px;
        height: 35px;
        object-fit: cover;
        border-radius: 2px;
    }

    /* Style cho dòng phân loại con */
    .variant-row {
        background-color: #fdfdfd !important;
        font-size: 0.9rem;
    }

    .variant-row td {
        padding-top: 12px !important;
        padding-bottom: 12px !important;
        border-top: 1px dashed #eee !important;
    }

    .badge-outline {
        border: 1px solid #ddd;
        color: #666;
        font-weight: 400;
        background: none;
    }

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
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-plain">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mt-0 text-dark fw-bold">Danh Sách Sản Phẩm Tại Cửa Hàng</h4>
                <button class="btn btn-success d-flex align-items-center">
                    <i class="material-icons me-1">add</i> Thêm sản phẩm
                </button>
            </div>

            <div class="card-body">
                <div class="row g-2 mb-3 filter-bar">
                    <div class="col-md-3">
                        <input type="text" id="customSearch" class="form-control" placeholder="Lọc theo tên sản phẩm...">
                    </div>
                    <div class="col-md-2">
                        <select id="filterCategory" class="form-select">
                            <option value="">Tất cả danh mục</option>
                            <option value="Thuốc BVTV">Thuốc bảo vệ thực vật</option>
                            <option value="Vật tư">Vật tư nông nghiệp</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="changeLength" class="form-select">
                            <option value="5">5 dòng/trang</option>
                            <option value="10" selected>10 dòng/trang</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="productTable" class="table table-hover align-middle">
                        <thead class="text-secondary bg-light">
                            <tr>
                                <th style="width: 40px">#</th>
                                <th>Tên sản phẩm / Phân loại</th>
                                <th class="text-center">Doanh số</th>
                                <th class="text-center">Giá bán</th>
                                <th class="text-center">Kho hàng</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="table-light fw-bold">
                                <td>23</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/50" class="product-img me-3">
                                        <div>
                                            Áo mưa TAKIMI unisex dáng dài vải kaki cao cấp...
                                            <div class="text-muted small fw-normal mt-1">ID Sản phẩm: 27753607395</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">0</td>
                                <td class="text-center">₫399.000</td>
                                <td class="text-center">6k</td>
                                <td class="table-actions text-end">
                                    <a href="#" class="text-primary"><i class="material-icons">edit</i></a>
                                    <a href="#" class="text-danger"><i class="material-icons">delete_outline</i></a>
                                </td>
                            </tr>

                            <tr class="variant-row">
                                <td></td>
                                <td class="ps-5">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/35" class="package-img me-2">
                                        <span>Be, M (Bao 10kg)</span>
                                    </div>
                                </td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center">₫399.000</td>
                                <td class="text-center text-muted">1k</td>
                                <td class="text-end"></td>
                            </tr>
                            <tr class="variant-row">
                                <td></td>
                                <td class="ps-5">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/35" class="package-img me-2">
                                        <span>Be, L (Bao 25kg)</span>
                                    </div>
                                </td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center">₫399.000</td>
                                <td class="text-center text-muted">2k</td>
                                <td class="text-end"></td>
                            </tr>

                            <tr class="table-light fw-bold">
                                <td>23</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/50" class="product-img me-3">
                                        <div>
                                            Áo mưa TAKIMI unisex dáng dài vải kaki cao cấp...
                                            <div class="text-muted small fw-normal mt-1">ID Sản phẩm: 27753607395</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">0</td>
                                <td class="text-center">₫399.000</td>
                                <td class="text-center">6k</td>
                                <td class="table-actions text-end">
                                    <a href="#" class="text-primary"><i class="material-icons">edit</i></a>
                                    <a href="#" class="text-danger"><i class="material-icons">delete_outline</i></a>
                                </td>
                            </tr>

                            <tr class="variant-row">
                                <td></td>
                                <td class="ps-5">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/35" class="package-img me-2">
                                        <span>Be, M (Bao 10kg)</span>
                                    </div>
                                </td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center">₫399.000</td>
                                <td class="text-center text-muted">1k</td>
                                <td class="text-end"></td>
                            </tr>
                            <tr class="variant-row border-bottom">
                                <td></td>
                                <td class="ps-5">
                                    <div class="d-flex align-items-center">
                                        <img src="https://via.placeholder.com/35" class="package-img me-2">
                                        <span>Be, L (Bao 25kg)</span>
                                    </div>
                                </td>
                                <td class="text-center text-muted">0</td>
                                <td class="text-center">₫399.000</td>
                                <td class="text-center text-muted">2k</td>
                                <td class="text-end"></td>
                            </tr>

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
@push('scripts')
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        // --- BƯỚC 1: Xử lý dữ liệu ẩn để hỗ trợ tìm kiếm ---
        // Biến lưu tên sản phẩm cha hiện tại
        var currentProductName = '';

        // Duyệt qua từng dòng trong bảng TRƯỚC khi khởi tạo DataTable
        $('#productTable tbody tr').each(function() {
            var $row = $(this);

            if (!$row.hasClass('variant-row')) {
                // Nếu là dòng cha: Lấy nội dung tên sản phẩm (ở cột thứ 2 - index 1)
                // .text() lấy cả ID bên dưới, nhưng không sao, miễn là có chứa tên
                currentProductName = $row.find('td:eq(1)').text().trim();
            } else {
                // Nếu là dòng con: Chèn tên sản phẩm cha vào một thẻ ẩn (d-none)
                // Việc này giúp khi search "Áo mưa", dòng này cũng được coi là có chứa từ khóa đó
                if (currentProductName) {
                    $row.find('td:eq(1)').append(
                        '<span class="d-none"> ' + currentProductName + ' </span>'
                    );
                }
            }
        });

        // --- BƯỚC 2: Khởi tạo DataTable ---
        var table = $('#productTable').DataTable({
            "dom": '<"top"rt><"datatable-footer"ip><"clear">',
            "pageLength": 10,
            "ordering": false, // Bắt buộc tắt ordering để giữ thứ tự cha-con
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json"
            }
        });

        // --- BƯỚC 3: Xử lý các bộ lọc Custom ---
        $('#customSearch').on('keyup', function() {
            table.search(this.value).draw();
        });

        $('#changeLength').on('change', function() {
            table.page.len(this.value).draw();
        });

        // Di chuyển thanh phân trang ra vị trí mong muốn
        $('.datatable-footer').appendTo('#pagination-container');
    });
</script>
@endpush
@endpush