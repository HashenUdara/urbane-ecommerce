<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_query = "SELECT * FROM users WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);

    // Check if email already exists for another user
    $email_check_query = "SELECT user_id FROM users WHERE email = '$email' AND user_id != $user_id";
    $email_check_result = mysqli_query($conn, $email_check_query);

    if (mysqli_num_rows($email_check_result) > 0) {
        $error_message = "This email is already in use by another account.";
    } else {
        // Update user details
        $update_query = "UPDATE users SET name = '$name', email = '$email', address = '$address', phone_number = '$phone_number' WHERE user_id = $user_id";

        if (mysqli_query($conn, $update_query)) {
            $success_message = "Your account details have been updated successfully.";
            // Refresh user data
            $user_result = mysqli_query($conn, $user_query);
            $user = mysqli_fetch_assoc($user_result);
        } else {
            $error_message = "Error updating account details. Please try again.";
        }
    }

    // Handle password change
    if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        if ($_POST['new_password'] === $_POST['confirm_password']) {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $password_update_query = "UPDATE users SET password = '$new_password' WHERE user_id = $user_id";

            if (mysqli_query($conn, $password_update_query)) {
                $success_message .= " Your password has been updated.";
            } else {
                $error_message .= " Error updating password. Please try again.";
            }
        } else {
            $error_message .= " New password and confirmation do not match.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details | Luxe Couture</title>
    <link rel="stylesheet" href="./css/home.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include './navbar.php'; ?>

    <main class="container" style="margin-top: 100px;">
        <h1>My Account</h1>
        <div class="dashboard-container">
            <aside class="sidebar">
                <h2>Dashboard</h2>
                <ul>
                    <li><a href="order_history.php">My Orders</a></li>
                    <li><a href="account_details.php" class="active">Account Details</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </aside>
            <div class="main-content">
                <section class="dashboard-section">
                    <h2>Account Details</h2>
                    <?php if ($error_message): ?>
                        <p class="error-message"><?php echo $error_message; ?></p>
                    <?php endif; ?>
                    <?php if ($success_message): ?>
                        <p class="success-message"><?php echo $success_message; ?></p>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>">
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password (leave blank to keep current password)</label>
                            <input type="password" id="new_password" name="new_password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                        </div>
                        <button type="submit" class="btn">Update Account</button>
                    </form>
                </section>
            </div>
        </div>
    </main>
</body>

</html>