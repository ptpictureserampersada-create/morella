<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel kegiatan pengabdian masyarakat. Nama kolom memakai camelCase
// agar sama dengan konvensi MorelaStore (lihat create_content_tables).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengabdian_activities', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('title', 255)->default('');
            $table->string('titleEn', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('date', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('descriptionEn')->nullable();
            $table->text('imageUrl')->nullable();
            $table->boolean('published')->default(false);
            $table->integer('sort_order')->default(0)->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengabdian_activities');
    }
};
