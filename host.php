<?php
session_start();
require "includes/dbconn.php";

// Check if user is host
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "host") {
    header("Location: login.php");
    exit;
}

$host_id = $_SESSION["userId"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="[Your Team Members' Names]" />
    <title>Host Dashboard - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">My Properties</h2>
        <a href="add-accommodation.php" class="btn btn-success">
          <i class="bi bi-plus-circle me-2"></i>Add Property
        </a>
      </div>

      <!-- My Accommodations Table -->
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Property Name</th>
              <th scope="col">Address</th>
              <th scope="col">City</th>
              <th scope="col">Price/Night</th>
              <th scope="col">Guests</th>
              <th scope="col">Bedrooms</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT * FROM ACCOMMODATIONS WHERE hostId = $host_id;";
            if ($result = $conn->query($sql)) {
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row["name"] . "</td>";
                  echo "<td>" . $row["address"] . "</td>";
                  echo "<td>" . $row["city"] . "</td>";
                  echo "<td>$" . $row["pricePerNight"] . "</td>";
                  echo "<td>" . $row["maxGuests"] . "</td>";
                  echo "<td>" . $row["bedrooms"] . "</td>";
                  echo "<td>";
                  echo "<a href='edit-host-property.php?id=" . $row["accommodationId"] . "' class='btn btn-sm btn-primary me-2'><i class='bi bi-pencil-square'></i> Edit</a>";
                  echo "<a href='delete-accommodation.php?id=" . $row["accommodationId"] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?')\"><i class='bi bi-trash'></i> Delete</a>";
                  echo "</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='7' class='text-center'>No properties yet</td></tr>";
              }
              $result->free();
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Booking Requests Section -->
      <h3 class="mt-5 mb-3">Booking Requests for My Properties</h3>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Property</th>
              <th scope="col">Customer</th>
              <th scope="col">Check-in</th>
              <th scope="col">Check-out</th>
              <th scope="col">Guests</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT b.*, a.name, u.firstName, u.lastName FROM BOOKINGS b 
                    JOIN ACCOMMODATIONS a ON b.accommodationId = a.accommodationId 
                    JOIN USERS u ON b.userId = u.userId 
                    WHERE a.hostId = $host_id;";
            if ($result = $conn->query($sql)) {
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row["name"] . "</td>";
                  echo "<td>" . $row["firstName"] . " " . $row["lastName"] . "</td>";
                  echo "<td>" . $row["checkInDate"] . "</td>";
                  echo "<td>" . $row["checkOutDate"] . "</td>";
                  echo "<td>" . ($row["guests"] ?? $row["maxGuests"]) . "</td>";
                  echo "<td>$" . $row["totalPrice"] . "</td>";
                  echo "<td>" . $row["status"] . "</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='7' class='text-center'>No bookings yet</td></tr>";
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
