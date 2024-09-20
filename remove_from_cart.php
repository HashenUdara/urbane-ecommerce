<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if the product_id is passed via GET request
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']);

    // Get the cart_id for the user
    $sql = "SELECT cart_id FROM cart WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    $cart = mysqli_fetch_assoc($result);

    if ($cart) {
        $cart_id = $cart['cart_id'];

        // Delete the product from the cart items
        $sql_delete = "DELETE FROM cart_items WHERE product_id = $product_id AND cart_id = $cart_id";
        if (mysqli_query($conn, $sql_delete)) {
            // Redirect to the cart page with a success message
            header("Location: cart.php?message=Product removed successfully");
            exit();
        } else {
            // Redirect with an error message if something went wrong
            header("Location: cart.php?error=Unable to remove product from the cart");
            exit();
        }
    } else {
        // If no cart exists for the user, redirect with an error message
        header("Location: cart.php?error=No cart found for the user");
        exit();
    }
} else {
    // If product_id is not provided, redirect with an error message
    header("Location: cart.php?error=No product specified to remove");
    exit();
}

// Close database connection
mysqli_close($conn);
