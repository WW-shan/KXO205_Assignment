<?php
session_start();
require "includes/dbconn.php";

// Check if user is manager
if (!isset($_SESSION["role"]) || $_SESSION["role"] != "manager") {
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: manager.php");
    exit;
}

$user_id = $_GET["id"];

// Prevent deleting yourself
if ($user_id == $_SESSION["userId"]) {
    header("Location: manager.php?error=Cannot delete your own account");
    exit;
}

// Delete user
$sql = "DELETE FROM USERS WHERE userId = $user_id;";
$conn->query($sql);
$conn->close();
header("Location: manager.php");
exit;
?>
