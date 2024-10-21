<?php
include '../header.php';
include '../db_connect.php';
$title = 'Update User';
$err_msg = "";
$success_msg = "";

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $err_msg = "User not found.";
    }
}

if (isset($_POST['update-user'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];

    // Check if the email already exists for another user
    $check_email_query = "SELECT user_id FROM users WHERE email = '$email' AND user_id != $user_id";
    $email_result = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($email_result) > 0) {
        $err_msg = "The email '$email' is already in use by another user.";
    } else {
        // Prepare SQL statement for update
        $sql = "UPDATE users SET 
                name = '$name', 
                email = '$email', 
                password = '$password', 
                address = '$address', 
                phone_number = '$phone_number' 
                WHERE user_id = $user_id";

        if (mysqli_query($conn, $sql)) {
            $success_msg = "User updated successfully.";
            header("Location:users.php?msg=user_updated");
            exit();
        } else {
            $err_msg = "Error updating user: ";
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
                <h1>Update User Details</h1>
                <p class="sub-heading">Sign up to get started with our services.</p>
                <form name="myForm" method="post" action="" onsubmit="return validate();" style="margin-top:2rem;">

                    <!-- label tag learn from :  https://www.w3schools.com/html/html_forms.asp -->
                    <label for="email">User ID (Readonly):</label>
                    <input type="email" class="input-field" name="user_id" value='<?php echo $row['user_id'] ?>' required readonly>
                    <span id="emailError" class="error-message"></span>
                    <br>


                    <label for="email">Update Email:</label>
                    <input type="email" class="input-field" id="email" name="email" value='<?php echo $row['email'] ?>' required placeholder="Enter customer email">
                    <span id="emailError" class="error-message"></span>
                    <br>

                    <label for="name">Update Name:</label>
                    <input type="text" class="input-field" id="name" value='<?php echo $row['name'] ?>' name="name" required placeholder="Enter customer name">
                    <span id="nameError" class="error-message"></span>
                    <br>


                    <label for="password">Update Password:</label>
                    <input type="password" class="input-field" id="password" name="password" value='<?php echo $row['password'] ?>' required placeholder="Enter customer password">
                    <span id="passwordError" class="error-message"></span>
                    <br>



                    <label for="address">Update Address:</label>
                    <input type="text" class="input-field" id="address" name="address" value='<?php echo $row['address'] ?>' required placeholder="Enter customer address">
                    <span id="addressError" class="error-message"></span>
                    <br>

                    <label for="phone_number">Update Phone Number:</label>
                    <input type="text" class="input-field" id="phone_number" name="phone_number" value='<?php echo $row['phone_number'] ?>' required placeholder="Enter customer 10-digit phone number">
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
                        <input type="submit" class="button" name="update-user" value="Update">
                    </div>


                </form>
            </div>
        </div>
    </div>

</body>


</html>