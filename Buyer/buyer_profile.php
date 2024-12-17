<?php
require_once __DIR__ . "/../Classes/Buyer.php";
session_start();

// Check if the user is logged in
if (!isset($_SESSION['buyer_id'])) {
    header("Location: buyer_login.php");
    exit();
}

// Initialize buyer ID
$buyer_id = $_SESSION['buyer_id'];

// Instantiate Buyer class
$buyer = new Buyer($connect, $buyer_id);

// Get the buyer's details
$buyerDetails = $buyer->getProfileDetails();

// Display buyer profile details
$buyer->displayProfileDetails($buyerDetails);

// Check if profile is complete
$is_profile_complete = $buyerDetails !== null && $buyer->isProfileComplete($buyerDetails);
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
   <link rel="stylesheet" href="../css/profile.css">
</head>
<body>

<div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="../Seller/seller_sign_up.php">Seller Centre</a>
    <a href="buyer_page.php">Buy Products</a>
    <a href="cart.php">Cart</a>
    <a href="order_history.php" name="order_history">Order History</a>
    <a href="../log_out.php" name="log_out">Log Out</a>
    
<!-- Back Button -->
<div style="margin: 20px;">
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
</div>

<?php if (!$is_profile_complete): ?>
    <!-- Redirect to complete profile details -->
    <?php 
    header("Location: edit_buyer_profile.php");
    exit(); 
    ?>
<?php else: ?>
    <!-- Display profile details -->
    <div class="container">
    <div class="profile-details">
        <center><div class="heading">Profile</div></center>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($buyerDetails['name']) ?></p>
        <p><strong>Contact Number:</strong> <?= htmlspecialchars($buyerDetails['contact_number']) ?></p>
        <p><strong>Email Address:</strong> <?= htmlspecialchars($buyerDetails['email']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($buyerDetails['address']) ?></p>
    </div>
    <div style="margin-top: 20px;">
        <a href="edit_buyer_profile.php" class="btn">Edit Profile</a>
    </div>
    </div>
<?php endif; ?>

</body>
</html>
