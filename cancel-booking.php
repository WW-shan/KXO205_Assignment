<?php
session_start();
require "includes/dbconn.php";

if (!isset($_SESSION["user_id"])) {
    redirect("login.php");
}

if (!isset($_GET["id"])) {
    redirect($_SESSION["role"] == "client" ? "client.php" : "manager.php");
}

$bookingId = $_GET["id"];

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
    $sql = "DELETE FROM BOOKINGS WHERE bookingId = $bookingId AND userId = $userId;";
    return $conn->query($sql);
}

function cancelAnyBooking($conn, $bookingId)
{
    $sql = "DELETE FROM BOOKINGS WHERE bookingId = $bookingId;";
    return $conn->query($sql);
}
?>
