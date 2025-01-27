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
  <!-- font Roboto, Lato -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet" />
<!-- swiper -->
  <link
      rel="stylesheet"
      href="https://unpkg.com/swiper/swiper-bundle.min.css"
    />
  <!-- css -->
  <link rel="stylesheet" href="./style.css" />
  <link rel="stylesheet" href="./reset.css" />
    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="./header.css" />
    <link rel="stylesheet" href="./footer.css" />
    <link rel="stylesheet" href="./modal.css" />
    <link rel="stylesheet" href="./topBtn.css" />
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
  <!-- title -->
  <title>MUVIC</title>
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
                                <a class="nav-link" href="genre.php">Genre</a>
                                <!-- submenu -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="AddMovie.php">Add Movie</a>
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
                                    <a class="nav-link" href="verifyMovie.php">Verify Submissions</a>
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

        <!-- container -->
        <div id="home-container">
            <!-- main -->
            <main id="home-main">
                <div class="home-main-inner">
                    <dl>
                        <div>
                            <dt>
                                <span class="typing-text"></span>
                                <span class="input-cursor"></span>
                            </dt>
                            <dd>
                                with
                                <br/>
                                <h2>muvic</h2>
                            </dd>
                        </div>
                    </dl>
                </div>
            </main>
            <!-- section1 -->
            <section id="home-value-bar">
                <div class="home-value-bar-inner">
                    <!-- dl -->
                    <dl>
                        <dt>
                            <h3>Over 1000 movies and OSTs all in one place!</h3>
                        </dt>
                        <dd>
                            Experience <strong>muvic</strong>'s exclusive content<br/>that you won't find anywhere
                            else.
                        </dd>
                    </dl>
                </div>
            </section>
            <!-- section2 - top movies -->
            <section class="top_movies">
                <h2>Top 5 Movies!</h2>
                <div class="row top-movies-list">
                    <?php include 'top_movies.php'; ?>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- footer -->
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
                <dd><a href="./about/index.html">About Us</a></dd>
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

<!-- Modal -->
<div class="modal fade" id="movieModal" tabindex="-1" role="dialog" aria-labelledby="movieModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="movieModalLabel">Movie Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="movie_review.html">
                                <img id="movieModalImage" src="" alt="Movie cover image" class="img-fluid">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <h4 id="movieModalTitle"></h4>
                            <p id="movieModalDescription"></p>
                            <p><strong>Rating:</strong> <span id="movieModalRating"></span></p>
                            <p><strong>Director:</strong> <span id="movieModalDirector"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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