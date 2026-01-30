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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('transaction_id');
            $table->foreignId('package_id')
                ->constrained('product_packages', 'package_id');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users', 'user_id');

            $table->string('type'); // in, out, adjust
            $table->integer('quantity');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
