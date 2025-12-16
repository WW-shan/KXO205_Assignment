<?php
session_start();
require "includes/dbconn.php";

// Check if user is logged in
if (!isset($_SESSION["userId"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: " . ($_SESSION["role"] == "host" ? "host.php" : "manager.php"));
    exit;
}

$accommodationId = $_GET["id"];

if ($_SESSION["role"] == "host") {
    // Host can only delete their own properties
    $hostId = $_SESSION["userId"];
    $sql = "DELETE FROM ACCOMMODATIONS WHERE accommodationId = $accommodationId AND hostId = $hostId;";
    $conn->query($sql);
    $conn->close();
    header("Location: host.php");
} else if ($_SESSION["role"] == "manager") {
    // Manager can delete any accommodation
    $sql = "DELETE FROM ACCOMMODATIONS WHERE accommodationId = $accommodationId;";
    $conn->query($sql);
    $conn->close();
    header("Location: manager.php");
} else {
    header("Location: login.php");
}
exit;
?>
