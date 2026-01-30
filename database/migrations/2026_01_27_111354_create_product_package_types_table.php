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
        Schema::create('product_package_types', function (Blueprint $table) {
            $table->id('package_type_id');
            $table->foreignId('product_id')
                ->constrained('products', 'product_id')
                ->cascadeOnDelete();

            $table->string('type_name'); // Bao, Chai, Gói
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_package_types');
    }
};
