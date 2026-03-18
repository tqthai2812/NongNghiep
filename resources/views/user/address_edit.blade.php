@extends('user.layouts.default')
@section('title', 'Địa chỉ của tôi')

@push('page_specific_css')
<style>
    body {
        background-color: #f5f5f5;
    }

    .card-custom {
        border: none;
        border-radius: 3px;
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .05);
    }

    .btn-shopee {
        background-color: #ee4d2d;
        color: white;
        border: none;
    }

    .btn-shopee:hover {
        background-color: #d73a1c;
        color: white;
    }

    .address-item {
        border-bottom: 1px solid #ebebeb;
        padding: 20px 0;
    }

    .address-item:last-child {
        border-bottom: none;
    }

    .btn-outline-custom {
        border: 1px solid #d9d9d9;
        color: #555;
        background: white;
    }

    .btn-outline-custom:hover {
        background: #f8f8f8;
    }
</style>
@endpush

@section('content')
<div id="wp-content" class="bg-body-tertiary" style="margin-top: 57px;">
    <div class="container mt-4 pb-3 pt-3">
        <div class="row">

            @include('user.layouts.profile')

            <div class="col-md-9">
                <div class="card card-custom">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center p-4 border-bottom">
                        <h5 class="mb-0 fw-normal fs-5">Địa chỉ của tôi</h5>
                        <button class="btn btn-shopee px-3" data-bs-toggle="modal" data-bs-target="#addressModal">
                            <i class="fa-solid fa-plus"></i> Thêm địa chỉ mới
                        </button>
                    </div>

                    <div class="card-body p-4 pt-2">

                        @forelse($addresses as $addr)
                        <div class="address-item row align-items-start">

                            <div class="col-md-8">
                                <div class="d-flex align-items-baseline mb-1">
                                    <span class="fw-bold text-dark me-2 fs-6">{{ $addr->receiver_name }}</span>
                                    <span class="text-muted border-start ps-2" style="font-size: 0.9rem;">(+84) {{ ltrim($addr->receiver_phone, '0') }}</span>
                                </div>
                                <div class="text-muted mt-2" style="font-size: 0.9rem; line-height: 1.5;">
                                    {{ $addr->address_detail }},
                                    {{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }}
                                </div>

                                @if($addr->is_default)
                                <span class="badge border border-danger text-danger mt-2 rounded-0 px-2 py-1 fw-normal">Mặc định</span>
                                @endif
                            </div>

                            <div class="col-md-4 text-end d-flex flex-column justify-content-between h-100">
                                <div class="mb-3">
                                    <a href="#" style="font-size: 14px;" class="text-decoration-none text-primary me-2 btn-open-update" data-json="{{ json_encode($addr) }}">Cập nhật</a>
                                    <a href="#" style="font-size: 14px;" class="text-decoration-none text-primary btn-delete-address" data-id="{{ $addr->id }}">Xóa</a>
                                </div>
                            </div>

                        </div>
                        @empty
                        <div class="text-center py-5 text-muted">
                            Bạn chưa có địa chỉ nào. Hãy thêm địa chỉ mới!
                        </div>
                        @endforelse

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Thêm địa chỉ mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="add-address-form" action="/user/addresses" method="POST">
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6"><input type="text" name="receiver_name" class="form-control" placeholder="Họ và tên" required></div>
                            <div class="col-md-6"><input type="text" name="receiver_phone" class="form-control" placeholder="Số điện thoại" required></div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="addProvinceSelect" name="province_id" required>
                                        <option selected disabled value="">Tỉnh/TP</option>
                                    </select>
                                    <label>Tỉnh/Thành phố</label>
                                </div>
                                <input type="hidden" name="province" id="addProvinceName">
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="addDistrictSelect" name="district_id" required disabled>
                                        <option selected disabled value="">Quận/Huyện</option>
                                    </select>
                                    <label>Quận/Huyện</label>
                                </div>
                                <input type="hidden" name="district" id="addDistrictName">
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="addWardSelect" name="ward_id" required disabled>
                                        <option selected disabled value="">Phường/Xã</option>
                                    </select>
                                    <label>Phường/Xã</label>
                                </div>
                                <input type="hidden" name="ward" id="addWardName">
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="address_detail" style="height: 80px" placeholder="Địa chỉ cụ thể" required></textarea>
                            <label>Địa chỉ cụ thể (Số nhà, đường...)</label>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <span class="me-3 text-muted">Loại địa chỉ:</span>
                            <input type="radio" class="btn-check" name="address_type" id="addHome" value="home" checked>
                            <label class="btn btn-outline-danger px-3 py-1 me-2" for="addHome">Nhà Riêng</label>
                            <input type="radio" class="btn-check" name="address_type" id="addOffice" value="office">
                            <label class="btn btn-outline-secondary px-3 py-1" for="addOffice">Văn Phòng</label>
                        </div>

                        <div class="form-check mb-4 mt-2">
                            <input class="form-check-input" type="checkbox" name="is_default" id="addDefault" value="1" checked>
                            <label class="form-check-label text-muted" for="addDefault">Đặt làm địa chỉ mặc định</label>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Trở Lại</button>
                            <button type="submit" class="btn btn-shopee px-4">Hoàn thành</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title">Cập nhật địa chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="update-address-form" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="update_id">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6"><input type="text" name="receiver_name" id="upd_name" class="form-control" required></div>
                            <div class="col-md-6"><input type="text" name="receiver_phone" id="upd_phone" class="form-control" required></div>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="updProvinceSelect" name="province_id" required></select>
                                    <label>Tỉnh/Thành phố</label>
                                </div>
                                <input type="hidden" name="province" id="updProvinceName">
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="updDistrictSelect" name="district_id" required></select>
                                    <label>Quận/Huyện</label>
                                </div>
                                <input type="hidden" name="district" id="updDistrictName">
                            </div>
                            <div class="col-md-4">
                                <div class="form-floating">
                                    <select class="form-select" id="updWardSelect" name="ward_id" required></select>
                                    <label>Phường/Xã</label>
                                </div>
                                <input type="hidden" name="ward" id="updWardName">
                            </div>
                        </div>

                        <div class="form-floating mb-3">
                            <textarea class="form-control" name="address_detail" id="upd_detail" style="height: 80px" required></textarea>
                            <label>Địa chỉ cụ thể</label>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <span class="me-3 text-muted">Loại địa chỉ:</span>
                            <input type="radio" class="btn-check" name="address_type" id="updHome" value="home">
                            <label class="btn btn-outline-danger px-3 py-1 me-2" for="updHome">Nhà Riêng</label>
                            <input type="radio" class="btn-check" name="address_type" id="updOffice" value="office">
                            <label class="btn btn-outline-secondary px-3 py-1" for="updOffice">Văn Phòng</label>
                        </div>

                        <div class="form-check mb-4 mt-2">
                            <input class="form-check-input" type="checkbox" name="is_default" id="updDefault" value="1">
                            <label class="form-check-label text-muted" for="updDefault">Đặt làm địa chỉ mặc định</label>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Trở Lại</button>
                            <button type="submit" class="btn btn-shopee px-4">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_specific_js')
<script>
    document.addEventListener("DOMContentLoaded", function() {

        /* ===============================
           MỞ FORM CẬP NHẬT 
           (Đã dọn dẹp để phù hợp với Profile, không gọi AddressListModal)
        =============================== */
        document.querySelectorAll('.btn-open-update').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                let address = JSON.parse(this.dataset.json);

                document.getElementById('update-address-form').action = `/user/addresses/${address.id}`;
                document.getElementById('update_id').value = address.id;
                document.getElementById('upd_name').value = address.receiver_name;
                document.getElementById('upd_phone').value = address.receiver_phone;
                document.getElementById('upd_detail').value = address.address_detail;

                if (address.address_type === 'office')
                    document.getElementById('updOffice').checked = true;
                else
                    document.getElementById('updHome').checked = true;

                document.getElementById('updDefault').checked = address.is_default ? true : false;

                document.getElementById('updProvinceSelect').innerHTML = `<option value="${address.province_id}" selected>${address.province}</option>`;
                document.getElementById('updDistrictSelect').innerHTML = `<option value="${address.district_id}" selected>${address.district}</option>`;
                document.getElementById('updWardSelect').innerHTML = `<option value="${address.ward_id}" selected>${address.ward}</option>`;

                document.getElementById('updProvinceName').value = address.province;
                document.getElementById('updDistrictName').value = address.district;
                document.getElementById('updWardName').value = address.ward;

                new bootstrap.Modal(document.getElementById('updateAddressModal')).show();

                initAddressAPI(
                    'updProvinceSelect', 'updDistrictSelect', 'updWardSelect',
                    'updProvinceName', 'updDistrictName', 'updWardName'
                );
            });
        });

        /* ===============================
           API TỈNH THÀNH (Giữ nguyên)
        =============================== */
        function initAddressAPI(provId, distId, wardId, provName, distName, wardName) {
            const provinceSelect = document.getElementById(provId);
            const districtSelect = document.getElementById(distId);
            const wardSelect = document.getElementById(wardId);
            const nameP = document.getElementById(provName);
            const nameD = document.getElementById(distName);
            const nameW = document.getElementById(wardName);

            fetch('https://esgoo.net/api-tinhthanh/1/0.htm')
                .then(res => res.json())
                .then(data => {
                    if (data.error === 0) {
                        let currentProv = provinceSelect.value;
                        provinceSelect.innerHTML = '<option disabled value="">Tỉnh/Thành phố</option>';
                        data.data.forEach(p => {
                            let selected = (p.id == currentProv) ? 'selected' : '';
                            provinceSelect.innerHTML += `<option value="${p.id}" data-name="${p.full_name}" ${selected}>${p.full_name}</option>`;
                        });
                    }
                });

            provinceSelect.addEventListener('change', function() {
                nameP.value = this.options[this.selectedIndex].getAttribute('data-name');
                districtSelect.innerHTML = '<option selected disabled value="">Quận/Huyện</option>';
                wardSelect.innerHTML = '<option selected disabled value="">Phường/Xã</option>';
                wardSelect.disabled = true;

                if (this.value) {
                    districtSelect.disabled = false;
                    fetch(`https://esgoo.net/api-tinhthanh/2/${this.value}.htm`)
                        .then(res => res.json())
                        .then(data => {
                            data.data.forEach(d => {
                                districtSelect.innerHTML += `<option value="${d.id}" data-name="${d.full_name}">${d.full_name}</option>`;
                            });
                        });
                }
            });

            districtSelect.addEventListener('change', function() {
                nameD.value = this.options[this.selectedIndex].getAttribute('data-name');
                wardSelect.innerHTML = '<option selected disabled value="">Phường/Xã</option>';

                if (this.value) {
                    wardSelect.disabled = false;
                    fetch(`https://esgoo.net/api-tinhthanh/3/${this.value}.htm`)
                        .then(res => res.json())
                        .then(data => {
                            data.data.forEach(w => {
                                wardSelect.innerHTML += `<option value="${w.id}" data-name="${w.full_name}">${w.full_name}</option>`;
                            });
                        });
                }
            });

            wardSelect.addEventListener('change', function() {
                nameW.value = this.options[this.selectedIndex].getAttribute('data-name');
            });
        }

        // Khởi tạo API cho Form Thêm Mới
        initAddressAPI(
            'addProvinceSelect', 'addDistrictSelect', 'addWardSelect',
            'addProvinceName', 'addDistrictName', 'addWardName'
        );

        /* ===============================
           AJAX SUBMIT (Giữ nguyên)
        =============================== */
        function handleFormSubmit(formId) {
            document.getElementById(formId).addEventListener('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            location.reload();
                        } else if (data.errors) {
                            alert(Object.values(data.errors).map(err => err.join('\n')).join('\n'));
                        }
                    })
                    .catch(() => alert('Lỗi hệ thống'));
            });
        }

        handleFormSubmit('add-address-form');
        handleFormSubmit('update-address-form');
    });

    /* ===============================
           XÓA ĐỊA CHỈ BẰNG AJAX
        =============================== */
    document.querySelectorAll('.btn-delete-address').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            // Hiển thị hộp thoại xác nhận
            if (!confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) return;

            let addressId = this.dataset.id;

            // Gửi request DELETE lên server
            fetch(`/user/addresses/${addressId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Tải lại trang để cập nhật danh sách (hoặc bạn có thể dùng JS xóa thẻ div đó đi cho mượt)
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi xóa!');
                    }
                })
                .catch(err => {
                    console.error('Lỗi:', err);
                    alert('Lỗi hệ thống, vui lòng thử lại sau.');
                });
        });
    });
</script>
@endpush