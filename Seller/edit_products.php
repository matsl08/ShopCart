<?php
require_once __DIR__ . "/../Classes/Seller.php";

session_start();

// Check if the user is logged in
if (!isset($_SESSION['seller_id'])) {
    header("Location: seller_login.php");
    exit();
}

// Check if product_id is provided in the URL
if (!isset($_GET['product_id'])) {
    echo "<h3>No product selected. Please go back and select a product to edit.</h3>";
    exit();
}

// Get the product_id of the current product that you will edit the details
$product_id = $_GET['product_id'];

// Seller class Instantiation
$seller = new Seller($connect, $_SESSION['seller_id']);
    
    // Fetch product details
    $product = $seller->getProductDetails($product_id);

    // Access product details
    $item_name = $product['item_name'];
    $item_image = $product['item_image'];
    $item_desc = $product['item_desc'];
    $item_price = $product['item_price'];
    $stocks = $product['stocks'];
    $product_category = $product['product_category'];

// Check if form is submitted
if (isset($_POST['update_details'])) {

    $data = [
        'item_name' => $_POST['item_name'],
        'item_desc' => $_POST['item_desc'],
        'item_price' => $_POST['item_price'],
        'stocks' => $_POST['stocks'],
        'product_category' => $_POST['product_category'],
        'current_image' => $item_image // Use the current image path from the fetched product data
    ];

    // implementing the edit product function
    $result = $seller->editProductDetails($product_id, $data, $_FILES);
    if ($result === true) {
        header("Location: seller_page.php");
        exit();
    } else {
        echo "<h3>$result</h3>"; // Display error message if update failed
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/edit_products.css">
    <div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="seller_profile.php">Profile</a>
    <a href="../Buyer/buyer_page.php">Buy Products</a>
   
    <!-- Back Button -->

    <?php
    // Check if the referrer is available
    if (isset($_SERVER['HTTP_REFERER'])) {
        $previousPage = $_SERVER['HTTP_REFERER'];
        echo '<a href="' . htmlspecialchars($previousPage) . '" class="back-button">⬅ Back</a>';
    } else {
        echo '<a href="#" class="back-button disabled">⬅ Back</a>';
    }
    ?>   
    <a href="../log_out.php" name="log_out">Log Out</a>
</div>
</head>
<body>
<div class="container">
    <center><div class ="heading">Edit Product</div></center>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="inputBox">
            <label for="item_name">Item Name</label><br>
            <input type="text" id="item_name" name="item_name" value="<?= $item_name ?>" required><br><br>
        </div>

        <div class="inputBox">
            <label for="item_desc">Item Description</label><br>
            <input type="text" id="item_desc" name="item_desc" value="<?= $item_desc ?>" required><br><br>
        </div>

        <div class="inputBox">
            <label for="item_price">Item Price</label><br>
            <input type="number" id="item_price" name="item_price" value="<?= $item_price ?>" required><br><br>
        </div>

        <div class="inputBox">
            <label for="stocks">Stocks</label><br>
            <input type="number" id="stocks" name="stocks" value="<?= $stocks ?>" required><br><br>
        </div>

        <div class="inputBox">
            <label for="product_category">Product Category</label><br>
            <input type="text" id="product_category" name="product_category" value="<?= $product_category ?>" required><br><br>
        </div>

        <div class="inputBox">
            <label for="item_image">Item Image</label><br>
            <?php if ($item_image): ?>
                <img src="../Images/<?= $item_image ?>" alt="Current Image" style="width: 100px; height: auto;"><br>
            <?php endif; ?>
            <input type="file" id="item_image" name="item_image" accept="image/*"><br><br>
            <small>If you upload a new image, it will replace the current one.</small>
        </div>

        <input type="submit" value="Update Details" name="update_details" class="btn">
    </form>
</div>
</body>
</html>
