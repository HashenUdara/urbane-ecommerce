<?php
include './auth.php';
include '../db_connect.php';

if (isset($_GET['order_id'])) {
    $delete_id = (int)$_GET['order_id'];

    // Begin a transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete order items related to the order
        $sql_order_items = "DELETE FROM order_items WHERE order_id = $delete_id";
        mysqli_query($conn, $sql_order_items);

        // Finally, delete the order itself
        $sql_order = "DELETE FROM orders WHERE order_id = $delete_id";
        mysqli_query($conn, $sql_order);

        // Commit the transaction
        mysqli_commit($conn);
        header("Location:orders.php?msg=order_deleted");
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction in case of an error
        mysqli_rollback($conn);
        echo "Error: Unable to delete order due to related data.";
    }
}

$conn->close();
