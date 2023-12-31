<!-- create_product.php -->

<?php
require_once 'Controllers/server.php';
require_once 'Controllers/Product.php';

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database_name = 'titansgym';
    try {
        $dataobj = new PDO('mysql:host=' . $host . ';dbname=' . $database_name, $username, $password);
   
        $dataobj->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }

    
    if (isset($_GET['edit'])) {
        $editProductId = $_GET['edit'];        
    
        // Retrieve product data by ID
        $stmt = $dataobj->prepare("SELECT * FROM gym_equipment WHERE id = :id");
        $stmt->bindParam(':id', $editProductId, PDO::PARAM_INT);
        $stmt->execute();
        $productData = $stmt->fetch(PDO::FETCH_ASSOC);
    
       
        $title = $productData['title'];
        $description = $productData['description'];
        $thumbnail = $productData['thumbnail'];
        $price = $productData['price'];
    } else {
        // Initialize form fields with empty values if it's a new product
        $title = '';
        $description = '';
        $thumbnail = '';
        $price = '';
    }
    


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $title = $_POST['title'];
    $description = $_POST['description'];
    $thumbnail = $_POST['thumbnail'];
    $price = $_POST['price'];


    if (isset($_GET['edit'])) {
        $editProductId = $_GET['edit'];
        Product::updateProduct($dataobj, $editProductId, $title, $description, $thumbnail, $price);
    } else {
      
        Product::createProduct($dataobj, $title, $description, $thumbnail, $price);
    }

    header('Location: products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/style.css"> -->
    <link rel="stylesheet" type="text/css" href="assets/css/cart.css">
    <title>Create Product</title>
</head>
<body>
    <header class="header">
        <a href="index.php" class="logo">
            <i class="fas fa-shopping-basket"></i> <i class="fa-solid fa-right-from-bracket"></i> Gym Store
        </a>
        <div class="d-flex gap-2 flex-md-row flex-column-reverse align-items-center justify-content-center">
            <div class="d-flex gap-2 align-items-center justify-content-center">
                <div class="icons">
                    <div class="w-auto" id="cart-btn"><a href="index.php?logout='1'" class="text-decoration-none">LogOut</a>
                </div>
            </div>
        </div>
        </div>
    </header>
    <div class="container cart_container" style="margin-top: 120px">

        <h2 class="text-light"><?php echo (!isset($_GET['edit'])) ? "Create Product" : "Edit Product" ?></h2>
        <div class="card-form">
            <form class="col-12 col-md-6 mt-5 mx-auto cart_container" method="POST" action="create_product.php<?php echo (isset($_GET['edit'])) ? '?edit=' . $_GET['edit'] : ''; ?>">
                <div class="mb-3">
                    <label for="title" class="form-label text-light">Title</label>
                    <input type="text" name="title" class="form-control shadow-none" value="<?php echo $title ?>" id="title"  require/>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label text-light">Description</label>
                    <input type="text" name="description" class="form-control shadow-none" value="<?php echo $description ?>" id="description" require/>
                </div>
                <div class="mb-3">
                    <label for="thumbnail" class="form-label text-light">Thumbnail URL</label>
                    <input type="text" name="thumbnail" class="form-control shadow-none" value="<?php echo $thumbnail ?>" id="thumbnail" require/>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label text-light">Price</label>
                    <input type="number"  name="price" class="form-control shadow-none" value="<?php echo $price ?>" id="price" require/>
                </div>
                <?php if (isset($_GET['edit'])) : ?>
                    <input type="hidden" name="editProductId" value="<?php echo $editProductId; ?>">
                <?php endif; ?>
                <button type="submit" class="btn add_cart_btn checkout_btn text-decoration-none"><?php echo (!isset($_GET['edit'])) ? "Create Product" : "Update Product" ?></button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>

</body>
</html>
