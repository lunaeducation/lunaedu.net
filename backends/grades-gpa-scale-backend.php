<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../_backend-libs.php';

if (empty($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_scales') {
    try {
        $prefs = loadPrefs($_SESSION['id']);
        $gpaScales = $prefs['gpa'] ?? [];
        echo json_encode(['success' => true, 'scales' => $gpaScales]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $classCode = $input['class_code'] ?? '';
    $gpaScale  = $input['gpa_scale'] ?? 4.0;

    if (empty($classCode)) {
        throw new Exception('Class code is required');
    }

    $validScales = [0.0, 4.0, 4.5, 5.0];
    if (!in_array((float) $gpaScale, $validScales)) {
        throw new Exception('Invalid GPA scale');
    }

    $formattedScale = number_format((float)$gpaScale, 1);

    $prefs = loadPrefs($_SESSION['id']);

    if (!isset($prefs['gpa'])) {
        $prefs['gpa'] = [];
    }

    $prefs['gpa'][$classCode] = $formattedScale;

    if (savePrefs($_SESSION['id'], $prefs)) {
        echo json_encode(['success' => true]);
    } else {
        throw new Exception('Failed to save preferences');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}