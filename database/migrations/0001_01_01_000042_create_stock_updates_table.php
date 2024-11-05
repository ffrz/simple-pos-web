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
        Schema::create('stock_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id2')->nullable()->default(null);
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedBigInteger('party_id')->nullable()->default(null);
            $table->string('party_name')->default('');
            $table->string('party_phone')->default('');
            $table->string('party_address')->default('');
            $table->datetime('datetime')->nullable()->default(null);
            $table->datetime('created_datetime')->nullable()->default(null);
            $table->datetime('closed_datetime')->nullable()->default(null);
            $table->datetime('updated_datetime')->nullable()->default(null);
            $table->unsignedBigInteger('created_by_uid')->nullable()->default(null);
            $table->unsignedBigInteger('closed_by_uid')->nullable()->default(null);
            $table->unsignedBigInteger('updated_by_uid')->nullable()->default(null);
            $table->decimal('total_tax', 12, 0)->default(0.);
            $table->decimal('total_discount', 12, 0)->default(0.);
            $table->decimal('total_cost', 12, 0)->default(0.);
            $table->decimal('total_price', 12, 0)->default(0.);
            $table->decimal('total', 12, 0)->default(0.);
            $table->decimal('total_receivable', 12, 0)->default(0.);
            $table->text('notes')->nullable()->default(null);
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('set null');
            $table->foreign('created_by_uid')->references('id')->on('users')->onDelete('set null');
            $table->foreign('closed_by_uid')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by_uid')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_updates');
    }
};
