<?php 
 
    require_once 'Controllers/server.php';
    require_once 'Controllers/Product.php';
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: auth/login.php');
    }
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: auth/login.php");
    }
 

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database_name = 'titansgym';
    try {
        $dataobj = new PDO('mysql:host=' . $host . ';dbname=' . $database_name, $username, $password);
   
        $dataobj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $cart = new Cart($dataobj);
        $userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

     
        $query = "SELECT COUNT(*) AS cart_count FROM add_cart WHERE userID = :userId";
        $stmt = $dataobj->prepare($query);
        
      
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        $cartCount = $result['cart_count'];

        $stmt = $dataobj->query("SELECT * FROM gym_equipment");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
  
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = $_POST['product_id'];
        
        $quantity = (int)$_POST['quantity'];
    
        $selectedProduct = null;
        foreach ($products as $product) {
            if ($product['id'] == $productId) {
                $selectedProduct = new Product($product['id'], $product['title'], $product['description'], $product['thumbnail'], $product['price']);
                break;
            }
        }

        if ($selectedProduct) {
            $cart->addToCart($selectedProduct->getId(),$selectedProduct->getTitle(), $selectedProduct->getPrice(), $quantity,$selectedProduct->getThumbnail());
            
            $_SESSION['cart_items'] = $cart->getCartItems();
            
            $_SESSION['item_added'] = true;
            
            header('Location: products.php');
            exit;
        }
    }
    
    
    $searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : '';

    // Filter products based on the search query
    $filteredProducts = [];
    foreach ($products as $product) {
        // Check if the search query is found in the product title
        if (stripos($product['title'], $searchQuery) !== false) {
            $filteredProducts[] = $product;
        }
    }

    $products = $searchQuery ? $filteredProducts : $products;

    $userType = isset($_SESSION['usertype']) ? $_SESSION['usertype'] : ''; 


    // Handle product deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
        $deleteProductId = $_POST['delete_product_id'];
        // Perform the deletion from the database
        $stmt = $dataobj->prepare("DELETE FROM gym_equipment WHERE id = :id");
        $stmt->bindParam(':id', $deleteProductId, PDO::PARAM_INT);
        $stmt->execute();

        // Set a session variable to indicate successful deletion
        $_SESSION['product_deleted'] = true;

        header('Location: products.php');
        exit;
    }   
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gym Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body>
    <!-- header section starts  -->
    <header class="header">
        <a href="index.php" class="logo">
            <i class="fas fa-shopping-basket"></i> <i class="fa-solid fa-right-from-bracket"></i> Gym Store
        </a>
        <div class="d-flex gap-2 flex-md-row flex-column-reverse align-items-center justify-content-center">
            <!-- Add the search form at the top of your HTML code -->
            <?php if ($userType !== 'admin') : ?>
                <form action="products.php" method="get" >
                    <div class="input-group">
                        <input type="text" class="form-control shadow-none" value="<?php echo $searchQuery ?>" style="height:60px" placeholder="Search for products" name="search_query">
                        <button class="btn search_btn" type="submit">Search</button>
                    </div>
                </form>
            <?php endif; ?>
            <div class="d-flex gap-2 align-items-center justify-content-center">
                <?php if ($userType !== 'admin') : ?>
                    <div class="icons">
                        <a href="cart.php" class="text-decoration-none">
                            <div class="fas fa-shopping-cart" id="cart-btn" name="cart_button">
                                <span id="count_cart_value"><?php echo $cartCount;?></span>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if ($userType == 'admin') : ?>
                    <div class="icons">
                        <a href="create_product.php" class="text-decoration-none add_cart_btn" >
                        Create Product
                        </a>
                    </div>
                <?php endif; ?>
                <div class="icons">
                    <div class="w-auto" id="cart-btn"><a href="products.php?logout='1'" class="text-decoration-none">LogOut</a>
                </div>
            </div>
        </div>
        </div>
    </header>
    <!-- header section ends -->
    <div class="container" style="margin-top: 120px; margin-bottom: 80px">

        <div class="row">
            <?php
            foreach ($products as $product) : ?>
            <div class="col-12 col-md-6 col-lg-4 mt-3">
                <form action="products.php" method="post">
                    <div class="card productCard">
                        <div class="card_img_box">
                            <img src="<?php echo $product['thumbnail']; ?>" class="card-img-top w-100" alt="Product Image"
                            style="height: 100%; object-fit:cover">
                        </div>
                        <div class="card-body">
                            <?php if (isset($product['title'])) : ?>
                            <h5 class="card-title"><?php echo $product['title']; ?></h5>
                            <?php endif; ?>
                            <p class="card-text"><?php echo $product['description']; ?></p>
                            <p class="card-text">Price: $<?php echo $product['price']; ?></p>
                            <div class="d-flex align-items-center justify-content-between"> 
                                <?php if ($userType !== 'admin') : ?>
                                    <div class="quantity-control">
                                        <button type="button" onclick="decrementQuantity(this)">-</button>
                                        <input type="text" name="quantity" value="1" min="1" max="10" required>
                                        <button type="button" onclick="incrementQuantity(this)">+</button>
                                    </div>
                                    <button class="btn add_cart_btn" type="submit">Add to Cart</button>
                                <?php endif; ?>
                                <?php if ($userType == 'admin') : ?>
                                    <a href='create_product.php?edit=<?php echo $product["id"]; ?>' class='btn btn-primary'><i class='bi bi-pencil'></i> Edit</a>
                                    <form action="products.php" method="post">
                                        <input type="hidden" name="delete_product_id" value="<?php echo $product["id"]; ?>">
                                        <button type="submit" class="btn btn-danger" name="delete_product">
                                            <i class='bi bi-trash'></i> Delete
                                        </button>
                                    </form>
                                    
                                <?php endif; ?>
                                <input type='hidden' name='product_id' value='<?php echo $product["id"]; ?>'>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="toast-container">
        <?php
    if (isset($_SESSION['item_added'])) {
        echo '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                    Item has been added to the cart!
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>';
        unset($_SESSION['item_added']);
    }

    if (isset($_SESSION['product_deleted'])) {
        echo '<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body">
                    Product deleted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>';
        unset($_SESSION['product_deleted']);
    }
    ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script>
    
    function incrementQuantity(input) {
        var currentQuantity = parseInt(input.previousElementSibling.value);
        var maxQuantity = parseInt(input.previousElementSibling.getAttribute('max'));
        if (currentQuantity < maxQuantity) {
            input.previousElementSibling.value = currentQuantity + 1;
        }
    }

   
    function decrementQuantity(input) {
        var currentQuantity = parseInt(input.nextElementSibling.value);
        if (currentQuantity > 1) {
            input.nextElementSibling.value = currentQuantity - 1;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl);
        });
        toastList.forEach(function(toast) {
            toast.show();
        });
    });
    </script>
</body>

</html>