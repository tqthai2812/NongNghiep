@extends('user.layouts.default')
@section('title', 'Thanh toán đơn hàng')

@push('page_specific_css')
<link rel="stylesheet" href="{{ asset('assets/css/user/checkout.css') }}">
@endpush

@section('content')
<div class="container mb-5" style="margin-top: 80px;">

    <div class="card card-custom overflow-hidden">
        <div class="address-border"></div>
        <div class="card-body p-4">
            <h5 class="text-shopee mb-3"><i class="fa-solid fa-location-dot me-2"></i> Địa Chỉ Nhận Hàng</h5>

            <div class="d-flex align-items-center justify-content-between" id="address-display-block">
                @if($addresses->isEmpty())
                <div class="text-muted">Bạn chưa có địa chỉ nhận hàng nào.</div>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addressModal">
                    <i class="fa-solid fa-plus me-1"></i> Thêm địa chỉ mới
                </button>
                @else
                @php $defaultAddress = $addresses->firstWhere('is_default', 1) ?? $addresses->first(); @endphp
                <div>
                    <strong class="me-3">{{ $defaultAddress->receiver_name }} ({{ $defaultAddress->receiver_phone }})</strong>
                    <span>{{ $defaultAddress->address_detail }}, {{ $defaultAddress->ward }}, {{ $defaultAddress->district }}, {{ $defaultAddress->province }}</span>
                    @if($defaultAddress->is_default)
                    <span class="badge border border-danger text-danger ms-2 rounded-0">Mặc Định</span>
                    @endif
                </div>
                <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#addressListModal">Thay Đổi</a>
                @endif
            </div>
        </div>
    </div>

    <div class="card card-custom p-4">
        <div class="row text-muted mb-3 d-none d-md-flex fw-bold">
            <div class="col-md-6">Sản phẩm</div>
            <div class="col-md-2 text-center">Đơn giá</div>
            <div class="col-md-2 text-center">Số lượng</div>
            <div class="col-md-2 text-end">Thành tiền</div>
        </div>

        @foreach($selectedItems as $item)
        <div class="row align-items-center mb-3">
            <div class="col-md-6 d-flex align-items-center">
                @php
                $primaryImage = $item->package->product->primaryImage;
                $imgUrl = $primaryImage ? asset('storage/' . $primaryImage->image_url) : asset('assets/img/default.png');
                @endphp
                <img src="{{ $imgUrl }}" alt="Product" class="me-3 border rounded" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <div class="text-truncate fw-medium">{{ $item->package->product->name }}</div>
                    <div class="text-muted" style="font-size: 0.85rem;">
                        Phân loại: {{ $item->package->packageType->type_name ?? '' }} - {{ $item->package->size }} {{ $item->package->unit }}
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-center">{{ number_format($item->package->price, 0, ',', '.') }}₫</div>
            <div class="col-md-2 text-center">{{ $item->quantity }}</div>
            <div class="col-md-2 text-end text-danger fw-medium">{{ number_format($item->package->price * $item->quantity, 0, ',', '.') }}₫</div>
        </div>
        @endforeach

        <hr class="text-muted opacity-25">

        <div class="row align-items-center">
            <div class="col-md-6 d-flex align-items-center">
                <span class="me-3 text-nowrap">Lời nhắn:</span>
                <input type="text" form="order-form" name="note" class="form-control form-control-sm w-75" placeholder="Lưu ý cho Người bán...">
            </div>
            <div class="col-md-6 text-end">
                <span class="me-4 text-muted">Phí vận chuyển:</span>
                <span>{{ number_format($shippingFee, 0, ',', '.') }}₫</span>
            </div>
        </div>
        <div class="text-end mt-3 border-top pt-3">
            <span class="text-muted me-3">Tổng số tiền ({{ $selectedItems->sum('quantity') }} sản phẩm):</span>
            <strong class="text-shopee fs-4">{{ number_format($totalAmount + $shippingFee, 0, ',', '.') }}₫</strong>
        </div>
    </div>

    <form id="order-form" action="/place-order" method="POST">
        @csrf
        @foreach($selectedItems as $item)
        <input type="hidden" name="cart_ids[]" value="{{ $item->id }}">
        @endforeach
        @if(!$addresses->isEmpty())
        <input type="hidden" name="address_id" id="checkout_address_id" value="{{ $defaultAddress->id }}">
        @endif

        <div class="card card-custom p-4 mb-0 border-bottom-0">
            <div class="d-flex align-items-center mb-3">
                <h6 class="mb-0 me-4 fw-bold">Phương thức thanh toán</h6>
                <input type="radio" class="btn-check" name="payment_method" id="paymentCOD" value="cod" checked>
                <label class="payment-method-btn" for="paymentCOD">Thanh toán khi nhận hàng</label>
                <input type="radio" class="btn-check" name="payment_method" id="paymentBank" value="bank_transfer">
                <label class="payment-method-btn" for="paymentBank">Chuyển khoản ngân hàng</label>
            </div>
        </div>

        <div class="card card-custom p-4 bg-light bg-opacity-50">
            <div class="row justify-content-end">
                <div class="col-md-4">
                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Tổng tiền hàng</span>
                        <span>{{ number_format($totalAmount, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 text-muted">
                        <span>Tổng tiền phí vận chuyển</span>
                        <span>{{ number_format($shippingFee, 0, ',', '.') }}₫</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-muted">Tổng thanh toán</span>
                        <strong class="text-shopee fs-2">{{ number_format($totalAmount + $shippingFee, 0, ',', '.') }}₫</strong>
                    </div>
                </div>
            </div>
            <div class="row border-top pt-4 mt-2">
                <div class="col-md-8 d-flex align-items-center text-muted" style="font-size: 0.9rem;">
                    Nhấn "Đặt hàng" đồng nghĩa với việc bạn đồng ý tuân theo <a href="#" class="text-decoration-none text-primary ms-1">Điều khoản của chúng tôi</a>
                </div>
                <div class="col-md-4 text-end">
                    <button type="submit" class="btn bg-shopee px-5 py-2 fs-5 w-100" style="max-width: 250px;" {{ $addresses->isEmpty() ? 'disabled' : '' }}>
                        Đặt hàng
                    </button>
                    @if($addresses->isEmpty())
                    <div class="text-danger mt-2" style="font-size: 0.85rem;">Vui lòng thêm địa chỉ nhận hàng!</div>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="addressListModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Địa Chỉ Của Tôi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                @foreach($addresses as $addr)
                <div class="p-3 border-bottom d-flex">
                    <div class="form-check w-100 d-flex align-items-start">
                        <input class="form-check-input mt-1 address-item-radio" type="radio" name="selected_address" id="addr_{{ $addr->id }}" value="{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }} data-json="{{ json_encode($addr) }}">

                        <label class="form-check-label w-100 ms-3 address-content" for="addr_{{ $addr->id }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold text-dark">{{ $addr->receiver_name }}</span>
                                    <span class="text-muted mx-1">|</span>
                                    <span class="text-muted">{{ $addr->receiver_phone }}</span>
                                </div>
                                <a href="#" class="text-decoration-none text-primary btn-open-update" data-json="{{ json_encode($addr) }}">Cập nhật</a>
                            </div>
                            <div class="text-muted mt-1" style="font-size: 0.9rem;">
                                {{ $addr->address_detail }}<br>
                                {{ $addr->ward }}, {{ $addr->district }}, {{ $addr->province }}
                            </div>
                            @if($addr->is_default)
                            <span class="badge border border-danger text-danger mt-2 rounded-0">Mặc Định</span>
                            @endif
                        </label>
                    </div>
                </div>
                @endforeach

                <div class="p-3">
                    <button type="button" class="btn btn-outline-danger w-100 py-2" data-bs-toggle="modal" data-bs-target="#addressModal">
                        <i class="fa-solid fa-plus me-1"></i> Thêm Địa Chỉ Mới
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn bg-shopee px-4" id="btn-confirm-address">Xác nhận</button>
            </div>
        </div>
    </div>
</div>

@include('user.components.modal_address_add', ['title' => 'Thêm địa chỉ', 'backToList' => true])

@include('user.components.modal_address_update', ['isCheckout' => true])

@endsection

@push('page_specific_js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        /* ===============================
           1. CHỌN ĐỊA CHỈ TỪ DANH SÁCH
        =============================== */

        document.getElementById('btn-confirm-address').addEventListener('click', function() {

            let selectedRadio = document.querySelector('input[name="selected_address"]:checked');

            if (!selectedRadio) return;

            let address = JSON.parse(selectedRadio.dataset.json);

            updateCheckoutUI(address);

            let modalEl = document.getElementById('addressListModal');
            let modalInstance = bootstrap.Modal.getInstance(modalEl);

            if (modalInstance) modalInstance.hide();

        });


        function updateCheckoutUI(address) {

            const addressBlock = document.getElementById('address-display-block');

            const defaultBadge = address.is_default ?
                '<span class="badge border border-danger text-danger ms-2 rounded-0">Mặc Định</span>' :
                '';

            addressBlock.innerHTML = `
            <div>
                <strong class="me-3">${address.receiver_name} (${address.receiver_phone})</strong>
                <span>${address.address_detail}, ${address.ward}, ${address.district}, ${address.province}</span>
                ${defaultBadge}
            </div>
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#addressListModal">Thay Đổi</a>
        `;

            let orderAddressInput = document.getElementById('checkout_address_id');

            if (orderAddressInput) orderAddressInput.value = address.id;
        }


        /* ===============================
           2. MỞ FORM CẬP NHẬT
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

                document.getElementById('updProvinceSelect').innerHTML =
                    `<option value="${address.province_id}" selected>${address.province}</option>`;

                document.getElementById('updDistrictSelect').innerHTML =
                    `<option value="${address.district_id}" selected>${address.district}</option>`;

                document.getElementById('updWardSelect').innerHTML =
                    `<option value="${address.ward_id}" selected>${address.ward}</option>`;

                document.getElementById('updProvinceName').value = address.province;
                document.getElementById('updDistrictName').value = address.district;
                document.getElementById('updWardName').value = address.ward;

                bootstrap.Modal.getInstance(document.getElementById('addressListModal')).hide();

                new bootstrap.Modal(document.getElementById('updateAddressModal')).show();

                initAddressAPI(
                    'updProvinceSelect',
                    'updDistrictSelect',
                    'updWardSelect',
                    'updProvinceName',
                    'updDistrictName',
                    'updWardName'
                );

            });

        });


        /* ===============================
           3. API TỈNH THÀNH
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

                            provinceSelect.innerHTML +=
                                `<option value="${p.id}" data-name="${p.full_name}" ${selected}>${p.full_name}</option>`;

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

                                districtSelect.innerHTML +=
                                    `<option value="${d.id}" data-name="${d.full_name}">${d.full_name}</option>`;

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

                                wardSelect.innerHTML +=
                                    `<option value="${w.id}" data-name="${w.full_name}">${w.full_name}</option>`;

                            });

                        });

                }

            });


            wardSelect.addEventListener('change', function() {

                nameW.value = this.options[this.selectedIndex].getAttribute('data-name');

            });

        }


        initAddressAPI(
            'addProvinceSelect',
            'addDistrictSelect',
            'addWardSelect',
            'addProvinceName',
            'addDistrictName',
            'addWardName'
        );


        /* ===============================
           4. AJAX SUBMIT
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

                            alert(
                                Object.values(data.errors)
                                .map(err => err.join('\n'))
                                .join('\n')
                            );

                        }

                    })

                    .catch(() => alert('Lỗi hệ thống'));

            });

        }

        handleFormSubmit('add-address-form');
        handleFormSubmit('update-address-form');


        /* ===============================
           5. FIX BACKDROP MODAL
        =============================== */

        document.addEventListener('hidden.bs.modal', function() {

            if (document.querySelectorAll('.modal.show').length === 0) {

                document.body.classList.remove('modal-open');

                document.body.style.overflow = '';

                document.body.style.paddingRight = '';

                document.querySelectorAll('.modal-backdrop')
                    .forEach(el => el.remove());

            }

        });

    });
</script>
@endpush