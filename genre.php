<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Genres - MUVIC</title>
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
  </style>
</head>
<body>
  <?php include 'db_connection.php'; ?>
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
        <div class="row">
          <div class="col-md-3">
            <h2>Genres</h2>
            <div id="genreList">
              <!-- Genre categories will be dynamically added here using AJAX -->
            </div>
          </div>
          <div class="col-md-9">
            <h2>Movies</h2>
            <div id="moviesList">
              <!-- Movies will be dynamically added here using AJAX -->
            </div>
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
  <!-- Custom JavaScript -->
  <script src="./header.js" defer></script>
  <script src="./main.js" defer></script>
  <script src="./modal.js" defer></script>
  <script src="./topBtn.js" defer></script>
  <script>
    // Load genres and movies on page load
    $(document).ready(function () {
      // Fetch and display genres
      $.ajax({
        url: 'fetchGenres.php',
        method: 'GET',
        success: function (response) {
          $('#genreList').html(response);
        },
        error: function () {
          alert('Error fetching genres');
        }
      });

      // Function to fetch and display movies by category
      function fetchMoviesByCategory(categoryId) {
        $.ajax({
          url: 'fetchMovies.php',
          method: 'GET',
          data: { categoryId: categoryId },
          success: function (response) {
            $('#moviesList').html(response);
          },
          error: function () {
            alert('Error fetching movies');
          }
        });
      }

      // Event handler for clicking on a genre
      $(document).on('click', '.genre-link', function (e) {
        e.preventDefault();
        var categoryId = $(this).data('categoryid');
        fetchMoviesByCategory(categoryId);
      });
    });
  </script>
</body>
</html>
