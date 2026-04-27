<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('name'); // Product name
            $table->string('product_code')->unique(); // Unique product code
            $table->text('description')->nullable(); // Product description
            $table->string('image')->nullable(); // Image path or URL
            $table->unsignedInteger('stock_quantity')->default(0); // Available stock
            $table->unsignedInteger('points_required'); // Points required to redeem
            $table->enum('status', ['ready', 'waiting_list'])->default('ready'); // Product status
            $table->unsignedBigInteger('owner_id'); // Owner of the product (user_id)
            $table->unsignedBigInteger('business_id'); // Business ID
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints
            $table->foreign('owner_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('business_id')
                ->references('id')
                ->on('businesses')
                ->onDelete('cascade');

            // Indexes for faster queries
            $table->index('status');
            $table->index('business_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
