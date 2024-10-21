<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)($_POST['product_id']);
    $new_quantity = (int)($_POST['quantity']);

    if ($new_quantity <= 0) {
        // Remove the item from the cart if quantity is less than or equal to 0
        $sql = "DELETE FROM cart_items WHERE product_id = $product_id AND cart_id = 
                (SELECT cart_id FROM cart WHERE user_id = $user_id)";
    } else {
        // Update the quantity of the cart item
        $sql = "UPDATE cart_items 
                SET quantity = $new_quantity 
                WHERE product_id = $product_id 
                AND cart_id = (SELECT cart_id FROM cart WHERE user_id = $user_id)";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: cart.php?message=Product quantity updated successfully");
        exit();
    } else {
        header("Location: cart.php?message=Failed to update quantity");
        exit();
    }
}

mysqli_close($conn);
