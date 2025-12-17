<?php
session_start();
require "includes/dbconn.php";

// Check if user is host or manager
if (!isset($_SESSION["role"]) || ($_SESSION["role"] != "host" && $_SESSION["role"] != "manager")) {
    redirect("login.php");
}

$hosts = [];
if ($_SESSION["role"] == "manager") {
    $hosts = fetchHosts();
}

if (empty($_POST)) {
    // show form
} else {
    insertAccommodation();
    $conn->close();
    redirect($_SESSION["role"] == "host" ? "host.php" : "manager.php");
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function fetchHosts()
{
    global $conn;
    $data = [];
    $sql = "SELECT userId, firstName, lastName FROM USER WHERE role = 'host' ORDER BY firstName;";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($host = $result->fetch_assoc()) {
            $data[] = $host;
        }
    }
    if ($result) {
        $result->free();
    }
    return $data;
}

function insertAccommodation()
{
    global $conn;

    $name = htmlspecialchars($_POST["name"]);
    $address = htmlspecialchars($_POST["address"]);
    $city = htmlspecialchars($_POST["city"]);
    $pricePerNight = htmlspecialchars($_POST["pricePerNight"]);
    $bedrooms = htmlspecialchars($_POST["bedrooms"]);
    $bathrooms = htmlspecialchars($_POST["bathrooms"]);
    $maxGuests = htmlspecialchars($_POST["maxGuests"]);
    $description = htmlspecialchars($_POST["description"]);
    $imagePath = htmlspecialchars($_POST["imagePath"]);
    $allowSmoking = isset($_POST["allowSmoking"]) ? 1 : 0;
    $hasGarage = isset($_POST["hasGarage"]) ? 1 : 0;
    $petFriendly = isset($_POST["petFriendly"]) ? 1 : 0;
    $hasInternet = isset($_POST["hasInternet"]) ? 1 : 0;

    // If manager is adding, use the hostId from form, otherwise use logged-in user
    $hostId = $_SESSION["role"] == "manager" ? htmlspecialchars($_POST["hostId"]) : $_SESSION["userId"];

    $sql = "INSERT INTO ACCOMMODATION (hostId, name, address, city, pricePerNight, bedrooms, bathrooms, maxGuests, description, imagePath, allowSmoking, hasGarage, petFriendly, hasInternet) 
            VALUES ($hostId, '$name', '$address', '$city', $pricePerNight, $bedrooms, $bathrooms, $maxGuests, '$description', '$imagePath', $allowSmoking, $hasGarage, $petFriendly, $hasInternet);";
    return $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Property - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5">
        <h2 class="mb-4">Add New Property</h2>

        <div class="row">
            <div class="col-md-8">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <!-- Manager can select which host owns this property -->
                    <?php if ($_SESSION["role"] == "manager"): ?>
                    <div class="mb-3">
                        <label for="host_id" class="form-label">Host</label>
                        <select class="form-select" name="host_id" id="host_id" required>
                            <option value="">-- Select Host --</option>
                            <?php foreach ($hosts as $host): ?>
                                <option value="<?php echo $host["userId"]; ?>"><?php echo htmlspecialchars($host["firstName"] . " " . $host["lastName"]); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Property Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" id="address" required>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" name="city" id="city" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price_per_night" class="form-label">Price per Night ($)</label>
                            <input type="number" class="form-control" name="price_per_night" id="price_per_night" step="0.01" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="max_guests" class="form-label">Max Guests</label>
                            <input type="number" class="form-control" name="max_guests" id="max_guests" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="bedrooms" class="form-label">Bedrooms</label>
                            <input type="number" class="form-control" name="bedrooms" id="bedrooms" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="bathrooms" class="form-label">Bathrooms</label>
                            <input type="number" class="form-control" name="bathrooms" id="bathrooms" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="4"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image_path" class="form-label">Image Path</label>
                        <input type="text" class="form-control" name="image_path" id="image_path" placeholder="e.g., img/house1.avif" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Features</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="allow_smoking" id="allow_smoking" value="1">
                            <label class="form-check-label" for="allow_smoking">
                                Allow Smoking
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_garage" id="has_garage" value="1">
                            <label class="form-check-label" for="has_garage">
                                Has Garage
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="pet_friendly" id="pet_friendly" value="1">
                            <label class="form-check-label" for="pet_friendly">
                                Pet Friendly
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_internet" id="has_internet" value="1">
                            <label class="form-check-label" for="has_internet">
                                Has Internet
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='<?php echo ($_SESSION["role"] == "host" ? "host.php" : "manager.php"); ?>'">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-2"></i>Add Property
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
