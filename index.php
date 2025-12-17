<?php 
session_start();
require "includes/dbconn.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Shengyi Shi 744564, Yuming Deng 744571, Mingxuan Xu 744580, Yanzhang Lu 744586" />
    <meta name="description" content="Find and book unique accommodations worldwide with KXO205 Booking System" />
    <title>KXO205 Accommodation Booking - Find Your Perfect Stay</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main>
      <!-- Hero Section -->
      <section class="hero-section">
        <div class="container">
          <h1>Find Your Next Stay</h1>
          <p>Book unique accommodations and experiences around the world.</p>
          <div class="search-panel mt-4">
            <form method="POST" action="search.php" class="row g-3 justify-content-center">
              <div class="col-md-3">
                <input
                  type="text"
                  class="form-control"
                  name="city"
                  placeholder="Destination"
                  required
                />
              </div>
              <div class="col-md-2">
                <input type="date" class="form-control" name="check_in" required />
              </div>
              <div class="col-md-2">
                <input type="date" class="form-control" name="check_out" required />
              </div>
              <div class="col-md-2">
                <input
                  type="number"
                  class="form-control"
                  name="guests"
                  placeholder="Guests"
                  min="1"
                  required
                />
              </div>
              <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
              </div>
            </form>
          </div>
        </div>
      </section>

      <!-- Featured Accommodations Section -->
      <section
        class="featured-accommodations bg-light"
        id="featured-accommodations"
      >
        <div class="container">
          <h2 class="text-center mb-5">Featured Accommodations</h2>
          <div class="row">
            <?php
            // Fetch featured accommodations from database
            $featured_sql = "SELECT a.*, u.firstName, u.lastName FROM ACCOMMODATION a 
                           JOIN USER u ON a.hostId = u.userId 
                            LIMIT 6";
            $featured_result = $conn->query($featured_sql);
            
            if ($featured_result && $featured_result->num_rows > 0) {
                while ($accommodation = $featured_result->fetch_assoc()) {
                    $img_src = htmlspecialchars($accommodation['imagePath'] ?? 'img/house1.avif');
                    $name = htmlspecialchars($accommodation['name']);
                    $city = htmlspecialchars($accommodation['city']);
                    $price = number_format($accommodation['pricePerNight'], 2);
                    $host_name = htmlspecialchars($accommodation['firstName'] . ' ' . $accommodation['lastName']);
                    $acc_id = $accommodation['accommodationId'];
                    $bedrooms = $accommodation['bedrooms'];
                    $bathrooms = $accommodation['bathrooms'];
                    $max_guests = $accommodation['maxGuests'];
                    
                    echo '
                    <div class="col-md-6 col-lg-4 mb-4">
                      <div class="card accommodation-card h-100 shadow-sm">
                        <img
                          src="' . $img_src . '"
                          class="card-img-top"
                          alt="' . $name . '"
                          style="height: 250px; object-fit: cover;"
                        />
                        <div class="card-body d-flex flex-column">
                          <h5 class="card-title">' . $name . '</h5>
                          <p class="text-muted mb-2">
                            <i class="bi bi-geo-alt"></i> ' . $city . '
                          </p>
                          <p class="h5 text-primary mb-2">
                            <strong>$' . $price . '</strong> <small class="text-muted">/night</small>
                          </p>
                          <small class="text-muted mb-3">
                            <i class="bi bi-door-closed"></i> ' . $bedrooms . ' bed(s) | 
                            <i class="bi bi-water"></i> ' . $bathrooms . ' bath(s) | 
                            <i class="bi bi-people"></i> Max ' . $max_guests . ' guests
                          </small>
                          <p class="small text-muted mb-2">
                            Hosted by: ' . $host_name . '
                          </p>
                          <a href="booking.php?accommodation_id=' . $acc_id . '" class="btn btn-primary mt-auto">
                            <i class="bi bi-calendar-check"></i> Book Now
                          </a>
                        </div>
                      </div>
                    </div>
                    ';
                }
            } else {
                echo '<div class="col-12"><p class="text-center">No accommodations available at the moment.</p></div>';
            }
            ?>
          </div>
        </div>
      </section>
    </main>

    <!-- Scripts -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/dark-mode.js"></script>
    <script src="js/scroll-to-top.js"></script>
  </body>
</html>

<?php
// Close database connection
$conn->close();
?>
