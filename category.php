<?php
include 'db_connect.php';
include 'header.php';

// Get category ID 
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch category details
$category_sql = "SELECT name, description FROM categories WHERE id = $category_id";
$category_result = mysqli_query($conn, $category_sql);
$category = mysqli_fetch_assoc($category_result);

// Fetch products for this category
$products_sql = "SELECT id, name, description, price, img_url FROM products WHERE category_id = $category_id";
$products_result = mysqli_query($conn, $products_sql);


?>


<html lang="en">

<head>
    <title><?php echo ($category['name']); ?> - Urban Clothing</title>
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/category.css">
</head>

<body>
    <?php include './navbar.php'; ?>

    <main>
        <section class="about-section">
            <div class="container">
                <h1 class="section-title" style="margin-top: 30px;"><?php echo ($category['name']); ?></h1>
                <p class="category-description" style="font-size: larger;"><?php echo ($category['description']); ?></p>
            </div>
        </section>
        <div class="container">

            <h1 class="section-title"></h1>




            <div class="product-grid">
                <?php
                if (mysqli_num_rows($products_result) > 0) {
                    while ($row = mysqli_fetch_assoc($products_result)) {
                        $imagePath = dirname($_SERVER['PHP_SELF']) . $row['img_url'];
                ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo $imagePath ?>" alt="<?php echo ($row['name']) ?>" />
                            </div>
                            <div class="product-details">
                                <h2 class="product-title"><?php echo ($row['name']) ?></h2>
                                <p class="product-price">LKR.<?php echo number_format($row['price'], 2) ?></p>
                                <p class="product-description"><?php echo ($row['description']) ?></p>
                                <a href="product_details.php?product_id=<?php echo $row['id'] ?>" class="btn">View Details</a>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p>No products found in this category.</p>';
                }
                ?>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>