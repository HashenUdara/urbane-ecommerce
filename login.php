<?php
session_start();
include 'db_connect.php';
$err_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check user
    $query = "SELECT user_id , password, role FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {

            // Set session variables
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $row['role'];
            $_SESSION['user_id'] = $row['user_id'];
            if ($row['role'] == 'admin') {
                header("Location: ./admin/index.php");
                exit();
            } else if ($row['role'] == 'customer') {
                header('Location: dashboard.php');
            }
        } else {
            $err_msg = "Invalid password.";
        }
    } else {
        $err_msg = "No user found with this email.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 500px;

        }
    </style>
</head>

<body class="login">
    <div class="container" style="display: flex;">

        <div>
            <img src="img/logo-bg.png" class="img-cover-login" />
        </div>

        <div class="login-container">
            <div style=" width: 300px">
                <h1>Login to your account</h1>
                <p class="sub-heading">Lorem, amet consectetur ipsum dolor sit amet consectetur adipisicing elit. </p>
                <form method="post" action="" style="margin-top:2rem;">
                    <!-- label tag learn from :  https://www.w3schools.com/html/html_forms.asp -->
                    <label for="email">Email:</label>
                    <input type="text" class="input-field" id="email" name="email" required placeholder="Enter username or email">
                    <br>
                    <label for="password">Password:</label>
                    <input type="password" class="input-field" id="password" name="password" required placeholder="Enter your password">
                    <br>
                    <div class="error-label">
                        <?php echo $err_msg ?>
                    </div>

                    <input type="submit" class="button" value="Login">
                    <p class="sub-heading">Don't have an account? <a href="./register.php" class="link">Register Now</a></p>
                </form>
                <div class="message">
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($error)) {
                            echo $error;
                        }
                    }
                    ?>
                </div>
            </div>

        </div>
    </div>
</body>

</html>