<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('source', [
                'negozio', 'sito', 'manuale',
            ])->default('sito');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('discount_id')->nullable()->default(null);
            $table->string('reference');
            $table->double('shipment_price')->default(0);
            $table->double('product_price')->default(0);
            $table->double('duties_price')->default(0);
            $table->double('discount_amount')->default(0);
            $table->unsignedTinyInteger('tax_rate')->default(22);
            $table->double('final_price_without_tax')->default(0);
            $table->double('final_price_with_tax')->default(0);
            $table->boolean('pickup_at_store')->default(false);
            $table->boolean('should_be_shipped')->default(true);
            $table->string('payment_method');
            $table->unsignedTinyInteger('status')->default(0);
            $table->string('token');
            $table->enum('lang', [
                'it', 'en',
            ])->default('it');
            $table->boolean('should_be_invoiced')->default(true);
            $table->string('external_invoice_id')->nullable()->default(null);
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
