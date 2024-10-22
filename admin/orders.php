<?php
include './auth.php';
include '../db_connect.php';
$title = 'Orders Summary';

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$sql = "SELECT o.order_id, o.total_amount, o.status, o.created_at, u.email, u.name 
        FROM orders o
        JOIN users u ON o.user_id = u.user_id";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function confirmDelete(orderId) {
            if (confirm("Are you sure you want to delete this order? This action cannot be undone.")) {
                window.location.href = 'delete_order.php?order_id=' + orderId;
            }
        }
    </script>
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
                <h1>Orders</h1>
                <p style="color:#4c4c4c; padding-bottom:20px;">View and manage all customer orders below</p>
            </div>

        </div>

        <?php
        $messages = [
            'order_added' => 'Order successfully added!',
            'order_updated' => 'Order successfully updated!',
            'order_deleted' => 'Order successfully deleted!',
        ];

        if (array_key_exists($msg, $messages)) {
            $messageText = $messages[$msg];
        ?>
            <div class="alert">
                <?= $messageText ?>
                <a class="close-btn" href="./orders.php">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>
        <?php
        }
        ?>

        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer Email</th>
                <th>Customer Name</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row['order_id'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td>LKR.<?php echo $row['total_amount'] ?></td>
                    <td>
                        <?php if ($row['status'] == 'Pending') { ?>
                            <span class="badge badge-pending">Pending</span>
                        <?php } elseif ($row['status'] == 'Shipped') { ?>
                            <span class="badge badge-shipped">Shipped</span>
                        <?php } elseif ($row['status'] == 'Delivered') { ?>
                            <span class="badge badge-delivered">Delivered</span>
                        <?php } elseif ($row['status'] == 'Canceled') { ?>
                            <span class="badge badge-canceled">Canceled</span>
                        <?php } ?>
                    </td>
                    <td><?php echo $row['created_at'] ?></td>
                    <td><a class="update-btn" href="view_order.php?order_id=<?php echo $row['order_id']; ?>">View Order</a></td>
                    <td><a class="delete-btn" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['order_id']; ?>)">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>

</body>

</html>