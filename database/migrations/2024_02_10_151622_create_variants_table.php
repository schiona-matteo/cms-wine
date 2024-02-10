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
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->string('code');
            $table->double('price');
            $table->float('weight')->default(0);
            $table->json('attributes');
            $table->boolean('available_for_order')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variants');
    }
};
