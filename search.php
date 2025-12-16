<?php
session_start();
require "includes/dbconn.php";

$city = isset($_POST["city"]) ? trim($_POST["city"]) : "";
$check_in = isset($_POST["check_in"]) ? trim($_POST["check_in"]) : "";
$check_out = isset($_POST["check_out"]) ? trim($_POST["check_out"]) : "";
$guests = isset($_POST["guests"]) ? intval($_POST["guests"]) : 0;

$search_results = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = validateSearch($city, $check_in, $check_out, $guests);
    if (empty($errors)) {
        $search_results = searchAccommodations($conn, $city, $check_in, $check_out, $guests);
        if ($search_results === false) {
            $errors[] = "Unable to perform search right now.";
        }
    }
}

$conn->close();

function validateSearch($city, $check_in, $check_out, $guests)
{
    $errs = [];
    if ($city === "") {
        $errs[] = "City is required.";
    }
    if ($check_in === "") {
        $errs[] = "Check-in date is required.";
    }
    if ($check_out === "") {
        $errs[] = "Check-out date is required.";
    }
    if ($check_in !== "" && $check_out !== "") {
        $checkInDate = date_create($check_in);
        $checkOutDate = date_create($check_out);
        if (!$checkInDate || !$checkOutDate) {
            $errs[] = "Invalid date format.";
        } elseif ($checkOutDate <= $checkInDate) {
            $errs[] = "Check-out date must be after check-in date.";
        }
    }
    if ($guests <= 0) {
        $errs[] = "Number of guests is required.";
    }
    return $errs;
}

function searchAccommodations($conn, $city, $check_in, $check_out, $guests)
{
    $results = [];
    $sql = "SELECT a.*, u.firstName, u.lastName, u.phoneNumber, u.email
                    FROM ACCOMMODATIONS a
                    JOIN USERS u ON a.hostId = u.userId
                    WHERE a.city LIKE ?
                        AND a.maxGuests >= ?
                        AND a.accommodationId NOT IN (
                                SELECT accommodationId FROM BOOKINGS
                                WHERE (checkInDate < ? AND checkOutDate > ?)
                                    AND status = 'confirmed'
                        )";

    if ($stmt = $conn->prepare($sql)) {
        $cityFilter = "%" . $city . "%";
        $stmt->bind_param("siss", $cityFilter, $guests, $check_out, $check_in);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $results[] = $row;
        }
        $stmt->close();
        return $results;
    }

    return false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="[Your Team Members' Names]" />
    <title>Find Stay - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <?php include "includes/navbar.php"; ?>

    <main class="container mt-5">
        <h2 class="mb-4">Find Your Stay</h2>

        <div class="search-panel bg-light p-4 rounded mb-4">
            <form id="searchForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="row g-3">
                <div class="col-md-3">
                    <label for="destination" class="form-label">City</label>
                    <input type="text" class="form-control" id="destination" name="city" value="<?php echo htmlspecialchars($city); ?>" placeholder="e.g., Sydney" required>
                </div>
                <div class="col-md-3">
                    <label for="checkin" class="form-label">Check-in Date</label>
                    <input type="date" class="form-control" id="checkin" name="check_in" value="<?php echo htmlspecialchars($check_in); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="checkout" class="form-label">Check-out Date</label>
                    <input type="date" class="form-control" id="checkout" name="check_out" value="<?php echo htmlspecialchars($check_out); ?>" required>
                </div>
                <div class="col-md-2">
                    <label for="guests" class="form-label">Guests</label>
                    <input type="number" class="form-control" id="guests" name="guests" value="<?php echo $guests ? htmlspecialchars($guests) : ''; ?>" min="1" required>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php foreach ($errors as $error): ?>
                    <div><i class="bi bi-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)): ?>
            <?php if (count($search_results) > 0): ?>
                <h3 class="mb-4">Available Properties (<?php echo count($search_results); ?> found)</h3>
                <div class="row">
                    <?php foreach ($search_results as $accommodation): ?>
                        <?php
                            $check_in_obj = new DateTime($check_in);
                            $check_out_obj = new DateTime($check_out);
                            $nights = $check_out_obj->diff($check_in_obj)->days;
                            $total_price = $accommodation['pricePerNight'] * $nights;
                        ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?php echo htmlspecialchars($accommodation['imagePath']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($accommodation['name']); ?>" style="height: 250px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($accommodation['name']); ?></h5>
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($accommodation['address']); ?>, <?php echo htmlspecialchars($accommodation['city']); ?>
                                    </p>
                                    <p class="h5 text-primary mb-3">
                                        <strong>$<?php echo number_format($accommodation['pricePerNight'], 2); ?></strong> / night
                                    </p>
                                    <div class="mb-3">
                                        <p class="mb-2">
                                            <i class="bi bi-door-closed"></i> <?php echo $accommodation['bedrooms']; ?> Bedroom(s)
                                            <i class="bi bi-water ms-2"></i> <?php echo $accommodation['bathrooms']; ?> Bathroom(s)
                                        </p>
                                        <p class="mb-0">
                                            <i class="bi bi-people"></i> Max <?php echo $accommodation['maxGuests']; ?> Guest(s)
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <?php if ($accommodation['allowSmoking']): ?>
                                            <span class="badge bg-info me-2"><i class="bi bi-tornado"></i> Smoking</span>
                                        <?php endif; ?>
                                        <?php if ($accommodation['hasGarage']): ?>
                                            <span class="badge bg-secondary me-2"><i class="bi bi-car-front"></i> Garage</span>
                                        <?php endif; ?>
                                        <?php if ($accommodation['petFriendly']): ?>
                                            <span class="badge bg-success me-2"><i class="bi bi-heart"></i> Pet Friendly</span>
                                        <?php endif; ?>
                                        <?php if ($accommodation['hasInternet']): ?>
                                            <span class="badge bg-warning text-dark"><i class="bi bi-wifi"></i> Internet</span>
                                        <?php endif; ?>
                                    </div>
                                    <p class="card-text small text-muted flex-grow-1">
                                        <?php echo htmlspecialchars(substr($accommodation['description'], 0, 120)); ?><?php echo strlen($accommodation['description']) > 120 ? '...' : ''; ?>
                                    </p>
                                    <hr>
                                    <p class="small mb-3">
                                        <strong>Host:</strong> <?php echo htmlspecialchars($accommodation['firstName'] . ' ' . $accommodation['lastName']); ?>
                                    </p>
                                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'client'): ?>
                                        <a href="booking.php?accommodation_id=<?php echo $accommodation['accommodationId']; ?>&check_in=<?php echo urlencode($check_in); ?>&check_out=<?php echo urlencode($check_out); ?>&guests=<?php echo $guests; ?>" class="btn btn-primary w-100 mt-auto">
                                            <i class="bi bi-calendar-check"></i> Book Now ($<?php echo number_format($total_price, 2); ?>)
                                        </a>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-outline-primary w-100 mt-auto">
                                            <i class="bi bi-box-arrow-in-right"></i> Login to Book
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>No accommodations available matching your criteria. Try different dates or location.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/validation.js"></script>
    <script src="js/dark-mode.js"></script>
    <script src="js/scroll-to-top.js"></script>
</body>
</html>
