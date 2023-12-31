<?php
    include "Controllers/server.php";
   
    require_once 'Controllers/Product.php';
    require_once 'Controllers/Order.php';
    if (!isset($_SESSION['username'])) {
      $_SESSION['msg'] = "You must log in first";
      header('location: auth/login.php');
      exit;
    }
    
    if (isset($_SESSION['cart_items'])) {
      $cartItems = $_SESSION['cart_items'];
    } else {
      $cartItems = [];
    }
    $cartItemsForReceipt = $cartItems;


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
    $userId = $_SESSION['id']; 
    $cart = new Cart($dataobj);
   
    $nameErr = $phoneErr = $emailErr = $passwordErr = $cpasswordErr = $postal_codeErr = $addressErr = $cityErr = $provinceErr = $card_numberErr = $card_holderErr = $card_monthErr = $card_yearErr = $card_cvvErr = $price_Err = "";
    $name = $phone = $email = $password = $cpassword = $postal_code = $address = $city = $province = $card_number = $card_holder = $card_month = $card_year = $card_cvv = "";
    $successMessage = '';
    $processingSuccess = false;
    $total = 0;
    if(isset($_POST['submit'])){
      
      // Name Section
      if($_POST["name"]==""){
        $nameErr = "Name is required";
        
      } else {
        $name = test_input($_POST["name"]);
        //Check if name only contains letters and whitespace
        if(!preg_match("/^[a-zA-Z\s]+$/",$name)){
          $nameErr = "Enter Valid name please!";
        }
      }

      // Phone Section
      if(empty($_POST["phone"])){
        $phoneErr = "Phone number is required";
      } else {
        $phone = test_input($_POST["phone"]);
        //Check if phone only contains number and 10 digit only
        if(!preg_match('/^[0-9]{10}+$/', $phone)){
          $phoneErr = "Please enter Valid phone number !";
        }
      }

      // Email Section  
      if(empty($_POST["email"])){
        $emailErr = "Email is required";
      } else {
        $email = test_input($_POST["email"]);
        //Check if e-mail address is correct
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          $emailErr = "Invalid email address";
        }
      }

      // Address Section 
      if(empty($_POST["address"])){
        $addressErr = "Please enter your address!";
      } else {
        $address = test_input($_POST["address"]);
      }
      
      // City Section 
      if(empty($_POST["city"])){
        $cityErr = "Please enter your city!";
      } else {
        $city = test_input($_POST["city"]);
      }

      // Card Number Section 
      if(empty($_POST["card_number"])){
        $card_numberErr = "Please enter the card number.";
      } else {
        $card_number = test_input($_POST["card_number"]);
        $length = strlen($card_number) - substr_count($card_number, ' ');
        if ($length < 16) {
          $card_numberErr = "Please enter a valid card number. ex. xxxx-xxxx-xxxx-xxxx";
        }
      }

      // Card Name Section 
      if(empty($_POST["card_holder"])){
        $card_holderErr = "Please enter the card holder name.";
      } else {
        $card_holder = test_input($_POST["card_holder"]);
        if(!preg_match("/^[a-zA-Z\s]+$/", $card_holder)){
          $card_holderErr = "Card holder Name must be in Alphabets.";
        }
      }

      // Card Month Section 
      if(empty($_POST["card_month"])){
        $card_monthErr = "Please enter the card month.";
      } else {
        $card_month = test_input($_POST["card_month"]);
        $months = array("JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC", "jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");
        if(!in_array($card_month, $months)){
          $card_monthErr = "Please Enter a valid Month";
        }
      }

      // Card Year Section 
      if(empty($_POST["card_year"])){
        $card_yearErr = "Please enter the card year.";
      } else {
        $card_year = test_input($_POST["card_year"]);
        if(!preg_match('/^[0-9]{10}+$/', $card_year) && strlen($card_year) < 4){
          $card_yearErr = "Please enter Valid Year!";
        }
      }

      // Card CVV Section 
      if(empty($_POST["card_cvv"])){
        $card_cvvErr = "Please enter the card cvv.";
      } else {
        $card_cvv = test_input($_POST["card_cvv"]);
        if(!preg_match('/^[0-9]{3,4}$/', $card_cvv) || strlen($card_cvv) < 3){
          $card_cvvErr = "Please enter Valid CVV!";
        }
      }
      $order = new Order($dataobj, $userId, $cart);
      $totalPrice = $cart->getTotalPrice();
      $cartItemsJson = urlencode(json_encode($cartItemsForReceipt));
      $_SESSION['checkout_info'] = [
        'total' => $totalPrice,
        'name' => $_POST['name'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'cartItems' => $cartItemsJson,
      ];
      $order->processCheckout();
      $successMessage = "Your order has been processed successfully!";
      header("Location: receipt.php");
      exit;
    }
    function test_input($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Checkout Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/cart.css">
    <link rel="stylesheet" type="text/css" href="assets/css/checkout_form.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body>
  <header class="header">
        <a href="index.php" class="logo">
            <i class="fas fa-shopping-basket"></i> <i class="fa-solid fa-right-from-bracket"></i> Gym Store
        </a>
        <div class="d-flex gap-2 align-items-center">
            <div class="icons">
              <a href="cart.php" class="text-decoration-none">
                <div class="fas fa-shopping-cart" id="cart-btn" name="cart_button">
                <span id="count_cart_value"><?php echo $cartCount;?></span>
                </div>
              </a>
            </div>
            <div class="icons">
                <div class="w-auto" id="cart-btn"><a href="index.php?logout='1'" class="text-decoration-none">LogOut</a>
                </div>
            </div>
        </div>
    </header>
    <div class="card-form" style="margin-top: 120px">
        <h1>Cart Summary</h1>
        <div id="myContainer">
            <div class="cart_summery">
              <?php 
              $totalPrice = 0;
              foreach ($cartItems as $item) : ?>
                <div class="cart_wrepper">
                    <h6><?php echo $item['name'] ?></h6>
                    <p >Quantity: <?php echo $item['quantity'] ?></p>
                    <div class="price">
                        <span>Price: $<?php echo $item['price']; ?></span>
                    </div>
                </div>
                <?php
                $totalPrice += $item['price'] * $item['quantity'];
                endforeach; 
                ?>
            </div>
            
            <div id="total_price" class="cart_total_price">Total Price: $<?php echo $totalPrice; ?></div>
            <span id="Price_error" class="error"><?php echo $price_Err;?></span>
        </div>
    </div>
    <div class="card-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h1>Checkout Form</h1>
            <?php if (!empty($successMessage)) : ?>
              <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>
            <div class="form-body">
              <div class="form-group mb-3">
                  <label for="name">Name</label>
                  <input type="text" id="name" name="name" placeholder="Enter Name" class="form-control"  value="<?php echo $name; ?>" data-error-message="Name is required" required/>
                  <span id="name-error" class="error"></span>
              </div>
              <div class="form-group mb-3">
                  <label for="phone">Phone</label>
                  <input type="text" id="phone" name="phone" placeholder="Enter Phone" class="number form-control"
                      maxlength="10"  value="<?php echo $phone; ?>" data-error-message="Phone is required" required/>
                  <span id="phone-error" class="error"><?php echo $phoneErr;?></span>
              </div>
              <div class="form-group mb-3">
                  <label for="email">Email</label>
                  <input type="email" id="email" name="email" placeholder="Enter Email" class="number form-control" data-error-message="Email is required"
                      value="<?php echo $email; ?>" required/>
                  <span id="email-error" class="error"><?php echo $emailErr;?></span>
              </div>
              <div class="form-group mb-3">
                  <label for="address">Address</label>
                  <input type="text" id="address" name="address" placeholder="Enter Your Address"  data-error-message="Please enter your address!"
                      class="number form-control" value="<?php echo $address; ?>" required/>
                  <span id="address-error" class="error"><?php echo $addressErr;?></span>
              </div>
              <div class="form-group mb-3">
                  <label for="city">City</label>
                  <input type="text" id="city" name="city" placeholder="Enter Your city" class="number form-control"  data-error-message="Please enter your city!"
                      value="<?php echo $city; ?>" required/>
                  <span id="city-error" class="error"><?php echo $cityErr;?></span>
              </div>
              <hr class="horizontal_line" />
              <div class="form-group mb-3">
                  <label for="card_number">Card Number</label>
                  <input type="text" id="card_number" name="card_number" placeholder="xxxx-xxxx-xxxx-xxxx"  data-error-message="Please enter the card number"
                      class="number card_number form-control" maxlength="16"
                      value="<?php echo wordwrap($card_number, 4 , ' ' , true ); ?>" required/>
                  <span id="card_number-error" class="error"></span>
              </div>
              <div class="form-group mb-3">
                  <label for="card_holder">Card Holder</label>
                  <input type="text" id="card_holder" name="card_holder" placeholder="John Doe"  data-error-message="Please enter the card holder name"
                      class="inputname form-control" value="<?php echo strtoupper($card_holder); ?>" required/>
                  <span id="card_holder-error" class="error"><?php echo $card_holderErr;?></span>
              </div>
              <div class="expiry_wrapper">
                  <div class="expiry_box">
                      <div class="form-group mb-3">
                          <label for="card_month">Expiry month</label>
                          <input class="expire form-control" type="text" name="card_month" placeholder="MMM"  data-error-message="Please enter the card month"
                              id="card_month" maxlength="3" value="<?php echo strtoupper($card_month); ?>" required/>
                          <span id="card_month-error" class="error"><?php echo $card_monthErr;?></span>
                      </div>
                  </div>
                  <div class="expiry_box">
                      <div class="form-group mb-3">
                          <label for="card_year">Expiry Year</label>
                          <input class="expire form-control" type="text" placeholder="YYYY" id="card_year"  data-error-message="Please enter the card year"
                              name="card_year" maxlength="4" value="<?php echo $card_year; ?>" required/>
                          <span id="card_year-error" class="error"><?php echo $card_yearErr;?></span>
                      </div>
                  </div>
              </div>
              <div class="form-group mb-3">
                  <label for="card_cvv">CVV</label>
                  <input type="text" id="card_cvv" name="card_cvv" placeholder="123" class="ccv form-control"  data-error-message="Please enter the card cvv"
                      maxlength="3" value="<?php echo $card_cvv; ?>" required/>
                  <span id="card_cvv-error" class="error"><?php echo $card_cvvErr;?></span>
              </div>
            </div>
            <button type="submit" class="btn add_cart_btn" name="submit">Submit</button>

        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script>
   function showValidationMessage(inputFieldId, errorMessage) {
        var errorElement = document.getElementById(inputFieldId + "-error");
        
        errorElement.textContent = errorMessage;
        errorElement.style.display = "block";
    }

    // Function to hide validation messages
    function hideValidationMessage(inputFieldId) {
        var errorElement = document.getElementById(inputFieldId + "-error");
        errorElement.style.display = "none";
    }

    // Event listener for blur on input fields
    document.addEventListener("DOMContentLoaded", function() {
        var inputFields = document.querySelectorAll(".form-control");
        
        inputFields.forEach(function(inputField) {
            inputField.addEventListener("blur", function() {
                var inputFieldId = inputField.getAttribute("id");
               
                var errorMessage = inputField.dataset.errorMessage;
                
                if (inputField.value.trim() === "") {
                    showValidationMessage(inputFieldId, errorMessage);
                } else {
                    hideValidationMessage(inputFieldId);
                }
            });
        });
    });
</script>
</body>

</html>