<?php
include 'db_connect.php';
include 'header.php';

// Handle search query
$search_query = isset($_GET['search']) ?  $_GET['search'] : '';

// Fetch categories from the database
$sql = "SELECT id, name, description FROM categories LIMIT 4"; // Adjust limit as needed
$categories_result = mysqli_query($conn, $sql);

// Fetch products (with search functionality)
$sql = "SELECT id, name, description, price, img_url 
        FROM products 
        WHERE name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
$products_result = mysqli_query($conn, $sql);
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
        <section class="about-section">
            <div class="container">
                <h1 class="section-title" style="margin-top: 70px;">Explore Our Collection</h1>

                <!-- Add search form -->
                <form action="" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="btn">Search</button>
                </form>
            </div>
            <?php
            if (mysqli_num_rows($categories_result) > 0 && !isset($_GET["search"])) {
            ?>
                <section class="container">
                    <div class="category-grid">
                        <?php
                        // Loop through each category and display it
                        while ($row = mysqli_fetch_assoc($categories_result)) {
                        ?>
                            <a class="category-card" href="category.php?id=<?php echo $row['id']; ?>">
                                <img src="./img/categories/img<?php echo $row['id']; ?>.webp" alt="<?php echo $row['name']; ?>" />
                                <div class="category-title"><?php echo $row['name']; ?></div>
                            </a>
                        <?php
                        }
                        ?>
                    </div>
                </section>

            <?php
            }
            ?>
        </section>
        <section class="featured-product">
            <div class="container">

                <h2 class="section-title" style="margin-top: 70px;">
                    <?php
                    if (isset($_GET['search'])) {
                        echo "Search Result for '" . $_GET['search'] . "' ";
                    } else {
                        echo "All Products";
                    }

                    ?>



                </h2>

                <div class="product-grid">
                    <?php
                    if (mysqli_num_rows($products_result) > 0) {
                        while ($row = mysqli_fetch_assoc($products_result)) {
                            $imagePath = dirname($_SERVER['PHP_SELF']) . $row['img_url'];
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
                        echo '<p>No products found matching your search.</p>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>