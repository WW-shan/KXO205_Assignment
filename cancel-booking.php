<?php
session_start();
require "includes/dbconn.php";

if (!isset($_SESSION["userId"])) {
    redirect("login.php");
}

if (!isset($_GET["id"])) {
    redirect($_SESSION["role"] == "client" ? "client.php" : "manager.php");
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
