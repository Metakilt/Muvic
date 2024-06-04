<?php
include 'session.php';
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate inputs
    $errors = [];
    $movieId = $_POST['movieId'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
        $errors[] = 'Rating must be a number between 1 and 5.';
    }
    if (empty($review)) {
        $errors[] = 'Review cannot be empty.';
    }

    // If no errors, insert the review into the database
    if (empty($errors)) {
        $userId = $_SESSION['userId']; // Get user ID from session
        $query = "INSERT INTO Reviews (movieId, userId, rating, review) VALUES (?, ?, ?, ?)";
        $stmt = $dbc->prepare($query);
        $stmt->bind_param("iiis", $movieId, $userId, $rating, $review);
        if ($stmt->execute()) {
            $successMessage = 'Review added successfully.';
        } else {
            $errorMessage = 'Error adding review.';
        }
        $stmt->close();
    }
}

// Fetch movies for dropdown
$query = "SELECT movieId, title as movieTitle FROM Movies";
$result = $dbc->query($query);
$movies = $result->fetch_all(MYSQLI_ASSOC);

// Fetch user's reviews
if ($isLoggedIn) {
    $userId = $_SESSION['userId'];
    $query = "SELECT r.rating, r.review, r.reviewDate, m.title as movieTitle 
              FROM Reviews r
              INNER JOIN Movies m ON r.movieId = m.movieId
              WHERE r.userId = ?
              ORDER BY r.reviewDate DESC";
    $stmt = $dbc->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $userReviewsResult = $stmt->get_result();
    $stmt->close();
}

mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Review - MUVIC</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./header.css">
    <link rel="stylesheet" href="./footer.css">
    <style>
        /* Custom styles here */
        #home-header {
            position: relative;
            z-index: 1100; /* Adjust as needed */
        }

        .movie-details {
            margin-bottom: 20px;
        }

        .review-form {
            max-width: 600px;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }

        .user-reviews {
            margin-top: 40px;
        }

        .review {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div id="overlay"></div>
<div class="search-modal"></div>
<div id="home-wrap">
    <div class="header-element checkbox-container">
        <header id="home-header">
            <div>
                <!-- nav -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="home-nav-inner d-flex align-items-center justify-content-between">
                        <!-- logo -->
                        <h1><a href="./index.php">MUVIC</a></h1>
                        <!-- menu -->
                        <ul class="home-menu navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="genre.php">Genre</a>
                                <!-- submenu -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#none">Music</a>
                                <!-- submenu -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="AddMovie.php">Add a Movie</a>
                                <!-- submenu -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="review.php">Review</a>
                            </li>
                        </ul>
                        <!-- search and user profile -->
                        <div class="d-flex">
                            <!-- search bar -->
                            <form class="form-inline my-2 my-lg-0" action="MovieandDirectorsearch.php" method="GET">
                                <select class="form-control mr-sm-2" name="searchType">
                                    <option value="movies">Movies</option>
                                    <option value="directors">Directors</option>
                                </select>
                                <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search"
                                       aria-label="Search" required>
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                            </form>
                            <!-- user profile dropdown -->
                            <?php if ($isLoggedIn): ?>
                                <div class="dropdown ml-2">
                                    <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                            id="userDropdown" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                        <?php echo htmlspecialchars($email); ?>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="#">Profile</a>
                                        <form class="dropdown-item" action="" method="POST">
                                            <button type="submit" name="logout" class="btn btn-link">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="login.php" class="btn btn-outline-primary ml-2">Login</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mt-4">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h2>Add Your Review</h2>
                    <form id="reviewForm" action="review.php" method="POST">
                        <div class="form-group">
                            <label for="movieId">Select a Movie:</label>
                            <select class="form-control" id="movieId" name="movieId" required>
                                <option value="">Select a movie...</option>
                                <?php foreach ($movies as $movie): ?>
                                    <option value="<?php echo $movie['movieId']; ?>"><?php echo $movie['movieTitle']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="rating">Rating:</label>
                            <input type="number" class="form-control" id="rating" name="rating" min="1" max="5" required>
                        </div>
                        <div class="form-group">
                            <label for="review">Review:</label>
                            <textarea class="form-control" id="review" name="review" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>

                    <!-- Button to show user's reviews -->
                    <?php if ($isLoggedIn): ?>
                        <div class="user-reviews mt-4">
                            <button class="btn btn-primary" onclick="toggleUserReviews()">My Reviews</button>
                            <div id="userReviews" style="display: none;">
                                <h3>Your Reviews</h3>
                                <?php if ($userReviewsResult->num_rows > 0): ?>
                                    <?php while ($row = $userReviewsResult->fetch_assoc()): ?>
                                        <div class="review">
                                            <h5>Movie: <?php echo htmlspecialchars($row['movieTitle']); ?></h5>
                                            <p>Rating: <?php echo htmlspecialchars($row['rating']); ?> / 5</p>
                                            <p><?php echo htmlspecialchars($row['review']); ?></p>
                                            <small>Review Date: <?php echo date('F j, Y', strtotime($row['reviewDate'])); ?></small>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p>No reviews found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer id="home-footer">
            <div class="home-footer-inner">
                <!-- logo -->
                <h3>muvic</h3>
                <!-- list -->
                <div class="home-footer-list">
                    <dl>
                        <dt>
                            <h4>GROUP 5</h4>
                        </dt>
                        <dd><a href=#none>Contact Us</a></dd>
                    </dl>
                </div>
            </div>
        </footer>

        <!-- aside -->
        <aside id="home-aside">
            <button type="button" onclick="scrollToTop()" id="topButton">
                <a href="#none">top</a>
            </button>
        </aside>

    </div>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
