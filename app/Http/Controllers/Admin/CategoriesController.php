<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create(
            $request->validated()
        );

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được cập nhật thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Kiểm tra chủ động (Dễ đọc, thông báo rõ ràng)
        if ($category->products()->exists()) {
            return back()->with('error', 'Danh mục này đang chứa ' . $category->products()->count() . ' sản phẩm, không thể xóa!');
        }

        try {
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công.');
        } catch (\Exception $e) {
            // Phòng hờ các lỗi hệ thống khác (vd: mất kết nối DB)
            return back()->with('error', 'Có lỗi hệ thống xảy ra, vui lòng thử lại sau.');
        }
    }
}
