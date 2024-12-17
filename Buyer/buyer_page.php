<?php
ob_start();
require_once __DIR__ . "/../Classes/Buyer.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: buyer_login.php"); // Redirect to login if not logged in
    exit();
}

// Initialize buyer ID to the current logged-in buyer ID
$buyer_id = $_SESSION['buyer_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/buyer_page.css">
    <title>Buyer Page</title>

    <div class="topnav">
        <h1><span>ShopCart</span></h1>
            <!-- Search Bar -->
        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="search-form">
            <input type="text" name="search" placeholder="Search for products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
        <a href="../Seller/seller_sign_up.php">Seller Centre</a>
        <a href="buyer_profile.php" name="buyer_profile">Profile</a>
        <a href="cart.php">Cart</a>
        <a href="order_history.php" name="order_history">Order History</a>
        <a href="../log_out.php" name="log_out">Log Out</a>
    </div> 
    <div class="user_header">
        <?php echo "<h4>Welcome, " . htmlspecialchars($_SESSION['buyer_name']) . "!</h4>"; ?>
    </div>
</head>
<body>

<center>
<!-- Products List -->
<div class="content">
    <h2>Products List</h2>



    <?php if (isset($_SESSION['message'])): ?>
        <div class='order-message-container'>
            <div class='message-container'>
                <h3><?= htmlspecialchars($_SESSION['message']); ?></h3>
            </div>
        </div>
        <?php unset($_SESSION['message']); // Clear the message after displaying ?>
    <?php endif; ?>

    <?php
    $buyer = new Buyer($connect, $buyer_id);

    if ($buyer->isProductsEmpty()) {
        echo "<p>No products yet.</p>";
    }

    // Fetch search term
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    // Fetch products (filtered by search term if provided)
    $addedProducts = $buyer->displaySearchProducts($searchTerm);

    while ($row = mysqli_fetch_assoc($addedProducts)) : ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display:inline;">
            <div class="gallery">
                <img src="../Images/<?php echo htmlspecialchars($row['item_image']); ?>" alt="Product Image">
                <p class="desc"><?php echo htmlspecialchars($row['item_name']); ?></p>
                <p class="itemPrice"><?php echo "₱" . number_format($row['item_price'], 2); ?></p>
                <p class="itemDesc"><?php echo htmlspecialchars($row['item_desc']); ?></p>
                <p class="itemQty"><?php echo "Stocks: " . htmlspecialchars($row['stocks']); ?></p>

                <input type="hidden" name="item_price" value="<?php echo $row['item_price']; ?>">
                <input type="hidden" name="item_name" value="<?php echo $row['item_name']; ?>">
                <input type="hidden" name="item_image" value="<?php echo $row['item_image']; ?>">
                <input type="submit" class="btn" value="Add To Cart" name="add_to_cart">
            </div>
        </form>
    <?php endwhile; ?>

</div>
<?php
if (isset($_POST['add_to_cart'])) {
    $response = $buyer->addToCart($_POST['item_name'], $_POST['item_image'], $_POST['item_price']);
    echo $response;
    header("refresh: 1;");
    exit();
}
?>
</center>
</body>
</html>
