<?php
 include "Controllers/server.php"; 
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database_name = 'titansgym';
    try {
       
        $dataobj = new PDO('mysql:host=' . $host . ';dbname=' . $database_name, $username, $password);
        $dataobj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
  
        $query = "SELECT COUNT(*) AS cart_count FROM add_cart WHERE userID = :userId";
        $stmt = $dataobj->prepare($query);
        
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
        $cartCount = $result['cart_count'];
      } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
      }
    require_once 'Controllers/server.php';

    
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/cart.css">
    
</head>
<body>
    <!-- header section starts  -->
    <header class="header">
        <a href="index.php" class="logo">
            <i class="fas fa-shopping-basket"></i> <i class="fa-solid fa-right-from-bracket"></i> Gym Store
        </a>
        <div class="d-flex gap-2 align-items-center">
            <div class="icons">
                <div class="fas fa-shopping-cart" id="cart-btn" name="cart_button">
                <span id="count_cart_value"><?php echo $cartCount;?></span>
                </div>
            </div>
            <div class="icons">
                <div class="w-auto" id="cart-btn"><a href="index.php?logout='1'" class="text-decoration-none">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    <!-- header section ends -->
    <div class="container cart_container" style="margin-top: 120px">
        <div id="myContainer">
            <?php
            $userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;
            
            $cart = new Cart($dataobj, $userId);
            $cartItems = $cart->getCartItems();
            $_SESSION['cart_items'] = $cartItems;
            
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_POST['increment_quantity']) && is_numeric($_POST['increment_quantity'])) {
                        $cartItemId = (int)$_POST['increment_quantity'];
                        $cart->incrementQuantity($cartItemId);
                    } else if (isset($_POST['decrement_quantity']) && is_numeric($_POST['decrement_quantity'])) {
                        $cartItemId = (int)$_POST['decrement_quantity'];
                        $cart->decrementQuantity($cartItemId);
                    } else if (isset($_POST['remove_item']) && is_numeric($_POST['remove_item'])) {
                        $cartItemId = (int)$_POST['remove_item'];
                        $cart->removeItem($cartItemId);
                    }
            
                    // Redirect back to the cart page after form submission
                    header('Location: cart.php');
                    exit;
                }

                foreach ($cartItems as $cartItem) :
                ?>
                <div class="horizontal_card">
                    <div class="horizontal_card_image">
                        <img src="<?php echo $cartItem['image']; ?>" alt="<?php echo $cartItem['name']; ?>" width="100%" height="100%">
                    </div>
                    <div class="bottom">
                    <form action="" method="post" name="update_cart">
                        <div class="quantity">
                            <button class="decrement-btn" type="submit" name="decrement_quantity" value="<?php echo $cartItem['id']; ?>">-</button>
                            <input type="number" name="quantity" value="<?php echo $cartItem['quantity']; ?>" min="1" required>
                            <button class="increment-btn" type="submit" name="increment_quantity" value="<?php echo $cartItem['id']; ?>">+</button>
                        </div>
                        <button class="del_btn" type="submit" name="remove_item" value="<?php echo $cartItem['id']; ?>">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                    <h3><?php echo $cartItem['name']; ?></h3>
                    <p><?php echo $cartItem['description']; ?></p>
                    <div class="price">
                        <span>Price: $<?php echo $cartItem['price']; ?></span>
                    </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($cartItems)) : ?>
                <div class="horizontal_card no_record mb-0">
                    No Product Found...
                </div>
            <?php endif; ?>
            <?php if (!empty($cartItems)) : ?>
            <a id="checkout" type="button" class="add_cart_btn checkout_btn text-decoration-none" href="checkout_form.php">Proceed to Checkout</a>
            <?php endif; ?>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
        </script>
</body>
</html>
