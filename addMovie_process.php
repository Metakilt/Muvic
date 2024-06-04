<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($dbc, $_POST['title']);
    $director = mysqli_real_escape_string($dbc, $_POST['director']);
    $genre = mysqli_real_escape_string($dbc, $_POST['genre']);
    $year = mysqli_real_escape_string($dbc, $_POST['year']);
    $plot = mysqli_real_escape_string($dbc, $_POST['plot']);
    $imageUrl = mysqli_real_escape_string($dbc, $_POST['imageUrl']);

    // Insert movie into database
    $insertQuery = "INSERT INTO Movies (title, releaseYear, description, imageUrl) 
                    VALUES ('$title', '$year', '$plot', '$imageUrl')";
    
    $result = mysqli_query($dbc, $insertQuery);

    if ($result) {
        // Get the inserted movie ID
        $movieId = mysqli_insert_id($dbc);

        // Insert director into Directors table (assuming director info is in the same form)
        $directorNames = explode(' ', $director);
        $firstName = mysqli_real_escape_string($dbc, $directorNames[0]);
        $lastName = mysqli_real_escape_string($dbc, $directorNames[1] ?? '');
        
        $insertDirectorQuery = "INSERT INTO Directors (firstName, lastName) 
                                VALUES ('$firstName', '$lastName')";
        
        $directorResult = mysqli_query($dbc, $insertDirectorQuery);
        
        if ($directorResult) {
            $directorId = mysqli_insert_id($dbc);

            // Link director to movie in Direction table
            $insertDirectionQuery = "INSERT INTO Direction (movieId, directorId) 
                                    VALUES ('$movieId', '$directorId')";
            
            $directionResult = mysqli_query($dbc, $insertDirectionQuery);

            if ($directionResult) {
                $_SESSION['success'] = "Movie added successfully. Pending admin approval.";
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['error'] = "Error linking director to movie: " . mysqli_error($dbc);
            }
        } else {
            $_SESSION['error'] = "Error adding director: " . mysqli_error($dbc);
        }
    } else {
        $_SESSION['error'] = "Error adding movie: " . mysqli_error($dbc);
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
}

header('Location: addmovie.php');
exit();
?>
