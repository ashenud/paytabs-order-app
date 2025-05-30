<?php
require_once __DIR__ . '/../config/Order.php';
require_once __DIR__ . '/../config/Payment.php';

session_start();

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    header('Location: create-orders.php');
    exit;
}

$orderId = (int)$_GET['order_id'];

$orderModel = new Order();

$orderProducts = $orderModel->getOrderProducts($orderId);

if (empty($orderProducts)) {
    echo "<div class='alert alert-danger'>No items found for this order.</div>";
    exit;
}

$productIds = array_column($orderProducts, 'product_id');

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

// Save to session for use in PayTabs or confirmation page
$_SESSION['checkout'] = [
    'order_id' => $orderId,
    'items'    => $checkoutItems,
    'total'    => $grandTotal
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../inc/header.php'); ?>
    <title>Checkout</title>
</head>
<body>
<?php include('../inc/navbar.php'); ?>

<div class="container mt-5">
    <h2>Order Summary</h2>

    <table class="table table-bordered">
        <thead class="thead-light">
        <tr>
            <th>Product</th>
            <th>Price (USD)</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($orderProducts as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= number_format($item['product_price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['product_price'] * $item['quantity'], 2) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th><?= number_format($grandTotal, 2) ?> USD</th>
        </tr>
        </tfoot>
    </table>

    <!-- Simulated Checkout Button -->
    <form action="pay.php" method="POST" id="checkout-form">
        <input type="hidden" name="action" value="pay">

        <h4 class="mt-4">Customer Information</h4>
        <div class="row">
            <div class="col-md-4">
                <label for="name">Full Name *</label>
                <label>
                    <input type="text" class="form-control" name="name" required>
                </label>
            </div>
            <div class="col-md-4">
                <label for="email">Email *</label>
                <label>
                    <input type="email" class="form-control" name="email" required>
                </label>
            </div>
            <div class="col-md-4">
                <label for="phone">Phone *</label>
                <label>
                    <input type="text" class="form-control" name="phone" required>
                </label>
            </div>
        </div>

        <h4 class="mt-4">Delivery Method</h4>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="shipping_method" id="pickup" value="pickup" checked>
            <label class="form-check-label" for="pickup">Pick Up</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="shipping_method" id="shipping" value="shipping">
            <label class="form-check-label" for="shipping">Shipping</label>
        </div>

        <div id="shipping-address" class="mt-3 d-none">
            <h5>Shipping Address</h5>
            <div class="mb-2">
                <label for="address">Address *</label>
                <label>
                    <input type="text" class="form-control" name="address">
                </label>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="city">City *</label>
                    <label>
                        <input type="text" class="form-control" name="city">
                    </label>
                </div>
                <div class="col-md-6">
                    <label for="zip">ZIP Code *</label>
                    <label>
                        <input type="text" class="form-control" name="zip">
                    </label>
                </div>
            </div>
        </div>

        <input type="hidden" name="order_id" value="<?= $orderId ?>">

        <button type="submit" class="btn btn-success mt-4">Proceed to PayTabs Payment</button>
    </form>

</div>

<?php include('../inc/footer.php'); ?>
<script>
    $(function () {
        $('input[name="shipping_method"]').on('change', function () {
            if ($(this).val() === 'shipping') {
                $('#shipping-address').removeClass('d-none');
                $('#shipping-address input').attr('required', true);
            } else {
                $('#shipping-address').addClass('d-none');
                $('#shipping-address input').attr('required', false);
            }
        });
    });
</script>

</body>

</html>
