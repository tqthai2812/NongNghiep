@props(['id' => 'addressModal', 'title' => 'Thêm địa chỉ mới', 'backToList' => false])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">{{ $title }}</h5>
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
                        @if($backToList)
                        {{-- Trường hợp 1: Quay lại danh sách Modal cũ --}}
                        <button type="button" class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#addressListModal">Trở Lại</button>
                        @else
                        {{-- Trường hợp 2: Chỉ đóng Modal --}}
                        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal">Trở Lại</button>
                        @endif

                        <button type="submit" class="btn bg-shopee px-4 text-white">Hoàn thành</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>