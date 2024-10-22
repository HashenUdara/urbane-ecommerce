<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user's orders
$orders_query = "SELECT o.order_id, o.total_amount, o.status, o.created_at,
                        oi.product_id, oi.quantity, oi.price_at_purchase,
                        p.name AS product_name, p.img_url
                 FROM orders o
                 JOIN order_items oi ON o.order_id = oi.order_id
                 JOIN products p ON oi.product_id = p.id
                 WHERE o.user_id = $user_id
                 ORDER BY o.created_at DESC";

$orders_result = mysqli_query($conn, $orders_query);

// Group order items by order_id
$orders = [];
while ($row = mysqli_fetch_assoc($orders_result)) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_id' => $order_id,
            'total_amount' => $row['total_amount'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'items' => []
        ];
    }
    $orders[$order_id]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price_at_purchase'],
        'img_url' => $row['img_url']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Order History | Luxe Couture</title>
    <link rel="stylesheet" href="./css/home.css">
</head>

<body>
    <?php
    include './navbar.php';
    ?>
    <main class="container" style="margin-top: 100px;">
        <h1>My Account</h1>
        <div class="dashboard-container">
            <aside class="sidebar">
                <h2>Dashboard</h2>
                <ul>
                    <li><a href="order_history.php" class="active">My Orders</a></li>
                    <li><a href="account_details.php">Account Details</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </aside>
            <div class="main-content">
                <section class="dashboard-section">
                    <h2>My Orders</h2>
                    <?php if (empty($orders)): ?>
                        <p>You have no orders yet.</p>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <div class="order">
                                <div class="order-header">
                                    <span class="order-number">Order #<?php echo $order['order_id']; ?></span>
                                    <span class="order-status status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></span>
                                </div>
                                <div class="order-items">
                                    <?php foreach ($order['items'] as $item): ?>
                                        <div class="order-item">
                                            <a href="product_details.php?product_id=<?php echo $item['product_id']; ?>">
                                                <img src="<?php
                                                            $imagePath = dirname($_SERVER['PHP_SELF'])  . $item['img_url'];
                                                            echo $imagePath; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="order-item-image" />
                                            </a>
                                            <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo $item['quantity']; ?></span>


                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="order-total">Total: $<?php echo number_format($order['total_amount'], 2); ?></div>
                                <a href="view_order.php?order_id=<?php echo $order['order_id']; ?>" class="btn">View Order</a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </main>
</body>

</html>