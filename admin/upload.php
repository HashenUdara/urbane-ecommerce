<?php
include '../db_connect.php';

// Directory for uploads
$image_dir = '../uploads/images/';

// Check if directories exist, if not, create them
if (!is_dir($image_dir)) {
    mkdir($image_dir, 0777, true);
}

// Check if the product ID is set in the POST request
if (!isset($_POST['product_id']) || empty($_POST['product_id'])) {
    die("Product ID is required.");
}

$product_id = intval($_POST['product_id']); // Sanitize product ID

$image_file = $image_dir . basename($_FILES["image"]["name"]);

// Check if image file is an actual image
$check_image = getimagesize($_FILES["image"]["tmp_name"]);
if ($check_image === false) {
    die("File is not an image.");
}

// Move uploaded image to the designated directory
if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_file)) {
    $image_path = $image_file;

    // Insert file path into product_images table
    $stmt = $conn->prepare("INSERT INTO product_images (img_url, product_id) VALUES (?, ?)");
    $stmt->bind_param("si", $image_path, $product_id);

    if ($stmt->execute()) {
        header("Location: view_products.php?msg=image_uploaded"); // Redirect after upload
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Sorry, there was an error uploading your image.";
}

$conn->close();
