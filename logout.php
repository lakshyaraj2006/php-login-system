<?php
require_once "constants.php";

// This script will handle logout
session_start();

// Check if the user is already loggedin
if (!isset($_SESSION["user"])) {
    header("location: login.php");
    exit;
} else {
    $_SESSION = array();
    unset($_SESSION["user"]);
    $_SESSION["loggedin"] = false;
    $_SESSION["alert"] = [
        "message" => "Logged out successfully",
        "type" => ALERT_SUCCESS
    ];
    header("location: login.php");
}
