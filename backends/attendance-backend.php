<?php
session_start();
require_once(__DIR__ . '/../_backend-libs.php');

header('Content-Type: application/json');

if (empty($_SESSION["name"])) {
    http_response_code(403);
    echo json_encode([
        'error' => 'Not logged in',
        'message' => 'Authentication required'
    ]);
    exit;
}

$month = isset($_GET['month']) ? trim($_GET['month']) : null;

$params = [];
if ($month) {
    $params['month'] = $month;
}

$attendanceData = api_call('attendance', $params, true);

if (!$attendanceData || !is_array($attendanceData)) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to load attendance data',
        'message' => 'The attendance service is currently unavailable'
    ]);
    exit;
}

echo json_encode($attendanceData);