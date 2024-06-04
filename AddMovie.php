<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add a Movie - MUVIC</title>
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
                  <a class="nav-link" href="genre.php">Genre</a>
                  <!-- submenu -->
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#none">Music</a>
                  <!-- submenu -->
                </li>
                <li class="nav-item active">
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
        <h2>Add a Movie</h2>
        <form action="addMovie_process.php" method="POST">
          <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
          </div>
          <div class="form-group">
            <label for="director">Director</label>
            <input type="text" class="form-control" id="director" name="director" required>
          </div>
          <div class="form-group">
            <label for="genre">Genre</label>
            <select class="form-control" id="genre" name="genre" required>
              <option value="">Select the genre</option>
              <?php
              include 'db_connection.php';
              // Fetch genres from the database
              $query = "SELECT categoryName FROM Categories";
              $result = mysqli_query($dbc, $query);
              if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                  echo '<option value="' . htmlspecialchars($row['categoryName']) . '">' . htmlspecialchars($row['categoryName']) . '</option>';
                }
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label for="year">Year</label>
            <input type="number" class="form-control" id="year" name="year" required>
          </div>
          <div class="form-group">
            <label for="plot">Plot</label>
            <textarea class="form-control" id="plot" name="plot" rows="4" required></textarea>
          </div>
          <div class="form-group">
            <label for="imageUrl">Image URL</label>
            <input type="text" class="form-control" id="imageUrl" name="imageUrl" required>
          </div>
          <button type="submit" class="btn btn-primary">Add Movie</button>
        </form>
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

    <!-- font awesome -->
    <script
      src="https://kit.fontawesome.com/5a0d19e43a.js"
      crossorigin="anonymous"
    ></script>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- script -->
    <script src="../header.js" defer></script>
    <script src="../main.js" defer></script>
    <script src="../modal.js" defer></script>
    <script src="../topBtn.js" defer></script>
  </body>
</html>
    </div>
  </div>

  <!-- Bootstrap JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
