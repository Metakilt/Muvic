<?php
session_start(); // Start session
include 'db_connection.php'; // Include the database connection file

// Fetch top 10 movies from the database
$query = "SELECT * FROM TopMovies ORDER BY rating DESC LIMIT 10";
$result = mysqli_query($dbc, $query);
$topMovies = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($dbc);
?>
