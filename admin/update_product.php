<?php
include '../header.php';
include '../db_connect.php';
$title = 'Update Product';
$err_msg = "";
$success_msg = "";

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $err_msg = "Product not found.";
    }
}

if (isset($_POST['update-product'])) {
    $product_id = $_POST['product_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];

    // Handle image upload
    if ($_FILES["image"]["name"]) {
        $image_dir = '../img/uploads/';
        $image_file = $image_dir . basename($_FILES["image"]["name"]);

        // Check if image file is an actual image
        $check_image = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check_image === false) {
            $err_msg = "File is not an image.";
        } elseif (!move_uploaded_file($_FILES["image"]["tmp_name"], $image_file)) {
            $err_msg = "Sorry, there was an error uploading your file.";
        } else {
            $image_path = '/img/uploads/' . basename($_FILES["image"]["name"]);
            $sql = "UPDATE products SET 
                    name = '$name', 
                    description = '$description', 
                    price = '$price', 
                    stock_quantity = '$stock_quantity', 
                    category_id = '$category_id', 
                    updated_at = NOW(), 
                    img_url = '$image_path' 
                    WHERE id = $product_id";
        }
    } else {
        // Update without changing the image
        $sql = "UPDATE products SET 
                name = '$name', 
                description = '$description', 
                price = '$price', 
                stock_quantity = '$stock_quantity', 
                category_id = '$category_id', 
                updated_at = NOW() 
                WHERE id = $product_id";
    }



    mysqli_query($conn, $sql);
    if (mysqli_query($conn, $sql)) {
        $success_msg = "Product updated successfully.";
        header("Location: products.php?msg=product_updated");
        exit();
    } else {
        $err_msg = "Error updating product: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Add your styles here */
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php include("./sidebar.php"); ?>

    <!-- Topbar -->
    <?php include("./topbar.php"); ?>

    <!-- Content -->
    <div class="content card-container-flex ">
        <div class="card-container">
            <div style="width: 400px; margin: 2rem 0rem;">
                <h1>Update Product Details</h1>
                <form method="post" action="" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">

                    <label for="name">Update Name:</label>
                    <input type="text" class="input-field" id="name" name="name" value='<?php echo $row['name']; ?>' required>
                    <br>

                    <label for="description">Update Description:</label>
                    <textarea class="input-field" id="description" name="description" required><?php echo $row['description']; ?></textarea>
                    <br>

                    <label for="price">Update Price:</label>
                    <input type="number" step="0.01" class="input-field" id="price" name="price" value='<?php echo $row['price']; ?>' required>
                    <br>

                    <label for="stock_quantity">Update Stock Quantity:</label>
                    <input type="number" class="input-field" id="stock_quantity" name="stock_quantity" value='<?php echo $row['stock_quantity']; ?>' required>
                    <br>

                    <label for="category_id">Category:</label>
                    <select class="input-field" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php
                        $category_query = "SELECT id, name FROM categories";
                        $categories = $conn->query($category_query);
                        while ($category = $categories->fetch_assoc()) {
                            $selected = ($category['id'] == $row['category_id']) ? 'selected' : '';
                            echo "<option value='{$category['id']}' $selected>{$category['name']}</option>";
                        }
                        ?>
                    </select>
                    <br>

                    <label for="image">Update Image:</label>
                    <input type="file" class="input-field" id="image" name="image">
                    <br>

                    <div class="error-label"><?php echo $err_msg; ?></div>
                    <div class="success-label"><?php echo $success_msg; ?></div>
                    <div class="btn-container">
                        <a href="products.php" class="button secondary-button">Back</a>
                        <input type="submit" class="button" name="update-product" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>


<?php
$conn->close();

?>