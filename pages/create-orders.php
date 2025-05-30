<?php
require_once __DIR__ . '/../config/Product.php';
require_once __DIR__ . '/../config/Order.php';

$productClass = new Product();
$products = $productClass->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderClass = new Order();

    $selectedProducts = $_POST['product'];
    $quantities = $_POST['quantity'];

    $orderId = $orderClass->create();

    foreach ($selectedProducts as $productId) {
        $quantity = isset($quantities[$productId]) ? intval($quantities[$productId]) : 1;
        $orderClass->addProduct($orderId, $productId, $quantity);
    }

    header("Location: checkout.php?order_id=" . $orderId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('../inc/header.php'); ?>
    <title>Create Order</title>
</head>
<body>
<?php include('../inc/navbar.php'); ?>

<div class="container mt-5">
    <h2>Create New Order</h2>
    <form method="POST">
        <table class="table table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>Select</th>
                <th>Product</th>
                <th>Price (EGP)</th>
                <th>Quantity</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <label>
                            <input type="checkbox" name="product[]" value="<?= $p['id'] ?>">
                        </label>
                    </td>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= number_format($p['price'], 2) ?></td>
                    <td>
                        <label>
                            <input
                                type="number"
                                class="form-control"
                                name="quantity[<?= $p['id'] ?>]"
                                value="1"
                                min="1"
                                max="10"
                            >
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
    </form>
</div>

<?php include('../inc/footer.php'); ?>

<script>
    $(document).ready(function () {
        $('form').on('submit', function (e) {
            let checked = $('input[name="product[]"]:checked').length;

            if (checked === 0) {
                alert('Please select at least one product to create an order.');
                e.preventDefault();
            }
        });
    });
</script>

</body>
</html>
