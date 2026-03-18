<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Thông tin người nhận
            $table->string('receiver_name');
            $table->string('receiver_phone', 20);

            // Địa chỉ phân cấp (Lưu tên để hiển thị nhanh trên web mà không cần gọi lại API)
            $table->string('province'); // Tỉnh/Thành phố
            $table->string('district'); // Quận/Huyện
            $table->string('ward');     // Phường/Xã

            // Mã code hành chính (Lưu dạng string vì mã VN có số 0 ở đầu)
            $table->string('province_id', 10)->nullable();
            $table->string('district_id', 10)->nullable();
            $table->string('ward_id', 10)->nullable();

            // Địa chỉ cụ thể (Số nhà, tên đường)
            $table->string('address_detail');

            // Tọa độ bản đồ (Phục vụ cho tính năng Google Map trong giao diện)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Phân loại: 'home' - Nhà riêng, 'office' - Văn phòng
            $table->string('address_type', 20)->default('home');

            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
