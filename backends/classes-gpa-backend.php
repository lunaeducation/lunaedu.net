<?php
session_start();
include_once('../_backend-libs.php');

header('Content-Type: application/json');

if (empty($_SESSION['id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'get_weights':
        $prefs = loadPrefs($_SESSION['id']);
        
        $defaultWeights = [
            'honors' => 0.5,
            'ap' => 1.0
        ];
        
        $weights = isset($prefs['gpa_weights']) ? $prefs['gpa_weights'] : $defaultWeights;
        
        echo json_encode(['success' => true, 'weights' => $weights]);
        break;
        
    case 'save_weights':
        $honorsWeight = floatval($_POST['honors'] ?? 0.5);
        $apWeight = floatval($_POST['ap'] ?? 1.0);
        
        $prefs = loadPrefs($_SESSION['id']);
        
        if (!isset($prefs['gpa_weights'])) {
            $prefs['gpa_weights'] = [];
        }
        
        $prefs['gpa_weights']['honors'] = $honorsWeight;
        $prefs['gpa_weights']['ap'] = $apWeight;
        
        if (savePrefs($_SESSION['id'], $prefs)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to save weights']);
        }
        break;
        
    case 'get_predictions':
        $prefs = loadPrefs($_SESSION['id']);
        $predictions = isset($prefs['gpa_predictions']) ? $prefs['gpa_predictions'] : [];
        
        echo json_encode(['success' => true, 'predictions' => $predictions]);
        break;
        
    case 'save_predictions':
        $predictions = json_decode($_POST['predictions'] ?? '[]', true);
        
        $prefs = loadPrefs($_SESSION['id']);
        $prefs['gpa_predictions'] = $predictions;
        
        if (savePrefs($_SESSION['id'], $prefs)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to save predictions']);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}