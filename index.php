
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

    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
    
    $userType = isset($_SESSION['usertype']) ? $_SESSION['usertype'] : ''; // Assuming user type is stored in the session

?>

<!DOCTYPE html>
<html lang="en">
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
                    <div class="w-auto" id="cart-btn"><a href="index.php?logout='1'" class="text-decoration-none">LogOut</a>
                </div>
            </div>
        </div>
        </div>
    </header>
    <!-- header section ends -->

    <!-- Hero Section Begin -->
    <section class="banner_main" style="margin-top: 70px;">
         <div class="container-fluid">
            <div class="row d_flex">
               <div class="col-md-6">
                  <div class="text-bg">
                     <div class="padding_lert">
                        <h1>GYM AND FITNESS</h1>
                        <span>ULTIMATE FITNESS</span>
                        <p>Discover a transformative fitness experience tailored just for you. Our cutting-edge facilities, expert trainers, and vibrant community create the perfect environment to sculpt your body, boost your energy, and achieve your health goals. Embrace the strength within – join us on the path to a healthier, happier you! #FitnessGoals #GymLife #HealthyLiving</p>
                        <a class="read_more add_cart_btn text-decoration-none" href="products.php">View Products</a>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="text-img">
                     <figure><img src="./assets/images/toy_img.png" alt="banner-main-page"/></figure>
                  </div>
               </div>
            </div>
         </div>
    </section>
    <!-- Hero Section End -->

</body>
</html>