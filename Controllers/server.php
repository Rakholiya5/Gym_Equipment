<?php
session_start();
class UserManagement
{
    private $db;
    private $errors = array();

    public function __construct($host, $username, $password, $database_name)
    {
        // Establish database connection
        $this->db = mysqli_connect($host, $username, $password, $database_name);

        // Check if the database connection is successful
        if (!$this->db) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Create the database and table if they don't exist
        $this->createDatabaseAndTable();
    }

    private function createDatabaseAndTable()
    {
        $database_name = "titansgym";
        $users_table = "users";
        $add_cart_table = "add_cart";
        $orders_table = "orders";
        $order_items_table = "order_items";
        $gym_equipment_table = "gym_equipment";
        $create_db_query = "CREATE DATABASE IF NOT EXISTS $database_name";
        $users_table_query = "CREATE TABLE IF NOT EXISTS $database_name.$users_table (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            usertype ENUM('user', 'admin') DEFAULT 'user',
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL
        )";

        $create_add_cart_query = "CREATE TABLE IF NOT EXISTS $database_name.$add_cart_table (
          id INT(11) AUTO_INCREMENT PRIMARY KEY,
          product_id INT(30) NOT NULL,
          name VARCHAR(255) NOT NULL,
          image VARCHAR(255) NOT NULL,
          description TEXT NOT NULL,
          price DECIMAL(10, 2) NOT NULL,
          userID INT(11),
          quantity int(30) NOT NULL,
          FOREIGN KEY (userID) REFERENCES users(id) ON DELETE CASCADE
        )";  
        
        $create_orders_query = "CREATE TABLE IF NOT EXISTS $database_name.$orders_table (
            id INT(30) AUTO_INCREMENT PRIMARY KEY,
            total_price DECIMAL(10, 2) NOT NULL,
            user_id INT(30) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
          )";    

        $create_order_items_query = "CREATE TABLE IF NOT EXISTS $database_name.$order_items_table (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            product_id INT(30) NOT NULL,
            quantity INT(30) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            order_id INT(30) NOT NULL,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        )";    

        $product_table_query = "CREATE TABLE IF NOT EXISTS $database_name.$gym_equipment_table (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description VARCHAR(255) NOT NULL,
            price DECIMAL(10, 2) NOT NULL,
            thumbnail VARCHAR(255) NOT NULL
        )";

       
        mysqli_query($this->db, $create_db_query);

 
        mysqli_query($this->db, $users_table_query);
        
       
        mysqli_query($this->db, $create_add_cart_query);
        mysqli_query($this->db, $create_orders_query);
        mysqli_query($this->db, $create_order_items_query);
        mysqli_query($this->db, $product_table_query);
    }

    public function registerUser($username, $email, $password_1, $password_2, $user_type)
    {
        $username = mysqli_real_escape_string($this->db, $username);
        $email = mysqli_real_escape_string($this->db, $email);
        $password_1 = mysqli_real_escape_string($this->db, $password_1);
        $password_2 = mysqli_real_escape_string($this->db, $password_2);

        // Form validation: Check for required fields and password match
        if (empty($username)) {
            $this->errors[] = "Username is required";
        }
        if (empty($email)) {
            $this->errors[] = "Email is required";
        }
        if (empty($password_1)) {
            $this->errors[] = "Password is required";
        }
        if ($password_1 != $password_2) {
            $this->errors[] = "The two passwords do not match";
        }

        // Check if a user already exists with the same username and/or email
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($this->db, $user_check_query);
        $user = mysqli_fetch_assoc($result);

        if ($user) { 
            if ($user['username'] === $username) {
                $this->errors[] = "Username already exists";
            }

            if ($user['email'] === $email) {
                $this->errors[] = "Email already exists";
            }
        }

        // If no errors, register the user
        if (empty($this->errors)) {
            $password = md5($password_1); 

            $query = "INSERT INTO users (username, email, password, usertype) VALUES ('$username', '$email', '$password', '$user_type')";
            mysqli_query($this->db, $query);
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
            header('location: login.php');
        }
    }

    public function loginUser($username, $password)
    {
        
        $username = mysqli_real_escape_string($this->db, $username);
        $password = mysqli_real_escape_string($this->db, $password);

        // Form validation: Check for required fields
        if (empty($username)) {
            $this->errors[] = "Username is required";
        }
        if (empty($password)) {
            $this->errors[] = "Password is required";
        }

        // If no errors, attempt to log in the user
        if (empty($this->errors)) {
            $password = md5($password);
            
            $query = "SELECT * FROM users WHERE email='$username' AND password='$password'";
            $results = mysqli_query($this->db, $query);
            if (mysqli_num_rows($results) == 1) {
                $user = mysqli_fetch_assoc($results);
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are now logged in";
                $_SESSION['usertype'] = $user['usertype'];
                header('location: ../index.php');
            } else {
                $this->errors[] = "Wrong username/password combination";
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}

// Usage:
$host = 'localhost';
$username = 'root';
$password = '';
$database_name = 'titansgym';

$userManagement = new UserManagement($host, $username, $password, $database_name);

// Handle registration
if (isset($_POST['reg_user'])) {
    $userManagement->registerUser($_POST['username'], $_POST['email'], $_POST['password_1'], $_POST['password_2'] , $_POST['usertype']);
}

// Handle login
if (isset($_POST['login_user'])) {
    $userManagement->loginUser($_POST['username'], $_POST['password']);
}

// To get errors (if any) after registration/login attempts
$errors = $userManagement->getErrors();


class Cart
{
    private $db;
    private $userId;
    private $items;
    public function __construct(PDO $dataobj)
    {
        $this->db = $dataobj;
        // You can set the user ID here when the user logs in or retrieve it from the session, depending on your authentication mechanism.
        $this->userId = $_SESSION['id']; 
        $this->items = [];
    }

    public function addToCart($product_id,$product_name, $price, $quantity,$image)
    {
        // Check if the item is already in the cart for the current user and update its quantity
        $stmt = $this->db->prepare("SELECT id, quantity FROM add_cart WHERE userID = :userID AND name = :name");
        $stmt->bindParam(':userID', $this->userId);
        $stmt->bindParam(':name', $product_name);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            $new_quantity = $row['quantity'] + $quantity;
            $this->updateCartItemQuantity($row['id'], $new_quantity);
        } else {
            // If the item is not in the cart, insert it as a new entry for the current user
            $stmt = $this->db->prepare("INSERT INTO add_cart (userID, product_id,name, price, quantity,image) VALUES (:userID,:product_id, :name, :price, :quantity,:image)");
            $stmt->bindParam(':userID', $this->userId);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':name', $product_name);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':image', $image);
            $stmt->execute();
        }
    }

    public function updateCartItemQuantity($cart_item_id, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE add_cart SET quantity = :quantity WHERE id = :id");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':id', $cart_item_id);
        $stmt->execute();
    }

    public function getCartItems()
    {
        $stmt = $this->db->prepare("SELECT * FROM add_cart WHERE userID = :userID");
        $stmt->bindParam(':userID', $this->userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function incrementQuantity($cartItemId)
    {
        $stmt = $this->db->prepare("UPDATE add_cart SET quantity = quantity + 1 WHERE id = :id AND userID = :userID");
        $stmt->bindParam(':id', $cartItemId);
        $stmt->bindParam(':userID', $this->userId);
        $stmt->execute();
    }

    public function decrementQuantity($cartItemId)
    {
        $stmt = $this->db->prepare("UPDATE add_cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = :id AND userID = :userID");
        $stmt->bindParam(':id', $cartItemId);
        $stmt->bindParam(':userID', $this->userId);
        $stmt->execute();
    }

    public function removeItem($cartItemId)
    {
        $stmt = $this->db->prepare("DELETE FROM add_cart WHERE id = :id AND userID = :userID");
        $stmt->bindParam(':id', $cartItemId);
        $stmt->bindParam(':userID', $this->userId);
        $stmt->execute();
    }
    public function getTotalPrice()
    {
        $totalPrice = 0;
        $cartItems = $this->getCartItems();

        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return $totalPrice;
    }
    public function clear() {
        $stmt = $this->db->prepare("DELETE FROM add_cart WHERE userID = :userID");
        $stmt->bindParam(':userID', $this->userId);
        $stmt->execute();
        $this->items = [];
    }
}



?>