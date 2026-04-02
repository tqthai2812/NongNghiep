// Bắt sự kiện khi click vào nút Đánh giá của từng sản phẩm
// document.querySelectorAll('.btn-review').forEach(button => {
//     button.addEventListener('click', function() {
//         let orderItemId = this.dataset.itemId;
//         let packageId = this.dataset.packageId;

//         // Log thử ra xem lấy đúng ID chưa
//         console.log("Chuẩn bị đánh giá cho OrderItem ID: " + orderItemId + ", Package ID: " + packageId);

//         // GỢI Ý NEXT STEP: Mở một Modal (Bootstrap) chứa Form đánh giá ở đây
//         // alert('Tính năng mở form đánh giá cho sản phẩm đang được xây dựng!');
//     });
// });
// xu ly modal review
document.addEventListener("DOMContentLoaded", function () {
    // Bắt sự kiện khi click mở modal đánh giá
    let reviewButtons = document.querySelectorAll(".btn-open-review");

    reviewButtons.forEach((button) => {
        button.addEventListener("click", function () {
            // Lấy data từ nút bấm
            let orderId = this.getAttribute("data-order-id");
            let packageId = this.getAttribute("data-package-id");
            let productName = this.getAttribute("data-product-name");

            // Điền vào form ẩn trong Modal
            document.getElementById("reviewOrderId").value = orderId;
            document.getElementById("reviewPackageId").value = packageId;
            document.getElementById("reviewProductName").innerText =
                "Sản phẩm: " + productName;
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".order-tabs .nav-link");
    const orderCards = document.querySelectorAll(".order-card");
    const searchInput = document.getElementById("orderSearchInput");

    let currentFilter = "all";

    // 1. Hàm lọc chính xử lý cả Tab và Tìm kiếm
    function filterOrders() {
        const searchText = searchInput.value.toLowerCase().trim();

        orderCards.forEach((card) => {
            const status = card.getAttribute("data-status");
            const searchContent = card
                .getAttribute("data-search")
                .toLowerCase();

            // Kiểm tra khớp tab
            const isTabMatched =
                currentFilter === "all" || status === currentFilter;
            // Kiểm tra khớp tìm kiếm
            const isSearchMatched = searchContent.includes(searchText);

            if (isTabMatched && isSearchMatched) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        });

        // Hiển thị thông báo nếu không tìm thấy đơn nào
        checkEmptyResult();
    }

    // 2. Xử lý click Tab
    tabs.forEach((tab) => {
        tab.addEventListener("click", function (e) {
            e.preventDefault();

            // UI: Đổi active class
            tabs.forEach((t) => t.classList.remove("active"));
            this.classList.add("active");

            // Logic: Lọc
            currentFilter = this.getAttribute("data-filter");
            filterOrders();
        });
    });

    // 3. Xử lý tìm kiếm (Debounce nhẹ để mượt hơn)
    searchInput.addEventListener("input", filterOrders);

    function checkEmptyResult() {
        const visibleCards = Array.from(orderCards).filter(
            (c) => c.style.display !== "none",
        );
        let emptyMsg = document.getElementById("empty-order-msg");

        if (visibleCards.length === 0) {
            if (!emptyMsg) {
                const div = document.createElement("div");
                div.id = "empty-order-msg";
                div.className =
                    "text-center p-5 bg-white rounded shadow-sm mt-3";
                div.innerHTML =
                    '<h5 class="text-muted">Không tìm thấy đơn hàng phù hợp</h5>';
                document.querySelector(".col-md-9").appendChild(div);
            }
        } else if (emptyMsg) {
            emptyMsg.remove();
        }
    }

    // --- Giữ nguyên Logic Modal Review của bạn ---
    let reviewButtons = document.querySelectorAll(".btn-open-review");
    reviewButtons.forEach((button) => {
        button.addEventListener("click", function () {
            let orderId = this.getAttribute("data-order-id");
            let packageId = this.getAttribute("data-package-id");
            let productName = this.getAttribute("data-product-name");

            document.getElementById("reviewOrderId").value = orderId;
            document.getElementById("reviewPackageId").value = packageId;
            document.getElementById("reviewProductName").innerText =
                "Sản phẩm: " + productName;
        });
    });
});
