@extends('user.layouts.default')
@section('title', 'Địa chỉ của tôi')

@push('page_specific_css')
<link rel="stylesheet" href="{{ asset('assets/css/user/address_edit.css') }}">
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

    @include('user.components.modal_address_add')

    @include('user.components.modal_address_update')

</div>
@endsection
@push('page_specific_js')
<script src="{{ asset('assets/js/user/address_edit.js') }}"></script>
@endpush