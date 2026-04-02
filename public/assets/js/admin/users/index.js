$(document).ready(function () {
    // Tự động ẩn các thông báo sau 4 giây để giao diện gọn gàng
    setTimeout(function () {
        $(".alert").fadeOut(800, function () {
            $(this).remove();
        });
    }, 4000);
});

$(document).ready(function () {
    // Khởi tạo DataTable đồng nhất cấu hình
    var table = $("#userTable").DataTable({
        dom: '<"top"rt><"datatable-footer"ip><"clear">',
        pageLength: 10,
        ordering: true, // User có thể bật ordering
        order: [[0, "desc"]], // Mặc định user mới nhất lên đầu
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json",
        },
    });

    // Xử lý tìm kiếm Custom
    $("#userSearch").on("keyup", function () {
        table.search(this.value).draw();
    });

    // Lọc theo Vai trò
    $("#filterRole").on("change", function () {
        table.column(2).search(this.value).draw();
    });

    // Lọc theo Trạng thái
    $("#filterStatus").on("change", function () {
        table.column(3).search(this.value).draw();
    });

    // Thay đổi độ dài trang
    $("#changeLength").on("change", function () {
        table.page.len(this.value).draw();
    });

    // Di chuyển pagination
    $(".datatable-footer").appendTo("#pagination-container");
});
