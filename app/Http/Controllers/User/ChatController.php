<?php

namespace App\Http\Controllers\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Nhớ import DB facade
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function ask(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                return response()->json(['reply' => 'Lỗi: Chưa cấu hình GEMINI_API_KEY'], 500);
            }

            // 1. Join thêm bảng Product_Images để lấy hình ảnh
            $products = DB::table('Products')
                ->join('Product_Package_Types', 'Products.id', '=', 'Product_Package_Types.product_id')
                ->join('Product_Packages', 'Product_Package_Types.id', '=', 'Product_Packages.package_type_id')
                ->leftJoin('Product_Images', function ($join) {
                    $join->on('Products.id', '=', 'Product_Images.product_id')
                        ->where('Product_Images.is_primary', '=', 1); // Lấy ảnh chính
                })
                ->select(
                    'Products.id as product_id',
                    'Products.name',
                    'Products.description',
                    'Product_Package_Types.type_name',
                    'Product_Packages.size',
                    'Product_Packages.unit',
                    'Product_Packages.price',
                    'Product_Images.image_url'
                )
                ->inRandomOrder()
                ->take(6)
                ->get();

            // 2. Format dữ liệu và tạo "Mã HTML giao diện" cho từng sản phẩm
            $productContext = "Danh sách sản phẩm hiện có:\n";

            foreach ($products as $item) {
                $formattedPrice = number_format($item->price);

                // Xử lý link ảnh (nếu ko có ảnh thì dùng ảnh mặc định)
                $imgUrl = $item->image_url ? asset('storage/' . $item->image_url) : 'https://via.placeholder.com/80?text=No+Image';

                // Tạo link đến trang chi tiết sản phẩm dựa theo route name của bạn
                $detailUrl = route('user.product.detail', ['id' => $item->product_id]);

                // Tạo một đoạn HTML Card sản phẩm thu nhỏ
                $htmlCard = "<div style='display:flex; gap:10px; background:#fff; border:1px solid #28a745; border-radius:8px; padding:8px; margin-top:8px;'><img src='{$imgUrl}' style='width:60px; height:60px; object-fit:cover; border-radius:4px;'><div style='flex:1;'><strong style='color:#28a745; font-size:13px; display:block;'>{$item->name}</strong><span style='color:#e53935; font-weight:bold; font-size:13px;'>{$formattedPrice} đ</span><br><a href='{$detailUrl}' style='display:inline-block; margin-top:4px; font-size:11px; background:#28a745; color:white; padding:3px 10px; border-radius:12px; text-decoration:none;'>Xem chi tiết</a></div></div>";

                // Ép HTML này vào làm dữ liệu ngữ cảnh cho AI
                $productContext .= "- Tên: {$item->name} (Quy cách: {$item->type_name} {$item->size} {$item->unit} - Giá: {$formattedPrice} VNĐ).\n  MÃ_GIAO_DIỆN: {$htmlCard}\n\n";
            }

            // 3. System Prompt: Ra lệnh cho AI phải dùng MÃ_GIAO_DIỆN
            $systemPrompt = "Bạn là nhân viên tư vấn bán hàng vật tư nông nghiệp.
            Dựa vào danh sách sản phẩm sau đây để tư vấn cho khách.
            QUY TẮC BẮT BUỘC: Khi bạn khuyên khách hàng mua hoặc nhắc đến một sản phẩm nào đó trong danh sách, bạn PHẢI copy y nguyên đoạn 'MÃ_GIAO_DIỆN' của sản phẩm đó và dán vào cuối câu trả lời của bạn. Tuyệt đối không tự chế ra mã HTML.
            
            " . $productContext;

            // 4. Gọi API
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nKhách hàng hỏi: " . $userMessage]
                        ]
                    ]
                ]
            ]);

            // 5. Trả kết quả
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'];
                    $reply = str_replace('**', '', $reply);
                    return response()->json(['reply' => $reply]);
                }
            }

            Log::error('Gemini API Error: ' . $response->body());
            return response()->json(['reply' => 'Lỗi từ Google API: ' . $response->body()], 500);
        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Lỗi Code Laravel: ' . $e->getMessage() . ' (Dòng ' . $e->getLine() . ')'
            ], 500);
        }
    }
}
