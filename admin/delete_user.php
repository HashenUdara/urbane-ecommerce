<?php
include './auth.php';
include '../db_connect.php';

if (isset($_GET['user_id'])) {
    $delete_id = (int)$_GET['user_id'];
    // learn from : https://www.tutorialspoint.com/php/php_function_mysqli_begin_transaction.htm
    mysqli_begin_transaction($conn);

    try {

        $sql_cart_items = "DELETE FROM cart_items WHERE cart_id IN (SELECT cart_id FROM cart WHERE user_id = $delete_id)";
        mysqli_query($conn, $sql_cart_items);


        $sql_cart = "DELETE FROM cart WHERE user_id = $delete_id";
        mysqli_query($conn, $sql_cart);


        $sql_order_items = "DELETE FROM order_items WHERE order_id IN (SELECT order_id FROM orders WHERE user_id = $delete_id)";
        mysqli_query($conn, $sql_order_items);

        $sql_orders = "DELETE FROM orders WHERE user_id = $delete_id";
        mysqli_query($conn, $sql_orders);


        $sql_reviews = "DELETE FROM reviews WHERE user_id = $delete_id";
        mysqli_query($conn, $sql_reviews);


        $sql_user = "DELETE FROM users WHERE user_id = $delete_id";
        mysqli_query($conn, $sql_user);


        mysqli_commit($conn);

        header("Location:users.php?msg=user_deleted");
    } catch (mysqli_sql_exception $exception) {


        mysqli_rollback($conn);
        echo "Error: Unable to delete user due to related data.";
    }
}

$conn->close();
