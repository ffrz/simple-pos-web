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
            $table->unsignedBigInteger('category_id')->nullable()->default(null);
            $table->unsignedBigInteger('supplier_id')->nullable()->default(null);
            $table->unsignedTinyInteger('type')->default(0);
            $table->boolean('active')->default(true);
            $table->string('code')->unique();
            $table->string('description')->default('');
            $table->string('barcode')->default('');
            $table->integer('stock')->default(0);
            $table->integer('minimum_stock')->default(0);
            $table->string('uom')->default('');
            $table->decimal('cost', 12, 0)->default(0.);
            $table->decimal('price', 12, 0)->default(0.);
            $table->text('notes')->nullable(true)->default(null);
            $table->datetime('created_datetime')->nullable()->default(null);
            $table->datetime('updated_datetime')->nullable()->default(null);
            $table->unsignedBigInteger('created_by_uid')->nullable()->default(null);
            $table->unsignedBigInteger('updated_by_uid')->nullable()->default(null);
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('parties')->onDelete('set null');
            $table->foreign('created_by_uid')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_uid')->references('id')->on('users')->onDelete('set null');
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
