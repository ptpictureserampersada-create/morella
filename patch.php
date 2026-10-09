<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$seedFile = storage_path("app/morela-seed.json");
$seed = json_decode(file_get_contents($seedFile), true);
$seed["gallery"][] = [
    "id" => uniqid("gal-"),
    "title" => "Warta & Kegiatan KKN",
    "titleEn" => "News & KKN Activities",
    "category" => "pengabdian",
    "location" => "Negeri Morela",
    "photographer" => "Admin",
    "dateTaken" => "Oktober 2026",
    "imageUrl" => "/images/uploaded_gal.png"
];
file_put_contents($seedFile, json_encode($seed, JSON_PRETTY_PRINT));
App\Support\MorelaStore::reset();
echo "Done";

