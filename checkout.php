<?php
session_start();


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Method</title>
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
<style>
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f5f5f5;
}

.checkout-container {
  display: flex; 
  justify-content: space-between; 
  align-items: flex-start; 
  max-width: 1200px; 
  margin: 40px auto; 
  padding: 20px;
  background: #fff;
  border-radius: 10px;
}

.checkout-form, .cart-section {
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  background-color: #fff;
}

.checkout-form {
  flex: 2; 
  margin-right: 20px; 
}

.cart-section {
  flex: 1; 
}

h1, h2 {
  font-size: 20px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

.form-group input, .form-group select {
  width: 100%;
  padding: 10px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.submit-btn {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 16px;
}

.submit-btn:hover {
  background-color: #45a049;
}

.cart-item {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.cart-item-details {
  flex: 1;
}

.cart-item-name {
  font-size: 14px;
  font-weight: bold;
}

.cart-item-price, .cart-item-quantity {
  font-size: 14px;
  color: #555;
}

.cart-total {
  font-weight: bold;
  text-align: right;
  margin-top: 20px;
}

@media (max-width: 768px) {
  .checkout-container {
      flex-direction: column; 
  }

  .checkout-form {
      margin-right: 0;
      margin-bottom: 20px;
  }
}   
</style>

<div class="checkout-container">
    <div class="checkout-form">
        <h1>Payment Method</h1>
        <form id="checkout" action="checkout.php" method="post" onsubmit="handleFormSubmit(event)">
            
            <section class="checkout-section">
                <div class="form-group">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Bilal" required>
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Hussain" required>
                </div>

                <div class="form-group">
                    <label for="street">Billing Address:</label>
                    <input type="text" id="street" name="street" placeholder="1234 Elm St" required>
                </div>

                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" placeholder="New York" required>
                </div>

                <div class="form-group">
                    <label for="postcode">Zip or Postal Code:</label>
                    <input type="text" id="postcode" name="postcode" placeholder="10001" required>
                </div>

                <div class="form-group">
                    <label for="country">Country:</label>
                    <select id="country" name="country" required>
                        <option value="AF">Afghanistan</option>
                        <option value="AL">Albania</option>
                        <option value="DZ">Algeria</option>
                        <option value="AD">Andorra</option>
                        <option value="AO">Angola</option>
                        <option value="AR">Argentina</option>
                        <option value="AU">Australia</option>
                        <option value="AT">Austria</option>
                        <option value="BD">Bangladesh</option>
                        <option value="BE">Belgium</option>
                        <option value="BR">Brazil</option>
                        <option value="CA">Canada</option>
                        <option value="CN">China</option>
                        <option value="FR">France</option>
                        <option value="DE">Germany</option>
                        <option value="IN">India</option>
                        <option value="IT">Italy</option>
                        <option value="JP">Japan</option>
                        <option value="MX">Mexico</option>
                        <option value="RU">Russia</option>
                        <option value="SA">Saudi Arabia</option>
                        <option value="ZA">South Africa</option>
                        <option value="ES">Spain</option>
                        <option value="SE">Sweden</option>
                        <option value="CH">Switzerland</option>
                        <option value="UK">United Kingdom</option>
                        <option value="US">United States</option>
                    </select>
                </div>
            </section>

            <section class="checkout-section">
                <div class="form-group">
                    <label for="payment-method">Payment Method:</label>
                    <select id="payment-method" name="payment-method" required>
                        <option value="visa">Visa</option>
                        <option value="mastercard">MasterCard</option>
                        <option value="paypal">PayPal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="cardnumber">Card Number:</label>
                    <input type="text" id="cardnumber" name="cardnumber" placeholder="1111-2222-3333-4444" required>
                </div>

                <div class="form-group">
                    <label for="expdate">Expiration Date:</label>
                    <input type="text" id="expdate" name="expdate" placeholder="MM/YY" required>
                </div>

                <div class="form-group">
                    <label for="securitycode">Security Code:</label>
                    <input type="text" id="securitycode" name="securitycode" placeholder="CVV" required>
                </div>
            </section>

            <button type="submit" class="submit-btn">Submit Payment</button>
        </form>
    </div>

    <!-- Cart Section -->
    <div class="cart-section">
        <h2>Your Cart</h2>
        
        <?php
        
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                echo "<div class='cart-item'>
                        <div class='cart-item-details'>
                            <p class='cart-item-name'>{$item['name']}</p>
                            <b><p class='cart-item-quantity'>Quantity: {$item['quantity']}</p></b>
                            <p class='cart-item-price'>£" . number_format($item['price'], 2) . "</p>
                        </div>
                    </div>";
            }

            
            $total = 0;
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['quantity'] * $item['price'];
            }
            echo "<div class='cart-total'>Total: £" . number_format($total, 2) . "</div>";
        } else {
            echo "<p>Your cart is empty.</p>";
        }
        ?>
    </div>
</div>

<script>
function handleFormSubmit(event) {
    event.preventDefault();
    const form = document.getElementById("checkout");
    form.submit();  
    setTimeout(function() {
        window.location.href = "Order_Confirmation_Page.html";  
    }, 1);  
}
</script>

</body>
</html>
