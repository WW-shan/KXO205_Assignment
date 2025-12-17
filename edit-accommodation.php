<?php
session_start();
require "includes/dbconn.php";
require "includes/csrf.php";

// Check if user is manager
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "manager") {
    redirect("login.php");
}

if (!isset($_GET["id"])) {
    redirect("manager.php");
}

$accommodation_id = $_GET["id"];
$row = null;

if (empty($_POST)) {
    $row = select();
    if (!$row) {
        redirect("manager.php");
    }
} else {
    // Verify CSRF token
    verifyCsrfToken();
    
    update();
    $conn->close();
    redirect("manager.php");
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

    $sql = "SELECT * FROM ACCOMMODATION WHERE accommodationId = $accommodation_id;";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}

function update()
{
    global $conn;
    global $accommodation_id;

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
    $sql = "UPDATE ACCOMMODATION SET name='$name', address='$address', city='$city', pricePerNight='$pricePerNight', bedrooms='$bedrooms', bathrooms='$bathrooms', maxGuests='$maxGuests', description='$description', imagePath='$imagePath', allowSmoking=$allowSmoking, hasGarage=$hasGarage, petFriendly=$petFriendly, hasInternet=$hasInternet WHERE accommodationId=$accommodation_id;";
    return $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Accommodation - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5">
        <h2 class="mb-4">Edit Accommodation</h2>

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
                        <label class="form-label">Features</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="allowSmoking" id="allowSmoking" value="1" <?php if ($row["allowSmoking"]) echo "checked"; ?>>
                            <label class="form-check-label" for="allowSmoking">
                                Allow Smoking
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hasGarage" id="hasGarage" value="1" <?php if ($row["hasGarage"]) echo "checked"; ?>>
                            <label class="form-check-label" for="hasGarage">
                                Has Garage
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="petFriendly" id="petFriendly" value="1" <?php if ($row["petFriendly"]) echo "checked"; ?>>
                            <label class="form-check-label" for="petFriendly">
                                Pet Friendly
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="hasInternet" id="hasInternet" value="1" <?php if ($row["hasInternet"]) echo "checked"; ?>>
                            <label class="form-check-label" for="hasInternet">
                                Has Internet
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='manager.php'">
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
