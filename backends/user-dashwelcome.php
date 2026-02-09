<?php
session_start();
require_once(__DIR__ . '/../_backend-libs.php');

if (!isset($_SESSION["id"])) {
    header("Location: /user");
    exit;
}

if (isset($_POST['dashwelcome'])) {
    $prefs = loadPrefs($_SESSION["id"]);
    $prefs["dashwelcome"] = $_POST['dashwelcome'] === '1' ? '1' : '0';
    $_SESSION["dashwelcome"] = $prefs["dashwelcome"];
    savePrefs($_SESSION["id"], $prefs);
}

header("Location: /user");
exit;
