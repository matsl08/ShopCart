<?php
session_start();
require_once __DIR__ . "/../Classes/Buyer.php";

// Check if the user is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: buyer_login.php");
    exit();
}

$buyerId = $_SESSION['buyer_id'];
$buyer = new Buyer($connect, $buyerId);

// Remove item from cart
if (isset($_POST['remove'])) {
    $remove_id = $_POST['remove_id'];  // This should now work as remove_id will be passed
    $buyer->removeHistoryRecord($remove_id);
}

// Fetch order history
$orderHistory = $buyer->fetchOrderHistory();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../css/order_history.css">
</head>
<body>
<div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="../Seller/seller_sign_up.php">Seller Centre</a>
    <a href="../Buyer/buyer_page.php">Buy Products</a>
    <a href="../Buyer/buyer_profile.php" name="buyer_profile">Profile</a>
    <a href="../Buyer/cart.php">Cart</a>
    <a href="../log_out.php" name="log_out">Log Out</a>  
    <a href="../Buyer/buyer_page.php" class="back-button">⬅ Back</a>
</div> 
<div class="container">
<center><div class="heading">Order History</div></center>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Payment Method</th>
                <th>Address</th>
                <th>Total Products</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orderHistory && mysqli_num_rows($orderHistory) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($orderHistory)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['order_id']); ?></td>
                        <td><?= htmlspecialchars($row['name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['payment_method']); ?></td>
                        <td><?= htmlspecialchars($row['address']); ?></td>
                        <td><?= htmlspecialchars($row['total_products']); ?></td>
                        <td>₱<?= number_format($row['total_price'], 2); ?></td>
                        <td><?= htmlspecialchars($row['order_date']); ?></td>
                        <td>
                        <form action="" method="post">
                            <input type="hidden" name="remove_id" value="<?= $row['order_id']; ?>"> <!-- Pass the order_id here -->
                            <input type="submit" class="btn_remove" value="Remove" name="remove">
                        </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                    <tr>
                     <td colspan="8">No orders found.</td>
                    </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <form action="buyer_page.php">
    <center><input type="submit" value="Continue Shopping" name="continue_shopping" class="btn"></center>
    </form>
</div>
</body>
</html>