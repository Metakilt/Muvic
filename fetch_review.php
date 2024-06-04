<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize the input to prevent SQL injection
    $movieId = intval($_POST['movieId']);

    // Prepare the query to fetch reviews
    $query = "SELECT r.rating, r.review, r.reviewDate, u.email 
              FROM Reviews r
              INNER JOIN Users u ON r.userId = u.id
              WHERE r.movieId = ?
              ORDER BY r.reviewDate DESC";

    $stmt = $dbc->prepare($query);
    $stmt->bind_param("i", $movieId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rating = $row['rating'];
            $review = $row['review'];
            $reviewDate = $row['reviewDate'];
            $email = $row['email'];

            // Format the review date
            $reviewDateFormatted = date('F j, Y', strtotime($reviewDate));

            // Output each review as a formatted HTML block
            echo "<div class='review'>";
            echo "<h5>Rating: $rating / 5</h5>";
            echo "<p>$review</p>";
            echo "<small>Reviewed by: $email on $reviewDateFormatted</small>";
            echo "</div>";
        }
    } else {
        echo "<p>No reviews found for this movie.</p>";
    }

    // Close statement and database connection
    $stmt->close();
    mysqli_close($dbc);
} else {
    // Invalid request method
    http_response_code(405);
    echo "Method Not Allowed";
    exit();
}
?>
