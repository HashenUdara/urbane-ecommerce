<?php
include 'header.php';
include 'db_connect.php';
$err_msg = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    // Validate password and confirm password match
    if ($password !== $confirm_password) {
        $err_msg = "Passwords do not match.";
    } else {

        // Check if email already exists
        $query = "SELECT user_id FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $err_msg = "Email is already registered. Please try logging in.";
        } else {

            // Hash the password
            // Code source: https://www.tutorialspoint.com/php/php_function_password_hash.htm
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users (email, password, name, address, phone_number, role)
                      VALUES ('$email', '$hashed_password', '$name', '$address', '$phone_number', 'customer')";

            if ($conn->query($query) === TRUE) {
                $success_msg = "Account created successfully! You can now <a href='login.php'>login</a>.";
            } else {
                $err_msg = "Error: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 500px;
        }

        .error-message {
            color: red;
            font-size: small;
        }
    </style>

    <script src="./js/registration-form-validation.js"></script>
</head>

<body class="register">
    <div class="container" style="display: flex;">
        <div>
            <img src="img/img1.jpg" class="img-cover-login" />
        </div>

        <div class="login-container">
            <div style="width: 300px; margin: 2rem 0rem;">
                <h1>Create a New Account</h1>
                <p class="sub-heading">Sign up to get started with our services.</p>
                <form name="myForm" method="post" action="" onsubmit="return validate();" style="margin-top:2rem;">

                    <!-- label tag learn from :  https://www.w3schools.com/html/html_forms.asp -->
                    <label for="name">Name:</label>
                    <input type="text" class="input-field" id="name" name="name" required placeholder="Enter your name">
                    <span id="nameError" class="error-message"></span>
                    <br>

                    <label for="email">Email:</label>
                    <input type="email" class="input-field" id="email" name="email" required placeholder="Enter your email">
                    <span id="emailError" class="error-message"></span>
                    <br>

                    <label for="password">Password:</label>
                    <input type="password" class="input-field" id="password" name="password" required placeholder="Enter your password">
                    <span id="passwordError" class="error-message"></span>
                    <br>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="input-field" id="confirm_password" name="confirm_password" required placeholder="Confirm your password">
                    <span id="confirmPasswordError" class="error-message"></span>
                    <br>

                    <label for="address">Address:</label>
                    <input type="text" class="input-field" id="address" name="address" required placeholder="Enter your address">
                    <span id="addressError" class="error-message"></span>
                    <br>

                    <label for="phone_number">Phone Number:</label>
                    <input type="text" class="input-field" id="phone_number" name="phone_number" required placeholder="Enter your 10-digit phone number">
                    <span id="phoneError" class="error-message"></span>
                    <br>

                    <div class="error-label">
                        <?php echo $err_msg ?>
                    </div>
                    <div class="success-label">
                        <?php echo $success_msg ?>
                    </div>

                    <input type="submit" class="button" value="Register">
                    <p class="sub-heading">Already have an account? <a href="./login.php" class="link">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</body>

</html>