<?php

require_once __DIR__ . "/../Classes/Buyer.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: ../Buyer/buyer_login.php");
    exit();
}

// Ensure `order_id` and `email` are set
if (!isset($_SESSION['order_id']) || !isset($_SESSION['email'])) {
    header("Location: ../Buyer/buyer_profile.php");
    exit();
}

// Fetch buyer information
$buyerId = $_SESSION['buyer_id'];
$buyer = new Buyer($connect, $buyerId);

// Get buyer's profile
$buyerDetails = $buyer->getProfileDetails();
$order_id = $_SESSION['order_id'];

// Fetch order details
$orderDetails = $buyer->getOrderDetails($order_id);

if ($orderDetails === null) {
    $error_message = "No orders found for Order ID: " . htmlspecialchars($order_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Success</title>
    <link rel="stylesheet" href="../css/order_details.css">
</head>
<body>
<div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="../Seller/seller_sign_up.php">Seller Centre</a>
    <a href="../Buyer/buyer_page.php">Buy Products</a>
    <a href="../Buyer/buyer_profile.php" name="buyer_profile">Profile</a>
    <a href="../Buyer/cart.php">Cart</a>
    <a href="../Buyer/order_history.php" name="order_history">Order History</a>
    <a href="../log_out.php" name="log_out">Log Out</a>
    <a href="../Buyer/buyer_page.php" class="back-button">⬅ Back</a>
</div>

<div class='order-message-container'>
    <div class='message-container'>
        <?php
        if (isset($error_message)) {
            echo "<h2>Error</h2>";
            echo "<div class='error'>" . $error_message . "</div>";
        } else {
            echo "<h1>Thank You for shopping, " . htmlspecialchars($_SESSION['buyer_name']) . "!</h1>";
            echo "<div class='order-detail'>";
            echo "<p>Total Products: " . htmlspecialchars($orderDetails['total_products']) . "</p>";
            echo "<p>Total Price: ₱". number_format($orderDetails['total_price'], 2) . "</p>";
            echo "</div>";
            //echo "<a href='../Buyer/download_receipt.php?order_id=" . htmlspecialchars($order_id) . "'>View Receipt</a>";
        }
        ?>
        <a href="../Buyer/download_receipt.php?name=<?= urlencode($_SESSION['buyer_name']); ?>&contact_number=<?= urlencode($_SESSION['buyer_contact_number']); ?>&email=<?= urlencode($_SESSION['buyer_email']); ?>&address=<?= urlencode($_SESSION['buyer_address']); ?>&payment_method=<?= urlencode($_SESSION['payment_method']); ?>&total_product=<?= urlencode($orderDetails['total_products']); ?>&total_price=<?= urlencode($orderDetails['total_price']); ?>" class="btn" name="view_receipt">View Receipt</a>


        <a href='../Buyer/buyer_page.php' class='btn'>Continue Shopping</a>
    </div>
</div>
</body>
</html>
