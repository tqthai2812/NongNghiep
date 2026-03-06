@extends('admin.layouts.master')

@section('title', 'Thêm danh mục')

@push('styles')

<style>
    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
    }

    :root {
        --google-blue: #1a73e8;
        --border-color: #dadce0;
    }

    .btn-save {
        background-color: #0d4d2b;
        color: white;
        padding: 10px 30px;
        border: none;
    }

    .btn-save:hover {
        background-color: #0a3d22;
        color: white;
    }

    /* Floating label style */
    .form-floating>label {
        color: #757575;
        padding-left: 12px;
    }

    .form-control:focus {
        border-color: var(--google-blue);
        box-shadow: none;
        border-width: 2px;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
        color: var(--google-blue);
        opacity: 1;
    }

    .required-star {
        color: #d93025;
        margin-right: 4px;
    }

    .input-group-text {
        background: none;
        border: none;
        padding-left: 0;
        font-weight: 500;
        color: #3c4043;
        min-width: 200px;
        text-align: right;
    }

    .form-row {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
    }
</style>

@endpush

@section('content')

<form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">
                    Thông tin danh mục
                </h4>
                {{-- NAME --}}
                <div class="form-row">
                    <div class="input-group-text">
                        <span class="required-star">*</span>
                        Tên danh mục
                    </div>
                    <div class="form-floating flex-grow-1">
                        <input type="text"
                            name="name"
                            class="form-control"
                            placeholder="Tên danh mục"
                            value="{{ old('name') }}"
                            required>
                        <label>
                            Nhập tên danh mục
                        </label>
                        @error('name')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                        @enderror
                    </div>
                </div>
                {{-- DESCRIPTION --}}
                <div class="form-row">
                    <div class="input-group-text">
                        Mô tả
                    </div>
                    <div class="form-floating flex-grow-1">
                        <textarea name="description"
                            class="form-control"
                            placeholder="Mô tả danh mục"
                            style="height:120px">{{ old('description') }}</textarea>
                        <label>
                            Mô tả danh mục (không bắt buộc)
                        </label>
                        @error('description')
                        <small class="text-danger">
                            {{ $message }}
                        </small>
                        @enderror
                    </div>
                </div>
                {{-- BUTTON --}}
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.categories.index') }}"
                            class="btn btn-secondary me-2 shadow-sm p-2">
                            Huỷ
                        </a>
                        <button type="submit"
                            class="btn btn-save shadow-sm">
                            Thêm danh mục
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection