<?php
include './auth.php';
include '../db_connect.php';
$title = 'Add New Product';
$err_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $category_id = $_POST['category_id'];
    $image = $_FILES['image']['name'];

    // Image upload handling
    $image_dir = '../img/uploads/';
    $image_file = $image_dir . basename($image);

    // Check if image file is an actual image
    $check_image = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check_image === false) {
        $err_msg = "File is not an image.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_file)) {
            $image_path = '/img/uploads/' . basename($image);
            // Insert product into database
            $query = "INSERT INTO products (name, description, price, stock_quantity, category_id, img_url)
                      VALUES ('$name', '$description', '$price', '$stock_quantity', '$category_id','$image_path')";

            if ($conn->query($query) === TRUE) {
                header("Location: products.php?msg=product_added");
            } else {
                $err_msg = "Error: " . $conn->error;
            }
        } else {
            $err_msg = "Sorry, there was an error uploading your image.";
        }
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
                <h1>Add a New Product</h1>

                <form method="post" enctype="multipart/form-data">
                    <label for="name">Product Name:</label>
                    <input type="text" class="input-field" id="name" name="name" required placeholder="Enter product name">
                    <br>

                    <label for="description">Description:</label>
                    <textarea class="input-field" id="description" name="description" required placeholder="Enter product description"></textarea>
                    <br>

                    <label for="price">Price:</label>
                    <input type="number" class="input-field" id="price" name="price" step="0.01" required placeholder="Enter product price">
                    <br>
                    <label for="image">Product Image:</label>
                    <input type="file" class="input-field" id="image" name="image" accept="image/*" required>
                    <br>

                    <label for="stock_quantity">Stock Quantity:</label>
                    <input type="number" class="input-field" id="stock_quantity" name="stock_quantity" required placeholder="Enter stock quantity">
                    <br>

                    <label for="category_id">Category:</label>
                    <select class="input-field" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php
                        $category_query = "SELECT id, name FROM categories";
                        $categories = $conn->query($category_query);

                        if ($categories) {
                            while ($category = $categories->fetch_assoc()) {
                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                            }
                        } else {
                            echo "<option value=''>Error fetching categories</option>";
                        }
                        ?>
                    </select>
                    <br>


                    <div class="error-label"><?php echo $err_msg; ?></div>
                    <div class="success-label"><?php echo $success_msg; ?></div>
                    <div class="btn-container">
                        <a href="products.php" class="button secondary-button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            Back</a>
                        <input type="submit" class="button" value="Add Product">
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