<?php
session_start();
require_once 'Controllers/PdfGenerator.php';
if (isset($_SESSION['checkout_info'])) {
  $checkoutInfo = $_SESSION['checkout_info'];
  $total = $checkoutInfo['total'];
  $name = $checkoutInfo['name'];
  $phone = $checkoutInfo['phone'];
  $email = $checkoutInfo['email'];
  $address = $checkoutInfo['address'];
  $cartItemsForReceipt = $checkoutInfo['cartItems'];
  
} else {
  die('Error: Missing checkout information.');
}

if (isset($_POST['generate_pdf']) && $_POST['generate_pdf'] == 1){

    
    $pdfGenerator = new PdfGenerator();
    $cartItemsJsonPdf = urldecode($cartItemsForReceipt);
    $cartItemsForPdf = json_decode($cartItemsJsonPdf, true);
    $pdfGenerator->generatePDF($total, $name, $phone, $email, $address, $cartItemsForPdf);
    unset($_SESSION['checkout_info']);
    exit;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/cart.css">
    <link rel="stylesheet" type="text/css" href="assets/css/checkout_form.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <title>Order Receipt</title>
  </head>
  <body>
    <header class="header">
          <a href="index.php" class="logo">
              <i class="fas fa-shopping-basket"></i> <i class="fa-solid fa-right-from-bracket"></i> Gym Store
          </a>
          <div class="d-flex gap-2 align-items-center">
              <div class="icons">
                  <div class="w-auto" id="cart-btn"><a href="products.php?logout='1'" class="text-decoration-none">LogOut</a>
                  </div>
              </div>
          </div>
      </header>
    <div class="card-form" style="margin-top: 120px">
        <h1>Receipt</h1>
        <div id="myContainer">
            <div class="cart_summery">
              <?php
                
                if (isset($cartItemsForReceipt)) {
                  $cartItemsJson = urldecode($cartItemsForReceipt);
                  $cartItems = json_decode($cartItemsJson, true);
                  foreach ($cartItems as $item) {
                      echo '<div class="cart_wrepper">';
                      echo '<h6>' . $item['name'] . '</h6>';
                      echo '<p>Quantity:' . $item['quantity'] . '</p>';
                      echo '<div class="price">';
                      echo '<span>Price: $' . $item['price'] . '</span>';
                      echo '</div>';
                      echo '</div>';
                  }
                } else {
                  echo '<div>No items found in the cart.</div>';
                }
                ?>
            </div>
           
        </div>
        <div id="grand_total" class="cart_total_price">Total Price: $<?php echo $total; ?></div>
  
        <h1 class="user_heading">User Information</h1>
        <div id="userContainer">
            <div class="nameWrapper">
              <h3>User Name: <span id="username"><?php echo $name; ?></span></h3>
              <h3>Mobile no.: <span id="mobile"><?php echo $phone; ?></span></h3>
              <h3>Email: <span id="email"><?php echo $email; ?></span></h3>
              <h3>Address: <span id="address"><?php echo $address; ?></span></h3>
            </div>
          </div>
          <form id="generateInvoiceForm" method="post" target="_blank">
          <input type="hidden" name="generate_pdf" value="1" />
       
          <input type="hidden" name="total" value="<?php echo $total; ?>" />
          <input type="hidden" name="name" value="<?php echo $name; ?>" />
          <input type="hidden" name="phone" value="<?php echo $phone; ?>" />
          <input type="hidden" name="email" value="<?php echo $email; ?>" />
          <input type="hidden" name="address" value="<?php echo $address; ?>" />
          <input type="hidden" name="cartItems" value="<?php echo urlencode($cartItemsForReceipt); ?>" />
          <button type="submit" class="add_cart_btn checkout_btn text-decoration-none d-flex text-end ms-auto">Generate Invoice</button>
        </form>
         
    </div>
</body>
</html>