<?php 
require_once __DIR__ . "/Classes/Buyer.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <title>Home Page</title>
        
    <div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="Seller/seller_login.php">Seller Centre</a>
    <a href="Buyer/buyer_sign_up.php">Sign Up</a>
    <a href="Buyer/buyer_login.php">Login</a> 
</div>
</head>
<body>
<center>
<!-- Products List -->
<div class="content">
    <h2 class="heading">Products List</h2>

    <?php
    $buyer = new Buyer($connect);

    if($buyer->isProductsEmpty()){
        echo "<p> No products yet. </p>";
    } else {
    // Fetch and display products
    $addedProducts = $buyer->displayProducts();
    while ($row = mysqli_fetch_assoc($addedProducts)) : ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="display:inline;">
            <div class="gallery">
                <img src="Images/<?php echo htmlspecialchars($row['item_image']); ?>" alt="Product Image">
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

    <?php endwhile; 
    }  ?>
</div>
<?php if (isset($_POST['add_to_cart'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['logged_in'])) {
        echo "<div class='error-message'>You need to log in first before adding to cart.</div>";
        header("refresh: 2;");
        exit();
    }
} ?>
</center>
</body>
</html>
