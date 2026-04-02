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