<?php
session_start();
include 'db_connect.php'; // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's cart_id
$cart_query = "SELECT cart_id FROM cart WHERE user_id = $user_id";
$cart_result = mysqli_query($conn, $cart_query);
$cart_row = mysqli_fetch_assoc($cart_result);
$cart_id = $cart_row['cart_id'];


// Calculate total amount from cart items
$total_amount = 0;
$order_items = [];

$cart_items_query = "SELECT ci.product_id, ci.quantity, p.price FROM cart_items ci 
                         JOIN products p ON ci.product_id = p.id 
                         WHERE ci.cart_id = $cart_id";
$result_cart_items = mysqli_query($conn, $cart_items_query);

while ($row = mysqli_fetch_assoc($result_cart_items)) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $price_at_purchase = $row['price'];

    $total_amount += $price_at_purchase * $quantity;
    $order_items[] = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'price_at_purchase' => $price_at_purchase
    ];
}

// Insert into orders table
$order_query = "INSERT INTO orders (user_id, total_amount, status) VALUES ($user_id, $total_amount, 'Pending')";
if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn); // Get the last inserted order ID

    // Insert into order_items table
    foreach ($order_items as $item) {
        $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) 
                                 VALUES ($order_id, {$item['product_id']}, {$item['quantity']}, {$item['price_at_purchase']})";
        mysqli_query($conn, $order_item_query);
    }

    // Clear the cart after the order is placed
    $clear_cart_query = "DELETE FROM cart_items WHERE cart_id = $cart_id";
    mysqli_query($conn, $clear_cart_query);

    echo "Order placed successfully! Your order ID is " . $order_id;
} else {
    echo "Error placing order: " . mysqli_error($conn);
}


mysqli_close($conn);
