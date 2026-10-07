<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Waktu (createdAt/paidAt/checkedInAt) tetap disimpan sebagai string berformat
// ("07/10/2026, 14.30 WIT") agar identik dengan data & tampilan lama.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('bookingCode', 64)->index();
            $table->string('destinationId', 64)->nullable();
            $table->string('destinationName', 255)->nullable();
            $table->string('destinationLocation', 255)->nullable();
            $table->string('visitDate', 64)->nullable();
            $table->string('visitorName', 255)->nullable();
            $table->string('visitorEmail', 255)->nullable();
            $table->string('visitorPhone', 64)->nullable();
            $table->string('visitorCity', 255)->nullable();
            $table->integer('adultCount')->default(0);
            $table->integer('childCount')->default(0);
            $table->integer('pricePerTicket')->default(0);
            $table->integer('cleanlinessFee')->default(0);
            $table->integer('totalAmount')->default(0);
            $table->string('paymentMethod', 64)->nullable();
            $table->string('paymentStatus', 32)->default('pending')->index();
            $table->string('createdAt', 64)->nullable();
            $table->string('paidAt', 64)->nullable();
            $table->string('checkedInAt', 64)->nullable();
            $table->string('qrValidationCode', 64)->nullable();
            $table->integer('sort_order')->default(0)->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
