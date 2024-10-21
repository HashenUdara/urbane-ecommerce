<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Customer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .dashboard-container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .counter-container {
            display: flex;
            justify-content: center;
        }
        .counter-btn {
            font-size: 1.5rem;
            padding: 5px 15px;
            margin: 0 5px;
            cursor: pointer;
        }
        .counter-display {
            font-size: 1.5rem;
            padding: 5px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 30px;
            text-align: center;
        }
        .checkout-container {
            margin-top: 30px;
        }
        .checkout-container h2 {
            text-align: center;
        }
        .checkout-form label {
            display: block;
            margin: 10px 0 5px;
        }
        .checkout-form input[type="text"], 
        .checkout-form input[type="email"], 
        .checkout-form input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .checkout-form button {
            padding: 10px 15px;
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        .checkout-form button:hover {
            background-color: #4cae4c;
        }
        .total-value {
            text-align: right;
            font-size: 1.5rem;
            margin-top: 20px;
        }
        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Cart Details - Customer</h1>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Details</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="https://th.bing.com/th/id/OIP.trgHvhRyIMcU8lupuBgl0QHaI4?rs=1&pid=ImgDetMain" height="50px" width="50px"></td>
                    <td>Leather Jacket</td>
                    <td>$10.00</td>
                    <td>
                        <div class="counter-container">
                            <button id="decrease" class="counter-btn">-</button>
                            <div id="counter-value" class="counter-display">0</div>
                            <button id="increase" class="counter-btn">+</button>
                        </div>
                    </td>
                    <td>$<span id="total-value">0.00</span></td>
                </tr>
            </tbody>
        </table>
        
        <!-- Checkout Form -->
        <div class="checkout-container">
            <h2>Checkout</h2>
            <form class="checkout-form" id="checkout-form">
                <label for="name">Full Name</label>
                <input type="text" id="name" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" required>

                <label for="phone">Phone Number</label>
                <input type="number" id="phone" required>

                <label for="address">Shipping Address</label>
                <input type="text" id="address" required>

                <label for="coupon">Coupon Code</label>
                <input type="text" id="coupon">

                <button type="button" id="apply-coupon">Apply Coupon</button>
                <p id="coupon-message"></p>

                <div class="total-value">
                    <span>Total: $<span id="final-total">0.00</span></span>
                </div>

                <button type="button" id="place-order">Place Order</button>
            </form>
        </div>

        <div class="success-message" id="success-message"></div>
    </div>
    <script src="script.js"></script>
</body>
</html>
