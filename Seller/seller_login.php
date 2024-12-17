<?php
require_once __DIR__ . "/../Classes/Seller.php";
session_start(); // Start the session

// Instantiate Seller class
$seller = new Seller($connect);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <title>Seller Login</title>
</head>
<body>

<div class="topnav">
    <h1><span>ShopCart</span></h1>  
    <a href="../Buyer/buyer_login.php">Buy Products</a>
    <a href="seller_sign_up.php">Sign Up</a>
</div>

<div class="container">
    <div class="box form-box">
        <div class="head">
            <header>Seller Login</header>
        </div> 
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
            // Retrieve form data
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Log in the seller
            $message = $seller->login( $email, $password);

            // Display error message if log in fails
            if ($message) {
                echo "<div class='error-message'>{$message}</div>";
            }
        }

        ?>

        <form action="" method="post">
            <div class="field input">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" required><br><br>
            </div>
            <div class="field input">
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" required><br><br>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <p><?= htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <div class="field">
                <input class="btn" type="submit" value="Login"><br><br>
            </div>

            <label>Don't have an account? <a href="seller_sign_up.php">Sign Up</a></label>
        </form>
    </div>
</div>

</body>
</html>
