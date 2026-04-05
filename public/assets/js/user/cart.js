document.addEventListener("DOMContentLoaded", function () {
    function formatMoney(number) {
        return number.toLocaleString("vi-VN") + "đ";
    }

    function updateItemTotal(item) {
        let price = parseInt(item.dataset.price);

        let qty = parseInt(item.querySelector(".qty-input").value);

        let total = price * qty;

        item.querySelector(".item-total").innerText = formatMoney(total);
    }

    function updateCartTotal() {
        let total = 0;

        document.querySelectorAll(".cart-item").forEach((item) => {
            let checked = item.querySelector(".item-check").checked;

            if (checked) {
                let price = parseInt(item.dataset.price);

                let qty = parseInt(item.querySelector(".qty-input").value);

                total += price * qty;
            }
        });

        document.getElementById("cart-total").innerText = formatMoney(total);
    }

    document.querySelectorAll(".qty-plus").forEach((btn) => {
        btn.onclick = function () {
            let item = this.closest(".cart-item");
            let input = item.querySelector(".qty-input");
            let cartItemId = item.dataset.id; // Lấy ID của dòng giỏ hàng

            input.value++; // Tăng UI

            updateItemTotal(item); // Cập nhật thành tiền UI
            updateCartTotal(); // Cập nhật tổng giỏ hàng UI

            // QUAN TRỌNG: Gửi dữ liệu lên Server
            syncQuantity(cartItemId, input.value);
        };
    });

    document.querySelectorAll(".qty-minus").forEach((btn) => {
        btn.onclick = function () {
            let item = this.closest(".cart-item");
            let input = item.querySelector(".qty-input");
            let cartItemId = item.dataset.id;

            if (input.value > 1) {
                input.value--; // Giảm UI

                updateItemTotal(item);
                updateCartTotal();

                // QUAN TRỌNG: Gửi dữ liệu lên Server
                syncQuantity(cartItemId, input.value);
            }
        };
    });

    document.querySelectorAll(".qty-input").forEach((input) => {
        input.onchange = function () {
            let item = this.closest(".cart-item");

            if (this.value <= 0) {
                this.value = 1;
            }

            updateItemTotal(item);

            updateCartTotal();
        };
    });

    document.querySelectorAll(".item-check").forEach((check) => {
        check.onchange = function () {
            updateCartTotal();
        };
    });

    document.getElementById("check-all").onchange = function () {
        let checked = this.checked;

        document.querySelectorAll(".item-check").forEach((check) => {
            check.checked = checked;
        });

        updateCartTotal();
    };

    document.getElementById("check-all-footer").onchange = function () {
        let checked = this.checked;

        document.querySelectorAll(".item-check").forEach((check) => {
            check.checked = checked;
        });

        updateCartTotal();
    };

    // Hàm cập nhật số lượng lên Server
    function syncQuantity(cartItemId, newQty) {
        fetch(window.cartConfig.updateUrl, {
            // Đường dẫn đến hàm updateQuantity trong Controller
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.cartConfig.csrfToken,
            },
            body: JSON.stringify({
                id: cartItemId,
                quantity: newQty,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status !== "success") {
                    alert(data.message || "Có lỗi xảy ra");
                    location.reload(); // Nếu lỗi (hết hàng), reload để lấy số lượng đúng
                }
            })
            .catch((err) => {
                console.error("Lỗi kết nối:", err);
            });
    }

    // Cập nhật sự kiện nút Xóa
    document.querySelectorAll(".remove-item").forEach((btn) => {
        btn.onclick = function (e) {
            e.preventDefault();
            if (!confirm("Xác nhận xóa sản phẩm?")) return;

            let itemRow = this.closest(".cart-item");
            let id = itemRow.dataset.id;

            fetch(`/cart/delete/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": window.cartConfig.csrfToken,
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    itemRow.remove();
                    updateCartTotal();
                });
        };
    });

    // Xử lý sự kiện nhấn nút Mua hàng
    document
        .getElementById("btn-checkout")
        .addEventListener("click", function (e) {
            e.preventDefault();

            let selectedCartIds = [];

            document
                .querySelectorAll(".item-check:checked")
                .forEach(function (checkbox) {
                    let cartItem = checkbox.closest(".cart-item");
                    // Chỉ lấy những sản phẩm không bị hết hàng
                    if (
                        cartItem &&
                        !cartItem.classList.contains("out-of-stock")
                    ) {
                        selectedCartIds.push(cartItem.dataset.id);
                    }
                });

            if (selectedCartIds.length === 0) {
                alert("Vui lòng chọn ít nhất một sản phẩm để thanh toán!");
                return;
            }

            // Tạo một form ẩn để gửi mảng ID sang trang Checkout bằng phương thức POST
            let form = document.createElement("form");
            form.method = "POST";
            form.action = "/checkout"; // Thay đổi đường dẫn này theo route của bạn (VD: route('checkout.index'))

            // Thêm CSRF Token
            let csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = window.cartConfig.csrfToken;
            form.appendChild(csrfInput);

            // Thêm các ID sản phẩm được chọn vào form
            selectedCartIds.forEach((id) => {
                let input = document.createElement("input");
                input.type = "hidden";
                input.name = "cart_ids[]";
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit(); // Chuyển hướng sang trang thanh toán
        });

    updateCartTotal();
});
