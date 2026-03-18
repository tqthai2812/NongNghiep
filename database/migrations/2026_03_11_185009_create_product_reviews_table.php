<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $blueprint) {
            $blueprint->id();

            // Khóa ngoại liên kết
            $blueprint->foreignId('package_id')->constrained('product_packages')->onDelete('cascade');
            $blueprint->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $blueprint->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            $blueprint->tinyInteger('rating')->default(5);
            $blueprint->text('comment')->nullable();

            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
