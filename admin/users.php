<?php
include './auth.php';
include '../db_connect.php';
$title = 'Admin Panel';

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

$sql = "SELECT * FROM users WHERE role='customer'";
$result = mysqli_query($conn, $sql);


?>

<html lang="en">

<head>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user? This action cannot be undone.")) {
                window.location.href = 'delete_user.php?user_id=' + userId;
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
                <h1>Users</h1>
                <p style="color:#4c4c4c; padding-bottom:20px;">Manage your application users here.</p>
            </div>
            <a class="button w-fit" href="./add_user.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                Add new user</a>
        </div>

        <?php
        $messages = [
            'user_added' => 'User successfully added!',
            'user_updated' => 'User successfully updated!',
            'user_deleted' => 'User successfully deleted!',
        ];

        if (array_key_exists($msg, $messages)) {
            $messageText = $messages[$msg];
        ?>
            <div class="alert">
                <?= $messageText ?>
                <a class="close-btn" href="./users.php">
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
                <th>ID</th>
                <th>Email</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row['user_id'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php echo $row['address'] ?></td>
                    <td><?php echo $row['phone_number'] ?></td>
                    <td><a class="update-btn" href="update_user.php?user_id=<?php echo $row['user_id']; ?>">Update</a></td>
                    <td><a class="delete-btn" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['user_id']; ?>)">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>

</body>

</html>

<?php

$conn->close();

?>