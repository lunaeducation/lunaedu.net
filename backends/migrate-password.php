<?php

// unused iirc

ob_start();
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['old_password'], $input['new_password'], $input['id'], $input['district_url'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$oldPassword = $input['old_password'];
$newPassword = $input['new_password'];
$id = strtoupper(trim($input['id']));
$districtUrl = $input['district_url'];

require_once('../_backend-libs.php');

function decryptWithPassword($encryptedData, $password) {
    if (empty($password) || empty($encryptedData)) {
        return false;
    }
    
    $data = base64_decode($encryptedData, true);
    if ($data === false || strlen($data) < 16) {
        return false;
    }
    
    $key = hash('sha256', $password, true);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}

function encryptWithPassword($data, $password) {
    if (empty($password)) {
        return false;
    }
    
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    
    if ($encrypted === false) {
        return false;
    }
    
    return base64_encode($iv . $encrypted);
}

$_SESSION['url'] = $districtUrl;
$_SESSION['id'] = $id;

$prefs = loadPrefs($id);

session_unset();
session_destroy();

if (empty($prefs['api_cache'])) {
    echo json_encode(['success' => false, 'error' => 'No encrypted cache found']); 
    exit;
}

$migrated = false;
$decryptionSucceeded = true;

foreach ($prefs['api_cache'] as $endpoint => &$cache) {
    if (isset($cache['encrypted']) && $cache['encrypted'] === true) {
        $decrypted = decryptWithPassword($cache['response'], $oldPassword);
        
        if ($decrypted === false) {
            $decryptionSucceeded = false;
            break;
        }
        
        $reencrypted = encryptWithPassword($decrypted, $newPassword);
        
        if ($reencrypted !== false) {
            $cache['response'] = $reencrypted;
            $migrated = true;
        }
    }
}

if (!$decryptionSucceeded) {
    echo json_encode(['success' => false, 'error' => 'Incorrect password.']);
    exit;
}

if ($migrated) {
    $_SESSION['url'] = $districtUrl;
    $_SESSION['id'] = $id;
    savePrefs($id, $prefs);
    session_unset();
    session_destroy();
}

session_start();
session_regenerate_id(true);

$_SESSION['url'] = $districtUrl;
$_SESSION['id'] = $id;
$_SESSION['pass'] = $newPassword;

if (isset($prefs['name'])) {
    $_SESSION['name'] = $prefs['name'];
} else {
    require_once('../_backend-libs.php');
    $nameData = api_call('name', [], true, false);
    if (isset($nameData['name'])) {
        $_SESSION['name'] = htmlspecialchars($nameData['name']);
    }
}

echo json_encode(['success' => true, 'redirect' => '/dash']);
exit;
?>