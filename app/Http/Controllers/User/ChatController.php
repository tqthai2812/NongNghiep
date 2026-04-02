<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

            // =========================================================================
            // BƯỚC 1: GỌI API GEMINI LẦN 1 - ĐỂ TRÍCH XUẤT TỪ KHÓA TÌM KIẾM
            // =========================================================================
            $keywordPrompt = "Bạn là hệ thống phân tích ngữ nghĩa cho cửa hàng vật tư nông nghiệp.
            Nhiệm vụ: Tìm ra các từ khóa quan trọng (tên sản phẩm, loại phân bón, tên bệnh, loại cây trồng) trong câu nói của khách.
            Quy tắc BẮT BUỘC:
            - Chỉ trả về các từ khóa, cách nhau bằng dấu phẩy (,).
            - Tuyệt đối không giải thích, không dùng Markdown, không thêm text dư thừa.
            - Nếu câu nói chỉ là chào hỏi (alo, hi, hello) hoặc không liên quan đến nông nghiệp, hãy trả về đúng 1 chữ: NONE
            
            Câu nói của khách: \"{$userMessage}\"";

            $keywordResponse = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key={$apiKey}", [
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $keywordPrompt]]]
                ],
                // Ép temperature thấp để AI trả lời có tính quy luật, không sáng tạo lung tung
                'generationConfig' => [
                    'temperature' => 0.1
                ]
            ]);

            $keywords = [];
            if ($keywordResponse->successful()) {
                $keywordData = $keywordResponse->json();
                if (isset($keywordData['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiExtractedText = trim($keywordData['candidates'][0]['content']['parts'][0]['text']);

                    // Nếu AI trả về NONE, tức là không có từ khóa nông nghiệp
                    if (strtoupper($aiExtractedText) !== 'NONE') {
                        // Tách chuỗi thành mảng từ khóa, loại bỏ khoảng trắng thừa
                        $rawKeywords = explode(',', $aiExtractedText);
                        foreach ($rawKeywords as $kw) {
                            $kw = trim($kw);
                            if (strlen($kw) > 1) { // Bỏ qua các từ quá ngắn
                                $keywords[] = $kw;
                            }
                        }
                    }
                }
            }

            // =========================================================================
            // BƯỚC 2: TÌM KIẾM SẢN PHẨM TRONG DATABASE (Dựa trên từ khóa AI cung cấp)
            // =========================================================================
            $productsQuery = DB::table('Products')
                ->join('Product_Package_Types', 'Products.id', '=', 'Product_Package_Types.product_id')
                ->join('Product_Packages', 'Product_Package_Types.id', '=', 'Product_Packages.package_type_id')
                ->leftJoin('Product_Images', function ($join) {
                    $join->on('Products.id', '=', 'Product_Images.product_id')
                        ->where('Product_Images.is_primary', '=', 1);
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
                );

            // Nếu AI lọc ra được từ khóa, dùng nó để query
            if (!empty($keywords)) {
                $productsQuery->where(function ($query) use ($keywords) {
                    foreach ($keywords as $index => $word) {
                        // Tìm kiếm theo từng từ khóa AI đã bóc tách
                        if ($index === 0) {
                            $query->where('Products.name', 'LIKE', '%' . $word . '%')
                                ->orWhere('Products.description', 'LIKE', '%' . $word . '%');
                        } else {
                            $query->orWhere('Products.name', 'LIKE', '%' . $word . '%')
                                ->orWhere('Products.description', 'LIKE', '%' . $word . '%');
                        }
                    }
                });
            } else {
                // Nếu mảng keywords rỗng (khách gửi linh tinh/chào hỏi), ép query trả về rỗng để nhảy vào logic Random bên dưới
                $productsQuery->whereRaw('1 = 0');
            }

            // Lấy tối đa 5 sản phẩm khớp
            $products = $productsQuery->take(5)->get();

            // Nếu không tìm thấy hoặc khách chỉ chào hỏi -> Lấy 6 sản phẩm ngẫu nhiên làm mồi
            if ($products->isEmpty()) {
                $products = DB::table('Products')
                    ->join('Product_Package_Types', 'Products.id', '=', 'Product_Package_Types.product_id')
                    ->join('Product_Packages', 'Product_Package_Types.id', '=', 'Product_Packages.package_type_id')
                    ->leftJoin('Product_Images', function ($join) {
                        $join->on('Products.id', '=', 'Product_Images.product_id')
                            ->where('Product_Images.is_primary', '=', 1);
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
            }

            // =========================================================================
            // BƯỚC 3: FORMAT DATA VÀ TẠO PROMPT CHO LẦN GỌI CHÍNH
            // =========================================================================
            $productContext = "Danh sách sản phẩm hiện có (chỉ tư vấn các sản phẩm này):\n";
            $productMap = [];

            foreach ($products as $item) {
                $formattedPrice = number_format($item->price);
                $productContext .= "- {$item->name} (Quy cách: {$item->type_name} {$item->size} {$item->unit} - Giá: {$formattedPrice} VNĐ) - MÃ_SP: {$item->product_id}\n";
                $productMap[$item->product_id] = $item;
            }

            $systemPrompt = "Bạn là nhân viên tư vấn bán hàng vật tư nông nghiệp.
            Dựa vào danh sách sản phẩm sau đây để tư vấn cho khách.
            QUY TẮC BẮT BUỘC: 
            - Quy tắc chèn mã: Khi nhắc đến hoặc khuyên khách mua một sản phẩm, bạn PHẢI chèn đoạn mã [PRODUCT_ID: {MÃ_SP}] ngay sau tên sản phẩm đó.
            - Định dạng hiển thị: Mã [PRODUCT_ID: {MÃ_SP}] phải được đặt ở MỘT DÒNG RIÊNG BIỆT để hệ thống có thể nhận diện và hiển thị thành card sản phẩm đẹp mắt.
            - Số lần sử dụng: Mỗi sản phẩm chỉ được chèn mã đúng 1 LẦN DUY NHẤT trong toàn bộ câu trả lời. Từ lần nhắc lại thứ hai trở đi, chỉ gọi thẳng tên sản phẩm, tuyệt đối không chèn lại mã, rất quan trọng, không được sai.
            - Tuyệt đối không tự ý viết mã HTML hay tự bịa ra sản phẩm không có trong danh sách.
            - NẾU KHÁCH GỬI TỪ VÔ NGHĨA (vd: 'asd', '123', 'hello', 'haha') không liên quan đến nhu cầu mua sắm: Chỉ chào hỏi lịch sự và hỏi xem họ cần tư vấn vật tư nông nghiệp gì, không đưa sản phẩm ra làm cho khách cảm thấy áp lực.
            - NẾU KHÁCH THAN VÃN, BẤT ỔN TÂM LÝ (vd: 'tôi bị khùng', 'tôi mệt quá', 'chán đời'): Hãy thể hiện sự đồng cảm nhẹ nhàng, đưa ra một lời khuyên hoặc động viên ngắn gọn. NGAY SAU ĐÓ, khéo léo chuyển hướng câu chuyện bằng cách nhắc lại vai trò của bạn và hỏi xem họ có cần tư vấn gì không.
            - Nếu khách hỏi lịch sử trò chuyện hôm nay có được lưu lại không, hãy trả lời là không bạn không lưu lại lịch sử trò chuyện của khách, nhưng bạn luôn sẵn sàng giúp đỡ họ.
            " . $productContext;

            $chatHistory = session('ai_chat_history', []);
            $apiContents = $chatHistory;
            $apiContents[] = [
                'role' => 'user',
                'parts' => [['text' => $userMessage]]
            ];

            // =========================================================================
            // BƯỚC 4: GỌI API GEMINI LẦN 2 - ĐỂ LẤY CÂU TRẢ LỜI GIAO TIẾP VỚI KHÁCH
            // =========================================================================
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent?key={$apiKey}", [
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]]
                ],
                'contents' => $apiContents
            ]);


            if ($response->status() == 429) {
                return response()->json([
                    'reply' => 'Hệ thống AI đang bận do có quá nhiều lượt truy vấn. Bạn vui lòng đợi vài giây rồi thử lại nhé!'
                ]);
            }

            // Bóc tách kết quả
            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $reply = $data['candidates'][0]['content']['parts'][0]['text'];

                    // Cập nhật lịch sử chat
                    $chatHistory[] = [
                        'role' => 'user',
                        'parts' => [['text' => $userMessage]]
                    ];
                    $chatHistory[] = [
                        'role' => 'model',
                        'parts' => [['text' => $reply]]
                    ];

                    if (count($chatHistory) > 12) {
                        $chatHistory = array_slice($chatHistory, -12);
                    }
                    session(['ai_chat_history' => $chatHistory]);

                    $reply = str_replace('**', '', $reply);

                    // Biến đổi TAG thành HTML Card
                    $finalReply = preg_replace_callback('/\[PRODUCT_ID:\s*(\d+)\]/', function ($matches) use ($productMap) {
                        $id = $matches[1];
                        if (!isset($productMap[$id])) {
                            return '';
                        }

                        $item = $productMap[$id];
                        $formattedPrice = number_format($item->price);
                        $imgUrl = $item->image_url ? asset('storage/' . $item->image_url) : 'https://via.placeholder.com/80?text=No+Image';
                        $detailUrl = route('user.product.detail', ['id' => $id]);

                        return "<div class='ai-product-card' style='display:flex; gap:10px; background:#fff; border:1px solid #28a745; border-radius:8px; padding:8px; margin-top:8px; margin-bottom:8px;'><img src='{$imgUrl}' style='width:60px; height:60px; object-fit:cover; border-radius:4px;'><div style='flex:1;'><strong style='color:#28a745; font-size:13px; display:block;'>{$item->name}</strong><span style='color:#e53935; font-weight:bold; font-size:13px;'>{$formattedPrice} đ</span><br><a href='{$detailUrl}' style='display:inline-block; margin-top:4px; font-size:11px; background:#28a745; color:white; padding:3px 10px; border-radius:12px; text-decoration:none;'>Xem chi tiết</a></div></div>";
                    }, $reply);

                    return response()->json(['reply' => $finalReply]);
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
