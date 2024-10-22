<?php
include './header.php';
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$cart_query = "SELECT cart_id FROM cart WHERE user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);
$cart_row = mysqli_fetch_assoc($cart_result);
$cart_id = $cart_row['cart_id'];

$total_amount = 0;
$order_items = [];

$cart_items_query = "SELECT ci.product_id, ci.quantity, p.price, p.name FROM cart_items ci 
                     JOIN products p ON ci.product_id = p.id 
                     WHERE ci.cart_id = $cart_id";
$result_cart_items = mysqli_query($conn, $cart_items_query);

while ($row = mysqli_fetch_assoc($result_cart_items)) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $price_at_purchase = $row['price'];
    $product_name = $row['name'];

    $total_amount += $price_at_purchase * $quantity;
    $order_items[] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'price_at_purchase' => $price_at_purchase,
        'name' => $product_name
    ];
}

$order_query = "INSERT INTO orders (user_id, total_amount, status) VALUES ($user_id, $total_amount, 'Pending')";
if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn);

    foreach ($order_items as $item) {
        $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) 
                             VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price_at_purchase']})";
        mysqli_query($conn, $order_item_query);
    }

    $clear_cart_query = "DELETE FROM cart_items WHERE cart_id = $cart_id";
    mysqli_query($conn, $clear_cart_query);

    // Fetch user details
    $user_query = "SELECT name, email FROM users WHERE user_id = $user_id";
    $user_result = mysqli_query($conn, $user_query);
    $user_data = mysqli_fetch_assoc($user_result);

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Order Confirmation | Urban Clothing</title>
        <link rel="stylesheet" href="./css/home.css">
        <style>
            /* order-success.css */
            .order-success {
                max-width: 800px;
                margin: 40px auto;
                margin-top: 100px;
                background-color: #fff;
                padding: 30px;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                margin-bottom: 30px;
            }

            .order-success h1 {
                color: #4CAF50;
                text-align: center;
                margin-bottom: 30px;
                font-family: "Playfair Display", serif;
            }

            .order-success h2 {
                color: #333;
                margin-top: 30px;
                margin-bottom: 15px;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }

            .order-details,
            .user-details {
                background-color: #f9f9f9;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 20px;
            }

            .order-details p,
            .user-details p {
                margin: 5px 0;
            }

            .order-summary table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
            }

            .order-summary th,
            .order-summary td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }

            .order-summary th {
                background-color: #f2f2f2;
            }

            .order-summary tfoot {
                font-weight: bold;
            }

            .next-steps ul {
                padding-left: 20px;
            }

            .next-steps li {
                margin-bottom: 10px;
            }

            .actions {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin-top: 30px;
            }



            .btn-secondary {
                background-color: transparent;
                border: 1px solid #333;
                color: #333;
            }
        </style>
    </head>

    <body>
        <?php include './navbar.php'; ?>
        <main class="container">
            <div class="order-success">
                <h1>Order Placed Successfully!</h1>
                <div class="order-details">
                    <p class="order-id">Order ID: #<?php echo $order_id; ?></p>
                    <p class="order-date">Date: <?php echo date('F j, Y'); ?></p>
                    <p class="order-status">Status: Pending</p>
                </div>
                <div class="user-details">
                    <h2>Customer Information</h2>
                    <p>Name: <?php echo htmlspecialchars($user_data['name']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($user_data['email']); ?></p>
                </div>
                <div class="order-summary">
                    <h2>Order Summary</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item['price_at_purchase'], 2); ?></td>
                                    <td>$<?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">Total Amount</td>
                                <td>$<?php echo number_format($total_amount, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="actions">
                    <a href="index.php" class="btn">Continue Shopping</a>
                    <a href="order_history.php" class="btn btn-secondary">View Order History</a>
                </div>
            </div>
        </main>
        <?php include 'footer.php'; ?>
    </body>

    </html>
<?php
} else {
    echo "Error placing order: " . mysqli_error($conn);
}
?>