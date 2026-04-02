$(document).ready(function () {
    // Ẩn thông báo sau 4 giây
    setTimeout(function () {
        $(".alert").fadeOut(800, function () {
            $(this).remove();
        });
    }, 4000);

    // Khởi tạo DataTable
    var table = $("#orderTable").DataTable({
        dom: '<"top"rt><"datatable-footer"ip><"clear">',
        pageLength: 10,
        ordering: true,
        order: [[4, "desc"]], // Mặc định sắp xếp theo ngày đặt mới nhất
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json",
        },
    });

    // Xử lý tìm kiếm Custom
    $("#orderSearch").on("keyup", function () {
        table.search(this.value).draw();
    });

    // Lọc theo Trạng thái (Sử dụng data-search trên thẻ <td>)
    $("#filterStatus").on("change", function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        // Cột index 3 là cột Trạng thái
        table
            .column(3)
            .search(val ? "^" + val + "$" : "", true, false)
            .draw();
    });

    // Thay đổi độ dài trang
    $("#changeLength").on("change", function () {
        table.page.len(this.value).draw();
    });

    // Di chuyển pagination
    $(".datatable-footer").appendTo("#pagination-container");
});

// Hàm cập nhật màu nền của Select ngay khi người dùng đổi option (trước khi reload web)
function updateStatusColor(selectElement) {
    // Xóa class màu cũ
    $(selectElement).removeClass(
        "status-pending status-shipping status-completed status-cancelled",
    );
    // Thêm class màu mới dựa trên value
    $(selectElement).addClass("status-" + $(selectElement).val());
}
