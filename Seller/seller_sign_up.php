<?php
require_once __DIR__ . "/../Classes/Seller.php";
session_start(); // Start the session


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Sofia:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../css/sign_up.css">
    <title>Seller Sign Up</title>
</head>
<body>
<div class="topnav">
    <h1><span>ShopCart</span></h1>
    <a href="../Buyer/buyer_login.php">Buy Products</a>
    <a href="seller_login.php">Login</a>
</div>
<div class="container">
    <div class="box form-box">
        <div class="head">
            <header>Seller Sign Up</header>
        </div> 

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Database connection
            $seller = new Seller($connect);

            // Retrieve form data
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Register the user
            $message = $seller->signUp($first_name, $last_name, $email, $password, $confirmPassword);

            // Display error message if registration fails
            if ($message) {
                echo "<div class='error-message'>{$message}</div>";
            }
        }
        ?>

        <form action="" method="post">
            <div class="field input">
                <label for="first_name">First Name</label><br>
                <input type="text" id="first_name" name="first_name" required><br><br>
            </div>
            <div class="field input">
                <label for="last_name">Last Name</label><br>
                <input type="text" id="last_name" name="last_name" required><br><br>
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
            <!-- Hidden inputs for temporary values -->
            <input type="hidden" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($contact_number); ?>">
            <input type="hidden" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">
            <div class="field">
                <input class="btn" type="submit" value="Sign Up"><br><br>
            </div>
            <label>Already have an account? <a href="seller_login.php">Login</a></label>
        </form>
    </div>
</div>
</body>
</html>
