<?php
include 'db_connection.php';

$query = "SELECT m.movieId, m.title, m.imageUrl, m.releaseYear, m.rating, m.description, d.firstName, d.lastName
          FROM Movies m
          LEFT JOIN Direction dir ON m.movieId = dir.movieId
          LEFT JOIN Directors d ON dir.directorId = d.directorId
          ORDER BY m.rating DESC LIMIT 5";

$result = mysqli_query($dbc, $query);

if (!$result) {
    echo "Error running query: " . mysqli_error($dbc);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='col-md-4 mb-4'>
                <div class='card'>
                  <img src='{$row['imageUrl']}' class='card-img-top' alt='Movie Poster'>
                  <div class='card-body'>
                    <h5 class='card-title'>{$row['title']}</h5>
                    <p class='card-text'>Release Year: {$row['releaseYear']}</p>
                    <button class='btn btn-primary' onclick='openModal(\"{$row['title']}\", \"{$row['description']}\", \"{$row['rating']}\", \"{$row['imageUrl']}\", \"{$row['firstName']} {$row['lastName']}\")'>View Details</button>
                  </div>
                </div>
              </div>";
    }
} else {
    echo "<p>No movies found.</p>";
}

mysqli_close($dbc);
?>
