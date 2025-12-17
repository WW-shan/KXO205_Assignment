<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Shengyi Shi 744564, Yuming Deng 744571, Mingxuan Xu 744580, Yanzhang Lu 744586" />
    <title>About Us - KXO205 Accommodation Booking</title>    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-icons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <!-- Dynamic Navigation -->
    <?php include("includes/navbar.php"); ?>

    <!-- Main Content -->
    <main class="container mt-5 mb-5">
      <div class="row">
        <div class="col-lg-10 mx-auto">
          <!-- Hero Section -->
          <div class="text-center mb-5">
            <h1 class="display-4 fw-bold mb-3">About KXO205 Accommodation Platform</h1>
            <p class="lead text-muted">
              Welcome to KXO205 Accommodation - the premier accommodation platform designed by students, for tourists. 
              Our mission is to simplify the process of finding and managing tourist housing, creating a seamless experience for everyone.
            </p>
          </div>

          <!-- Deployment Instructions Card -->
          <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
              <h4 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Deployment Instructions</h4>
            </div>
            <div class="card-body">
              <p class="mb-4">This project supports two deployment methods: WampServer for traditional local development, or Docker for containerized deployment. Choose the method that best suits your environment.</p>
              
              <!-- Deployment Method Tabs -->
              <ul class="nav nav-tabs mb-4" id="deploymentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active fw-bold" id="wamp-tab" data-bs-toggle="tab" data-bs-target="#wamp" type="button" role="tab" aria-controls="wamp" aria-selected="true">
                    <i class="bi bi-server me-2"></i><span class="fs-6">WampServer Deployment</span>
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link fw-bold" id="docker-tab" data-bs-toggle="tab" data-bs-target="#docker" type="button" role="tab" aria-controls="docker" aria-selected="false">
                    <i class="bi bi-box me-2"></i><span class="fs-6">Docker Deployment</span>
                  </button>
                </li>
              </ul>

              <div class="tab-content" id="deploymentTabContent">
                <!-- WampServer Deployment -->
                <div class="tab-pane fade show active" id="wamp" role="tabpanel" aria-labelledby="wamp-tab">
                  <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Prerequisites</strong>
                    <ul class="mb-0 mt-2">
                      <li>WampServer 3.2+ installed (includes Apache, PHP 8.0+, MySQL)</li>
                      <li>Wamp services running (green icon in system tray)</li>
                      <li>At least 500MB of free disk space</li>
                    </ul>
                  </div>

                  <h5 class="mt-4 mb-3"><i class="bi bi-list-ol me-2"></i>Deployment Steps</h5>
                  <ol class="deployment-steps">
                    <li class="mb-3">
                      <strong>Locate WampServer www directory</strong>
                      <ul class="mt-2">
                        <li>Default path: <code>C:\wamp64\www\</code></li>
                        <li>Or right-click Wamp icon → <strong>www directory</strong></li>
                      </ul>
                    </li>
                    <li class="mb-3">
                      <strong>Copy project folder</strong> to the www directory
                      <ul class="mt-2">
                        <li>Example: <code>C:\wamp64\www\KXO205_Assignment\</code></li>
                      </ul>
                    </li>
                    <li class="mb-3">
                      <strong>Create database:</strong>
                      <ul class="mt-2">
                        <li>Click Wamp icon → <strong>phpMyAdmin</strong></li>
                        <li>Or visit <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a></li>
                        <li>Click <strong>"New"</strong> in the left sidebar</li>
                        <li>Database name: <code class="bg-light px-2 py-1 rounded">KXO205</code></li>
                        <li>Collation: <code>utf8mb4_general_ci</code></li>
                        <li>Click <strong>"Create"</strong></li>
                      </ul>
                    </li>
                    <li class="mb-3">
                      <strong>Import database schema:</strong>
                      <ul class="mt-2">
                        <li>Select the <strong>KXO205</strong> database</li>
                        <li>Click <strong>"Import"</strong> tab</li>
                        <li>Click <strong>"Choose File"</strong></li>
                        <li>Select <code>database.sql</code> from project folder</li>
                        <li>Click <strong>"Go"</strong> at the bottom</li>
                        <li>Wait for success message</li>
                      </ul>
                    </li>
                    <li class="mb-3">
                      <strong>Configure database connection:</strong>
                      <ul class="mt-2">
                        <li>Open <code>includes/dbconn.php</code> in a text editor</li>
                        <li>The file is already configured for WampServer defaults:
                          <pre class="bg-dark text-white p-3 rounded mt-2"><code>$host = "localhost";
$user = "root";
$pass = "";  // WampServer default (no password)
$dbname = "KXO205";</code></pre>
                        </li>
                        <li>If your MySQL password is different, update the <code>$pass</code> variable</li>
                      </ul>
                    </li>
                    <li class="mb-3">
                      <strong>Access the application:</strong>
                      <ul class="mt-2">
                        <li>🌐 Web Application: <a href="http://localhost/KXO205_Assignment/" target="_blank" class="fw-bold">http://localhost/KXO205_Assignment/</a></li>
                        <li>🗄️ phpMyAdmin: <a href="http://localhost/phpmyadmin" target="_blank" class="fw-bold">http://localhost/phpmyadmin</a>
                            <span class="badge bg-secondary">Username: root</span>
                            <span class="badge bg-secondary">Password: (empty)</span>
                        </li>
                      </ul>
                    </li>
                  </ol>

                  <div class="alert alert-warning mt-4" role="alert">
                    <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Troubleshooting</h6>
                    <ul class="mb-0">
                      <li><strong>Port 80 already in use:</strong> Close Skype or change Apache port in Wamp settings</li>
                      <li><strong>Database connection error:</strong> Verify MySQL service is running (Wamp icon should be green)</li>
                      <li><strong>404 Not Found:</strong> Ensure project is in <code>www</code> folder and path matches URL</li>
                      <li><strong>Session errors:</strong> Check that PHP session directory has write permissions</li>
                    </ul>
                  </div>

                  <div class="card bg-light mt-4">
                    <div class="card-body">
                      <h6 class="card-title"><i class="bi bi-tools me-2"></i>WampServer Quick Actions</h6>
                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="p-3 bg-white rounded border">
                            <strong class="text-primary">Start/Stop Services:</strong><br>
                            <span class="text-dark">Right-click Wamp icon → Start/Stop All Services</span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-3 bg-white rounded border">
                            <strong class="text-primary">Check PHP Version:</strong><br>
                            <span class="text-dark">Left-click Wamp icon → PHP → Version</span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-3 bg-white rounded border">
                            <strong class="text-primary">View Error Logs:</strong><br>
                            <span class="text-dark">Wamp icon → Apache → Apache error log</span>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-3 bg-white rounded border">
                            <strong class="text-primary">Restart Apache:</strong><br>
                            <span class="text-dark">Wamp icon → Apache → Service administration → Restart</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Docker Deployment -->
                <div class="tab-pane fade" id="docker" role="tabpanel" aria-labelledby="docker-tab">
                  <div class="alert alert-info" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Prerequisites</strong>
                    <ul class="mb-0 mt-2">
                      <li>Docker Desktop installed and running</li>
                      <li>Docker Compose (included with Docker Desktop)</li>
                      <li>At least 2GB of free disk space</li>
                    </ul>
                  </div>
                  
                  <h5 class="mt-4 mb-3"><i class="bi bi-list-ol me-2"></i>Deployment Steps</h5>
                  <ol class="deployment-steps">
                    <li class="mb-3">
                      <strong>Clone or download the project</strong> to your local machine
                    </li>
                    <li class="mb-3">
                      <strong>Open a terminal</strong> in the project root directory (where docker-compose.yml is located)
                    </li>
                    <li class="mb-3">
                      <strong>Build and start containers:</strong>
                      <pre class="bg-dark text-white p-3 rounded mt-2"><code>docker-compose up -d --build</code></pre>
                      <div class="alert alert-secondary mt-2">
                        This will:
                        <ul class="mb-0 mt-2">
                          <li>Build the PHP-Apache web server image</li>
                          <li>Create MySQL 8.0 database container with database name <code class="bg-white text-dark px-2 py-1 rounded">KXO205</code></li>
                          <li>Initialize database schema from database.sql</li>
                          <li>Start phpMyAdmin for database management</li>
                        </ul>
                      </div>
                    </li>
                    <li class="mb-3">
                      <strong>Wait for initialization</strong> (approximately 30-60 seconds for first-time setup)
                    </li>
                    <li class="mb-3">
                      <strong>Access the application:</strong>
                      <ul class="mt-2">
                        <li>🌐 Web Application: <a href="http://localhost:80" target="_blank" class="fw-bold">http://localhost:8000</a></li>
                        <li>🗄️ phpMyAdmin: <a href="http://localhost:8080" target="_blank" class="fw-bold">http://localhost:8080</a> 
                            <span class="badge bg-secondary">Username: root</span>
                            <span class="badge bg-secondary">Password: (empty)</span>
                        </li>
                      </ul>
                    </li>
                  </ol>
                  
                  <div class="card bg-light mt-4">
                    <div class="card-body">
                      <h6 class="card-title"><i class="bi bi-terminal me-2"></i>Useful Docker Commands</h6>
                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="p-2 bg-white rounded">
                            <strong>Stop containers:</strong><br>
                            <code class="text-danger">docker-compose down</code>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-2 bg-white rounded">
                            <strong>View logs:</strong><br>
                            <code class="text-primary">docker-compose logs -f web</code>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-2 bg-white rounded">
                            <strong>Restart containers:</strong><br>
                            <code class="text-warning">docker-compose restart</code>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="p-2 bg-white rounded">
                            <strong>Rebuild after changes:</strong><br>
                            <code class="text-success">docker-compose up -d --build</code>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- User Guide Cards -->
          <div class="card shadow-sm mb-5" id="user-guide">
            <div class="card-header bg-primary text-white">
              <h4 class="mb-0"><i class="bi bi-book me-2"></i>4.1 User Guide - How to Use This Website</h4>
            </div>
            <div class="card-body">
              <p class="lead mb-4">Welcome to KXO205 Accommodation Booking System! This guide will help you navigate the platform and make the most of its features.</p>

              <!-- Role-based Guides -->
              <div class="row mb-4">
                <div class="col-12">
                  <h5 class="text-primary mb-3"><i class="bi bi-person-circle me-2"></i>Getting Started</h5>
                  <div class="alert alert-info">
                    <strong>First Time User?</strong> Start by registering an account. Choose your role based on your needs:
                    <ul class="mt-2 mb-0">
                      <li><strong>Client</strong> - If you want to search and book accommodations</li>
                      <li><strong>Host</strong> - If you want to list your properties for booking</li>
                      <li><strong>Manager</strong> - For administrative access (requires approval)</li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Client Guide -->
              <div class="accordion mb-4" id="userGuideAccordion">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClient" aria-expanded="true">
                      <i class="bi bi-search me-2 text-success"></i>
                      <strong>For Clients: How to Search and Book Accommodations</strong>
                    </button>
                  </h2>
                  <div id="collapseClient" class="accordion-collapse collapse show" data-bs-parent="#userGuideAccordion">
                    <div class="accordion-body">
                      <h6 class="text-success mb-3">Step 1: Search for Accommodations</h6>
                      <ol class="mb-4">
                        <li>Navigate to the homepage or click <strong>"Accommodations"</strong> in the navigation bar</li>
                        <li>Enter your destination city (e.g., "Sydney", "Melbourne")</li>
                        <li>Select your <strong>check-in</strong> and <strong>check-out</strong> dates</li>
                        <li>Specify the number of guests</li>
                        <li>Click the <strong>"Search"</strong> button</li>
                      </ol>

                      <h6 class="text-success mb-3">Step 2: Review Search Results</h6>
                      <ul class="mb-4">
                        <li>Browse available properties matching your criteria</li>
                        <li>View amenities (WiFi, Garage, Pet Friendly, etc.) displayed as badges</li>
                        <li>Check price per night and total cost for your stay</li>
                        <li>Review property details: bedrooms, bathrooms, max guests</li>
                      </ul>

                      <h6 class="text-success mb-3">Step 3: Make a Booking</h6>
                      <ol class="mb-4">
                        <li>Click <strong>"Book Now"</strong> on your chosen property</li>
                        <li>If not logged in, you'll be redirected to the login page - sign in or register</li>
                        <li>Review the booking summary (accommodation details, dates, total cost)</li>
                        <li>Enter your <strong>phone number</strong> for booking confirmation</li>
                        <li>Verify your check-in/out dates (you can modify them if needed - total cost updates automatically)</li>
                        <li>Select a <strong>payment method</strong> (Credit Card, Debit Card, PayPal, Bank Transfer)</li>
                        <li>Optionally enter the last 4 digits of your card for your records</li>
                        <li>Click <strong>"Confirm Booking"</strong></li>
                      </ol>

                      <h6 class="text-success mb-3">Step 4: Manage Your Bookings</h6>
                      <ul class="mb-0">
                        <li>Access your <strong>Client Dashboard</strong> from the navigation menu</li>
                        <li>View all your bookings with status (Confirmed, Cancelled, Completed)</li>
                        <li>See booking details: accommodation name, dates, guests, total price</li>
                        <li>Cancel bookings if needed (click the Cancel button with CSRF protection)</li>
                        <li>View payment information (encrypted and securely stored)</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHost">
                      <i class="bi bi-house-door me-2 text-info"></i>
                      <strong>For Hosts: How to List and Manage Properties</strong>
                    </button>
                  </h2>
                  <div id="collapseHost" class="accordion-collapse collapse" data-bs-parent="#userGuideAccordion">
                    <div class="accordion-body">
                      <h6 class="text-info mb-3">Step 1: Add a New Property</h6>
                      <ol class="mb-4">
                        <li>Log in as a <strong>Host</strong></li>
                        <li>Go to your <strong>Host Dashboard</strong></li>
                        <li>Click <strong>"Add New Property"</strong></li>
                        <li>Fill in property details:
                          <ul>
                            <li>Property name and address</li>
                            <li>City location</li>
                            <li>Price per night</li>
                            <li>Number of bedrooms and bathrooms</li>
                            <li>Maximum guests allowed</li>
                            <li>Property description</li>
                            <li>Image path (e.g., img/house1.avif)</li>
                          </ul>
                        </li>
                        <li>Select amenities: Smoking Allowed, Garage, Pet Friendly, Internet, Air Conditioning, Pool, Kitchen, Washer/Dryer</li>
                        <li>Click <strong>"Add Property"</strong></li>
                      </ol>

                      <h6 class="text-info mb-3">Step 2: Manage Your Properties</h6>
                      <ul class="mb-4">
                        <li>View all your listed properties in the Host Dashboard</li>
                        <li>See booking statistics for each property</li>
                        <li>Edit property details by clicking the <strong>"Edit"</strong> button</li>
                        <li>Update amenities to attract more bookings</li>
                        <li>Delete properties that are no longer available</li>
                      </ul>

                      <h6 class="text-info mb-3">Step 3: Track Bookings</h6>
                      <ul class="mb-0">
                        <li>View all bookings for your properties</li>
                        <li>See guest information and contact details</li>
                        <li>Monitor booking dates and status</li>
                        <li>Check payment information for confirmed bookings</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseManager">
                      <i class="bi bi-gear me-2 text-warning"></i>
                      <strong>For Managers: Administrative Functions</strong>
                    </button>
                  </h2>
                  <div id="collapseManager" class="accordion-collapse collapse" data-bs-parent="#userGuideAccordion">
                    <div class="accordion-body">
                      <h6 class="text-warning mb-3">User Management</h6>
                      <ul class="mb-4">
                        <li>View all users (Clients, Hosts, Managers) in the system</li>
                        <li>Edit user information and roles</li>
                        <li>Delete user accounts when necessary</li>
                        <li>Monitor user activity and booking history</li>
                      </ul>

                      <h6 class="text-warning mb-3">Accommodation Management</h6>
                      <ul class="mb-4">
                        <li>View all accommodations across all hosts</li>
                        <li>Add new accommodations on behalf of hosts</li>
                        <li>Edit any accommodation details</li>
                        <li>Remove inappropriate or inactive listings</li>
                      </ul>

                      <h6 class="text-warning mb-3">Booking Oversight</h6>
                      <ul class="mb-0">
                        <li>Monitor all bookings system-wide</li>
                        <li>View booking statistics and trends</li>
                        <li>Handle booking disputes or cancellations</li>
                        <li>Access payment information for auditing</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Key Features -->
              <div class="row g-3 mt-4">
                <div class="col-12">
                  <h5 class="text-primary mb-3"><i class="bi bi-star me-2"></i>Key Features to Know</h5>
                </div>
                <div class="col-md-6">
                  <div class="card h-100 border-success">
                    <div class="card-body">
                      <h6 class="card-title text-success"><i class="bi bi-shield-check me-2"></i>Security Features</h6>
                      <ul class="small mb-0">
                        <li><strong>CSRF Protection</strong> - All forms are protected against Cross-Site Request Forgery attacks</li>
                        <li><strong>Password Encryption</strong> - Passwords are hashed using bcrypt</li>
                        <li><strong>Payment Encryption</strong> - Payment details are encrypted with AES-256-CBC</li>
                        <li><strong>XSS Prevention</strong> - All user inputs are sanitized before display</li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card h-100 border-info">
                    <div class="card-body">
                      <h6 class="card-title text-info"><i class="bi bi-magic me-2"></i>Smart Features</h6>
                      <ul class="small mb-0">
                        <li><strong>Real-time Price Calculation</strong> - Total cost updates automatically when you change dates</li>
                        <li><strong>Conflict Prevention</strong> - System prevents double-booking the same property</li>
                        <li><strong>Dark Mode</strong> - Toggle between light and dark themes (saved preference)</li>
                        <li><strong>Responsive Design</strong> - Works seamlessly on desktop, tablet, and mobile</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Troubleshooting -->
              <div class="alert alert-secondary mt-4">
                <h6 class="alert-heading"><i class="bi bi-question-circle me-2"></i>Need Help?</h6>
                <ul class="mb-0">
                  <li><strong>Can't find accommodations?</strong> Try different dates or a nearby city</li>
                  <li><strong>Booking failed?</strong> The dates may have been taken by another user - try different dates</li>
                  <li><strong>Forgot password?</strong> Contact the system administrator</li>
                  <li><strong>Issues with property listing?</strong> Ensure all required fields are filled correctly</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Original Quick Guide Cards (kept for visual variety) -->
          <div class="row mb-5">
            <div class="col-md-6 mb-4">
              <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                  <h4 class="mb-0"><i class="bi bi-search me-2"></i>Quick Guide: Search</h4>
                </div>
                <div class="card-body">
                  <ol class="user-guide-steps">
                    <li>Click the "Accommodations" link in the top navigation bar</li>
                    <li>Enter your destination, check-in, and check-out dates</li>
                    <li>Specify the number of guests</li>
                    <li>Review the search results with amenities displayed as badges</li>
                    <li>Check property details and pricing</li>
                  </ol>
                </div>
              </div>
            </div>
            
            <div class="col-md-6 mb-4">
              <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                  <h4 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Quick Guide: Book</h4>
                </div>
                <div class="card-body">
                  <ol class="user-guide-steps">
                    <li>Select your desired property from the search results</li>
                    <li>Review property details and amenities</li>
                    <li>Verify check-in/check-out dates and guests (adjustable)</li>
                    <li>Click the "Book Now" button</li>
                    <li>Log in to your account or register</li>
                    <li>Enter phone number and payment information</li>
                    <li>Confirm booking and manage from your dashboard</li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
                  </ol>
                </div>
              </div>
            </div>
          </div>

          <!-- Team Section -->
          <div class="card shadow-sm mb-5" id="our-team">
            <div class="card-header bg-dark text-white">
              <h4 class="mb-0"><i class="bi bi-people-fill me-2"></i>Our Development Team</h4>
            </div>
            <div class="card-body">
              <p>Our team consists of four dedicated students from Swinburne University, each bringing unique skills and perspectives to the KXO205 Accommodation Platform project. The team members are:</p>
              <div class="row g-3 mt-3">
                <div class="col-md-6">
                  <div class="p-3 bg-light rounded">
                    <i class="bi bi-person-badge fs-3 text-primary"></i>
                    <h6 class="mt-2 mb-0"><strong>Shengyi Shi</strong></h6>
                    <small class="text-muted">Student ID: 744564</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="p-3 bg-light rounded">
                    <i class="bi bi-person-badge fs-3 text-success"></i>
                    <h6 class="mt-2 mb-0"><strong>Yuming Deng</strong></h6>
                    <small class="text-muted">Student ID: 744571</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="p-3 bg-light rounded">
                    <i class="bi bi-person-badge fs-3 text-warning"></i>
                    <h6 class="mt-2 mb-0"><strong>Mingxuan Xu</strong></h6>
                    <small class="text-muted">Student ID: 744580</small>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="p-3 bg-light rounded">
                    <i class="bi bi-person-badge fs-3 text-danger"></i>
                    <h6 class="mt-2 mb-0"><strong>Yanzhang Lu</strong></h6>
                    <small class="text-muted">Student ID: 744586</small>
                  </div>
                </div>
              </div>
              <p class="mt-4">Throughout the development process, our team maintained strong communication and collaboration. We held regular meetings to discuss progress, address challenges, and ensure all components integrated seamlessly. Each member took ownership of their assigned areas while remaining flexible to assist teammates when needed, fostering a collaborative and supportive development environment.</p>
            </div>
          </div>

          <!-- Team Collaboration Section -->
          <div class="card shadow-sm mb-5" id="team-collaboration">
            <div class="card-header bg-secondary text-white">
              <h4 class="mb-0"><i class="bi bi-diagram-3 me-2"></i>Team Collaboration & Role Distribution</h4>
            </div>
            <div class="card-body">
              <p class="lead">As a full-stack web application, the KXO205 Accommodation Platform required expertise across multiple technology layers—from frontend UI/UX to backend business logic, database design, and deployment infrastructure. The project workload was strategically divided among team members based on individual strengths, with each member contributing to both frontend and backend components to ensure comprehensive understanding and seamless integration across the entire stack.</p>
              
              <div class="accordion" id="roleAccordion">
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShengyi" aria-expanded="true">
                      <i class="bi bi-person-circle me-2 text-primary"></i>
                      <strong>Shengyi Shi</strong> <span class="badge bg-primary ms-2">Frontend + Backend API</span>
                    </button>
                  </h2>
                  <div id="collapseShengyi" class="accordion-collapse collapse show" data-bs-parent="#roleAccordion">
                    <div class="accordion-body">
                      <p><strong>Frontend Development:</strong> Implemented the advanced search and filtering system with dynamic AJAX requests, developed the dark mode toggle feature with localStorage persistence, and created comprehensive form validation scripts that provide real-time user feedback.</p>
                      <p><strong>Backend Integration:</strong> Worked on the PHP search functionality, implementing SQL queries with proper sanitization and integrating the accommodation search API with the frontend interface. This dual-focus ensured that user interactions were both responsive and securely backed by server-side logic.</p>
                    </div>
                  </div>
                </div>
                
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYuming">
                      <i class="bi bi-person-circle me-2 text-success"></i>
                      <strong>Yuming Deng</strong> <span class="badge bg-success ms-2">Structure + Business Logic</span>
                    </button>
                  </h2>
                  <div id="collapseYuming" class="accordion-collapse collapse" data-bs-parent="#roleAccordion">
                    <div class="accordion-body">
                      <p><strong>Frontend Structure:</strong> Designed the HTML semantic markup and information architecture for all pages, created well-structured forms for registration, login, and booking workflows, and implemented accessible navigation components.</p>
                      <p><strong>Backend Logic:</strong> Developed the core PHP business logic including user authentication with bcrypt password hashing, session management with role-based access control (Client, Host, Manager), and the booking system's transaction-based concurrency control. This comprehensive approach ensured that the user interface was backed by robust and secure server-side processing.</p>
                    </div>
                  </div>
                </div>
                
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMingxuan">
                      <i class="bi bi-person-circle me-2 text-warning"></i>
                      <strong>Mingxuan Xu</strong> <span class="badge bg-warning ms-2">Styling + Database</span>
                    </button>
                  </h2>
                  <div id="collapseMingxuan" class="accordion-collapse collapse" data-bs-parent="#roleAccordion">
                    <div class="accordion-body">
                      <p><strong>Frontend Styling:</strong> Implemented Bootstrap 5 customizations and created responsive layouts that adapt seamlessly across all devices, developed the dual-theme CSS system using custom properties for both light and dark modes, and fine-tuned the visual hierarchy and user experience.</p>
                      <p><strong>Database Architecture:</strong> Designed the MySQL schema for the USER, ACCOMMODATION, and BOOKING tables, established proper relationships and constraints to maintain data integrity, and optimized queries for performance. This combination of aesthetic design and data architecture expertise ensured that the platform was both visually appealing and built on a solid data foundation.</p>
                    </div>
                  </div>
                </div>
                
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYanzhang">
                      <i class="bi bi-person-circle me-2 text-danger"></i>
                      <strong>Yanzhang Lu</strong> <span class="badge bg-danger ms-2">Project Management + DevOps</span>
                    </button>
                  </h2>
                  <div id="collapseYanzhang" class="accordion-collapse collapse" data-bs-parent="#roleAccordion">
                    <div class="accordion-body">
                      <p><strong>Project Management:</strong> Coordinated team activities, managed version control workflows, and maintained project documentation. Established and monitored project timelines and milestones, facilitating team meetings and resolving conflicts.</p>
                      <p><strong>DevOps & Infrastructure:</strong> Responsible for Docker containerization—creating the Dockerfile for the PHP-Apache web server, configuring docker-compose.yml to orchestrate MySQL, phpMyAdmin, and web services, implementing volume mappings for database persistence and configuration overrides, and troubleshooting deployment issues including session directory permissions and database initialization sequences. This work ensured consistent deployment across different environments.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Project Highlights Section -->
          <div class="card shadow-sm mb-5">
            <div class="card-header bg-warning text-dark">
              <h4 class="mb-0"><i class="bi bi-star-fill me-2"></i>Project Highlights</h4>
            </div>
            <div class="card-body">
              <div class="row g-4">
                <div class="col-md-6">
                  <div class="highlight-card p-4 border rounded h-100">
                    <h5 class="text-primary"><i class="bi bi-database me-2"></i>Full-Stack Architecture</h5>
                    <p class="small">This is a complete full-stack web application built with modern technologies across all layers. The frontend uses HTML5, CSS3, and vanilla JavaScript with Bootstrap 5 framework for a responsive and interactive user interface. The backend is powered by PHP 8.2, handling authentication, session management, and business logic. MySQL 8.0 serves as the relational database, storing user accounts, accommodation listings, and booking records with proper relationships and constraints. The entire stack is containerized using Docker and Docker Compose, ensuring consistent deployment and easy scalability.</p>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="highlight-card p-4 border rounded h-100">
                    <h5 class="text-success"><i class="bi bi-shield-lock me-2"></i>Secure Authentication & Authorization</h5>
                    <p class="small">Security is a cornerstone of our platform. We implement industry-standard bcrypt password hashing with PHP's password_hash() and password_verify() functions. The authentication system includes dual-layer validation—client-side JavaScript provides immediate user feedback while server-side PHP validation acts as a security backstop. Role-based access control (RBAC) differentiates between clients, hosts, and managers, with session-based authorization ensuring users can only access features appropriate to their role.</p>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="highlight-card p-4 border rounded h-100">
                    <h5 class="text-danger"><i class="bi bi-lock me-2"></i>Database Transaction & Concurrency Control</h5>
                    <p class="small">To handle the critical challenge of preventing double-bookings, we implemented sophisticated database transaction management using MySQL's ACID properties. The booking process uses row-level locking with SELECT FOR UPDATE queries, ensuring that when multiple users simultaneously attempt to book the same property for overlapping dates, only one transaction succeeds. This prevents race conditions that could lead to booking conflicts and demonstrates our understanding of concurrent database operations and data integrity in multi-user environments.</p>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="highlight-card p-4 border rounded h-100">
                    <h5 class="text-info"><i class="bi bi-phone me-2"></i>Responsive Frontend Design</h5>
                    <p class="small">Our website is meticulously designed to provide an optimal viewing experience across all devices, from mobile phones to desktop computers. Using Bootstrap's grid system combined with custom media queries, we ensure that layouts automatically adapt, images scale appropriately, and navigation transforms into a mobile-friendly menu on smaller screens. The responsive design extends beyond layout to interaction patterns—touch-friendly buttons on mobile, hover effects on desktop, and appropriate font sizes for readability across screen densities.</p>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="highlight-card p-4 border rounded h-100">
                    <h5 class="text-secondary"><i class="bi bi-moon-stars me-2"></i>Dynamic Theme System</h5>
                    <p class="small">We've implemented a sophisticated dark mode toggle that enhances user experience and reduces eye strain during nighttime browsing. The theme system uses CSS custom properties (variables) to define color schemes, allowing seamless transitions between light and dark modes. User preferences are stored in localStorage, persisting across browser sessions. The dark-mode.js script applies themes before page render to prevent flash of unstyled content (FOUC), and all UI elements correctly adapt to the selected theme.</p>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="highlight-card p-4 border rounded h-100">
                    <h5 class="text-warning"><i class="bi bi-search me-2"></i>Advanced Search & Database Queries</h5>
                    <p class="small">The accommodation search system showcases our backend development skills through complex SQL query construction and optimization. Users can search by location, date range, guest capacity, price range, and multiple amenities. The PHP backend dynamically builds SQL queries using parameterized statements and proper input sanitization to prevent SQL injection attacks. The search implementation uses efficient JOIN operations to combine data from multiple tables, WHERE clauses with AND/OR logic for filtering, and date range comparisons to check accommodation availability.</p>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="highlight-card p-4 border rounded h-100">
                    <h5 class="text-primary"><i class="bi bi-docker me-2"></i>Docker Containerization & DevOps</h5>
                    <p class="small">The entire application stack is containerized using Docker, demonstrating our understanding of modern DevOps practices. Our docker-compose.yml orchestrates three services: a custom PHP 8.2-Apache web server, a MySQL 8.0 database with persistent volume storage, and phpMyAdmin for database management. The configuration includes proper service dependencies, health checks, volume mappings, and network isolation. This containerized approach eliminates "works on my machine" problems and ensures consistent environments across development and production.</p>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="highlight-card p-4 border rounded h-100">
                    <h5 class="text-success"><i class="bi bi-diagram-3 me-2"></i>Multi-User Role Management</h5>
                    <p class="small">The platform implements a comprehensive role-based system supporting three distinct user types with different capabilities and interfaces. Clients can search accommodations, make bookings, and manage reservations. Hosts can list properties, manage accommodation details, and view booking requests. Managers have administrative privileges including user management and booking oversight. Each role has a dedicated dashboard with role-specific functionality, and the backend enforces authorization checks to prevent unauthorized access.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <h4 class="mt-5" id="development-challenges">Development Challenges & Solutions</h4>
          
          <!-- Development Challenges Section -->
          <div class="card shadow-sm mb-5" id="development-challenges">
            <div class="card-header bg-danger text-white">
              <h4 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Development Challenges & Solutions</h4>
            </div>
            <div class="card-body">
              <div class="challenge-item mb-4 pb-4 border-bottom">
                <h5 class="text-danger"><i class="bi bi-1-circle me-2"></i>Docker Database Configuration Issues</h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="alert alert-danger">
                      <strong><i class="bi bi-bug me-2"></i>Challenge:</strong>
                      <p class="small mb-0">During initial Docker setup, we encountered complex issues with database initialization and connection. The volume mapping order caused configuration files to be overridden incorrectly, resulting in connection failures. Additionally, session directory permissions in the PHP container caused session_start() errors.</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="alert alert-success">
                      <strong><i class="bi bi-check-circle me-2"></i>Solution:</strong>
                      <p class="small mb-0">We resolved this by carefully ordering volume mappings in docker-compose.yml—specific file mappings must come BEFORE directory mappings to ensure proper overrides. We also added explicit session directory creation in the Dockerfile with correct permissions (<code>mkdir -p /var/lib/php/sessions</code>). The database name was standardized to uppercase "KXO205" across all configuration files to maintain consistency.</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="challenge-item mb-4 pb-4 border-bottom">
                <h5 class="text-danger"><i class="bi bi-2-circle me-2"></i>Booking Concurrency and Double-Booking Prevention</h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="alert alert-danger">
                      <strong><i class="bi bi-bug me-2"></i>Challenge:</strong>
                      <p class="small mb-0">In testing, we discovered a critical bug where multiple users could simultaneously book the same property for overlapping dates. The original implementation only checked availability before insertion, creating a race condition where concurrent requests could both pass the check and create duplicate bookings.</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="alert alert-success">
                      <strong><i class="bi bi-check-circle me-2"></i>Solution:</strong>
                      <p class="small mb-0">We implemented database transaction-based concurrency control using MySQL's row-level locking mechanism. The booking process now uses <code>BEGIN TRANSACTION</code>, performs availability checking with <code>SELECT ... FOR UPDATE</code> to lock relevant rows, then either <code>COMMIT</code> the booking or <code>ROLLBACK</code> if conflicts are detected. This ensures atomicity and prevents race conditions.</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="challenge-item mb-4 pb-4 border-bottom">
                <h5 class="text-danger"><i class="bi bi-3-circle me-2"></i>Code Standardization and Naming Conventions</h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="alert alert-danger">
                      <strong><i class="bi bi-bug me-2"></i>Challenge:</strong>
                      <p class="small mb-0">The initial codebase had inconsistent naming conventions—some variables used snake_case (user_id, price_per_night) while others used camelCase. This inconsistency led to bugs, particularly with session variables where <code>$_SESSION["user_id"]</code> in some files didn't match <code>$_SESSION["userId"]</code> in others, causing authentication failures.</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="alert alert-success">
                      <strong><i class="bi bi-check-circle me-2"></i>Solution:</strong>
                      <p class="small mb-0">We performed a comprehensive codebase audit and standardized all naming to camelCase convention. This involved updating: session variables (userId, not user_id), form field names (pricePerNight, maxGuests, imagePath), and database column references. We systematically reviewed every PHP file, HTML form, and JavaScript validation script to ensure complete consistency.</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="challenge-item mb-4 pb-4 border-bottom">
                <h5 class="text-danger"><i class="bi bi-4-circle me-2"></i>Server-Side vs Client-Side Validation Balance</h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="alert alert-danger">
                      <strong><i class="bi bi-bug me-2"></i>Challenge:</strong>
                      <p class="small mb-0">Initially, the registration form relied solely on client-side JavaScript validation, which could be bypassed. However, when we added comprehensive server-side validation with error messages, it created redundant user feedback and cluttered the interface.</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="alert alert-success">
                      <strong><i class="bi bi-check-circle me-2"></i>Solution:</strong>
                      <p class="small mb-0">We implemented a dual-layer validation strategy: Client-side validation (validation.js) provides immediate feedback for better UX, checking password strength, format requirements, and field matching in real-time. Server-side validation acts as a security backstop, performing the same checks plus database operations (email uniqueness verification, ABN validation for hosts) without displaying error messages—failed validations simply prevent form submission while successful ones proceed to account creation.</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="challenge-item mb-4 pb-4 border-bottom">
                <h5 class="text-danger"><i class="bi bi-5-circle me-2"></i>Dark Mode Implementation Across Dynamic Content</h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="alert alert-danger">
                      <strong><i class="bi bi-bug me-2"></i>Challenge:</strong>
                      <p class="small mb-0">Implementing smooth dark mode switching required careful planning of CSS variables and JavaScript logic. Ensuring that all elements—including dynamically generated PHP content, Bootstrap components, and custom elements—correctly switched themes was complex. Additional challenges included persisting user preference across sessions and preventing flash of unstyled content (FOUC) on page load.</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="alert alert-success">
                      <strong><i class="bi bi-check-circle me-2"></i>Solution:</strong>
                      <p class="small mb-0">We developed a comprehensive dark mode system using CSS custom properties (variables) for colors, background, and text. The dark-mode.js script manages theme switching, stores preferences in localStorage, and applies the theme before page render to prevent FOUC. We created theme-specific CSS rules for all UI components and tested extensively across all pages to ensure consistent appearance.</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="challenge-item">
                <h5 class="text-danger"><i class="bi bi-6-circle me-2"></i>Team Collaboration and Version Control</h5>
                <div class="row">
                  <div class="col-md-6">
                    <div class="alert alert-danger">
                      <strong><i class="bi bi-bug me-2"></i>Challenge:</strong>
                      <p class="small mb-0">Coordinating development among team members presented challenges with merge conflicts, especially when multiple people edited the same files. Inconsistent code formatting and lack of clear file ownership led to confusion and rework.</p>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="alert alert-success">
                      <strong><i class="bi bi-check-circle me-2"></i>Solution:</strong>
                      <p class="small mb-0">We established clear coding standards and communication protocols. Each team member was assigned specific functional areas (JavaScript interactions, HTML structure, CSS styling, project management), reducing file conflicts. We conducted regular team meetings to synchronize progress, used descriptive commit messages, and implemented a review process before merging significant changes. This structured approach improved collaboration efficiency and code quality.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Resources & Credits Section -->
          <div class="card shadow-sm mb-5" id="resources">
            <div class="card-header bg-info text-white">
              <h4 class="mb-0"><i class="bi bi-bookmark-star me-2"></i>Resources & Credits</h4>
            </div>
            <div class="card-body">
              <p class="mb-4">We acknowledge and appreciate the following resources that made this project possible:</p>
              
              <div class="row g-4">
                <div class="col-md-6">
                  <h5 class="text-primary"><i class="bi bi-code-square me-2"></i>Frameworks & Libraries</h5>
                  <ul class="list-group">
                    <li class="list-group-item">
                      <strong>Bootstrap 5</strong> — CSS framework (MIT License)
                      <br><small class="text-muted">Source: <a href="https://getbootstrap.com/" target="_blank">https://getbootstrap.com/</a></small>
                    </li>
                    <li class="list-group-item">
                      <strong>Bootstrap Icons</strong> — Icon set (MIT License)
                      <br><small class="text-muted">Loaded locally from /css/bootstrap-icons.min.css</small>
                    </li>
                    <li class="list-group-item">
                      <strong>PHP 8.2</strong> — Server-side scripting language
                      <br><small class="text-muted">Official documentation: <a href="https://www.php.net/" target="_blank">https://www.php.net/</a></small>
                    </li>
                    <li class="list-group-item">
                      <strong>MySQL 8.0</strong> — Relational database management system
                      <br><small class="text-muted">Official site: <a href="https://www.mysql.com/" target="_blank">https://www.mysql.com/</a></small>
                    </li>
                  </ul>
                </div>
                
                <div class="col-md-6">
                  <h5 class="text-success"><i class="bi bi-file-code me-2"></i>Custom Assets</h5>
                  <ul class="list-group">
                    <li class="list-group-item">
                      <strong>Custom Stylesheets</strong>
                      <br><small class="text-muted">css/style.css - Custom theme and component styles</small>
                    </li>
                    <li class="list-group-item">
                      <strong>JavaScript Files</strong>
                      <br><small class="text-muted">
                        js/dark-mode.js - Theme switching functionality<br>
                        js/validation.js - Form validation logic<br>
                        js/scroll-to-top.js - Scroll behavior
                      </small>
                    </li>
                    <li class="list-group-item">
                      <strong>Database Schema</strong>
                      <br><small class="text-muted">database.sql - Complete database structure and relationships</small>
                    </li>
                  </ul>
                </div>
                
                <div class="col-md-6">
                  <h5 class="text-warning"><i class="bi bi-image me-2"></i>Images & Media</h5>
                  <ul class="list-group">
                    <li class="list-group-item">
                      <strong>Local Image Assets</strong>
                      <br><small class="text-muted">
                        /img/bg.avif - Background image<br>
                        /img/house1-6.avif - Property showcase images
                      </small>
                    </li>
                    <li class="list-group-item">
                      <strong>External Images</strong>
                      <br><small class="text-muted">Unsplash - Free stock photos for demonstration purposes</small>
                    </li>
                  </ul>
                </div>
                
                <div class="col-md-6">
                  <h5 class="text-danger"><i class="bi bi-docker me-2"></i>Development Tools</h5>
                  <ul class="list-group">
                    <li class="list-group-item">
                      <strong>Docker & Docker Compose</strong>
                      <br><small class="text-muted">Containerization platform for consistent deployment</small>
                    </li>
                    <li class="list-group-item">
                      <strong>phpMyAdmin</strong>
                      <br><small class="text-muted">Database administration tool for MySQL management</small>
                    </li>
                    <li class="list-group-item">
                      <strong>Visual Studio Code</strong>
                      <br><small class="text-muted">Primary code editor used by the development team</small>
                    </li>
                  </ul>
                </div>
              </div>
              
              <div class="alert alert-light mt-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Typography:</strong> System UI fonts (Arial, sans-serif) used as fallbacks throughout the application for optimal cross-platform compatibility.
              </div>
            </div>
          </div>

        </div>
      </div>
    </main>

    <!-- Footer Section -->
    <footer class="bg-light py-5 mt-5">
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-3 mb-3">
            <h5><i class="bi bi-house-heart-fill me-2" style="font-size: 24px"></i>KXO205</h5>
            <small class="text-muted">Full-Stack Accommodation Platform</small>
            <p class="mt-3 text-muted small">&copy; 2025 KXO205 Accommodation<br>Swinburne University Project</p>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <h6 class="text-uppercase fw-bold">Features</h6>
            <ul class="list-unstyled">
              <li><a class="text-decoration-none text-muted" href="index.php#featured-accommodations">Featured Properties</a></li>
              <li><a class="text-decoration-none text-muted" href="search.php">Search & Book</a></li>
              <li><a class="text-decoration-none text-muted" href="about.php#team-collaboration">Team Roles</a></li>
            </ul>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <h6 class="text-uppercase fw-bold">Resources</h6>
            <ul class="list-unstyled">
              <li><a class="text-decoration-none text-muted" href="about.php#resources">Resources & Credits</a></li>
              <li><a class="text-decoration-none text-muted" href="about.php#development-challenges">Challenges</a></li>
              <li><a class="text-decoration-none text-muted" href="README.md">Documentation</a></li>
            </ul>
          </div>
          <div class="col-6 col-md-3 mb-3">
            <h6 class="text-uppercase fw-bold">About</h6>
            <ul class="list-unstyled">
              <li><a class="text-decoration-none text-muted" href="about.php#our-team">Our Team</a></li>
              <li><a class="text-decoration-none text-muted" href="about.php">About Project</a></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/dark-mode.js"></script>
    <script src="js/scroll-to-top.js"></script>
  </body>
</html>
