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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->enum('type', [
                'shipment',
                'invoice',
            ])->default('shipment');
            $table->string('name');
            $table->string('email')->nullable()->default(null);
            $table->string('country')->default('IT');
            $table->string('address');
            $table->string('city');
            $table->string('cap');
            $table->string('province');
            $table->string('phone')->nullable()->default(null);
            $table->string('tax_code')->nullable()->default(null);
            $table->string('vat_code')->nullable()->default(null);
            $table->string('sdi')->nullable()->default(null);
            $table->string('pec')->nullable()->default(null);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
