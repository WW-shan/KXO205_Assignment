<?php
// Get current page name for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="p-3 bg-dark text-white shadow-sm">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
        <i class="bi bi-house-heart-fill me-2" style="font-size: 2.5rem"></i>
        <span class="fs-3 fw-bold">KXO205 Accommodation</span>
      </a>
      
      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <li>
          <a href="index.php" class="nav-link px-3 py-2 fs-5 <?php echo ($current_page == 'index.php') ? 'text-warning fw-bold border-bottom border-warning border-3' : 'text-white'; ?>" style="<?php echo ($current_page == 'index.php') ? 'padding-bottom: 0.5rem !important;' : ''; ?>">Home</a>
        </li>
        <li>
          <a href="search.php" class="nav-link px-3 py-2 fs-5 <?php echo ($current_page == 'search.php') ? 'text-warning fw-bold border-bottom border-warning border-3' : 'text-white'; ?>" style="<?php echo ($current_page == 'search.php') ? 'padding-bottom: 0.5rem !important;' : ''; ?>">
            <i class="bi bi-search me-1"></i>Find Stay
          </a>
        </li>
        <li>
          <a href="about.php" class="nav-link px-3 py-2 fs-5 <?php echo ($current_page == 'about.php') ? 'text-warning fw-bold border-bottom border-warning border-3' : 'text-white'; ?>" style="<?php echo ($current_page == 'about.php') ? 'padding-bottom: 0.5rem !important;' : ''; ?>">About Us</a>
        </li>
        
        <!-- Manager Navigation -->
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] == "manager"): ?>
        <li>
          <a href="manager.php" class="nav-link px-3 py-2 fs-5 <?php echo ($current_page == 'manager.php') ? 'text-warning fw-bold border-bottom border-warning border-3' : 'text-warning'; ?>" style="<?php echo ($current_page == 'manager.php') ? 'padding-bottom: 0.5rem !important;' : ''; ?>">
            <i class="bi bi-shield-check me-1"></i>Manager
          </a>
        </li>
        <?php endif; ?>
        
        <!-- Host Navigation -->
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] == "host"): ?>
        <li>
          <a href="host.php" class="nav-link px-3 py-2 fs-5 <?php echo ($current_page == 'host.php') ? 'text-success fw-bold border-bottom border-success border-3' : 'text-success'; ?>" style="<?php echo ($current_page == 'host.php') ? 'padding-bottom: 0.5rem !important;' : ''; ?>">
            <i class="bi bi-building me-1"></i>My Properties
          </a>
        </li>
        <?php endif; ?>
        
        <!-- Client Navigation -->
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] == "client"): ?>
        <li>
          <a href="client.php" class="nav-link px-3 py-2 fs-5 <?php echo ($current_page == 'client.php') ? 'text-info fw-bold border-bottom border-info border-3' : 'text-info'; ?>" style="<?php echo ($current_page == 'client.php') ? 'padding-bottom: 0.5rem !important;' : ''; ?>">
            <i class="bi bi-calendar-check me-1"></i>My Bookings
          </a>
        </li>
        <?php endif; ?>
      </ul>
      
      <div class="d-flex align-items-center gap-2 ms-auto">
        <button id="theme-toggle" class="btn btn-outline-light" title="Toggle Dark Mode">
          <i class="bi bi-moon-fill"></i>
        </button>
        
        <!-- Not Logged In -->
        <?php if (!isset($_SESSION["userId"])): ?>
          <a href="login.php" class="btn btn-outline-light">
            <i class="bi bi-box-arrow-in-right me-1"></i>Login
          </a>
          <a href="register.php" class="btn btn-warning">
            <i class="bi bi-person-plus me-1"></i>Register
          </a>
        <?php else: ?>
        <!-- Logged In User -->
          <div class="bg-secondary bg-opacity-50 px-3 py-1 rounded-2 d-flex align-items-center align-self-center">
            <i class="bi bi-person-circle text-warning me-2" style="font-size: 1.3rem;"></i>
            <div>
              <small class="text-white d-block"><?php echo htmlspecialchars($_SESSION["name"]); ?></small>
              <small class="text-warning"><?php echo ucfirst($_SESSION["role"]); ?></small>
            </div>
          </div>
          <a href="logout.php" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
