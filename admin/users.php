<?php
include '../header.php';
include '../db_connect.php';
$title = 'Admin Panel';

$sql = "SELECT * FROM users WHERE role='customer'";
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
                <p style="color:#4c4c4c; padding-bottom:20px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Similique dolorem porro quam rem suscipit </p>
            </div>
            <a class="button w-fit" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="height: 1.2rem; width:1.2rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                Add new user</a>
        </div>


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
                    <td><a class="update-btn" href="update.php?user_id=<?php echo $row['user_id']; ?>">Update</a></td>
                    <td><a class="delete-btn" href="delete.php?user_id=<?php echo $row['user_id']; ?>">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>

</body>

</html>