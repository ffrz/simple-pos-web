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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedTinyInteger('costing_method')->default(0);
            $table->string('name')->unique();
            $table->boolean('active')->default(false);
            $table->unsignedBigInteger('stock')->default(0);
            $table->string('uom')->default('');
            $table->string('description')->default('');
            $table->text('notes')->default('');
            $table->decimal('cost')->default(0);
            $table->decimal('price')->default(0);
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('last_supplier_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('parties')->onDelete('set null');
            $table->foreign('last_supplier_id')->references('id')->on('parties')->onDelete('set null');
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
