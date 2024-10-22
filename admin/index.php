<?php
include './auth.php';
include '../db_connect.php';
$title = 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <!-- Sidebar -->
    <?php include("./sidebar.php"); ?>

    <!-- Topbar -->
    <?php include("./topbar.php"); ?>

    <div class="content">
        <h1>Dashboard</h1>
        <br>
        <div class="grid-container">
            <!-- Users Card -->
            <div class="card">
                <img src="../img/users-icon.png" alt="Users Icon" class="card-icon">
                <div class="card-header">Users</div>
                <p>Manage all registered users. View, edit, or delete user information and control user access.</p>
                <a href="users.php" class="card-btn">Go to Users</a>
            </div>

            <!-- Products Card -->
            <div class="card">
                <img src="../img/products-icon.png" alt="Products Icon" class="card-icon">
                <div class="card-header">Products</div>
                <p>Manage the products in your store. Add new products, edit details, and update inventory.</p>
                <a href="products.php" class="card-btn">Go to Products</a>
            </div>

            <!-- Orders Card -->
            <div class="card">
                <img src="../img/orders-icon.png" alt="Orders Icon" class="card-icon">
                <div class="card-header">Orders</div>
                <p>Track customer orders. View order statuses, update shipment details, and manage payments.</p>
                <a href="orders.php" class="card-btn">Go to Orders</a>
            </div>
        </div>
    </div>

</body>

</html>