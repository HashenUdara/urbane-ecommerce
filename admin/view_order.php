<?php
include '../header.php';
include '../db_connect.php';

if (isset($_GET['order_id'])) {
    $order_id = (int)$_GET['order_id'];

    // Handle status update
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
        $new_status = $_POST['status'];
        $update_sql = "UPDATE orders SET status='$new_status' WHERE order_id=$order_id";
        if (mysqli_query($conn, $update_sql)) {
            $msg = "Order status updated successfully!";
        } else {
            $msg = "Failed to update order status.";
        }
    }

    // Fetch order details
    $order_sql = "SELECT orders.*, users.name, users.email, users.address, users.phone_number 
                  FROM orders 
                  JOIN users ON orders.user_id = users.user_id 
                  WHERE orders.order_id = $order_id";
    $order_result = mysqli_query($conn, $order_sql);
    $order = mysqli_fetch_assoc($order_result);

    // Fetch order items
    $items_sql = "SELECT order_items.*, products.name AS product_name, products.img_url 
                  FROM order_items 
                  JOIN products ON order_items.product_id = products.id 
                  WHERE order_items.order_id = $order_id";
    $items_result = mysqli_query($conn, $items_sql);
} else {
    echo "Order ID not provided.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- Sidebar -->
    <?php include("./sidebar.php"); ?>

    <!-- Topbar -->
    <?php include("./topbar.php"); ?>

    <!-- Content -->
    <div class="content">

        <div class="content-header">
            <div>
                <h1>Order Details: #<?php echo $order['order_id'] ?></h1>
                <p style="color:#4c4c4c; padding-bottom:20px;">Manage single order</p>
            </div>

        </div>

        <?php if ($order) { ?>
            <div class="order-card-wrapper ">
                <div class="order-card">
                    <div>
                        <h2>Customer Details</h2>

                        <table>

                            <tr>
                                <td><strong>Name:</strong> </td>
                                <td>
                                    <?php echo $order['name']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong> </td>
                                <td>
                                    <?php echo $order['email']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong> </td>
                                <td>
                                    <?php echo $order['address']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Phone Number:</strong> </td>
                                <td>
                                    <?php echo $order['phone_number']; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="order-card">
                    <div>
                        <h2>Order Summary</h2>
                        <table>

                            <tr>
                                <td><strong>Order ID:</strong> </td>
                                <td>
                                    #<?php echo $order['order_id']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong> </td>
                                <td>
                                    <?php echo $order['status']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Total Amount:</strong> </td>
                                <td>
                                    LKR.<?php echo $order['total_amount']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Order Date:</strong> </td>
                                <td>
                                    <?php echo $order['created_at']; ?>
                                </td>
                            </tr>
                        </table>




                    </div>
                </div>

                <div class="order-card">
                    <div style="width: 100%;">
                        <h2>Change Order Status</h2>

                        <form method="post" action="" style=" margin-top:1rem;">
                            <label for="status">Update Status:</label>
                            <select class="input-field" id="status" name="status" required>
                                <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Shipped" <?php if ($order['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                <option value="Delivered" <?php if ($order['status'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                                <option value="Canceled" <?php if ($order['status'] == 'Canceled') echo 'selected'; ?>>Canceled</option>
                            </select>
                            <br>

                            <?php if (isset($msg)) { ?>
                                <div class="success-label"><?php echo $msg; ?></div>
                            <?php } ?>

                            <div class="btn-container">
                                <input type="submit" class="button" value="Update Status">
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div style=" margin-top: 2rem;">
                <h2>Ordered Items</h2>
                <table>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                    <?php while ($item = mysqli_fetch_assoc($items_result)) { ?>
                        <tr>

                            <td>
                                <?php if ($item['img_url']):

                                    $imagePath = dirname($_SERVER['PHP_SELF']) . '/..' . $item['img_url']; ?>
                                    <img src="<?php echo $imagePath ?>" alt="<?php echo $item['product_name'] ?>" class="product-img">
                                <?php else: ?>
                                    <img src="../img/default-product.png" alt="No image" style="width:50px; height:auto;">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $item['product_name']; ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td>LKR.<?php echo $item['price_at_purchase']; ?></td>
                            <td>LKR.<?php echo number_format($item['quantity'] * $item['price_at_purchase'], 2); ?></td>
                        </tr>
                    <?php } ?>
                </table>

            <?php } else { ?>
                <p>Order not found.</p>
            <?php } ?>
            </div>


    </div>

</body>

</html>