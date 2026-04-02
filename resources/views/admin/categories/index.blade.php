@extends('admin.layouts.master')

@section('title', 'Danh sách danh mục')

@section('page_specific_css')
<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/admin/categories/index.css') }}">
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mt-3" role="alert" style="background-color: #fdeaea; color: #d93025; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="material-icons me-2">error_outline</i>
                <span class="fw-bold">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('success'))
        <div class=" alert alert-success alert-dismissible fade show border-0 shadow-sm mt-3" role="alert" style="background-color: #e6f4ea; color: #1e8e3e; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="material-icons me-2">check_circle_outline</i>
                <span class="fw-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="card card-plain">

            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title text-dark fw-bold">
                    Danh sách danh mục
                </h4>

                <a href="{{ route('admin.categories.create') }}" class="btn btn-success d-flex align-items-center">
                    <i class="material-icons me-1">add</i>
                    Thêm danh mục
                </a>
            </div>

            <div class="card-body">

                <div class="row g-2 mb-3">

                    <div class="col-md-4">
                        <input type="text"
                            id="customSearch"
                            class="form-control"
                            placeholder="Tìm kiếm danh mục...">
                    </div>

                    <div class="col-md-2">
                        <select id="changeLength" class="form-select">
                            <option value="5">5 dòng</option>
                            <option value="10" selected>10 dòng</option>
                            <option value="25">25 dòng</option>
                        </select>
                    </div>

                </div>

                <div class="table-responsive">

                    <table id="categoryTable"
                        class="table table-hover align-middle">

                        <thead class="text-secondary bg-light">
                            <tr>
                                <th width="60">ID</th>
                                <th>Danh mục</th>
                                <th class="text-center">Mô tả</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>

                            {{-- Demo data --}}

                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            {{ $category->name }}
                                            <div class="text-muted small">
                                                15 sản phẩm
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ $category->description }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="text-primary me-2">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                        method="POST"
                                        style="display:inline-block;"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            style="border:none;background:none;padding:0;margin:0;"
                                            class="text-danger">
                                            <i class="material-icons">delete_outline</i>
                                        </button>

                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="pagination-container"
                    class="datatable-footer">
                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.8/r-3.0.2/sp-2.3.1/datatables.min.js"></script>
<script src="{{ asset('assets/js/admin/categories/index.js') }}"></script>
@endpush