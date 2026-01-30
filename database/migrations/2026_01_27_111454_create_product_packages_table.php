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
        Schema::create('product_packages', function (Blueprint $table) {
            $table->id('package_id');
            $table->foreignId('package_type_id')
                ->constrained('product_package_types', 'package_type_id')
                ->cascadeOnDelete();

            $table->decimal('size', 8, 2);
            $table->string('unit');
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_packages');
    }
};
