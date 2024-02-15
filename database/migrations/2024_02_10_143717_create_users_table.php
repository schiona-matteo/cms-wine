<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->date('birthday')->nullable()->default(null);
            $table->foreignId('discount_id')->nullable()->default(null)->constrained();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('last_login_at')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->enum('type', [
                'admin',
                'operator',
                'user',
                'guest',
            ])->default('guest');

            $table->boolean('marketing_acceptance')->default(false);
            $table->enum('source', [
                'sito',
                'visita',
                'negozio',
                'manuale',
                'altro',
            ])->default('sito');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
