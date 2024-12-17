<?php
require_once __DIR__ . "/../Admin/database_connection.php";
require_once __DIR__ . "/../Classes/Buyer.php";
session_start(); // Start the session


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/sign_up.css">
    <title>Buyer Sign Up</title>
</head>
<body>
<div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="../Seller/seller_login.php">Seller Centre</a>
    <a href="buyer_login.php">Login</a>
</div>
<div class="container">
    <div class="box form-box">
        <div class="head">
            <header>Sign Up</header>
        </div> 

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection
            $buyer = new Buyer($connect);


            // Retrieve form data
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Register the user
            $message = $buyer->signUp($fname, $lname, $email, $password, $confirmPassword);

            // Display error message if registration fails
            if ($message) {
                echo "<div class='error-message'>{$message}</div>";
            }
        }
        ?>

        <form action="" method="post"> <!-- Keep action empty to post to the same page -->
            <div class="field input">
                <label for="fname">First Name</label><br>
                <input type="text" id="fname" name="fname" required><br><br>
            </div>
            <div class="field input">
                <label for="lname">Last Name</label><br>
                <input type="text" id="lname" name="lname" required><br><br>
            </div>
            <div class="field input">
                <label for="email">Email</label><br>
                <input type="email" id="email" name="email" required><br><br>
            </div>
            <div class="field input">
                <label for="password">Password</label><br>
                <input type="password" id="password" name="password" required><br><br>
            </div>
            <div class="field input">
                <label for="confirm_password">Confirm Password</label><br>
                <input type="password" id="confirm_password" name="confirm_password" required><br><br>
            </div>
            <div class="field">
                <input class="btn" type="submit" value="Sign Up"><br><br>
            </div>
            <!-- Hidden inputs for temporary values -->
            <input type="hidden" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>">
            <input type="hidden" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">
            <label>Already have an account? <a href="buyer_login.php">Login</a></label>
        </form>
    </div>
</div>
</body>
</html>
