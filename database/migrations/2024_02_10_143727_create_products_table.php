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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('vino');
            $table->string('category')->nullable()->default(null);
            $table->string('link_rewrite');
            $table->string('name');
            $table->string('subtitle_it');
            $table->string('subtitle_en');
            $table->text('description_it');
            $table->text('description_en');
            $table->json('settings');
            $table->boolean('available_for_order')->default(true);
            $table->unsignedSmallInteger('limited_buy')->nullable()->default(null);
            $table->unsignedTinyInteger('limited_buy_for_user')->nullable()->default(null);
            $table->boolean('discountable')->default(true);
            $table->boolean('prevent_bankwire')->default(false);
            $table->boolean('is_virtual')->default(true);
            $table->json('meta_data');
            $table->enum('visibility', [
                'public',
                'logged_in',
                'only_manual',
            ])->default('public');
            $table->timestamps();
            $table->softDeletes();
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
