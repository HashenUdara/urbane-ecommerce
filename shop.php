<?php
include 'db_connect.php';
include 'header.php';
// Fetch categories from the database
$sql = "SELECT id, name, description FROM categories LIMIT 4"; // Adjust limit as needed
$categories_result = mysqli_query($conn, $sql);

// Fetch featured products
$sql = "SELECT id ,name, description, price, img_url 
        FROM products 
        WHERE featured = 0 
        LIMIT 8";
$featured_products_result = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Urban Clothing - Elegant Fashion for the Modern Elite</title>

    <link rel="stylesheet" href="./css/home.css">
</head>

<body>

    <?php include './navbar.php'; ?>
    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>Elegance Redefined</h1>
                <p>
                    Discover the latest in high-end fashion, where style meets
                    sophistication.
                </p>
                <a href="#" class="btn">Shop Now</a>
            </div>
        </section>
        <?php
        if (mysqli_num_rows($categories_result) > 0) {
        ?>
            <section class="featured-categories container">
                <h2 class="section-title">Featured Categories</h2>
                <div class="category-grid">
                    <?php
                    // Loop through each category and display it
                    while ($row = mysqli_fetch_assoc($categories_result)) {
                    ?>
                        <div class="category-card">
                            <img src="./img/categories/img<?php echo $row['id']; ?>.webp" alt="<?php echo $row['name']; ?>" />
                            <div class="category-title"><?php echo $row['name']; ?></div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </section>

        <?php
        } else {
            echo "<p>No categories found.</p>";
        }
        ?>

        <section class="about-section">
            <div class="container">
                <h2 class="section-title">Our Story</h2>
                <div class="about-content">
                    <p>
                        Luxe Couture was born from a passion for exceptional craftsmanship
                        and timeless design. Our mission is to bring the finest in fashion
                        to those who appreciate the art of dressing well. Each piece in
                        our collection is carefully curated to ensure the highest quality
                        and style.
                    </p>
                </div>
            </div>
        </section>
        <section class="featured-product">
            <div class="container">
                <h2 class="section-title">Featured Products</h2>
                <div class="product-grid">
                    <?php
                    if (mysqli_num_rows($featured_products_result) > 0) {
                        while ($row = mysqli_fetch_assoc($featured_products_result)) {

                            $imagePath = dirname($_SERVER['PHP_SELF'])  . $row['img_url'];
                    ?>

                            <div class="product-card">
                                <div class="product-image">
                                    <img src="<?php echo $imagePath ?>" alt="<?php echo $row['name'] ?>" />
                                </div>
                                <div class="product-details">
                                    <h2 class="product-title"><?php echo $row['name'] ?></h2>
                                    <p class="product-price">LKR.<?php echo number_format($row['price'], 2) ?></p>
                                    <p class="product-description"><?php echo $row['description'] ?></p>
                                    <a href="product_details.php?product_id=<?php echo $row['id'] ?>" class="btn">View Details</a>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p>No featured products available at the moment.</p>';
                    }
                    ?>
                </div>
            </div>

        </section>

    </main>


    <?php include 'footer.php'; ?>
</body>

</html>