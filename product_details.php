<?php
include 'db_connect.php';
include 'header.php';



if (isset($_GET['product_id'])) {
    $product_id = (int)$_GET['product_id'];

    // Fetch product details from the database
    $sql = "SELECT products.name, products.description, products.price, products.img_url, categories.name  AS category , products.category_id  
            FROM products 
            JOIN categories ON products.category_id = categories.id 
            WHERE products.id = $product_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid product ID.";
    exit;
}


$category_id = $product['category_id']; // Assuming you have the category ID stored in $product

// Fetch products from the same category, excluding the current product
$sql = "SELECT id, name, description, price, img_url 
        FROM products 
        WHERE category_id = $category_id 
        AND id != $product_id 
        LIMIT 8";

$same_category_products_result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        Urban Clothing - Elegant Fashion for the Modern Elite</title>

    <link rel="stylesheet" href="./css/home.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.add-to-cart-form');
            const quantityInput = document.getElementById('quantity');
            const minusBtn = form.querySelector('.minus-btn');
            const plusBtn = form.querySelector('.plus-btn');

            minusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;
                }
            });

            plusBtn.addEventListener('click', function() {
                let currentValue = parseInt(quantityInput.value);
                quantityInput.value = currentValue + 1;
            });

            // Prevent form submission when pressing Enter on quantity input
            quantityInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        });
    </script>
    <style>
        #quantity {
            width: 40px;
            text-align: center;
            border: none;
            font-size: 16px;
            padding: 5px;
        }

        .add-to-cart-form {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>

<body>

    <?php include './navbar.php'; ?>
    <div class="container">
        <div class="product">
            <div class="product-image">
                <img src="<?php

                            $imagePath = dirname($_SERVER['PHP_SELF'])  . $product['img_url'];
                            echo $imagePath; ?>" alt="<?php echo $product['name']; ?>" />
            </div>
            <div class="product-details">
                <h1 style=" font-size: 3em;"><?php echo $product['name']; ?></h1>
                <h3>Category: <?php echo $product['category']; ?></h3>
                <br>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="description">
                    <?php echo $product['description']; ?>
                </p>
                <form action="add_to_cart.php" method="post" class="add-to-cart-form">
                    <div class="quantity-control">
                        <button type="button" class="quantity-btn minus-btn">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1" readonly>
                        <button type="button" class="quantity-btn plus-btn">+</button>
                    </div>
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="submit" class="btn add-to-cart-btn" value="Add to Cart">
                </form>

                <div class="product-meta">
                    We deliver Island-wide within 2/3 working days. <br>
                    Same-day delivery can be arranged within Colombo 1-15 areas. (T&C) apply <br>

                </div>

            </div>
        </div>
    </div>
    <section class="featured-product">
        <div class="container">
            <h2 class="section-title" style="text-align: left;">You might also like</h2>
            <div class="product-grid">
                <?php
                if (mysqli_num_rows($same_category_products_result) > 0) {
                    while ($row = mysqli_fetch_assoc($same_category_products_result)) {

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

    <?php include 'footer.php'; ?>
</body>

</html>