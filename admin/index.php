<?php
include '../header.php';
include '../db_connect.php';
$title = 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <!-- Sidebar -->
    <?php include("./sidebar.php"); ?>

    <!-- Topbar -->
    <div class="topbar">
        <div class="search-box">
            <input type="text" placeholder="Search...">
        </div>
        <div class="profile">
            <img src="https://via.placeholder.com/40" alt="Profile">
            <span>Admin Name</span>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <h1>Dashboard</h1>
        <div class="grid-container">
            <div class="card">
                <div class="card-header">Card 1</div>
                <p>Some content inside this card.</p>
            </div>
            <div class="card">
                <div class="card-header">Card 2</div>
                <p>Some content inside this card.</p>
            </div>
            <div class="card">
                <div class="card-header">Card 3</div>
                <p>Some content inside this card.</p>
            </div>
        </div>
    </div>

</body>

</html>