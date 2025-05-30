<?php
require_once __DIR__ . '/config/Order.php';

$orderModel = new Order();
$orders = $orderModel->getAll();
?>

<!doctype html>
<html lang="en">
<head>
    <?php include('./inc/header.php'); ?>
    <title>Paytabs - Orders</title>
</head>

<body>
    <?php include('./inc/navbar.php'); ?>

    <div class="container mt-5">
        <h2 class="mb-4">All Orders</h2>
        <?php if (empty($orders)): ?>
            <div class="alert alert-warning">No orders found.</div>
        <?php else: ?>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>#ID</th>
                    <th>Shipping Type</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['shipping_method']) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td>
                            <a href="pages/order-details.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php include('./inc/footer.php'); ?>
</body>
</html>