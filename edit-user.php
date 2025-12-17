<?php
session_start();
require "includes/dbconn.php";

// Check if user is manager
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "manager") {
    redirect("login.php");
}

if (!isset($_GET["id"])) {
    redirect("manager.php");
}

$user_id = $_GET["id"];
$row = null;

if (empty($_POST)) {
    $row = select();
    if (!$row) {
        redirect("manager.php");
    }
} else {
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
    global $user_id;

    $sql = "SELECT * FROM USER WHERE userId = $user_id;";
    $result = $conn->query($sql);
    return $result ? $result->fetch_assoc() : null;
}

function update()
{
    global $conn;
    global $user_id;

    $firstName = htmlspecialchars($_POST["firstName"]);
    $lastName = htmlspecialchars($_POST["lastName"]);
    $email = htmlspecialchars($_POST["email"]);
    $phoneNumber = htmlspecialchars($_POST["phoneNumber"]);
    $postalAddress = htmlspecialchars($_POST["postalAddress"]);
    $role = htmlspecialchars($_POST["role"]);

    $sql = "UPDATE USER SET firstName='$firstName', lastName='$lastName', email='$email', phoneNumber='$phoneNumber', postalAddress='$postalAddress', role='$role' WHERE userId=$user_id;";
    return $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit User - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5">
        <h2 class="mb-4">Edit User</h2>

        <div class="row">
            <div class="col-md-6">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=$user_id"); ?>">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" name="firstName" id="firstName" 
                               value="<?php echo htmlspecialchars($row["firstName"]); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="lastName" id="lastName" 
                               value="<?php echo htmlspecialchars($row["lastName"]); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" 
                               value="<?php echo htmlspecialchars($row["email"]); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="phoneNumber" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" name="phoneNumber" id="phoneNumber" 
                               value="<?php echo htmlspecialchars($row["phoneNumber"]); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="postalAddress" class="form-label">Postal Address</label>
                        <input type="text" class="form-control" name="postalAddress" id="postalAddress" 
                               value="<?php echo htmlspecialchars($row["postalAddress"] ?? ""); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" name="role" id="role" required>
                            <option value="manager" <?php if ($row["role"] === "manager") echo "selected"; ?>>Manager</option>
                            <option value="host" <?php if ($row["role"] === "host") echo "selected"; ?>>Host</option>
                            <option value="client" <?php if ($row["role"] === "client") echo "selected"; ?>>Client</option>
                        </select>
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
