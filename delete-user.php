<?php
session_start();
require "includes/dbconn.php";
require "includes/csrf.php";

// Check if user is manager
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "manager") {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"]) || !isset($_GET["token"])) {
    header("Location: manager.php");
    exit;
}

// Verify CSRF token from URL
if (!validateCsrfToken($_GET["token"])) {
    http_response_code(403);
    die("Invalid CSRF token. Please try again.");
}

$user_id = $_GET["id"];

// Prevent deleting yourself
if ($user_id == $_SESSION["userId"]) {
    header("Location: manager.php?error=Cannot delete your own account");
    exit;
}

// Delete bookings for accommodations owned by this user (if host)
$delete_accommodation_bookings_sql = "DELETE FROM BOOKING WHERE accommodationId IN (SELECT accommodationId FROM ACCOMMODATION WHERE hostId = $user_id);";
$conn->query($delete_accommodation_bookings_sql);

// Delete user's bookings first (due to foreign key constraint)
$delete_bookings_sql = "DELETE FROM BOOKING WHERE userId = $user_id;";
$conn->query($delete_bookings_sql);

// Delete accommodation amenities for accommodations owned by this user (if host)
$delete_amenities_sql = "DELETE FROM ACCOMMODATION_AMENITY WHERE accommodationId IN (SELECT accommodationId FROM ACCOMMODATION WHERE hostId = $user_id);";
$conn->query($delete_amenities_sql);

// Delete accommodations owned by this user (if host)
$delete_accommodations_sql = "DELETE FROM ACCOMMODATION WHERE hostId = $user_id;";
$conn->query($delete_accommodations_sql);

// Delete user
$sql = "DELETE FROM USER WHERE userId = $user_id;";
$conn->query($sql);
$conn->close();
header("Location: manager.php");
exit;
?>
