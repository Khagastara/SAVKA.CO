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
        Schema::create('history_demands', function (Blueprint $table) {
            $table->id();
            $table->integer('week_number');
            $table->integer('month');
            $table->integer('year');
            $table->integer('demand_quantity');

            $table->unsignedBigInteger('shipment_id');
            $table->unsignedBigInteger('forecasting_id');

            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
            $table->foreign('forecasting_id')->references('id')->on('forecastings')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_demands');
    }
};
