<?php
require_once __DIR__ . "/../Admin/database_connection.php";
require_once __DIR__ . "/../Classes/AbstractUser.php";
class Seller extends User {
    private $db;
    private $seller_id;

    public function __construct($db, $seller_id = 0) {
        $this->db = $db;
        $this->seller_id = $seller_id;
    }

    //Sign Up Function
    public function signUp($first_name, $last_name, $email, $password, $confirmPassword) {
        // Sanitize email
        $email = $this->db->real_escape_string($email);

        // Check if the email already exists
        $query = "SELECT * FROM sellers WHERE email = ?";
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
        $query = "INSERT INTO sellers (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                // Build user data array
                $user = [
                    'seller_id' => $stmt->insert_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'contact_number' => '',
                    'address' => '',
                ];
                
                // Start a session and store seller details
                $this->startSession($user);
                header("Location: seller_login.php");
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

        // Prepare query to fetch seller's details
        $query = "SELECT seller_id, first_name, last_name, email, password, contact_number, address FROM sellers WHERE email = ?";
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
                header("Location: seller_page.php");
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
        $_SESSION['seller_id'] = $user['seller_id'];
        $_SESSION['seller_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['seller_email'] = $user['email'];
        $_SESSION['seller_contact_number'] = $user['contact_number'];
        $_SESSION['seller_address'] = $user['address'];
    }

    public function getProfileDetails() {
        $query = "SELECT first_name, last_name, contact_number, email, address FROM sellers WHERE seller_id = ?";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "i", $this->seller_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $first_name, $last_name, $contact_number, $email, $address);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Return an associative array with sanitized profile details
        return [
            'name' => htmlspecialchars($first_name) . ' ' . htmlspecialchars($last_name),
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
            header("Location: edit_seller_profile.php");
            exit();
        }
    }

        // Method to edit seller profile
    public function editProfileDetails($name, $contactNumber, $email, $address) {
        $query = "UPDATE sellers SET contact_number = ?, address = ? WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iss", $contactNumber, $address, $email);
    
        if ($stmt->execute()) {
            $_SESSION['seller_name'] = $name;
            $_SESSION['seller_contact_number'] = $contactNumber;
            $_SESSION['seller_email'] = $email;
            $_SESSION['seller_address'] = $address;
            return true;
        }
        return false;
    }

    // Function to display products
    public function displayProducts() {
        $sql = "SELECT * FROM products";
        $result = mysqli_query($this->db, $sql);
        return $result;
    }

    // Method to add a product
    public function addProduct($data, $file) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate form inputs
            $item_name = $data['item_name'] ?? '';
            $item_desc = $data['item_desc'] ?? '';
            $item_price = $data['item_price'] ?? 0;
            $stocks = $data['stocks'] ?? 0;
            $product_category = $data['product_category'] ?? '';
            
            $item_image = $this->uploadImage($file);
    
            if ($item_image) {
                $sql = "INSERT INTO products (item_name, item_image, item_desc, item_price, stocks, product_category)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->bind_param("sssdis", $item_name, $item_image, $item_desc, $item_price, $stocks, $product_category);
                
                if ($stmt->execute()) {
                return "<div class='productAddedSuccessfully-message'>Product added successfully!</div>";
                } else {
                    error_log("Error adding product: " . $stmt->error);
                    return "Error adding product.";
                }
            }
        }
    }


    // Method to upload the image
    private function uploadImage($file) {
        if (isset($file['item_image']) && $file['item_image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $file['item_image']['tmp_name'];
            $imageName = $file['item_image']['name'];
            $imagePath = 'Images/' . $imageName;
    
            // Create directory if it doesn't exist
            if (!is_dir('Images/')) {
                mkdir('Images/', 0777, true);
            }
    
            // Move the uploaded file
            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                return $imageName; // Return the image name to be stored in the database
            }
        }
        return false;
    }

       
    // Method to delete a product
    public function deleteProduct($product_id) {
        $delete_query = "DELETE FROM `products` WHERE product_id = ?";
        $stmt = mysqli_prepare($this->db, $delete_query);
        mysqli_stmt_bind_param($stmt, 'i', $product_id);
        mysqli_stmt_execute($stmt);
        return "<div class='productDeletedSuccessfully-message'>Product deleted successfully!</div>";

    } 
    
    // Method to get product details
    public function getProductDetails($product_id) {
        $query = "SELECT * FROM products WHERE product_id = ?";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        if ($result && $product = mysqli_fetch_assoc($result)) {
            // Sanitize product details
            $product = array_map('htmlspecialchars', $product);

            mysqli_stmt_close($stmt);
            return $product; // Return the sanitized product details as an associative array
        } 
    }



    
    // Method to edit a product
    public function editProductDetails($productId, $data, $file) {
        // Sanitize inputs
        $item_name = mysqli_real_escape_string($this->db, $data['item_name']);
        $item_desc = mysqli_real_escape_string($this->db, $data['item_desc']);
        $item_price = (float)$data['item_price'];
        $stocks = (int)$data['stocks'];
        $product_category = mysqli_real_escape_string($this->db, $data['product_category']);

        // Check if a new image file is uploaded
        if (!empty($file['item_image']['name'])) {
            $item_image = $this->uploadImage($file); // Assumes uploadImage is defined to handle file upload
            if (!$item_image) {
                return "Error: Image upload failed.";
            }
        } else {
            // Keep the current image if no new image is uploaded
            $item_image = $data['current_image']; // Pass current image as part of $data
        }

        // Update product in database
        $query = "UPDATE products SET item_name = ?, item_image = ?, item_desc = ?, item_price = ?, stocks = ?, product_category = ? WHERE product_id = ?";
        $stmt = mysqli_prepare($this->db, $query);

        if (!$stmt) {
            return "Error: " . mysqli_error($this->db);
        }

        mysqli_stmt_bind_param($stmt, "sssdisi", $item_name, $item_image, $item_desc, $item_price, $stocks, $product_category, $productId);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            return true;
        } else {
            $error = mysqli_error($this->db);
            mysqli_stmt_close($stmt);
            return "Error: " . $error;
        }
    }

    
 
    
    


    
    

}