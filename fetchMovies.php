<?php
include 'db_connection.php';

if (isset($_GET['categoryId'])) {
  $categoryId = $_GET['categoryId'];

  // Query to fetch movies based on category
  $query = "SELECT m.* FROM Movies m
            INNER JOIN MovieCategories mc ON m.movieId = mc.movieId
            WHERE mc.categoryId = $categoryId";
  
  $result = mysqli_query($dbc, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      echo '<div class="card mb-3">
              <div class="card-body">
                <h5 class="card-title">' . $row['movieTitle'] . '</h5>
                <p class="card-text">' . $row['movieDescription'] . '</p>
              </div>
            </div>';
    }
  } else {
    echo '<div>No movies found</div>';
  }
}

mysqli_close($dbc);
?>
