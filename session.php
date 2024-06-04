<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['email']);
$email = $isLoggedIn ? $_SESSION['email'] : '';
$userId = $isLoggedIn ? $_SESSION['userId'] : null; // Ensure userId is set in session

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}
?>
