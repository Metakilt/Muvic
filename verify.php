<?php
session_start(); // Start session
include 'db_connection.php'; // Include the database connection file

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['email']);
$email = $isLoggedIn ? $_SESSION['email'] : '';
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// When user clicks logout, redirect to index.php
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

// Check if movieId is set and isAdmin is true
if (isset($_GET['movieId']) && $isAdmin) {
    $movieId = $_GET['movieId'];

    // Verify movie submission
    $query = "UPDATE Movies SET verified = 1 WHERE movieId = $movieId";
    $result = mysqli_query($dbc, $query);

    if ($result) {
        $_SESSION['success'] = "Movie submission verified successfully.";
    } else {
        $_SESSION['error'] = "Error verifying movie submission.";
    }

    // Redirect back to index.php
    header('Location: index.php');
    exit();
}

$query = isset($_GET['query']) ? $_GET['query'] : '';
$searchType = isset($_GET['searchType']) ? $_GET['searchType'] : '';

if ($searchType === 'directors') {
    // Command to search directors
    $director_Query = "SELECT * FROM Directors WHERE firstName LIKE '%$query%' OR lastName LIKE '%$query%'";
    $director_result = mysqli_query($dbc, $director_Query);

    if (!$director_result) {
        echo "Error running director query: " . mysqli_error($dbc);
        exit;
    }
} else {
    // Commands to search for movies
    $movie_Query = "SELECT m.movieId, m.title, m.imageUrl, m.releaseYear, m.rating, m.description,
                GROUP_CONCAT(CONCAT(d.firstName, ' ', d.lastName) SEPARATOR ', ') AS directors
                FROM Movies m
                LEFT JOIN Direction dir ON m.movieId = dir.movieId
                LEFT JOIN Directors d ON dir.directorId = d.directorId
                WHERE m.title LIKE '%$query%'
                GROUP BY m.movieId
                ORDER BY m.rating DESC LIMIT 5";

    $movies_result = mysqli_query($dbc, $movie_Query);

    if (!$movies_result) {
        echo "Error running movie query: " . mysqli_error($dbc);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Submissions</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="./header.css" />
    <link rel="stylesheet" href="./footer.css" />
    <link rel="stylesheet" href="./modal.css" />
    <link rel="stylesheet" href="./topBtn.css" />
    <style>
        .navbar-nav {
            flex-direction: row;
        }

        .navbar-nav .nav-item {
            padding: 0 10px;
        }

        .navbar-nav .nav-item:last-child {
            margin-left: auto;
        }

        @media (max-width: 768px) {
            .navbar-nav .nav-item:last-child {
                margin-left: 0;
            }
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
                                <a class="nav-link" href="genre.html">Genre</a>
                                <!-- submenu -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#none">Music</a>
                                <!-- submenu -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#none">Trailer</a>
                                <!-- submenu -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="review.php">Review</a>
                            </li>
                            <?php if ($isAdmin): ?>
                                <!-- Admin only: verify submissions -->
                                <li class="nav-item">
                                    <a class="nav-link" href="verify.php">Verify Submissions</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <!-- search and login -->
                        <div class="d-flex">
                            <!-- search bar -->
                            <form class="form-inline my-2 my-lg-0" action="MovieandDirectorsearch.php" method="GET">
                                <select class="form-control mr-sm-2" name="searchType">
                                    <option value="movies">Movies</option>
                                    <option value="directors">Directors</option>
                                </select>
                                <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search"
                                       aria-label="Search" required/>
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
        <!-- home-header end -->
    </div>
</div>

<!-- font awesome -->
<script src="https://kit.fontawesome.com/5a0d19e43a.js" crossorigin="anonymous"></script>
<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- script -->
<script src="./header.js" defer></script>
<script src="./main.js" defer></script>
<script src="./modal.js" defer></script>
<script src="./topBtn.js" defer></script>
<script>
    function openModal(title, description, rating, imageUrl, director) {
        $('#movieModalTitle').text(title);
        $('#movieModalDescription').text(description);
        $('#movieModalRating').text(rating);
        $('#movieModalDirector').text(director);
        $('#movieModalImage').attr('src', imageUrl);
        $('#movieModal').modal('show');
    }
</script>
</body>
</html>
