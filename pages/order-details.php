<?php
require_once __DIR__ . '/../config/Order.php';
require_once __DIR__ . '/../config/Product.php';

session_start();

if (isset($_GET['id']) === false || is_numeric($_GET['id']) === false) {
    header("Location: ../index.php");
    exit;
}

$orderId = (int)$_GET['id'];
$orderModel = new Order();
$orderData = $orderModel->getById($orderId); // You should already have this method to fetch order by ID

if (!$orderData) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    exit;
}

$orderItems = $orderModel->getOrderProducts($orderId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../inc/header.php'); ?>
    <title>Order #<?= htmlspecialchars($orderId) ?> Details</title>
</head>
<body>
<?php include('../inc/navbar.php'); ?>

<div class="container mt-5">
    <h2>Order #<?= htmlspecialchars($orderId) ?> Details</h2>

    <div class="mb-3">
        <strong>Status:</strong> <?= htmlspecialchars($orderData['status']) ?><br>
        <strong>Shipping Method:</strong> <?= htmlspecialchars($orderData['shipping_method']) ?><br>
        <strong>Created At:</strong> <?= htmlspecialchars($orderData['created_at']) ?>
    </div>

    <table class="table table-bordered">
        <thead class="thead-light">
        <tr>
            <th>Product</th>
            <th>Price (EGP)</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        foreach ($orderItems as $item):
            $lineTotal = $item['product_price'] * $item['quantity'];
            $total += $lineTotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= number_format($item['product_price'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($lineTotal, 2) ?> EGP</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="3" class="text-end">Total</th>
            <th><?= number_format($total, 2) ?> EGP</th>
        </tr>
        </tfoot>
    </table>
</div>

<?php include('../inc/footer.php'); ?>
</body>
</html>
