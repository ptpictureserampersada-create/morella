<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// portal_settings menyimpan pengaturan (paymentSettings, heroSliders, contactInfo,
// heroText) sebagai satu baris per key dengan value JSON.
// visit_logs mencatat pengunjung unik per bulan (waktu WIT).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_settings', function (Blueprint $table) {
            $table->string('key', 64)->primary();
            $table->json('value')->nullable();
        });

        Schema::create('visit_logs', function (Blueprint $table) {
            $table->id();
            $table->char('month', 7);
            $table->string('visitorId', 64);
            $table->timestamps();
            $table->unique(['month', 'visitorId']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_logs');
        Schema::dropIfExists('portal_settings');
    }
};
