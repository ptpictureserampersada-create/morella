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
        Schema::create('social_media_links', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('platform_name');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
        });

        // Migrate existing data
        $setting = DB::table('portal_settings')->where('key', 'socialMedia')->first();
        if ($setting && !empty($setting->value)) {
            $data = json_decode($setting->value, true);
            if (is_array($data)) {
                $sort = 0;
                foreach (['instagram', 'youtube', 'facebook', 'whatsapp'] as $key) {
                    if (!empty($data[$key])) {
                        DB::table('social_media_links')->insert([
                            'id' => 'sm-' . uniqid(),
                            'platform_name' => ucfirst($key),
                            'url' => $data[$key],
                            'icon' => $key === 'whatsapp' ? 'MessageCircle' : ucfirst($key),
                            'is_active' => true,
                            'sort_order' => $sort++
                        ]);
                    }
                }
            }
            DB::table('portal_settings')->where('key', 'socialMedia')->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_media_links');
    }
};
