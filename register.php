<?php
require "includes/dbconn.php";
require "includes/csrf.php";
session_start();

if (isset($_POST["email"]) && isset($_POST["password"])) {
  // Verify CSRF token
  verifyCsrfToken();
  
  $userType       = htmlspecialchars($_POST["userType"]);
  $role           = ($userType === "host") ? "host" : "client";
  $firstName      = htmlspecialchars($_POST["firstName"]);
  $lastName       = htmlspecialchars($_POST["lastName"]);
  $email          = htmlspecialchars($_POST["email"]);
  $mobile         = htmlspecialchars($_POST["mobile"]);
  $postalAddress  = htmlspecialchars($_POST["postalAddress"] ?? "");
  $password       = $_POST["password"]; // password_hash will handle sanitization
  $confirmPassword = $_POST["confirmPassword"] ?? "";
  $abn            = htmlspecialchars($_POST["abn"] ?? "");

  // Server-side validation
  $isValid = true;
  
  // 1. Check password match
  if ($password !== $confirmPassword) {
    $isValid = false;
  }
  // 2. Check password strength (6-12 chars, uppercase, lowercase, number, special char)
  elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+={}[\]|\\:;"\'<>,.?\/~`]).{6,12}$/', $password)) {
    $isValid = false;
  }
  // 3. Check email uniqueness
  else {
    $emailCheck = $conn->real_escape_string($email);
    $checkSql = "SELECT userId FROM USER WHERE email = '$emailCheck'";
    $checkResult = $conn->query($checkSql);
    if ($checkResult && $checkResult->num_rows > 0) {
      $isValid = false;
      $checkResult->free();
    }
    // 4. Check ABN for hosts
    elseif ($role === "host" && empty($abn)) {
      $isValid = false;
    }
  }

  // If valid, proceed with registration
  if ($isValid) {
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO USER (email, password, firstName, lastName, phoneNumber, postalAddress, role, abnNumber) VALUES (
      \"$email\", \"$hashedPassword\", \"$firstName\", \"$lastName\", \"$mobile\", \"$postalAddress\", \"$role\", " . ($role === "host" ? "\"$abn\"" : "NULL") . "
    )";
    
    if ($result = $conn->query($sql)) {
      $conn->close();
      header("Location: login.php?registered=success");
      exit;
    }
  }
  $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Shengyi Shi 744564, Yuming Deng 744571, Mingxuan Xu 744580, Yanzhang Lu 744586" />
    <title>Register - KXO205 Accommodation Booking</title>
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
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <h2 class="mb-4 text-center">Create Your Account</h2>
          
          <form id="registrationForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
            <?php csrfTokenField(); ?>
            <div class="mb-3">
              <label for="userType" class="form-label">User Type</label>
              <select class="form-select" id="userType" name="userType" required>
                <option value="customer" selected>Customer</option>
                <option value="host">Host</option>
              </select>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="firstName"
                  name="firstName"
                  required
                />
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName" class="form-label">Last Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="lastName"
                  name="lastName"
                  required
                />
              </div>
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" required />
            </div>

            <div class="mb-3">
              <label for="mobile" class="form-label">Mobile Number</label>
              <input type="tel" class="form-control" id="mobile" name="mobile" required />
            </div>

            <div class="mb-3">
              <label for="postalAddress" class="form-label">Postal Address</label>
              <input type="text" class="form-control" id="postalAddress" name="postalAddress" required />
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                required
              />
            </div>

            <div class="mb-3">
              <label for="confirmPassword" class="form-label"
                >Confirm Password</label
              >
              <input
                type="password"
                class="form-control"
                id="confirmPassword"
                name="confirmPassword"
                required
              />
            </div>

            <div class="mb-3 d-none" id="abnSection">
              <label for="abn" class="form-label"
                >ABN (Australian Business Number)</label
              >
              <input type="text" class="form-control" id="abn" name="abn" />
              <div class="form-text">Required for hosts.</div>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">
                Register
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>

    <!-- Scripts -->
    <script src="js/validation.js?v=<?php echo time(); ?>"></script>
    <script src="js/dark-mode.js"></script>
    <script src="js/scroll-to-top.js"></script>
  </body>
</html>
