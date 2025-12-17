<?php
session_start();
require "includes/dbconn.php";
require "includes/csrf.php";

// Check if user is host
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "host") {
    redirect("login.php");
}

if (!isset($_GET["id"])) {
    redirect("host.php");
}

$accommodation_id = $_GET["id"];
$host_id = $_SESSION["userId"];
$row = null;
$allAmenities = [];
$selectedAmenities = [];

if (empty($_POST)) {
    $row = select();
    if (!$row) {
        redirect("host.php");
    }
    $allAmenities = fetchAllAmenities();
    $selectedAmenities = fetchAccommodationAmenities($accommodation_id);
} else {
    // Verify CSRF token
    verifyCsrfToken();
    
    update();
    $conn->close();
    redirect("host.php");
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function select()
{
    global $conn;
    global $accommodation_id;
    global $host_id;

    $sql = "SELECT * FROM ACCOMMODATION WHERE accommodationId = $accommodation_id AND hostId = $host_id;";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}

function fetchAllAmenities()
{
    global $conn;
    $data = [];
    $sql = "SELECT amenityId, name, icon FROM AMENITY ORDER BY amenityId;";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($amenity = $result->fetch_assoc()) {
            $data[] = $amenity;
        }
    }
    if ($result) {
        $result->free();
    }
    return $data;
}

function fetchAccommodationAmenities($accommodationId)
{
    global $conn;
    $amenityIds = [];
    $sql = "SELECT amenityId FROM ACCOMMODATION_AMENITY WHERE accommodationId = $accommodationId;";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $amenityIds[] = $row['amenityId'];
        }
    }
    if ($result) {
        $result->free();
    }
    return $amenityIds;
}

function update()
{
    global $conn;
    global $accommodation_id;
    global $host_id;

    $name = htmlspecialchars($_POST["name"]);
    $address = htmlspecialchars($_POST["address"]);
    $city = htmlspecialchars($_POST["city"]);
    $pricePerNight = htmlspecialchars($_POST["pricePerNight"]);
    $bedrooms = htmlspecialchars($_POST["bedrooms"]);
    $bathrooms = htmlspecialchars($_POST["bathrooms"]);
    $maxGuests = htmlspecialchars($_POST["maxGuests"]);
    $description = htmlspecialchars($_POST["description"]);
    $imagePath = htmlspecialchars($_POST["imagePath"]);

    // Update accommodation without amenity columns
    $sql = "UPDATE ACCOMMODATION SET name='$name', address='$address', city='$city', pricePerNight='$pricePerNight', bedrooms='$bedrooms', bathrooms='$bathrooms', maxGuests='$maxGuests', description='$description', imagePath='$imagePath' WHERE accommodationId=$accommodation_id AND hostId=$host_id;";
    
    if ($conn->query($sql)) {
        // Delete existing amenity relationships
        $deleteSql = "DELETE FROM ACCOMMODATION_AMENITY WHERE accommodationId = $accommodation_id;";
        $conn->query($deleteSql);
        
        // Insert new amenity relationships
        if (isset($_POST["amenities"]) && is_array($_POST["amenities"])) {
            foreach ($_POST["amenities"] as $amenityId) {
                $amenityId = intval($amenityId);
                $amenitySql = "INSERT INTO ACCOMMODATION_AMENITY (accommodationId, amenityId) VALUES ($accommodation_id, $amenityId);";
                $conn->query($amenitySql);
            }
        }
        return true;
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Property - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5">
        <h2 class="mb-4">Edit Property</h2>

        <div class="row">
            <div class="col-md-8">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$accommodation_id"); ?>">
                    <?php csrfTokenField(); ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Property Name</label>
                        <input type="text" class="form-control" name="name" id="name" 
                               value="<?php echo htmlspecialchars($row["name"]); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="address" 
                               value="<?php echo htmlspecialchars($row["address"]); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" name="city" id="city" 
                               value="<?php echo htmlspecialchars($row["city"]); ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="pricePerNight" class="form-label">Price per Night ($)</label>
                            <input type="number" class="form-control" name="pricePerNight" id="pricePerNight" 
                                   value="<?php echo htmlspecialchars($row["pricePerNight"]); ?>" step="0.01" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="maxGuests" class="form-label">Max Guests</label>
                            <input type="number" class="form-control" name="maxGuests" id="maxGuests" 
                                   value="<?php echo htmlspecialchars($row["maxGuests"]); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bedrooms" class="form-label">Bedrooms</label>
                            <input type="number" class="form-control" name="bedrooms" id="bedrooms" 
                                   value="<?php echo htmlspecialchars($row["bedrooms"]); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bathrooms" class="form-label">Bathrooms</label>
                            <input type="number" class="form-control" name="bathrooms" id="bathrooms" 
                                   value="<?php echo htmlspecialchars($row["bathrooms"]); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="4"><?php echo htmlspecialchars($row["description"] ?? ""); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="imagePath" class="form-label">Image Path</label>
                        <input type="text" class="form-control" name="imagePath" id="imagePath" 
                               value="<?php echo htmlspecialchars($row["imagePath"] ?? ""); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amenities</label>
                        <?php foreach ($allAmenities as $amenity): ?>
                        <?php $isChecked = in_array($amenity['amenityId'], $selectedAmenities) ? 'checked' : ''; ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="amenities[]" id="amenity<?php echo $amenity['amenityId']; ?>" value="<?php echo $amenity['amenityId']; ?>" <?php echo $isChecked; ?>>
                            <label class="form-check-label" for="amenity<?php echo $amenity['amenityId']; ?>">
                                <i class="<?php echo htmlspecialchars($amenity['icon']); ?>"></i> <?php echo htmlspecialchars($amenity['name']); ?>
                            </label>
                        </div>
                        <?php endforeach; ?>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='host.php'">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="js/dark-mode.js"></script>
    <script src="js/scroll-to-top.js"></script>
</body>
</html>
