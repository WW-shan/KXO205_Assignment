<?php
session_start();
require "includes/dbconn.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "client") {
    redirect("login.php");
}

if (!isset($_GET["accommodation_id"])) {
    redirect("search.php");
}

$accommodation_id = intval($_GET["accommodation_id"]);
// Prioritize POST data (from form submission), fallback to GET data (initial load from search/homepage)
$check_in = isset($_POST["check_in"]) ? htmlspecialchars($_POST["check_in"]) : (isset($_GET["check_in"]) ? htmlspecialchars($_GET["check_in"]) : "");
$check_out = isset($_POST["check_out"]) ? htmlspecialchars($_POST["check_out"]) : (isset($_GET["check_out"]) ? htmlspecialchars($_GET["check_out"]) : "");
$guests = isset($_POST["guests"]) ? intval($_POST["guests"]) : (isset($_GET["guests"]) ? intval($_GET["guests"]) : 0);
$user_id = $_SESSION["userId"];

$accommodation = fetchAccommodation($conn, $accommodation_id);
if (!$accommodation) {
    redirect("search.php");
}

$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = validateBooking($accommodation, $check_in, $check_out, $guests);
    if (empty($errors) && hasConflict($conn, $accommodation_id, $check_in, $check_out)) {
        $errors[] = "These dates are no longer available. Please choose different dates.";
    }

    if (empty($errors)) {
        if (createBooking($conn, $user_id, $accommodation_id, $check_in, $check_out, $guests, $accommodation["pricePerNight"])) {
            $conn->close();
            redirect("client.php?success=Booking completed successfully!");
        }
        $errors[] = "Error creating booking. Please try again.";
    }
}

$nights = 0;
$total_price = 0;
if (!empty($check_in) && !empty($check_out)) {
    try {
        $check_in_obj = new DateTime($check_in);
        $check_out_obj = new DateTime($check_out);
        $nights = $check_out_obj->diff($check_in_obj)->days;
        $total_price = $accommodation["pricePerNight"] * $nights;
    } catch (Exception $e) {
        $nights = 0;
        $total_price = 0;
    }
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function fetchAccommodation($conn, $accommodation_id)
{
    $sql = "SELECT * FROM ACCOMMODATION WHERE accommodationId = $accommodation_id;";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $result->free();
        return $row;
    }
    return null;
}

function validateBooking($accommodation, $check_in, $check_out, $guests)
{
    $errs = [];
    if (empty($check_in)) {
        $errs[] = "Check-in date is required";
    }
    if (empty($check_out)) {
        $errs[] = "Check-out date is required";
    }
    if ($check_out <= $check_in) {
        $errs[] = "Check-out date must be after check-in date";
    }
    if ($guests <= 0 || $guests > $accommodation["maxGuests"]) {
        $errs[] = "Invalid number of guests. Maximum is " . $accommodation["maxGuests"];
    }
    return $errs;
}

function hasConflict($conn, $accommodation_id, $check_in, $check_out)
{
    $conflict_sql = "SELECT COUNT(*) as count FROM BOOKING 
                    WHERE accommodationId = $accommodation_id 
                    AND status = 'confirmed'
                    AND (checkInDate < '$check_out' AND checkOutDate > '$check_in') FOR UPDATE;";
    $conflict_result = $conn->query($conflict_sql);
    if ($conflict_result) {
        $conflict_row = $conflict_result->fetch_assoc();
        $conflict_result->free();
        return $conflict_row["count"] > 0;
    }
    return false;
}

function createBooking($conn, $user_id, $accommodation_id, $check_in, $check_out, $guests, $price_per_night)
{
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Re-check conflict with row lock
        if (hasConflict($conn, $accommodation_id, $check_in, $check_out)) {
            $conn->rollback();
            return false;
        }
        
        $check_in_obj = new DateTime($check_in);
        $check_out_obj = new DateTime($check_out);
        $nights = $check_out_obj->diff($check_in_obj)->days;
        $total_price = $price_per_night * $nights;
        $payment_details = "Payment processed";

        $insert_sql = "INSERT INTO BOOKING (userId, accommodationId, checkInDate, checkOutDate, guests, totalPrice, paymentDetails, status) 
                      VALUES ($user_id, $accommodation_id, '$check_in', '$check_out', $guests, $total_price, '$payment_details', 'confirmed');";

        $success = $conn->query($insert_sql);
        
        if ($success) {
            $conn->commit();
            return true;
        } else {
            $conn->rollback();
            return false;
        }
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Complete Booking - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5">
        <h2 class="mb-4">Complete Your Booking</h2>

        <div class="row">
            <!-- Booking Summary -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($accommodation["imagePath"]); ?>" 
                        class="card-img-top" alt="<?php echo htmlspecialchars($accommodation["name"]); ?>" style="height: 300px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($accommodation["name"]); ?></h5>
                        <p class="text-muted mb-3">
                            <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($accommodation["address"] . ", " . $accommodation["city"]); ?>
                        </p>
                        
                        <div class="mb-3">
                            <h6>Accommodation Details</h6>
                            <ul class="list-unstyled small">
                                <li><i class="bi bi-door-closed"></i> <?php echo $accommodation["bedrooms"]; ?> Bedroom(s)</li>
                                <li><i class="bi bi-water"></i> <?php echo $accommodation["bathrooms"]; ?> Bathroom(s)</li>
                                <li><i class="bi bi-people"></i> Max <?php echo $accommodation["maxGuests"]; ?> Guest(s)</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <h6>Features</h6>
                            <div>
                                <?php if ($accommodation["allowSmoking"]): ?>
                                    <span class="badge bg-info me-2"><i class="bi bi-tornado"></i> Smoking</span>
                                <?php endif; ?>
                                <?php if ($accommodation["hasGarage"]): ?>
                                    <span class="badge bg-secondary me-2"><i class="bi bi-car-front"></i> Garage</span>
                                <?php endif; ?>
                                <?php if ($accommodation["petFriendly"]): ?>
                                    <span class="badge bg-success me-2"><i class="bi bi-heart"></i> Pet Friendly</span>
                                <?php endif; ?>
                                <?php if ($accommodation["hasInternet"]): ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-wifi"></i> Internet</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h6>Booking Details</h6>
                            <p class="mb-1"><strong>Check-in:</strong> <?php echo $check_in; ?></p>
                            <p class="mb-1"><strong>Check-out:</strong> <?php echo $check_out; ?></p>
                            <p class="mb-1"><strong>Guests:</strong> <?php echo $guests; ?></p>
                            <p class="mb-0"><strong>Nights:</strong> <?php echo $nights; ?></p>
                        </div>

                        <hr>

                        <div class="mb-0">
                            <h5>Price Breakdown</h5>
                            <p class="mb-1">$<?php echo number_format($accommodation["pricePerNight"], 2); ?> x <?php echo $nights; ?> nights = <strong>$<?php echo number_format($total_price, 2); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Guest Information</h5>

                        <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php foreach ($errors as $error): ?>
                                <div><i class="bi bi-exclamation-circle me-2"></i><?php echo $error; ?></div>
                            <?php endforeach; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?accommodation_id=$accommodation_id"); ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($_SESSION["name"] ?? ""); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_SESSION["email"] ?? ""); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="check_in" class="form-label">Check-in Date</label>
                                <input type="date" class="form-control" id="check_in" name="check_in" 
                                       value="<?php echo !empty($check_in) ? htmlspecialchars($check_in) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="check_out" class="form-label">Check-out Date</label>
                                <input type="date" class="form-control" id="check_out" name="check_out" 
                                       value="<?php echo !empty($check_out) ? htmlspecialchars($check_out) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="guests" class="form-label">Number of Guests</label>
                                <input type="number" class="form-control" id="guests" name="guests" 
                                       value="<?php echo $guests > 0 ? $guests : ''; ?>" min="1" max="<?php echo $accommodation["maxGuests"]; ?>" required>
                            </div>

                            <div class="mb-3 p-3 bg-light rounded">
                                <h6>Total Cost</h6>
                                <h4 class="text-primary mb-0">$<?php echo number_format($total_price, 2); ?></h4>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="search.php" class="btn btn-secondary flex-grow-1">
                                    <i class="bi bi-arrow-left me-2"></i>Back to Search
                                </a>
                                <button type="submit" class="btn btn-success flex-grow-1">
                                    <i class="bi bi-check-circle me-2"></i>Confirm Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="js/dark-mode.js"></script>
    <script src="js/scroll-to-top.js"></script>
</body>
</html>
