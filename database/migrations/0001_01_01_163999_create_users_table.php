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
            $table->string('username');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('name');
            $table->string('surname');
            $table->string('idoc_series');
            $table->string('idoc_number');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('clearance_id');
            $table->integer('time_in_air')->default(0);
            $table->integer('time_out_air')->default(0);
            $table->string('medicial_number')->nullable();
            $table->date('medicial_to')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_to')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('clearance_id')->references('id')->on('clearances');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreign('status_id')->references('id')->on('crew_statuses');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
