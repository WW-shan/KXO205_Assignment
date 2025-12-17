<?php
session_start();
require "includes/dbconn.php";
require "includes/csrf.php";
require "includes/encryption.php";

// Check if user is client
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "client") {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["userId"];
$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Shengyi Shi 744564, Yuming Deng 744571, Mingxuan Xu 744580, Yanzhang Lu 744586" />
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
              <th scope="col">Phone</th>
              <th scope="col">Total Price</th>
              <th scope="col">Payment</th>
              <th scope="col">Status</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
                $sql = "SELECT b.*, a.name, u.firstName, u.lastName FROM BOOKING b 
                  JOIN ACCOMMODATION a ON b.accommodationId = a.accommodationId 
                  JOIN USER u ON a.hostId = u.userId 
                    WHERE b.userId = $user_id;";
            if ($result = $conn->query($sql)) {
              if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $check_out = strtotime($row["checkOutDate"]);
                  $today = strtotime(date('Y-m-d'));
                  $can_cancel = ($check_out > $today) ? true : false;
                  
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
                  echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["firstName"] . " " . $row["lastName"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["checkInDate"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["checkOutDate"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["guests"] ?? $row["maxGuests"]) . "</td>";
                  echo "<td>" . htmlspecialchars($row["phoneNumber"] ?? "-") . "</td>";
                  echo "<td>$" . htmlspecialchars($row["totalPrice"]) . "</td>";
                  echo "<td><small>" . htmlspecialchars($payment_display) . "</small></td>";
                  echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                  if ($can_cancel) {
                    echo "<td><a href='cancel-booking.php?id=" . htmlspecialchars($row["bookingId"]) . "&token=" . urlencode($csrfToken) . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?')\"><i class='bi bi-x-circle'></i> Cancel</a></td>";
                  } else {
                    echo "<td><span class='text-muted'>Expired</span></td>";
                  }
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='10' class='text-center'>No bookings yet</td></tr>";
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
