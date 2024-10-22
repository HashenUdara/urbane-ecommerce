<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the user_id from session
$user_id = $_SESSION['user_id'];

// Check if product ID and quantity are passed via GET or POST
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    // Fetch product details to validate
    $sql_product = "SELECT stock_quantity FROM products WHERE id = $product_id";
    $result_product = mysqli_query($conn, $sql_product);
    $product = mysqli_fetch_assoc($result_product);

    // Check if the product exists and has sufficient stock
    if ($product && $product['stock_quantity'] >= $quantity) {

        // Check if the user already has a cart
        $sql_cart = "SELECT cart_id FROM cart WHERE user_id = $user_id";
        $result_cart = mysqli_query($conn, $sql_cart);
        $cart = mysqli_fetch_assoc($result_cart);

        // If no cart exists, create one
        if (!$cart) {
            $sql_create_cart = "INSERT INTO cart (user_id) VALUES ($user_id)";
            mysqli_query($conn, $sql_create_cart);
            $cart_id = mysqli_insert_id($conn);
        } else {
            $cart_id = $cart['cart_id'];
        }

        // Check if the product is already in the cart
        $sql_cart_item = "SELECT cart_item_id, quantity FROM cart_items WHERE cart_id = $cart_id AND product_id = $product_id";
        $result_cart_item = mysqli_query($conn, $sql_cart_item);
        $cart_item = mysqli_fetch_assoc($result_cart_item);

        if ($cart_item) {
            // Update the quantity if the product is already in the cart
            $new_quantity = $cart_item['quantity'] + $quantity;
            $sql_update_item = "UPDATE cart_items SET quantity = $new_quantity WHERE cart_item_id = " . $cart_item['cart_item_id'];
            mysqli_query($conn, $sql_update_item);
        } else {
            // Add the new product to the cart
            $sql_add_item = "INSERT INTO cart_items (cart_id, product_id, quantity) VALUES ($cart_id, $product_id, $quantity)";
            mysqli_query($conn, $sql_add_item);
        }

        // Reduce the product stock quantity
        $new_stock_quantity = $product['stock_quantity'] - $quantity;
        $sql_update_stock = "UPDATE products SET stock_quantity = $new_stock_quantity WHERE id = $product_id";
        mysqli_query($conn, $sql_update_stock);

        header("Location: cart.php?msg=added_to_cart");
    } else {
        // If the product doesn't exist or insufficient stock
        header("Location: product_details.php?product_id=$product_id&msg=out_of_stock");
    }
} else {
    header("Location: index.php");
}

$conn->close();
