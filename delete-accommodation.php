<?php
session_start();
require "includes/dbconn.php";
require "includes/csrf.php";

// Check if user is logged in
if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"]) || !isset($_GET["token"])) {
    header("Location: " . ($_SESSION["role"] == "host" ? "host.php" : "manager.php"));
    exit;
}

// Verify CSRF token from URL
if (!validateCsrfToken($_GET["token"])) {
    http_response_code(403);
    die("Invalid CSRF token. Please try again.");
}

$accommodationId = $_GET["id"];

// Delete associated bookings first (due to foreign key constraint)
$delete_bookings_sql = "DELETE FROM BOOKING WHERE accommodationId = $accommodationId;";
$conn->query($delete_bookings_sql);

if ($_SESSION["role"] == "host") {
    // Host can only delete their own properties
    $hostId = $_SESSION["userId"];
    $sql = "DELETE FROM ACCOMMODATION WHERE accommodationId = $accommodationId AND hostId = $hostId;";
    $conn->query($sql);
    $conn->close();
    header("Location: host.php");
} else if ($_SESSION["role"] == "manager") {
    // Manager can delete any accommodation
    $sql = "DELETE FROM ACCOMMODATION WHERE accommodationId = $accommodationId;";
    $conn->query($sql);
    $conn->close();
    header("Location: manager.php");
} else {
    header("Location: login.php");
}
exit;
?>
