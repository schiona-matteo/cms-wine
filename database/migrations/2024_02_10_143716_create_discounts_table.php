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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', [
                'fixed',
                'percentage',
                'shipment',
            ])->default('percentage');
            $table->unsignedInteger('usage_limit')->nullable()->default(null);
            $table->unsignedInteger('usage_limit_user')->nullable()->default(null);
            $table->unsignedInteger('count')->default(0);
            $table->datetime('expires_at')->nullable()->default(null);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
