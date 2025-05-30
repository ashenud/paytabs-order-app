<?php
require_once __DIR__ . '/../config/Payment.php';
require_once __DIR__ . '/../config/Order.php';

session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || ($_POST['action'] ?? '') !== 'generate_token') {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

if (empty($_SESSION['checkout']) === true) {
    http_response_code(403);
    echo json_encode(['error' => 'Session expired. Please create order again.']);
    exit;
}

$orderId = $_POST['order_id'];
$total = round($_POST['total'], 2);
$customerDetails = $_POST['customer_details'];

unset($customerDetails['shipping_method']);

$serverKey = PAYTABS_INTEGRATION_KEY;
$profileId = PAYTABS_PROFILE_ID;

// Generate token payload
$payload = [
    "profile_id" => $profileId,
    "tran_type" => "sale",
    "tran_class" => "ecom",
    "cart_id" => "order_" . $orderId,
    "cart_description" => "Order Payment for Order #$orderId",
    "cart_currency" => "EGP",
    "cart_amount" => $total,
    "callback" => "https://dfbf-112-134-175-3.ngrok-free.app/pages/payment-callback.php",
    "return" => "https://dfbf-112-134-175-3.ngrok-free.app/pages/payment-result.php",
    "hide_shipping" => true,
    "customer_details" => $customerDetails
];

$ch = curl_init("https://secure-egypt.paytabs.com/payment/request");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "authorization: $serverKey",
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$response) {
    echo json_encode(['error' => 'Failed to contact PayTabs']);
    exit;
}

$data = json_decode($response, true);
if (!isset($data['redirect_url'])) {
    echo json_encode(['error' => 'Invalid response from PayTabs', 'details' => $data]);
    exit;
}

echo json_encode($data);
exit;
