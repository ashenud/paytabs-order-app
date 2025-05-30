<?php
require_once __DIR__ . '/../config/Payment.php';
require_once __DIR__ . '/../config/Order.php';

session_start();

if (
        empty($_SESSION['checkout']) ||
        isset($_POST['order_id']) === false ||
        is_numeric($_POST['order_id']) === false
) {
    header("Location: create-orders.php");
    exit;
}

$orderModel = new Order();
$paymentModel = new Payment();

$orderId = $_POST['order_id'];

$orderProducts = $orderModel->getOrderProducts($orderId);

$grandTotal = 0;
$checkoutItems = [];

foreach ($orderProducts as $item) {
    $productId = $item['product_id'];
    $productPrice = $item['product_price'];
    $quantity = $item['quantity'];

    $lineTotal = $productPrice * $quantity;
    $grandTotal += $lineTotal;

    $checkoutItems[] = [
        'product_id' => $productId,
        'name'       => $item['product_name'],
        'quantity'   => $quantity,
        'price'      => $productPrice,
        'subtotal'   => $lineTotal
    ];
}

$customerDetails = [
    'name'             => $_POST['name'],
    'email'            => $_POST['email'],
    'phone'            => $_POST['phone'],
    'shipping_method'  => $_POST['shipping_method'],
    "street1"          => $_POST['address'] ?? "N/A",
    "city"             => $_POST['address'] ?? "N/A",
    "state"            => "N/A",
    "country"          => "EG",
    "zip"              => $_POST['address'] ?? "N/A",
];

$orderDetails = [
    'order_id'        => $orderId,
    'items'           => $checkoutItems,
    'total'           => $grandTotal,
    'customerDetails' => $customerDetails,
];

$paymentData = $paymentModel->initiate($orderId, json_encode($orderDetails));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../inc/header.php'); ?>
    <title>Pay via PayTabs</title>
</head>
<body>
<?php include('../inc/navbar.php'); ?>

<div class="container mt-5">
    <h3>Payment for Order #<?= htmlspecialchars($orderId) ?></h3>
    <div id="paytabs-iframe-container" class="my-4 text-center">
        <p>Loading secure payment form...</p>
    </div>
</div>

<?php include('../inc/footer.php'); ?>

<script>
    $(document).ready(function () {
        $.ajax({
            url: "../paytabs/iframe-token.php",
            method: "POST",
            data: {
                action: "generate_token",
                order_id: <?= $orderId ?>,
                total: <?= $grandTotal ?>,
                customer_details: <?= json_encode($customerDetails) ?>,
            },
            success: function (res) {
                if (res && res.redirect_url) {
                    $('#paytabs-iframe-container').html(
                        `<iframe src="${res.redirect_url}" width="100%" height="550" frameborder="0" allowfullscreen></iframe>`
                    );
                } else {
                    $('#paytabs-iframe-container').html('<div class="alert alert-danger">Failed to load payment form.</div>');
                    console.error(res);
                }
            },
            error: function () {
                $('#paytabs-iframe-container').html('<div class="alert alert-danger">Error while contacting payment gateway.</div>');
            }
        });
    });
</script>


</body>
</html>
