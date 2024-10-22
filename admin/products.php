<?php
include './auth.php';
include '../db_connect.php';
$title = 'Product List';

$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// SQL query to get product details including image URL directly from the products table
$sql = "SELECT p.id, p.name, p.description, p.price, p.stock_quantity, c.name AS category_name, p.img_url
        FROM products p
        JOIN categories c ON p.category_id = c.id";

$result = mysqli_query($conn, $sql);

?>
<html lang="en">

<head>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function confirmDelete(productId) {
            if (confirm("Are you sure you want to delete this product? This action cannot be undone.")) {
                window.location.href = 'delete_product.php?product_id=' + productId;
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
                <h1>Products</h1>
                <p style="color:#4c4c4c; padding-bottom:20px;">Manage your products here.</p>
            </div>
            <a class="button w-fit" href="./add_product.php">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Add new product
            </a>
        </div>

        <?php
        $messages = [
            'product_added' => 'Product successfully added!',
            'product_updated' => 'Product successfully updated!',
            'product_deleted' => 'Product successfully deleted!',
        ];

        if (array_key_exists($msg, $messages)) {
            $messageText = $messages[$msg];
        ?>
            <div class="alert">
                <?= $messageText ?>
                <a class="close-btn" href="./products.php">
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
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock Quantity</th>
                <th>Category</th>
                <th>Update</th>
                <th>Delete</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td>
                        <?php if ($row['img_url']):

                            $imagePath = dirname($_SERVER['PHP_SELF']) . '/..' . $row['img_url']; ?>
                            <img src="<?php echo $imagePath ?>" alt="<?php echo $row['name'] ?>" class="product-img">
                        <?php else: ?>
                            <img src="../img/default-product.png" alt="No image" style="width:50px; height:auto;">
                        <?php endif; ?>
                    </td>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php echo $row['description'] ?></td>
                    <td>LKR.<?php echo number_format($row['price'], 2) ?></td>
                    <td><?php echo $row['stock_quantity'] ?></td>
                    <td><?php echo $row['category_name'] ?></td>
                    <td><a class="update-btn" href="update_product.php?product_id=<?php echo $row['id']; ?>">Update</a></td>
                    <td><a class="delete-btn" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>

</body>

</html>