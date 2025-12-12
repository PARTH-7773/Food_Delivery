<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment & Checkout - Food Delivery</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav class="navbar flex between wrapper">
            <a href="index.php" class="logo">Food Delivery</a>
        </nav>
    </header>

    <main class="wrapper p-top">
        <div class="checkout-container">
            
            <div class="order-summary">
                <h2>Your Order</h2>
                <div id="order-items-list">
                    </div>
                <div class="order-total">
                    <h4>Total Amount</h4>
                    <h4 id="order-total-price">₹0.00</h4>
                </div>
            </div>

            <div class="customer-details">
                <h2>Details & Payment</h2>
                <form id="checkout-form">
                    <h3>1. Delivery Info</h3><br>
                    <div class="input-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    </div>
                    <div class="input-group">
                        <label for="address">Delivery Address</label>
                        <input type="text" id="address" name="address" placeholder="House No, Street, City" required>
                    </div>
                    <div class="input-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

                    <h3>2. Payment Method</h3><br>
                    <div class="input-group">
                        <label>Card Number</label>
                        <input type="text" placeholder="XXXX XXXX XXXX XXXX" maxlength="19" required>
                    </div>
                    
                    <div style="display: flex; gap: 10px;">
                        <div class="input-group" style="flex: 1;">
                            <label>Expiry Date</label>
                            <input type="text" placeholder="MM/YY" maxlength="5" required>
                        </div>
                        <div class="input-group" style="flex: 1;">
                            <label>CVV</label>
                            <input type="password" placeholder="123" maxlength="3" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn" style="margin-top: 1rem;">Pay & Place Order</button>
                </form>
            </div>
        </div>
    </main>

    <script src="checkout.js"></script>
</body>
</html>