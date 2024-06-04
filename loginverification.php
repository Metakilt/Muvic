<?php
include "db_connection.php";

session_start(); // Start the session

$email = $_POST['email'];
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    // Redirect back to the login page with an error message
    header("Location: login.php?error=Email and password are required");
    exit();
} else {
    // Use prepared statements to prevent SQL injection
    $stmt = $dbc->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if ($password == $user['password']) {
            $_SESSION['email'] = $email;
            $_SESSION['success'] = "You are now logged in";

            // Set role based on user type
            if ($user['isAdmin'] == 1) {
                $_SESSION['role'] = 'admin';
            } else {
                $_SESSION['role'] = 'user';
            }

            header("Location: index.php"); // Redirect to the index page
            exit();
        } else {
            // Redirect back to the login page with an error message
            header("Location: login.php?error=Invalid email/password combination");
            exit();
        }
    } else {
        // Redirect back to the login page with an error message
        header("Location: login.php?error=Invalid email/password combination");
        exit();
    }
}

$stmt->close();
$dbc->close();
?>
