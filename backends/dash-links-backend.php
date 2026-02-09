<?php

// I should consolidate into one file

session_start();
include_once(__DIR__ . '/../_backend-libs.php');

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0); 
ini_set('log_errors', 1);

function sendJsonResponse($data) {
    if (ob_get_length()) ob_clean();
    
    echo json_encode($data);
    exit;
}

if (empty($_SESSION['id'])) {
    sendJsonResponse(['error' => 'Not authenticated']);
}

try {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'get_links':
            $links = getLinks($_SESSION['id']);
            sendJsonResponse(['success' => true, 'links' => $links]);
            break;
            
        case 'add_link':
            $title = trim($_POST['title'] ?? '');
            $url = trim($_POST['url'] ?? '');
            
            if (empty($title) || empty($url)) {
                sendJsonResponse(['error' => 'Title and URL are required']);
            }
            
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                if (!preg_match('#^https?://#', $url)) {
                    $url = 'http://' . $url;
                    
                    if (!filter_var($url, FILTER_VALIDATE_URL)) {
                        sendJsonResponse(['error' => 'Invalid URL format']);
                    }
                } else {
                    sendJsonResponse(['error' => 'Invalid URL format']);
                }
            }
            
            $link = [
                'title' => $title,
                'url' => $url,
                'created_at' => time()
            ];
            
            if (saveLink($_SESSION['id'], $link)) {
                sendJsonResponse(['success' => true]);
            } else {
                sendJsonResponse(['error' => 'Failed to save link']);
            }
            break;
            
        case 'update_link':
            $linkId = $_POST['id'] ?? '';
            $title = trim($_POST['title'] ?? '');
            $url = trim($_POST['url'] ?? '');
            
            $links = getLinks($_SESSION['id']);
            
            if (!isset($links[$linkId])) {
                sendJsonResponse(['error' => 'Link not found']);
            }
            
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                if (!preg_match('#^https?://#', $url)) {
                    $url = 'http://' . $url;
                    
                    if (!filter_var($url, FILTER_VALIDATE_URL)) {
                        sendJsonResponse(['error' => 'Invalid URL format']);
                    }
                } else {
                    sendJsonResponse(['error' => 'Invalid URL format']);
                }
            }
            
            $link = $links[$linkId];
            $link['title'] = $title;
            $link['url'] = $url;
            
            if (saveLink($_SESSION['id'], $link, $linkId)) {
                sendJsonResponse(['success' => true]);
            } else {
                sendJsonResponse(['error' => 'Failed to update link']);
            }
            break;
            
        case 'delete_link':
            $linkId = $_POST['id'] ?? '';
            
            if (deleteLink($_SESSION['id'], $linkId)) {
                sendJsonResponse(['success' => true]);
            } else {
                sendJsonResponse(['error' => 'Failed to delete link']);
            }
            break;
            
        default:
            sendJsonResponse(['error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    error_log("Error in links-backend.php: " . $e->getMessage());
    sendJsonResponse(['error' => 'Server error occurred']);
}