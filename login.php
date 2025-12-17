<?php
session_start();
require "includes/dbconn.php";

$invalidLogin = false;
if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Complete this if statement
    if ($user = authenticate($email, $password)) {
        // Password is correct
        $_SESSION["userId"] = $user["userId"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["name"] = $user["firstName"];
        $_SESSION["email"] = $user["email"];
        
        // Redirect based on role
        if ($user["role"] == "manager") {
            header("Location: manager.php");
        } elseif ($user["role"] == "host") {
            header("Location: host.php");
        } else {
            header("Location: client.php"); // Default to client/customer
        }
        exit;
    } 
    else {
      $invalidLogin = true;
    }
}

// Queries the DBMS with the supplied user details
// Returns user data array on successful authentication and false otherwise.
function authenticate($email, $password) {
    global $conn;

    $email = $conn->real_escape_string($email);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT userId, password, role, firstName, email FROM USER WHERE email = \"$email\"";
    if ($result = $conn->query($sql)) {
        // Should only get one row returned
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row["password"])) {
                return $row;
            }
        }
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
    <title>Login - KXO205 Accommodation Booking</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Dynamic Navigation -->
    <?php include "includes/navbar.php"; ?>

    <main class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
          <h2 class="mb-4 text-center">User Login</h2>
            <?php if (isset($_GET['registered']) && $_GET['registered'] === 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>Registration successful! Please login with your credentials.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
            <?php
            // Add PHP code here to display an error message if the login attempt is invalid.
            if($invalidLogin): ?>
            <div id="error">
                <p><b>Invalid username or password.<br>Please try again.</b></p>
            </div>
            <?php endif ?>
          <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
            <div class="mb-3">
              <label for="loginEmail" class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                id="loginEmail"
                name="email"
                required
              />
            </div>

            <div class="mb-3">
              <label for="loginPassword" class="form-label">Password</label>
              <input
                type="password"
                class="form-control"
                id="loginPassword"
                name="password"
                required
              />
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg">
                Login
              </button>
            </div>
            <p class="mt-3 text-center">
              Don't have an account? <a href="register.php">Register Now</a>
            </p>
          </form>
        </div>
      </div>
    </main>

    <!-- Scripts -->
    <script src="js/validation.js?"></script>
    <script src="js/dark-mode.js"></script>
    <script src="js/scroll-to-top.js"></script>
  </body>
</html>
