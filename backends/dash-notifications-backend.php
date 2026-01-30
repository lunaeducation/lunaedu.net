<?php

// Not needed, since notifications were killed pretty early back. 
// Maybe I should add them once more.

session_start();
include_once('../_backend-libs.php');

header('Content-Type: application/json');

if (empty($_SESSION['id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'get_count':
        $notifications = getPendingNotifications($_SESSION['id']);
        echo json_encode(['success' => true, 'count' => count($notifications)]);
        break;
        
    case 'clear_all':
        $prefs = loadPrefs($_SESSION['id']);
        
        if (isset($prefs['notifications'])) {
            foreach ($prefs['notifications'] as &$notification) {
                $notification['dismissed'] = true;
            }
            
            if (savePrefs($_SESSION['id'], $prefs)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['error' => 'Failed to save preferences']);
            }
        } else {
            echo json_encode(['success' => true]);
        }
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}