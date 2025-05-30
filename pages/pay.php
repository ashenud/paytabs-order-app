<?php
require_once __DIR__ . '/../config/Payment.php';
require_once __DIR__ . '/../config/Order.php';

session_start();

if (!isset($_SESSION['checkout'])) {
    header("Location: create-orders.php");
    exit;
}

$checkout = $_SESSION['checkout'];
$orderId = $checkout['order_id'];
$total = $checkout['total'];

$paymentModel = new Payment();
$paymentData = $paymentModel->initiate($orderId, json_encode($checkout));

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
                action: "generate_token"
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
