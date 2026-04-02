document.addEventListener("DOMContentLoaded", function () {
    /* ===============================
           MỞ FORM CẬP NHẬT 
           (Đã dọn dẹp để phù hợp với Profile, không gọi AddressListModal)
        =============================== */
    document.querySelectorAll(".btn-open-update").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            let address = JSON.parse(this.dataset.json);

            document.getElementById("update-address-form").action =
                `/user/addresses/${address.id}`;
            document.getElementById("update_id").value = address.id;
            document.getElementById("upd_name").value = address.receiver_name;
            document.getElementById("upd_phone").value = address.receiver_phone;
            document.getElementById("upd_detail").value =
                address.address_detail;

            if (address.address_type === "office")
                document.getElementById("updOffice").checked = true;
            else document.getElementById("updHome").checked = true;

            document.getElementById("updDefault").checked = address.is_default
                ? true
                : false;

            document.getElementById("updProvinceSelect").innerHTML =
                `<option value="${address.province_id}" selected>${address.province}</option>`;
            document.getElementById("updDistrictSelect").innerHTML =
                `<option value="${address.district_id}" selected>${address.district}</option>`;
            document.getElementById("updWardSelect").innerHTML =
                `<option value="${address.ward_id}" selected>${address.ward}</option>`;

            document.getElementById("updProvinceName").value = address.province;
            document.getElementById("updDistrictName").value = address.district;
            document.getElementById("updWardName").value = address.ward;

            new bootstrap.Modal(
                document.getElementById("updateAddressModal"),
            ).show();

            initAddressAPI(
                "updProvinceSelect",
                "updDistrictSelect",
                "updWardSelect",
                "updProvinceName",
                "updDistrictName",
                "updWardName",
            );
        });
    });

    /* ===============================
           API TỈNH THÀNH (Giữ nguyên)
        =============================== */
    function initAddressAPI(
        provId,
        distId,
        wardId,
        provName,
        distName,
        wardName,
    ) {
        const provinceSelect = document.getElementById(provId);
        const districtSelect = document.getElementById(distId);
        const wardSelect = document.getElementById(wardId);
        const nameP = document.getElementById(provName);
        const nameD = document.getElementById(distName);
        const nameW = document.getElementById(wardName);

        fetch("https://esgoo.net/api-tinhthanh/1/0.htm")
            .then((res) => res.json())
            .then((data) => {
                if (data.error === 0) {
                    let currentProv = provinceSelect.value;
                    provinceSelect.innerHTML =
                        '<option value="">Tỉnh/Thành phố</option>';
                    data.data.forEach((p) => {
                        let selected = p.id == currentProv ? "selected" : "";
                        provinceSelect.innerHTML += `<option value="${p.id}" data-name="${p.full_name}" ${selected}>${p.full_name}</option>`;
                    });
                }
            });

        provinceSelect.addEventListener("change", function () {
            nameP.value =
                this.options[this.selectedIndex].getAttribute("data-name");
            districtSelect.innerHTML =
                '<option selected disabled value="">Quận/Huyện</option>';
            wardSelect.innerHTML =
                '<option selected disabled value="">Phường/Xã</option>';
            wardSelect.disabled = true;

            if (this.value) {
                districtSelect.disabled = false;
                fetch(`https://esgoo.net/api-tinhthanh/2/${this.value}.htm`)
                    .then((res) => res.json())
                    .then((data) => {
                        data.data.forEach((d) => {
                            districtSelect.innerHTML += `<option value="${d.id}" data-name="${d.full_name}">${d.full_name}</option>`;
                        });
                    });
            }
        });

        districtSelect.addEventListener("change", function () {
            nameD.value =
                this.options[this.selectedIndex].getAttribute("data-name");
            wardSelect.innerHTML =
                '<option selected disabled value="">Phường/Xã</option>';

            if (this.value) {
                wardSelect.disabled = false;
                fetch(`https://esgoo.net/api-tinhthanh/3/${this.value}.htm`)
                    .then((res) => res.json())
                    .then((data) => {
                        data.data.forEach((w) => {
                            wardSelect.innerHTML += `<option value="${w.id}" data-name="${w.full_name}">${w.full_name}</option>`;
                        });
                    });
            }
        });

        wardSelect.addEventListener("change", function () {
            nameW.value =
                this.options[this.selectedIndex].getAttribute("data-name");
        });
    }

    // Khởi tạo API cho Form Thêm Mới
    initAddressAPI(
        "addProvinceSelect",
        "addDistrictSelect",
        "addWardSelect",
        "addProvinceName",
        "addDistrictName",
        "addWardName",
    );

    /* ===============================
           AJAX SUBMIT (Giữ nguyên)
        =============================== */
    function handleFormSubmit(formId) {
        document
            .getElementById(formId)
            .addEventListener("submit", function (e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch(this.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        Accept: "application/json",
                    },
                    body: formData,
                })
                    .then((res) => res.json())
                    .then((data) => {
                        if (data.status === "success") {
                            alert(data.message);
                            location.reload();
                        } else if (data.errors) {
                            alert(
                                Object.values(data.errors)
                                    .map((err) => err.join("\n"))
                                    .join("\n"),
                            );
                        }
                    })
                    .catch(() => alert("Lỗi hệ thống"));
            });
    }

    handleFormSubmit("add-address-form");
    handleFormSubmit("update-address-form");
});

/* ===============================
           XÓA ĐỊA CHỈ BẰNG AJAX
        =============================== */
document.querySelectorAll(".btn-delete-address").forEach((btn) => {
    btn.addEventListener("click", function (e) {
        e.preventDefault();

        // Hiển thị hộp thoại xác nhận
        if (!confirm("Bạn có chắc chắn muốn xóa địa chỉ này?")) return;

        let addressId = this.dataset.id;

        // Gửi request DELETE lên server
        fetch(`/user/addresses/${addressId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                Accept: "application/json",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success") {
                    // Tải lại trang để cập nhật danh sách (hoặc bạn có thể dùng JS xóa thẻ div đó đi cho mượt)
                    location.reload();
                } else {
                    alert(data.message || "Có lỗi xảy ra khi xóa!");
                }
            })
            .catch((err) => {
                console.error("Lỗi:", err);
                alert("Lỗi hệ thống, vui lòng thử lại sau.");
            });
    });
});
