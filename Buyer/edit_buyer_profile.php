<?php
require_once __DIR__ . "/../Classes/Buyer.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: buyer_login.php"); // Redirect to login if not logged in
    exit();
}

// Initialize buyer ID
$buyer_id = $_SESSION['buyer_id'];

// Instantiate Buyer class
$buyer = new Buyer($connect, $buyer_id);

if (isset($_POST['update_details'])) {
    $name = htmlspecialchars($_POST['name']);
    $contactNumber = (int) $_POST['contact_number'];
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);

    if ($buyer->editProfileDetails($name, $contactNumber, $email, $address)) {
        header("Location: buyer_profile.php");
        exit();
    } else {
        $error = "Failed to update details. Please try again!";
    }
}

if (isset($_GET['error'])) {
    echo "<div class='error-message'>" . htmlspecialchars($_GET['error']) . "</div>";
}


?>





<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Buyer's Profile</title>
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/edit_profile.css">

   <div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="../Seller/seller_sign_up.php">Seller Centre</a>
    <a href="buyer_page.php">Buy Products</a>
    <a href="cart.php">Cart</a>
    <a href="order_history.php" name="order_history">Order History</a>
    <a href="../log_out.php" name="log_out">Log Out</a>
    
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
</div>
</head>
<body>
<div class="container">
<center><div class="heading">Edit Profile</div></center>
<form action="" method="post">
    <div class="flex">
        <div class="inputBox">
            <span>Full Name</span>
            <input type="text" placeholder="Enter your name" name="name" value="<?= htmlspecialchars($_SESSION['buyer_name']) ?? '' ?>" required>
        </div>
        <div class="inputBox">
            <span>Contact Number</span>
            <input type="number" placeholder="Enter your number" name="contact_number" value="<?=htmlspecialchars($_SESSION['buyer_contact_number']) ?? '' ?>"  required>
        </div>
        <div class="inputBox">
            <span>Email Address</span>
            <input type="email" placeholder="Enter your email" name="email" value="<?= htmlspecialchars($_SESSION['buyer_email']) ?? '' ?>" required>
        </div>
        <div class="inputBox">
            <span>Address</span>
            <input type="text" placeholder="e.g. Purok 1, Talisay City, Cebu" name="address" value="<?= htmlspecialchars($_SESSION['buyer_address']) ?? '' ?>"  required>
        </div>
    </div>
    <input type="submit" value="Update Details" name="update_details" class="btn">
</form>
</div>
</body>
</html>