<?php
session_start();
require "includes/dbconn.php";
require "includes/csrf.php";

if (!isset($_SESSION["userId"])) {
    redirect("login.php");
}

if (!isset($_GET["id"]) || !isset($_GET["token"])) {
    redirect($_SESSION["role"] == "client" ? "client.php" : "manager.php");
}

// Verify CSRF token from URL
if (!validateCsrfToken($_GET["token"])) {
    http_response_code(403);
    die("Invalid CSRF token. Please try again.");
}

$bookingId = $_GET["id"];
$currentUserId = $_SESSION["userId"];

if ($_SESSION["role"] == "client") {
    cancelClientBooking($conn, $bookingId, $currentUserId);
    $conn->close();
    redirect("client.php");
} elseif ($_SESSION["role"] == "manager") {
    cancelAnyBooking($conn, $bookingId);
    $conn->close();
    redirect("manager.php");
} else {
    redirect("login.php");
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function cancelClientBooking($conn, $bookingId, $userId)
{
    $sql = "DELETE FROM BOOKING WHERE bookingId = $bookingId AND userId = $userId;";
    return $conn->query($sql);
}

function cancelAnyBooking($conn, $bookingId)
{
    $sql = "DELETE FROM BOOKING WHERE bookingId = $bookingId;";
    return $conn->query($sql);
}
?>
