<?php
session_start();
include 'db_connection.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $repeatPassword = $_POST['repeatPassword'];

    // Basic validation
    if (empty($email) || empty($password) || empty($repeatPassword)) {
        $_SESSION['message'] = 'All fields are required.';
        $_SESSION['message_type'] = 'error';
        header('Location: register.php');
        exit;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'Invalid email address.';
        $_SESSION['message_type'] = 'error';
        header('Location: register.php');
        exit;
    } elseif ($password !== $repeatPassword) {
        $_SESSION['message'] = 'Passwords do not match.';
        $_SESSION['message_type'] = 'error';
        header('Location: register.php');
        exit;
    } else {
        // Check if the email already exists in the database
        $stmt = $dbc->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['message'] = 'Email is already registered.';
            $_SESSION['message_type'] = 'error';
            header('Location: register.php');
            exit;
        } else {
            // Insert new user into the database with plain text password
            $stmt = $dbc->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password);

            if ($stmt->execute()) {
                $_SESSION['message'] = 'Registration successful. Please login.';
                $_SESSION['message_type'] = 'success';
                header('Location: login.php');
                exit;
            } else {
                $_SESSION['message'] = 'Registration failed. Please try again.';
                $_SESSION['message_type'] = 'error';
                header('Location: register.php');
                exit;
            }

            $stmt->close();
        }
    }
} else {
    $_SESSION['message'] = 'Invalid request method.';
    $_SESSION['message_type'] = 'error';
    header('Location: register.php');
    exit;
}

$dbc->close();
?>