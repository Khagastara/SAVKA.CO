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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->date('shipment_date');
            $table->string('destination_address');
            $table->integer('total_price');
            $table->enum('shipment_status', ['Dalam Pengiriman', 'Sampai Tujuan'])->default('Dalam Pengiriman');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('history_demand_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('history_demand_id')->references('id')->on('history_demands')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
