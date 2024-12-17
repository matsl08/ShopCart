<?php
require_once __DIR__ . "/../Admin/database_connection.php";
require_once __DIR__ . "/../Classes/AbstractUser.php";

class Buyer extends User{
    private $db;
    private $buyer_id;

    public function __construct($db, $buyer_id = 0) {
        $this->db = $db;
        $this->buyer_id = $buyer_id;
    }

    // Check if the products table is empty
    public function isProductsEmpty() {
        $query = "SELECT COUNT(*) as count FROM products";
        $result = mysqli_query($this->db, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] == 0; // Returns true if no products are found
    }

    // Fetch products
    public function displayProducts() {
        $sql = "SELECT * FROM products";
        $result = mysqli_query($this->db, $sql);
        return $result;
    }

    //Sign Up Function
    public function signUp($fname, $lname, $email, $password, $confirmPassword) {
        // Sanitize email
        $email = $this->db->real_escape_string($email);

        // Check if the email already exists
        $query = "SELECT * FROM buyers WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "Email already exists. Please choose a different email.";
        }

        // Check if passwords match
        if ($password !== $confirmPassword) {
            return "Passwords do not match. Please try again.";
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind the SQL statement
        $query = "INSERT INTO buyers (fname, lname, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssss", $fname, $lname, $email, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                // Build user data array
                $user = [
                    'id' => $stmt->insert_id,
                    'fname' => $fname,
                    'lname' => $lname,
                    'email' => $email,
                    'contact_number' => '',
                    'address' => '',
                ];
                
                // Start a session and store buyer details
                $this->startSession($user);
                header("Location: buyer_login.php");
                exit();
            } else {
                return "Error: " . $stmt->error;
            }
        } else {
            return "Error preparing SQL: " . $this->db->error;
        }
    }

    public function login($email, $password) {
        // Sanitize email input
        $email = $this->db->real_escape_string($email);
        $password = $_POST['password'];

        // Prepare query to fetch buyer's details
        $query = "SELECT id, fname, lname, email, password, contact_number, address FROM buyers WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $stored_hashed_password = $user['password'];

            // Verify the password
            if (password_verify($password, $stored_hashed_password)) {
                // Start session and store user details
                $this->startSession($user);
                header("Location: buyer_page.php");
                exit();
            } else {
                return "Invalid password!";
            }
        } else {
            return "No account found with this email. Please sign up.";
        }
    }

        // Session Management Function
    private function startSession($user) {
        session_start();
        $_SESSION['buyer_id'] = $user['id'];
        $_SESSION['buyer_name'] = $user['fname'] . ' ' . $user['lname'];
        $_SESSION['buyer_email'] = $user['email'];
        $_SESSION['buyer_contact_number'] = $user['contact_number'];
        $_SESSION['buyer_address'] = $user['address'];
    }



    public function addToCart($item_name, $item_image, $item_price, $item_quantity = 1) {
        // Use prepared statements for secure queries
        $select_query = "SELECT * FROM `cart` WHERE item_name = ?";
        $stmt = $this->db->prepare($select_query);
        $stmt->bind_param("s", $item_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "<div class='error-message'>Product already added to cart.</div>";
        }

        $insert_query = "
            INSERT INTO `cart` (item_name, item_image, item_price, item_quantity) 
            VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($insert_query);
        $stmt->bind_param("ssdi", $item_name, $item_image, $item_price, $item_quantity);

        if ($stmt->execute()) {
            return "<div class='success-message'>Product added to cart successfully!</div>";
        } else {
            error_log("Error adding to cart: " . $stmt->error);
            return "Error adding product to cart.";
        }
    }


    public function getProfileDetails() {
        $query = "SELECT fname, lname, contact_number, email, address FROM buyers WHERE id = ?";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "i", $this->buyer_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $fname, $lname, $contact_number, $email, $address);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Return an associative array with sanitized profile details
        return [
            'name' => htmlspecialchars($fname) . ' ' . htmlspecialchars($lname),
            'contact_number' => htmlspecialchars($contact_number),
            'email' => htmlspecialchars($email),
            'address' => htmlspecialchars($address),
        ];
    }

    public function isProfileComplete($profileDetails) {
        return $profileDetails['name'] && $profileDetails['contact_number'] && $profileDetails['email'] && $profileDetails['address'];
    }

   
    // Function to display profile details
    public function displayProfileDetails($Details) {
        if ($Details) {
            $name = htmlspecialchars($Details['name']);
            $contact_number = htmlspecialchars($Details['contact_number']);
            $email = htmlspecialchars($Details['email']);
            $address = htmlspecialchars($Details['address']);
        } else {
            // If no details were retrieved, redirect to edit profile page
            header("Location: edit_buyer_profile.php");
            exit();
        }
    }

    // Method to edit buyer profile
    public function editProfileDetails($name, $contactNumber, $email, $address) {
        $query = "UPDATE buyers SET contact_number = ?, address = ? WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iss", $contactNumber, $address, $email);

        if ($stmt->execute()) {
            $_SESSION['buyer_name'] = $name;
            $_SESSION['buyer_contact_number'] = $contactNumber;
            $_SESSION['buyer_email'] = $email;
            $_SESSION['buyer_address'] = $address;
            return true;
        }
        return false;
    }


    // Display products added to cart
    public function getProductsAddedToCart() {
        $result = mysqli_query($this->db, "SELECT * FROM cart");
        return $result;
    }

    // Update cart item quantity
    public function updateCartItemQuantity($quantity, $productId) {
        $update_sql = "UPDATE `cart` SET item_quantity = ? WHERE product_id = ?";
        $stmt = mysqli_prepare($this->db, $update_sql);
        mysqli_stmt_bind_param($stmt, 'ii', $quantity, $productId);
        mysqli_stmt_execute($stmt);
        header("refresh: 0");
        exit();
    }

    // Remove item from cart
    public function removeCartItem($productId) {
        $delete_query = "DELETE FROM `cart` WHERE product_id = ?";
        $stmt = mysqli_prepare($this->db, $delete_query);
        mysqli_stmt_bind_param($stmt, 'i', $productId);
        mysqli_stmt_execute($stmt);
        header("refresh: 0");
        exit();
    }

    // Delete all items from the cart
    public function deleteAllCartItems() {
        mysqli_query($this->db, "DELETE FROM cart");
        header("refresh: 0");
        exit();
    }

    
    public function placeOrder($buyerDetails, $paymentMethod)
    {
        $cartQuery = "SELECT * FROM `cart`";
        $cartResult = mysqli_query($this->db, $cartQuery);
    
        if (mysqli_num_rows($cartResult) == 0) {
            return ['success' => false, 'message' => 'Your cart is empty.'];
        }
    
        $productNames = [];
        $priceTotal = 0;
    
        // Update stock and calculate totals
        while ($productItem = mysqli_fetch_assoc($cartResult)) {
            $productNames[] = $productItem['item_name'] . ' (' . $productItem['item_quantity'] . ')';
            $priceTotal += $productItem['item_price'] * $productItem['item_quantity'];
    
            $updateStockQuery = "UPDATE products SET stocks = stocks - ? WHERE item_name = ?";
            $stmt = mysqli_prepare($this->db, $updateStockQuery);
            mysqli_stmt_bind_param($stmt, "is", $productItem['item_quantity'], $productItem['item_name']);
            mysqli_stmt_execute($stmt);
        }
    
        $productList = implode(', ', $productNames);
    
        // Insert order into `orders` table
        $insertOrderQuery = "INSERT INTO `orders` (name, contact_number, email, payment_method, address, total_products, total_price)
                             VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->db, $insertOrderQuery);
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssd",
            $buyerDetails['name'],
            $buyerDetails['contact_number'],
            $buyerDetails['email'],
            $paymentMethod,
            $buyerDetails['address'],
            $productList,
            $priceTotal
        );
    
        if (mysqli_stmt_execute($stmt)) {
            // Save order details in the session
            $_SESSION['order_id'] = mysqli_insert_id($this->db); // Get the inserted order's ID
            $_SESSION['buyer_name'] = $buyerDetails['name'];
            $_SESSION['payment_method'] = $paymentMethod;
            $_SESSION['total_products'] = $productList;
            $_SESSION['total_price'] = $priceTotal;
        
            // Clear the cart
            mysqli_query($this->db, "DELETE FROM `cart`");
        
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Order failed. Please try again!'];
        }
        
    }
    

    private function updateStock($itemName, $quantity) {
        $query = "UPDATE products SET stocks = stocks - ? WHERE item_name = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $quantity, $itemName);
        $stmt->execute();
    }


    public function getOrderDetails($order_id) {
        if (!$order_id) {
            return null;
        }
    
        $query = "SELECT total_products, total_price FROM `orders` WHERE order_id = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $order_id); // Use integer for order_id
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null; // No order found
        }
    }
    
    // Function to fetch the latest order ID
    public function getLatestOrderId($db) {
    $query = "SELECT MAX(order_id) AS latest_order_id FROM orders";
    $result = $db->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        return $row['latest_order_id'];
    }
    return null; 
}
// Define the getReceiptDetails function
    public function getReceiptDetails($db, $order_id) {
    if (!$order_id) {
        return null;
    }

    $query = "SELECT name AS buyer_name, total_products, total_price, payment_method FROM orders WHERE order_id = ? LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $order_id); // Use integer for order_id
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null; // No order found
    }
}

public function fetchOrderHistory() {
    // Get the buyer's details
    $buyerDetails = $this->getProfileDetails();

    // Fetch orders for the logged-in buyer
    $query = "SELECT * FROM orders WHERE email = ? ORDER BY order_date DESC";
    $stmt = mysqli_prepare($this->db, $query);
    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, "s", $buyerDetails['email']);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

    // Remove item from cart
    public function removeHistoryRecord($order_id) {
        $delete_query = "DELETE FROM `orders` WHERE order_id = ?";
        $stmt = mysqli_prepare($this->db, $delete_query);
        mysqli_stmt_bind_param($stmt, 'i', $order_id);
        mysqli_stmt_execute($stmt);

    }

    // Delete all items from the cart
    /*
    public function clearAllHistory($email) {
        $delete_query = "DELETE FROM orders WHERE email = ?";
        $stmt = mysqli_prepare($this->db, $delete_query);
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        header("refresh: 0");
        exit();
    }*/



   




}
?>