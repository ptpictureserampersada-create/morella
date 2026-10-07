<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE destinations MODIFY imageUrl LONGTEXT');
        DB::statement('ALTER TABLE destinations MODIFY galleryImages LONGTEXT');
        
        DB::statement('ALTER TABLE umkm_products MODIFY imageUrl LONGTEXT');
        DB::statement('ALTER TABLE news_articles MODIFY imageUrl LONGTEXT');
        DB::statement('ALTER TABLE events MODIFY imageUrl LONGTEXT');
        DB::statement('ALTER TABLE culture_items MODIFY imageUrl LONGTEXT');
        DB::statement('ALTER TABLE gallery_items MODIFY imageUrl LONGTEXT');
        DB::statement('ALTER TABLE team_members MODIFY photoUrl LONGTEXT');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE destinations MODIFY imageUrl TEXT');
        DB::statement('ALTER TABLE destinations MODIFY galleryImages TEXT');
        
        DB::statement('ALTER TABLE umkm_products MODIFY imageUrl TEXT');
        DB::statement('ALTER TABLE news_articles MODIFY imageUrl TEXT');
        DB::statement('ALTER TABLE events MODIFY imageUrl TEXT');
        DB::statement('ALTER TABLE culture_items MODIFY imageUrl TEXT');
        DB::statement('ALTER TABLE gallery_items MODIFY imageUrl TEXT');
        DB::statement('ALTER TABLE team_members MODIFY photoUrl TEXT');
    }
};
