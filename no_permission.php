<html lang="en">

<head>
    <title>No Permission</title>
    <link rel="stylesheet" href="./css/home.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .permission-container {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            font-size: 2rem;
            color: #333;
        }

        p {
            font-size: 1rem;
            color: #555;
            margin-top: 10px;
        }

        .icon {
            font-size: 3rem;
            color: #ff0000;
        }
    </style>
</head>

<body>
    <div class="permission-container">
        <div class="icon">&#9888;</div>
        <h1>Access Denied</h1>
        <p>You do not have permission to view this page.</p>
        <p>Please contact your administrator if you believe this is a mistake.</p>
        <br>
        <a href="login.php" class="btn">Return to Login</a>
    </div>
</body>

</html>