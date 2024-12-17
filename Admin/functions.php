<?php
include("database_connection.php");

class Functions {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
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
                $stmt = mysqli_prepare($this->db, $sql);
                mysqli_stmt_bind_param($stmt, "sssdis", $item_name, $item_image, $item_desc, $item_price, $stocks, $product_category);
                
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    return true;
                } else {
                    return "Error: " . mysqli_error($this->db);
                }
            }
        }
    }
    
     // Method to check if the products table is empty
     public function isProductsEmpty() {
        $query = "SELECT COUNT(*) as count FROM products";
        $result = mysqli_query($this->db, $query);
        $row = mysqli_fetch_assoc($result);
        return $row['count'] == 0; // Returns true if no products are found
    }

    // add products to Cart
    public function addToCart() {
        include("database_connection.php");
        if (isset($_POST['add_to_cart'])) {
            $item_name = $_POST['item_name'];
            $item_image = $_POST['item_image'];
            $item_price = $_POST['item_price'];
            $item_quantity = 1;
        
            // Ensure the query uses the correct column name
            $select_cart_query = "SELECT * FROM `cart` WHERE item_name = '$item_name'";
            $select_cart = mysqli_query($connect, $select_cart_query);
        
            // Check for query errors
            if (!$select_cart) {
                die("Query failed: " . mysqli_error($connect));
            }
        
            if (mysqli_num_rows($select_cart) > 0) {
                $message[] = 'Product already added to cart';
            } else {
                // Correct the insert query syntax
                $insert_product_query = "
                    INSERT INTO `cart` (item_name, item_image, item_price, item_quantity) 
                    VALUES ('$item_name', '$item_image', '$item_price', '$item_quantity')
                ";
                $insert_product = mysqli_query($connect, $insert_product_query);
        
                // Check for insert query errors
                if ($insert_product) {
                    $message[] = 'Product added to cart successfully';
                } else {
                    die("Error adding to cart: " . mysqli_error($connect));
                }
            }
        }
    }
    
    // Method to upload the image
    private function uploadImage($file) {
        if (isset($file['item_image']) && $file['item_image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $file['item_image']['tmp_name'];
            $imageName = $file['item_image']['name'];
            $imagePath = 'ShopCartv11/' . $imageName;

            // Create directory if it doesn't exist
            if (!is_dir('ShopCartv11/')) {
                mkdir('ShopCartv11/', 0777, true);
            }

            // Move the uploaded file
            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                return $imageName; // Return the image name to be stored in the database
            }
        }
        return false;
    }

    // Method to fetch products
    public function getProducts() {
        $sql = "SELECT * FROM products";
        $result = mysqli_query($this->db, $sql);
        return $result;
    }

    // Method to fetch products from buyer's page
    public function getProductsAddedToCart() {
        $result = mysqli_query($this->db, "SELECT * FROM cart");
        
       
        return $result;
    }


    
// Method to delete a product
    public function deleteProduct($product_id) {
        $sql = "DELETE FROM products WHERE product_id = $product_id";
        return mysqli_query($this->db, $sql);
    }



    // Method to get a product by ID for editing
    public function getProductById($product_id) {
        $sql = "SELECT * FROM products WHERE product_id = '$product_id'";
        $result = mysqli_query($this->db, $sql);
        return mysqli_fetch_assoc($result);
    }

    // Method to update a product
    public function updateProduct($productId, $data, $file) {
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
    public function get_buyer_details() {
        $sql = "SELECT * FROM buyers";
        $result = mysqli_query($this->db, $sql);
        return $result;
    }

    // Function to check profile completeness
    function isProfileComplete($buyer_id, $connect) {
        $buyer_query = "SELECT fname, lname, contact_number, email, address FROM buyers WHERE id = ?";
        $stmt = mysqli_prepare($connect, $buyer_query);

        if (!$stmt) {
            die("Statement Preparation Failed: " . mysqli_error($connect));
        }

        mysqli_stmt_bind_param($stmt, 'i', $buyer_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($buyer_row = mysqli_fetch_assoc($result)) {
            return !empty($buyer_row['fname']) &&
                   !empty($buyer_row['lname']) &&
                   !empty($buyer_row['contact_number']) &&
                   !empty($buyer_row['email']) &&
                   !empty($buyer_row['address']);
        }
        return false;
    }

    //Method for displaying user-profile
    public function displa_user_info() {
        $sql = "SELECT * FROM buyers";
        $result = mysqli_query($this->db, $sql);
        return $result;
    } 
}




?>
