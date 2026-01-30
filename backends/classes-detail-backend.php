<?php

// this file is not needed

session_start();
header('Content-Type: application/json');

require_once(__DIR__ . '/../_backend-libs.php');

$password = '#$%3$#%@@&@!@#((&^$#$%&6(76435&(*$%^#^&#%^&()&(*';
$key = hash('sha256', $password, true);

function decryptData($b64data, $key) {
    $raw = base64_decode($b64data);
    if ($raw === false || strlen($raw) < 16) {
        return false;
    }
    $iv = substr($raw, 0, 16);
    $ciphertext = substr($raw, 16);
    return openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
}

$apiParams = [];
if (isset($_GET['run']) && !empty($_GET['run'])) {
    $apiParams['run'] = $_GET['run'];
}

// Handle request with encoded data FIRST
if (isset($_GET['data'])) {
    $encodedData = $_GET['data'];

    try {
        $decryptedJson = decryptData($encodedData, $key);
        $classData = json_decode($decryptedJson, true);

        if ($classData && isset($classData['class_code'])) {
            $currentTime = time();
            $dataTime = $classData['timestamp'] ?? 0;
            $timeDiff = $currentTime - $dataTime;

            if ($timeDiff <= 120) { // 2 minutes
                $className = $classData['class_name'] ?? 'Unknown Class';
                $response = [
                    $className => $classData
                ];
                echo json_encode($response);
                exit;
            }
        }
    } catch (Exception $e) {
        error_log("Data decoding failed: " . $e->getMessage());
    }
}

// If no valid encoded data, fetch fresh
if (!isset($_GET['code'])) {
    echo json_encode([]);
    exit;
}

$classCode = urldecode($_GET['code']);
$allAssignments = api_call("assignments", $apiParams, true, false); // Force fresh, no cache
$response = [];

// Check if the response has a 'classes' key
if (isset($allAssignments['classes']) && is_array($allAssignments['classes'])) {
    foreach ($allAssignments['classes'] as $className => $classData) {
        if (isset($classData['class_code']) && $classData['class_code'] === $classCode) {
            $classData['timestamp'] = time();
            $response[$className] = $classData;
            break;
        }
    }
} else if (is_array($allAssignments)) {
    foreach ($allAssignments as $className => $classData) {
        if (isset($classData['class_code']) && $classData['class_code'] === $classCode) {
            $classData['timestamp'] = time();
            $response[$className] = $classData;
            break;
        }
    }
}

if (empty($response)) {
    $response = [];
}

echo json_encode($response);
exit;
?>
