
<?php
include './auth.php';
include '../db_connect.php';

if (isset($_GET['product_id'])) {
    $delete_id = (int)$_GET['product_id'];

    // learn from : https://www.tutorialspoint.com/php/php_function_mysqli_begin_transaction.htm
    mysqli_begin_transaction($conn);

    try {
        // Delete product images related to the product
        $sql_product_images = "DELETE FROM product_images WHERE product_id = $delete_id";
        mysqli_query($conn, $sql_product_images);

        // Delete cart items related to the product
        $sql_cart_items = "DELETE FROM cart_items WHERE product_id = $delete_id";
        mysqli_query($conn, $sql_cart_items);

        // Delete order items related to the product
        $sql_order_items = "DELETE FROM order_items WHERE product_id = $delete_id";
        mysqli_query($conn, $sql_order_items);

        // Delete reviews related to the product
        $sql_reviews = "DELETE FROM reviews WHERE product_id = $delete_id";
        mysqli_query($conn, $sql_reviews);

        // Finally, delete the product itself
        $sql_product = "DELETE FROM products WHERE id = $delete_id";
        mysqli_query($conn, $sql_product);

        // Commit the transaction
        mysqli_commit($conn);
        header("Location:products.php?msg=product_deleted");
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction in case of an error
        mysqli_rollback($conn);
        echo "Error: Unable to delete product due to related data.";
    }
}

$conn->close();
?>