<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Tabel konten portal. Nama kolom sengaja memakai camelCase agar sama persis
// dengan key array/JSON lama (MorelaStore), sehingga view & controller tidak perlu berubah.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destinations', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('slug', 255)->default('');
            $table->string('name', 255)->default('');
            $table->string('nameEn', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('tagline', 255)->nullable();
            $table->string('taglineEn', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('descriptionEn')->nullable();
            $table->string('location', 255)->nullable();
            $table->json('coordinates')->nullable();
            $table->string('visitingHours', 255)->nullable();
            $table->string('ticketPrice', 255)->nullable();
            $table->integer('ticketPriceNum')->default(0);
            $table->json('facilities')->nullable();
            $table->string('contactName', 255)->nullable();
            $table->string('contactPhone', 255)->nullable();
            $table->text('imageUrl')->nullable();
            $table->json('galleryImages')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('published')->default(false);
            $table->integer('sort_order')->default(0)->index();
        });

        Schema::create('umkm_products', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('name', 255)->default('');
            $table->string('nameEn', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->integer('price')->default(0);
            $table->string('priceFormatted', 255)->nullable();
            $table->string('unit', 64)->nullable();
            $table->string('sellerName', 255)->nullable();
            $table->string('sellerGroup', 255)->nullable();
            $table->string('sellerPhone', 255)->nullable();
            $table->string('sellerAddress', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('descriptionEn')->nullable();
            $table->text('imageUrl')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('inStock')->default(false);
            $table->boolean('published')->default(false);
            $table->integer('sort_order')->default(0)->index();
        });

        Schema::create('news_articles', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('slug', 255)->default('');
            $table->string('title', 255)->default('');
            $table->string('titleEn', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('author', 255)->nullable();
            $table->string('authorRole', 255)->nullable();
            $table->string('publishedDate', 255)->nullable();
            $table->string('readTime', 64)->nullable();
            $table->text('summary')->nullable();
            $table->text('summaryEn')->nullable();
            $table->longText('content')->nullable();
            $table->longText('contentEn')->nullable();
            $table->text('imageUrl')->nullable();
            $table->boolean('published')->default(false);
            $table->integer('sort_order')->default(0)->index();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('title', 255)->default('');
            $table->string('titleEn', 255)->nullable();
            $table->string('date', 255)->nullable();
            $table->string('time', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('organizer', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('descriptionEn')->nullable();
            $table->string('status', 64)->nullable();
            $table->text('imageUrl')->nullable();
            $table->string('videoUrl', 255)->nullable();
            $table->string('contactPerson', 255)->nullable();
            $table->integer('sort_order')->default(0)->index();
        });

        Schema::create('culture_items', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('slug', 255)->default('');
            $table->string('title', 255)->default('');
            $table->string('titleEn', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->text('summary')->nullable();
            $table->text('summaryEn')->nullable();
            $table->longText('content')->nullable();
            $table->longText('contentEn')->nullable();
            $table->string('periodOrTime', 255)->nullable();
            $table->text('historicalSignificance')->nullable();
            $table->text('imageUrl')->nullable();
            $table->json('tags')->nullable();
            $table->boolean('published')->default(false);
            $table->integer('sort_order')->default(0)->index();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('title', 255)->default('');
            $table->string('titleEn', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('photographer', 255)->nullable();
            $table->string('dateTaken', 255)->nullable();
            $table->text('imageUrl')->nullable();
            $table->integer('sort_order')->default(0)->index();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('name', 255)->default('');
            $table->string('role', 255)->nullable();
            $table->string('department', 255)->nullable();
            $table->string('university', 255)->nullable();
            $table->text('photoUrl')->nullable();
            $table->text('bio')->nullable();
            $table->text('contribution')->nullable();
            $table->integer('sort_order')->default(0)->index();
        });

        Schema::create('map_markers', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('title', 255)->default('');
            $table->string('type', 64)->nullable();
            $table->string('categoryLabel', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('mapX')->default(0);
            $table->integer('mapY')->default(0);
            $table->string('phone', 255)->nullable();
            $table->float('rating')->nullable();
            $table->integer('sort_order')->default(0)->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_markers');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('culture_items');
        Schema::dropIfExists('events');
        Schema::dropIfExists('news_articles');
        Schema::dropIfExists('umkm_products');
        Schema::dropIfExists('destinations');
    }
};
