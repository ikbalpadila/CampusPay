<?php

use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;

require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$request = new Request();
$request->merge([
    'tagihan_id' => 1,
    'mahasiswa_id' => 1,
    'nominal' => 4000000,
    'status' => 'belum_bayar',
    'payment_type_nama' => 'UKT',
    'semester_nama' => '2026/2027 Ganjil',
    'jatuh_tempo' => '2026-07-01'
]);

$controller = app(PaymentController::class);
try {
    $response = $controller->initiate($request);
    print_r($response->getData(true));
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
