<?php
session_start();
require "includes/dbconn.php";
require "includes/csrf.php";
require "includes/encryption.php";

// Check if user is manager
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "manager") {
    header("Location: login.php");
    exit;
}

$csrfToken = generateCsrfToken();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Shengyi Shi 744564, Yuming Deng 744571, Mingxuan Xu 744580, Yanzhang Lu 744586" />
    <title>Manager Dashboard - KXO205 Accommodation Booking</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5">
      <h2 class="mb-4">Platform Management</h2>

      <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($_GET['error']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
      <?php endif; ?>

      <!-- User Management Table -->
      <h4 class="mt-5 mb-3">User Management</h4>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">User ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Phone</th>
              <th scope="col">Role</th>
              <th scope="col">Address</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT * FROM USER;";
            if ($result = $conn->query($sql)) {
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row["userId"] . "</td>";
                  echo "<td>" . $row["firstName"] . " " . $row["lastName"] . "</td>";
                  echo "<td>" . $row["email"] . "</td>";
                  echo "<td>" . $row["phoneNumber"] . "</td>";
                  echo "<td>" . $row["role"] . "</td>";
                  echo "<td>" . $row["postalAddress"] . "</td>";
                  echo "<td>";
                  echo "<a href='edit-user.php?id=" . $row["userId"] . "' class='btn btn-sm btn-primary me-2'><i class='bi bi-pencil-square'></i> Edit</a>";
                  if ($row["userId"] != $_SESSION["userId"]) {
                    echo "<a href='delete-user.php?id=" . $row["userId"] . "&token=" . urlencode($csrfToken) . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?')\"><i class='bi bi-trash'></i> Delete</a>";
                  }
                  echo "</td>";
                  echo "</tr>";
                }
              }
              $result->free();
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Accommodations Table -->
      <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
        <h4 class="mb-0">All Accommodations</h4>
        <a href="add-accommodation.php" class="btn btn-success btn-sm">
          <i class="bi bi-plus-circle me-2"></i>Add Accommodation
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Host</th>
              <th scope="col">City</th>
              <th scope="col">Price/Night</th>
              <th scope="col">Bedrooms</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT a.*, u.firstName, u.lastName FROM ACCOMMODATION a JOIN USER u ON a.hostId = u.userId;";
            if ($result = $conn->query($sql)) {
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row["accommodationId"] . "</td>";
                  echo "<td>" . $row["name"] . "</td>";
                  echo "<td>" . $row["firstName"] . " " . $row["lastName"] . "</td>";
                  echo "<td>" . $row["city"] . "</td>";
                  echo "<td>$" . $row["pricePerNight"] . "</td>";
                  echo "<td>" . $row["bedrooms"] . "</td>";
                  echo "<td><a href='edit-accommodation.php?id=" . $row["accommodationId"] . "' class='btn btn-sm btn-primary me-2'><i class='bi bi-pencil-square'></i> Edit</a>";
                  echo "<a href='delete-accommodation.php?id=" . $row["accommodationId"] . "&token=" . urlencode($csrfToken) . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?')\"><i class='bi bi-trash'></i> Delete</a></td>";
                  echo "</tr>";
                }
              }
              $result->free();
            }
            ?>
          </tbody>
        </table>
      </div>

      <!-- Bookings Table -->
      <h4 class="mt-5 mb-3">All Bookings</h4>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col">Booking ID</th>
              <th scope="col">Customer</th>
              <th scope="col">Property</th>
              <th scope="col">Check-in</th>
              <th scope="col">Actions</th>
              <th scope="col">Check-out</th>
              <th scope="col">Phone</th>
              <th scope="col">Payment</th>
              <th scope="col">Total</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $sql = "SELECT b.*, u.firstName, u.lastName, a.name FROM BOOKING b JOIN USER u ON b.userId = u.userId JOIN ACCOMMODATION a ON b.accommodationId = a.accommodationId;";
            if ($result = $conn->query($sql)) {
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $is_cancelled = ($row["status"] === 'cancelled');
                  
                  // Decrypt payment details
                  $payment_info = decryptPaymentDetails($row["paymentDetails"]);
                  $payment_display = "-";
                  if ($payment_info && strpos($payment_info, 'Payment Method:') !== false) {
                    preg_match('/Payment Method: ([^|]+)/', $payment_info, $method_match);
                    preg_match('/Last 4: ([^|]+)/', $payment_info, $last4_match);
                    $payment_display = isset($method_match[1]) ? trim($method_match[1]) : "-";
                    if (isset($last4_match[1])) {
                      $payment_display .= " (****" . trim($last4_match[1]) . ")";
                    }
                  }
                  
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row["bookingId"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["firstName"] . " " . $row["lastName"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["checkInDate"]) . "</td>";
                  
                  // Cancel button
                  if (!$is_cancelled) {
                    echo "<td><a href='cancel-booking.php?id=" . htmlspecialchars($row["bookingId"]) . "&token=" . urlencode($csrfToken) . "' class='btn btn-sm btn-warning' onclick=\"return confirm('Are you sure you want to cancel this booking?')\"><i class='bi bi-x-circle'></i> Cancel</a></td>";
                  } else {
                    echo "<td><span class='text-muted'>Cancelled</span></td>";
                  }
                  
                  echo "<td>" . htmlspecialchars($row["checkOutDate"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["phoneNumber"] ?? "-") . "</td>";
                  echo "<td><small>" . htmlspecialchars($payment_display) . "</small></td>";
                  echo "<td>$" . htmlspecialchars($row["totalPrice"]) . "</td>";
                  
                  // Display status with badge
                  $statusBadge = $row["status"] === 'cancelled' ? 
                    "<span class='badge bg-secondary'>Cancelled</span>" : 
                    "<span class='badge bg-success'>Confirmed</span>";
                  echo "<td>" . $statusBadge . "</td>";
                  
                  echo "</tr>";
                }
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
