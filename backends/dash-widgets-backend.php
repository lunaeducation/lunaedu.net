<?php

// Remnant

session_start();
require_once(__DIR__ . '/../_backend-libs.php');

if (!isset($_SESSION["id"])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'save_layout':
        saveLayout();
        break;
    case 'get_layout':
        getLayout();
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
        exit;
}

function saveLayout() {
    if (!isset($_POST['layout'])) {
        echo json_encode(['success' => false, 'error' => 'No layout data provided']);
        exit;
    }
    
    $layout = json_decode($_POST['layout'], true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'error' => 'Invalid JSON data']);
        exit;
    }
    
    $prefs = loadPrefs($_SESSION["id"]);
    $prefs["dashboard_layout"] = $layout;
    savePrefs($_SESSION["id"], $prefs);
    
    echo json_encode(['success' => true]);
}

function getLayout() {
    $prefs = loadPrefs($_SESSION["id"]);
    $layout = $prefs["dashboard_layout"] ?? null;
    
    if ($layout) {
        echo json_encode(['success' => true, 'layout' => $layout]);
    } else {
        $defaultLayout = [
            'widget-launchpad' => ['x' => 0, 'y' => 0, 'width' => 8, 'height' => 4],
            'widget-assignments' => ['x' => 0, 'y' => 4, 'width' => 8, 'height' => 4],
            'widget-todo' => ['x' => 8, 'y' => 0, 'width' => 4, 'height' => 8]
        ];
        echo json_encode(['success' => true, 'layout' => $defaultLayout]);
    }
}
?>