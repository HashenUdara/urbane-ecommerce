<?php
include '../header.php';
include '../db_connect.php';


if (isset($_GET['user_id'])) {
    $delete_id = (int)$_GET['user_id'];

    // Start a transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete from the cart_items table first
        $sql_cart_items = "DELETE FROM cart_items WHERE cart_id IN (SELECT cart_id FROM cart WHERE user_id = $delete_id)";
        mysqli_query($conn, $sql_cart_items);

        // Delete from the cart table
        $sql_cart = "DELETE FROM cart WHERE user_id = $delete_id";
        mysqli_query($conn, $sql_cart);

        // Delete from the orders and order_items table
        $sql_order_items = "DELETE FROM order_items WHERE order_id IN (SELECT order_id FROM orders WHERE user_id = $delete_id)";
        mysqli_query($conn, $sql_order_items);

        $sql_orders = "DELETE FROM orders WHERE user_id = $delete_id";
        mysqli_query($conn, $sql_orders);

        // Delete any reviews the user has made
        $sql_reviews = "DELETE FROM reviews WHERE user_id = $delete_id";
        mysqli_query($conn, $sql_reviews);

        // Finally, delete the user from the users table
        $sql_user = "DELETE FROM users WHERE user_id = $delete_id";
        mysqli_query($conn, $sql_user);

        // If everything is successful, commit the transaction
        mysqli_commit($conn);
        header("Location:users.php?msg=user_deleted");
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction in case of an error
        mysqli_rollback($conn);
        echo "Error: Unable to delete user due to related data.";
    }
}

$conn->close();
