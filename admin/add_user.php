<?php
include './auth.php';
include '../db_connect.php';

$title = 'Add New User';
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
            $err_msg = "Email: '$email' is already registered. <br/> Please try differnet one.";
        } else {

            // Hash the password
            // Code source: https://www.tutorialspoint.com/php/php_function_password_hash.htm
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $query = "INSERT INTO users (email, password, name, address, phone_number, role)
                      VALUES ('$email', '$hashed_password', '$name', '$address', '$phone_number', 'customer')";

            if ($conn->query($query) === TRUE) {
                header("Location:users.php?msg=user_added");
            } else {
                $err_msg = "Error: " . $conn->error;
            }
        }
    }
}

$conn->close();
?>


<html lang="en">

<head>
    <title><?php echo $title ?></title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/style.css">


    <script src="../js/registration-form-validation.js"></script>
</head>

<body>

    <!-- Sidebar -->
    <?php include("./sidebar.php"); ?>

    <!-- Topbar -->
    <?php include("./topbar.php"); ?>

    <!-- Content -->
    <div class="content card-container-flex ">
        <div class="card-container">
            <div style="width: 400px; margin: 2rem 0rem;">
                <h1>Create a new user</h1>
                <p class="sub-heading">Sign up to get started with our services.</p>
                <form name="myForm" method="post" action="" onsubmit="return validate();" style="margin-top:2rem;">

                    <!-- label tag learn from :  https://www.w3schools.com/html/html_forms.asp -->
                    <label for="name">Name:</label>
                    <input type="text" class="input-field" id="name" name="name" placeholder="Enter customer name">
                    <span id="nameError" class="error-message"></span>
                    <br>

                    <label for="email">Email:</label>
                    <input type="email" class="input-field" id="email" name="email" placeholder="Enter customer email">
                    <span id="emailError" class="error-message"></span>
                    <br>

                    <label for="password">Password:</label>
                    <input type="password" class="input-field" id="password" name="password" placeholder="Enter customer password">
                    <span id="passwordError" class="error-message"></span>
                    <br>

                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" class="input-field" id="confirm_password" name="confirm_password" placeholder="Confirm customer password">
                    <span id="confirmPasswordError" class="error-message"></span>
                    <br>

                    <label for="address">Address:</label>
                    <input type="text" class="input-field" id="address" name="address" placeholder="Enter customer address">
                    <span id="addressError" class="error-message"></span>
                    <br>

                    <label for="phone_number">Phone Number:</label>
                    <input type="text" class="input-field" id="phone_number" name="phone_number" placeholder="Enter customer 10-digit phone number">
                    <span id="phoneError" class="error-message"></span>
                    <br>

                    <div class="error-label">
                        <?php echo $err_msg ?>
                    </div>
                    <div class="success-label">
                        <?php echo $success_msg ?>
                    </div>
                    <div class="btn-container">
                        <a href="users.php" class="button secondary-button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>

                            Back</a>
                        <input type="submit" class="button" value="Save">
                    </div>


                </form>
            </div>
        </div>
    </div>

</body>


</html>