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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('surname', 32);
            $table->string('name', 32);
            $table->string('username', 64);
            $table->string('password');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('api_token')->nullable()->unique();
            $table->foreignId('role_id')->constrained('roles', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
