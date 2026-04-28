<?php

use App\Models\Order;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = Order::whereNull('order_number')->orderBy('id', 'asc')->get();
$count = 1001;

foreach ($orders as $order) {
    $order->order_number = 'ORD-' . $count;
    $order->save();
    echo "Updated Order ID {$order->id} with Order Number {$order->order_number}\n";
    $count++;
}

echo "Finished updating " . count($orders) . " orders.\n";
