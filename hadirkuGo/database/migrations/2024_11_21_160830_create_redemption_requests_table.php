<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedemptionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('redemption_requests', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->unsignedBigInteger('user_id'); // User who made the request
            $table->unsignedBigInteger('product_id'); // Product being redeemed
            $table->enum('status', ['pending', 'waiting_list', 'approved', 'rejected'])->default('pending');
            $table->timestamp('requested_at')->useCurrent(); // Time of request
            $table->timestamp('updated_at')->nullable(); // Time of last update

            // Foreign key constraints
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');

            // Indexes for performance (optional but recommended)
            $table->index('user_id');
            $table->index('product_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('redemption_requests');
    }
}
