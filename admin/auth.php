<?php
session_start();

if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Role-based access control
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] != 'admin') {
        // Admin can access admin pages
        header("Location: ../no_permission.php");
        exit();
    }
} else {
    // If no role is set, force a logout for safety
    header("Location: logout.php");
    exit();
}
