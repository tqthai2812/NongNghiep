$(document).ready(function () {
    setTimeout(function () {
        $(".alert").fadeOut(800, function () {
            $(this).remove();
        });
    }, 4000);

    var table = $("#categoryTable").DataTable({
        dom: '<"top"rt><"datatable-footer"ip><"clear">',

        pageLength: 10,

        ordering: false,

        language: {
            url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json",
        },
    });

    $("#customSearch").on("keyup", function () {
        table.search(this.value).draw();
    });

    $("#changeLength").on("change", function () {
        table.page.len(this.value).draw();
    });

    $(".datatable-footer").appendTo("#pagination-container");

    // Tự động ẩn các thông báo sau 4 giây để giao diện gọn gàng
});
