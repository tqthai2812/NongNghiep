@extends('admin.layouts.master')

@section('title', 'Sửa danh mục')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/categories/edit.css') }}">
@endpush

@section('content')

<form action="{{ route('admin.categories.update', $category->id) }}" method="POST" novalidate>
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="card p-4">
                <h4 class="card-title mb-4">
                    Sửa danh mục
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
                            value="{{ $category->name }}"
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
                            style="height:120px">{{ $category->description }}</textarea>
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
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection