<?php
session_start();
require_once(__DIR__ . '/../_backend-libs.php');

if (!isset($_SESSION["id"])) {
    header("Location: /user");
    exit;
}

if (isset($_POST['ads'])) {
    $prefs = loadPrefs($_SESSION["id"]);
    $prefs["ads"] = in_array($_POST['ads'], ['0', '1', '3']) ? $_POST['ads'] : '0'; // Fallback to 0
    $_SESSION["ads"] = $prefs["ads"];
    savePrefs($_SESSION["id"], $prefs);
}

header("Location: /user");
exit;
