<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "INT: " . App\Models\Meeting::whereJsonContains('assignsale_ids', 1)->count() . PHP_EOL;
echo "STRING: " . App\Models\Meeting::whereJsonContains('assignsale_ids', '1')->count() . PHP_EOL;
echo "Q_STRING: " . App\Models\Meeting::whereJsonContains('assignsale_ids', '"1"')->count() . PHP_EOL;
echo "JSON_ENCODED_INT: " . App\Models\Meeting::where('assignsale_ids', 'like', '%"1"%')->count() . PHP_EOL;

$saleId = 1;
echo "Original string casting: " . App\Models\Meeting::whereJsonContains('assignsale_ids', (string)$saleId)->count() . PHP_EOL;
