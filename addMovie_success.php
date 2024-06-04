<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Added Successfully - MUVIC</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Roboto, Lato -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="./style.css">
  <link rel="stylesheet" href="./header.css">
  <link rel="stylesheet" href="./footer.css">
  <style>
    /* Custom styles here */
  </style>
</head>
<body>
  <?php include 'session.php'; ?>
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

      <!-- Main Content -->
      <main class="container mt-4">
        <div class="alert alert-success" role="alert">
          The movie has been successfully added!
        </div>
        <a href="addmovie.php" class="btn btn-primary">Add Another Movie</a>
        <a href="index.php" class="btn btn-secondary">Back to Home</a>
      </main>

      <!-- Footer -->
      <footer class="footer mt-5">
        <div class="container">
          <span class="text-muted">MUVIC - Your Movie Hub</span>
        </div>
      </footer>
    </div>
  </div>

  <!-- Bootstrap JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
