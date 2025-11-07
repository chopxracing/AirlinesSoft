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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('arrival');
            $table->string('departure');
            $table->string('flight_number');
            $table->unsignedBigInteger('aircraft_id');
            $table->unsignedBigInteger('flight_status_id')->default(1);
            $table->foreign('aircraft_id')->references('id')->on('aircraft');
            $table->datetime('departure_date')->nullable();
            $table->datetime('arrival_date')->nullable();
            $table->integer('flight_time')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('flight_status_id')->references('id')->on('flight_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
