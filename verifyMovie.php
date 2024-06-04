<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// When admin verifies the movie
if (isset($_GET['movieId'])) {
    $movieId = $_GET['movieId'];

    // Verify movie submission
    $query = "UPDATE Movies SET approved = 1 WHERE movieId = $movieId";
    $result = mysqli_query($dbc, $query);

    if ($result) {
        $_SESSION['success'] = "Movie submission verified successfully.";
    } else {
        $_SESSION['error'] = "Error verifying movie submission.";
    }

    // Redirect back to verify submissions or wherever appropriate
    header('Location: verifyMovie.php');
    exit();
}

// Fetch all unapproved movies
$unapprovedQuery = "SELECT * FROM Movies WHERE approved = 0";
$unapprovedResult = mysqli_query($dbc, $unapprovedQuery);

if (!$unapprovedResult) {
    $_SESSION['error'] = "Error fetching unapproved movies: " . mysqli_error($dbc);
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
    <style>
        /* Custom styles here */
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
                            <li class="nav-item active">
                                <a class="nav-link" href="addmovie.php">Add a Movie</a>
                                <!-- submenu -->
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#none">Review</a>
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
        <!-- home-header end -->
    </div>
</div>

<!-- Main Content -->
<main class="container mt-4">
    <h2>Verify Submissions</h2>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Year</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (mysqli_num_rows($unapprovedResult) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($unapprovedResult)): ?>
                <tr>
                    <th scope="row"><?php echo htmlspecialchars($row['movieId']); ?></th>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['releaseYear']); ?></td>
                    <td>
                        <a href="verifyMovie.php?movieId=<?php echo htmlspecialchars($row['movieId']); ?>"
                           class="btn btn-success btn-sm">Verify</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No unapproved movies found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>