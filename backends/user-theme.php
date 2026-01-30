<?php
session_start();
require_once(__DIR__ . '/../_backend-libs.php');

if (!empty($_SESSION['id']) && isset($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
    $prefs = loadPrefs($_SESSION['id']);
    $prefs['theme'] = $_POST['theme'];
    savePrefs($_SESSION['id'], $prefs);
}

http_response_code(200);
echo "OK";
exit;
