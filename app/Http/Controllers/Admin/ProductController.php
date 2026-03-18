<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductPackageType;
use App\Models\ProductPackage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Models\InventoryTransaction; // Import model InventoryTransaction
use Illuminate\Support\Facades\Auth; // Import Auth để lấy ID người dùng hiện tại

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with([
            'category',
            'primaryImage',
            'packageTypes.packages'
        ])
            ->latest()
            ->get();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        try {

            // 1️⃣ Tạo product
            $product = Product::create([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'brand' => $request->brand,
                'description' => $request->description,
            ]);

            // 2️⃣ Lưu hình ảnh
            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $image) {

                    if (!$image) continue;

                    $path = $image->store('products', 'public');

                    $product->images()->create([
                        'image_url' => $path,
                        'is_primary' => false,
                    ]);
                }

                // Sau khi lưu xong → set ảnh đầu tiên làm primary
                $product->images()->first()->update([
                    'is_primary' => true
                ]);
            }

            // 3️⃣ Tạo package type
            $packageType = $product->packageTypes()->create([
                'type_name' => $request->package_type_name
            ]);

            // 4️⃣ Tạo packages (size + price + stock)
            foreach ($request->packages as $packageData) {
                $stockInitial = $packageData['stock'] ?? 0;

                $packageType->packages()->create([
                    'size' => $packageData['size'],
                    'unit' => $request->package_type_unit,
                    'price' => $packageData['price'] ?? 0,
                    'stock' => $packageData['stock'] ?? 0,
                ]);

                if ($stockInitial > 0) {
                    InventoryTransaction::create([
                        'package_id' => $packageType->packages()->latest()->first()->id,
                        'user_id'    => Auth::id(),
                        'type'       => 'in',
                        'quantity'   => $stockInitial,
                        'reason'     => 'Khởi tạo sản phẩm mới',
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Thêm sản phẩm thành công');
        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
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
    public function edit(Product $product)
    {
        $product->load([
            'images',
            'packageTypes.packages',
            'category'
        ]);

        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        DB::transaction(function () use ($request, $product) {
            // 1. Cập nhật thông tin cơ bản Product
            $product->update([
                'name' => $request->name,
                'brand' => $request->brand,
                'description' => $request->description,
                'category_id' => $request->category_id,
            ]);

            // 2. Xử lý hình ảnh (Giữ nguyên logic của bạn)
            $oldImageIds = $request->old_images ?? [];
            $product->images()->whereNotIn('id', $oldImageIds)->get()->each(function ($img) {
                Storage::disk('public')->delete($img->image_url);
                $img->delete();
            });

            if ($request->hasFile('images')) {
                $currentCount = $product->images()->count();
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'image_url' => $path,
                        'is_primary' => $currentCount === 0 && $index === 0,
                        'sort_order' => $currentCount + $index
                    ]);
                }
            }

            // 3. ===== CẬP NHẬT PACKAGE KHÔNG DÙNG LỆNH DELETE =====
            $packageType = $product->packageTypes()->first();
            if (!$packageType) {
                $packageType = $product->packageTypes()->create(['type_name' => $request->package_type_name]);
            } else {
                $packageType->update(['type_name' => $request->package_type_name]);
            }

            if ($request->packages) {
                $keepPackageIds = [];

                foreach ($request->packages as $pkgData) {
                    $oldPackage = $packageType->packages()->where('size', $pkgData['size'])->first();
                    $oldStock = $oldPackage ? $oldPackage->stock : 0;
                    $newStock = $pkgData['stock'] ?? 0;
                    // Kiểm tra xem package này đã tồn tại chưa dựa trên 'size'
                    // Hoặc nếu bạn có truyền 'id' của package từ form edit thì dùng ID sẽ chuẩn hơn
                    $package = $packageType->packages()->updateOrCreate(
                        ['size' => $pkgData['size']], // Điều kiện tìm kiếm
                        [
                            'unit' => $request->package_type_unit,
                            'price' => $pkgData['price'],
                            'stock' => $pkgData['stock']
                        ]
                    );

                    // Nếu có sự thay đổi về số lượng tồn kho -> Ghi log Adjust
                    if ($oldStock != $newStock) {
                        InventoryTransaction::create([
                            'package_id' => $package->id,
                            'user_id'    => Auth::id(),
                            'type'       => 'adjust',
                            'quantity'   => $newStock - $oldStock, // Số dương là tăng, số âm là giảm
                            'reason'     => 'Cập nhật sản phẩm (Điều chỉnh thủ công)',
                        ]);
                    }
                    $keepPackageIds[] = $package->id;
                }

                // Xóa những package cũ không còn nằm trong danh sách cập nhật 
                // và KHÔNG có trong giỏ hàng (hoặc dùng Soft Delete)
                $packageType->packages()
                    ->whereNotIn('id', $keepPackageIds)
                    ->each(function ($oldPkg) {
                        // Kiểm tra nếu có trong giỏ hàng thì không xóa, chỉ ẩn hoặc giữ lại
                        $existsInCart = DB::table('cart')->where('package_id', $oldPkg->id)->exists();
                        if (!$existsInCart) {
                            $oldPkg->delete();
                        }
                    });
            }
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {

            // 1. Xóa file ảnh
            foreach ($product->images as $img) {
                if ($img->image_url && Storage::disk('public')->exists($img->image_url)) {
                    Storage::disk('public')->delete($img->image_url);
                }
            }

            // 2. Xóa images DB
            $product->images()->delete();

            // 3. Xóa package types + packages
            foreach ($product->packageTypes as $type) {
                $type->packages()->delete();
            }

            $product->packageTypes()->delete();

            // 4. Xóa product
            $product->delete();
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công');
    }
}
