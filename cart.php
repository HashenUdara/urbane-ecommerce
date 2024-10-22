<?php
include 'db_connect.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch cart items for the user
$sql_cart = "SELECT ci.product_id, ci.quantity, p.name, p.price, p.img_url
             FROM cart_items ci
             JOIN cart c ON ci.cart_id = c.cart_id
             JOIN products p ON ci.product_id = p.id
             WHERE c.user_id = $user_id";

$result_cart = mysqli_query($conn, $sql_cart);

// Initialize totals
$subtotal = 0;
$shipping = 10.00; // Flat rate shipping
$tax_rate = 0; // 10% tax rate
$all_qty = 0;
$cart_items = [];

while ($row = mysqli_fetch_assoc($result_cart)) {
    $cart_items[] = $row;
    $subtotal += $row['price'] * $row['quantity'];
    $all_qty += $row['quantity'];
}

// Calculate tax and total
$tax = $subtotal * $tax_rate;
$total = $subtotal + $shipping + $tax;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart | Urban Clothing</title>
    <link rel="stylesheet" href="./css/home.css">
</head>

<body>

    <?php include './navbar.php'; ?>
    <main class="container" style="margin: 100px auto;">
        <h1>Your Cart (<?php echo $all_qty; ?> items)</h1>
        <br>
        <div class="cart-container">
            <div class="cart-items">
                <?php if (empty($cart_items)) : ?>
                    <p>Your cart is empty.</p>
                    <a href="index.php" class="btn">Continue Shopping</a>
                <?php else : ?>
                    <?php foreach ($cart_items as $item) : ?>
                        <div class="cart-item">
                            <a href="product_details.php?product_id=<?php echo $item['product_id']; ?>">
                                <img src="<?php
                                            $imagePath = dirname($_SERVER['PHP_SELF'])  . $item['img_url'];
                                            echo $imagePath; ?>" alt="<?php echo $item['name']; ?>" />
                            </a>

                            <div class="item-details">
                                <a class="item-name"><?php echo $item['name']; ?></a>
                                <div class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                                <div class="item-quantity">
                                    <form method="POST" action="update_cart_quantity.php">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo (int)$item['quantity'] - 1; ?>">
                                        <button type="submit" name="update_quantity" value="decrease" class="quantity-btn">-</button>
                                    </form>
                                    <span class="quantity"><?php echo $item['quantity']; ?></span>
                                    <form method="POST" action="update_cart_quantity.php">
                                        <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo (int)$item['quantity'] + 1; ?>">
                                        <button type="submit" name="update_quantity" value="increase" class="quantity-btn">+</button>
                                    </form>
                                    <span class="remove-item">
                                        <a class="btn" style="background-color: red; font-size:small; padding:8px 12px;" href="remove_from_cart.php?product_id=<?php echo $item['product_id']; ?>">Remove</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="cart-summary">
                <h2 class="summary-title">Order Summary</h2>
                <div class="summary-item">
                    <span>Subtotal</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="summary-item">
                    <span>Shipping</span>
                    <span>$<?php echo number_format($shipping, 2); ?></span>
                </div>
                <div class="summary-item">
                    <span>Tax</span>
                    <span>$<?php echo number_format($tax, 2); ?></span>
                </div>
                <div class="summary-item summary-total">
                    <span>Total</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
                <a href="checkout.php" class="btn">Proceed to Checkout</a>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>

<?php
mysqli_close($conn);
?>