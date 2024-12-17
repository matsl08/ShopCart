<?php
session_start(); // Start session at the top
require_once __DIR__ . "/../Classes/Buyer.php";


// Check if the user is logged in
if (!isset($_SESSION['buyer_id'])) {
   header("Location: buyer_login.php");
   exit();
}

// Sanitize session input
$buyer_id = filter_var($_SESSION['buyer_id'], FILTER_SANITIZE_NUMBER_INT);

$buyer = new Buyer($connect, $buyer_id);

// Get the buyer's details
$buyerDetails = $buyer->getProfileDetails();

// Check if profile is complete
$is_profile_complete = $buyerDetails !== null && $buyer->isProfileComplete($buyerDetails);
if (!$is_profile_complete) {
   echo "<div class='error-message'>You need to complete your profile first before placing an order.</div>";
   sleep(3);
    header("Location: buyer_profile.php");
    exit();
}

if(isset($_POST["add_more_products"])) {
   header("Location: buyer_page.php");
   exit();
}



//OOP Place Order
/*
   if (isset($_POST['order'])) {
      $paymentMethod = htmlspecialchars($_POST['payment_method']);
      $buyer = new Buyer($connect, $buyer_id);
  
      // Use the placeOrder method from Buyer class
      $result = $buyer->placeOrder($buyerDetails, $paymentMethod);
  }
      */

// Temporary non OOP place order

if (isset($_POST['order'])) {
   // Validate form inputs to avoid undefined array key warnings
   $_SESSION['name'] = $_POST['buyer_name'] ?? '';
   $_SESSION['contact_number'] = $_POST['contact_number'] ?? '';
   $_SESSION['email'] = $_POST['email'] ?? '';
   $_SESSION['payment_method'] = $_POST['payment_method'] ?? '';
   $_SESSION['address'] = $_POST['address'] ?? '';
   $paymentMethod = $_SESSION['payment_method'];

   // Place the order
   $result = $buyer->placeOrder($buyerDetails, $paymentMethod);

   if ($result['success']) {
       header("Location: ../Order/order_details.php");
       exit();
   } else {
       echo "<h3>" . $result['message'] . "</h3>";
   }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/checkout.css">

</head>
<body>
<div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="../Seller/seller_sign_up.php">Seller Centre</a>
    <a href="buyer_page.php">Buy Products</a>
    <a href="buyer_profile.php" name="buyer_profile">Profile</a>
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

<div class="container">

<section class="checkout-form">

   <h1 class="heading">Complete Your Order</h1>

   <form action="" method="post">

   <div class="display-order">
      <?php
         $select_cart = mysqli_query($connect, "SELECT * FROM `cart`");
         $total = 0;
         $grand_total = 0;
         if(mysqli_num_rows($select_cart) > 0){
            while($row = mysqli_fetch_assoc($select_cart)){
            $total_price = $row['item_price'] * $row['item_quantity'];
            $grand_total = $total += $total_price;
      ?>
      <p><?= $row['item_name']; ?>(<?= $row['item_quantity']; ?>)</p>
      <?php
         }
      }else{
         echo "<div class='display-order'><span>your cart is empty!</span></div>";
      }
      ?>
      <span class="grand-total"> Grand Total: ₱<?= number_format($total, 2); ?> </span>
   </div>

<?php if (!$is_profile_complete): ?>
    <!-- Redirect to complete profile details -->
    <?php 
    header("Location: edit_buyer_profile.php");
    exit(); 
    ?>
<?php else: ?>
   <form action="" method="post">
 
    <!-- Display profile details -->
    <div class="profile-details">
    <p><strong>Full Name:</strong> <?= htmlspecialchars($buyerDetails['name']) ?></p>
        <p><strong>Contact Number:</strong> <?= htmlspecialchars($buyerDetails['contact_number']) ?></p>
        <p><strong>Email Address:</strong> <?= htmlspecialchars($buyerDetails['email']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($buyerDetails['address']) ?></p>
    </div>
<?php endif; ?>
      <div class="inputBox">
         <span>Payment Method</span>
         <select name="payment_method">
            <option value="cash on delivery" selected>Cash on Delivery</option>
            <option value="credit card">Credit Card</option>
            <option value="gcash">Gcash</option>
         </select>
      </div>
      <div class="button_container">
      <input type="submit" value="Place Order" name="order" class="btn">
      <input type="submit" value="Add More Products" name="add_more_products" class="btn">
   </div>
   </div>
</form>
</section>
</div>
</body>
</html>