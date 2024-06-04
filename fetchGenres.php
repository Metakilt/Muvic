<?php
include 'db_connection.php';

// Query to fetch all categories
$query = "SELECT * FROM Categories";
$result = mysqli_query($dbc, $query);

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    echo '<button type="button" class="btn btn-primary genre-btn" data-categoryid="' . $row['categoryId'] . '">' . $row['categoryName'] . '</button>';
  }
} else {
  echo '<div>No genres found</div>';
}

mysqli_close($dbc);
?>
