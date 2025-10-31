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
        Schema::create('forecastings', function (Blueprint $table) {
            $table->id();
            $table->date('forecast_date');
            $table->integer('week_used');
            $table->integer('predicted_demand');
            $table->integer('accurancy');

            $table->unsignedBigInteger('history_demand_id');
            $table->foreign('history_demand_id')->references('id')->on('history_demands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasting');
    }
};
