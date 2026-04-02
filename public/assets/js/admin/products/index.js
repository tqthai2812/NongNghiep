$(document).ready(function () {
    setTimeout(function () {
        $(".alert").fadeOut(800, function () {
            $(this).remove();
        });
    }, 4000);
    // --- BƯỚC 1: Xử lý dữ liệu ẩn để hỗ trợ tìm kiếm ---
    // Biến lưu tên sản phẩm cha hiện tại
    var currentProductName = "";

    // Duyệt qua từng dòng trong bảng TRƯỚC khi khởi tạo DataTable
    $("#productTable tbody tr").each(function () {
        var $row = $(this);

        if (!$row.hasClass("variant-row")) {
            // Nếu là dòng cha: Lấy nội dung tên sản phẩm (ở cột thứ 2 - index 1)
            // .text() lấy cả ID bên dưới, nhưng không sao, miễn là có chứa tên
            currentProductName = $row.find("td:eq(1)").text().trim();
        } else {
            // Nếu là dòng con: Chèn tên sản phẩm cha vào một thẻ ẩn (d-none)
            // Việc này giúp khi search "Áo mưa", dòng này cũng được coi là có chứa từ khóa đó
            if (currentProductName) {
                $row.find("td:eq(1)").append(
                    '<span class="d-none"> ' + currentProductName + " </span>",
                );
            }
        }
    });

    // --- BƯỚC 2: Khởi tạo DataTable ---
    var table = $("#productTable").DataTable({
        dom: '<"top"rt><"datatable-footer"ip><"clear">',
        pageLength: 10,
        ordering: false, // Bắt buộc tắt ordering để giữ thứ tự cha-con
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json",
        },
    });

    // --- BƯỚC 3: Xử lý các bộ lọc Custom ---
    $("#customSearch").on("keyup", function () {
        table.search(this.value).draw();
    });

    $("#changeLength").on("change", function () {
        table.page.len(this.value).draw();
    });

    // Di chuyển thanh phân trang ra vị trí mong muốn
    $(".datatable-footer").appendTo("#pagination-container");
});
