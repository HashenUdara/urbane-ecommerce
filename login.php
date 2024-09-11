<?php
include 'header.php';
include 'db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check user
    $query = "SELECT password FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify password
        if (password_verify($password, $hashed_password)) {
            echo "Login successful!"; // Redirect or handle login success
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
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
        .img-cover {

            width: 500px;
            border-radius: 24px;
            margin: 0.5rem;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 500px;

        }
    </style>
</head>

<body>
    <div class="container" style="display: flex;">

        <div>
            <img src="img/logo-bg.png" class="img-cover" />
        </div>

        <div class="login-container">
            <div style=" width: 300px">
                <h1>Login to your account</h1>
                <p class="sub-heading">Lorem, amet consectetur ipsum dolor sit amet consectetur adipisicing elit. </p>
                <form method="post" action="" style=" margin-top:2rem;">
                    <label for="email">Email:</label>
                    <input type="text" class="input-field" id="email" name="email" required placeholder="Enter username or email">
                    <br>
                    <label for="password">Password:</label>
                    <input type="password" class="input-field" id="password" name="password" required placeholder="Enter your password">
                    <br>
                    <input type="submit" class="button" value="Login">
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