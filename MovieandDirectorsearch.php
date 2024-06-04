<?php
include 'db_connection.php';

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
    <title>Search</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
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
                                <a class="nav-link" href="#none">Review</a>
                            </li>
                        </ul>
                        <!-- search and login -->
                        <div class="d-flex">
                            <!-- search bar -->
                            <form class="form-inline my-2 my-lg-0" action="MovieandDirectorsearch.php" method="GET">
                                <select class="form-control mr-sm-2" name="searchType">
                                    <option value="movies">Movies</option>
                                    <option value="directors">Directors</option>
                                </select>
                                <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search" aria-label="Search" required />
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                            </form>
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
                <div class="container mt-4">
                    <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>

                    <?php if ($searchType === 'directors'): ?>
                        <!-- Display director results -->
                        <h2>Directors</h2>
                        <?php if (mysqli_num_rows($director_result) > 0): ?>
                            <div class="row">
                                <?php while ($row = mysqli_fetch_assoc($director_result)): ?>
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <img src="<?php echo htmlspecialchars($row['imageUrl']); ?>" class="card-img-top" alt="Image of <?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?>">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <a href="director.php?id=<?php echo htmlspecialchars($row['directorId']); ?>">
                                                        <?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?>
                                                    </a>
                                                </h5>
                                                <p class="card-text"><?php echo htmlspecialchars($row['bio']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p>No directors found.</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <!-- Display movie results -->
                        <h2>Movies</h2>
                        <?php if (mysqli_num_rows($movies_result) > 0): ?>
                            <div class="row">
                                <?php while ($row = mysqli_fetch_assoc($movies_result)): ?>
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <img src="<?php echo htmlspecialchars($row['imageUrl']); ?>" class="card-img-top" alt="Cover image of <?php echo htmlspecialchars($row['title']); ?>">
                                            <div class="card-body">
                                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                                <p class="card-text"><strong>Rating:</strong> <?php echo htmlspecialchars($row['rating']); ?></p>
                                                <p class="card-text"><strong>Directors:</strong>
                                                    <?php
                                                    $directors = explode(', ', $row['directors']);
                                                    foreach ($directors as $director) {
                                                        echo '<a href="director.php?name=' . urlencode($director) . '">' . htmlspecialchars($director) . '</a> ';
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p>No movies found.</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
