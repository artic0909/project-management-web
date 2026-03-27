<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$statuses = \App\Models\Status::all();
echo "Total:" . $statuses->count() . "\n";
echo "Lead:" . $statuses->where('type', 'lead')->count() . "\n";
echo "Order:" . $statuses->where('type', 'order')->count() . "\n";
echo "Payment:" . $statuses->where('type', 'payment')->count() . "\n";
echo "Project:" . $statuses->where('type', 'project')->count() . "\n";
