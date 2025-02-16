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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade'); 

            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade'); 
            $table->string('product_price');
            $table->decimal('quantity', 10, 2);
            $table->string('total_price');
            $table->enum('status', ['pending', 'completed', 'canceled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
