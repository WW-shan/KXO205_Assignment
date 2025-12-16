<?php
session_start();
require "includes/dbconn.php";

// Check if user is client
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "client") {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["userId"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="[Your Team Members' Names]" />
    <title>Customer Dashboard - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5">
      <h2 class="mb-4">My Bookings</h2>

      <!-- My Bookings Table -->
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Property</th>
              <th scope="col">Host</th>
              <th scope="col">Check-in</th>
              <th scope="col">Check-out</th>
              <th scope="col">Guests</th>
              <th scope="col">Total Price</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT b.*, a.name, u.firstName, u.lastName FROM BOOKINGS b 
                    JOIN ACCOMMODATIONS a ON b.accommodationId = a.accommodationId 
                    JOIN USERS u ON a.hostId = u.userId 
                    WHERE b.userId = $user_id;";
            if ($result = $conn->query($sql)) {
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $check_out = strtotime($row["checkOutDate"]);
                  $today = strtotime(date('Y-m-d'));
                  $can_cancel = ($check_out > $today) ? true : false;
                  
                  echo "<tr>";
                  echo "<td>" . $row["name"] . "</td>";
                  echo "<td>" . $row["firstName"] . " " . $row["lastName"] . "</td>";
                  echo "<td>" . $row["checkInDate"] . "</td>";
                  echo "<td>" . $row["checkOutDate"] . "</td>";
                  echo "<td>" . ($row["guests"] ?? $row["maxGuests"]) . "</td>";
                  echo "<td>$" . $row["totalPrice"] . "</td>";
                  echo "<td>" . $row["status"] . "</td>";
                  if ($can_cancel) {
                    echo "<td><a href='cancel-booking.php?id=" . $row["bookingId"] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?')\"><i class='bi bi-x-circle'></i> Cancel</a></td>";
                  } else {
                    echo "<td><span class='text-muted'>Expired</span></td>";
                  }
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='8' class='text-center'>No bookings yet</td></tr>";
              }
              $result->free();
            }
            ?>
          </tbody>
        </table>
      </div>
    </main>

    <!-- Scripts -->
    <script src="js/dark-mode.js"></script>
    <script src="js/scroll-to-top.js"></script>
  </body>
</html>
