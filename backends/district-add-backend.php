<?php
header('Content-Type: application/json');

if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo json_encode(['success' => false, 'message' => 'No URL provided.']);
    exit;
}

$url = trim($_GET['url']);
$url = rtrim($url, "/");

if (!preg_match('~^https?://~', $url)) {
    $url = 'https://' . $url;
}

$parsed = parse_url($url);
if (!isset($parsed['host'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid URL, no host found.']);
    exit;
}

$cleanUrl = 'https://' . $parsed['host'];

if (!filter_var($cleanUrl, FILTER_VALIDATE_URL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid URL format.']);
    exit;
}

//TODO: Replace with own API
$apiUrl = 'https://homeaccesscenterapi.vercel.app/api/name?link=' . urlencode($cleanUrl);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);

if ($response === false) {
    echo json_encode(['success' => false, 'message' => 'Failed to reach API.']);
    curl_close($ch);
    exit;
}

curl_close($ch);

$json = json_decode($response, true);

if (isset($json['error'])) {
    if ($json['error'] === 'Invalid username or password') {

    // Load existing districts (pending)
    $pendingFile = __DIR__ . '/pending_districts.json';
    $pendingDistricts = [];
    if (file_exists($pendingFile)) {
        $content = file_get_contents($pendingFile);
        $pendingDistricts = json_decode($content, true);
        if (!is_array($pendingDistricts)) {
            $pendingDistricts = [];
        }
    }
    
    // Load approved districts
    $approvedFile = __DIR__ . '/districts.json';
    $approvedDistricts = [];
    if (file_exists($approvedFile)) {
        $contentApproved = file_get_contents($approvedFile);
        $approvedDistricts = json_decode($contentApproved, true);
        if (!is_array($approvedDistricts)) {
            $approvedDistricts = [];
        }
    }
    
    // Check if URL already approved
    foreach ($approvedDistricts as $district) {
        if (isset($district['url']) && rtrim($district['url'], '/') === $cleanUrl) {
            echo json_encode([
                'success' => false,
                'message' => 'This district is already approved.'
            ]);
            exit;
        }
    }
    
    // Check if URL already pending
    if (in_array($cleanUrl, $pendingDistricts)) {
        echo json_encode([
            'success' => false,
            'message' => 'This district is already pending for review. Please wait for it to be accepted.'
        ]);
        exit;
    }
    
    // Not a duplicate — add and save to pending
    $pendingDistricts[] = $cleanUrl;
    
    // Save pending districts with exclusive lock
    $saved = false;
    $fp = fopen($pendingFile, 'w');
    if ($fp) {
        if (flock($fp, LOCK_EX)) {
            fwrite($fp, json_encode($pendingDistricts, JSON_PRETTY_PRINT));
            fflush($fp);
            flock($fp, LOCK_UN);
            $saved = true;
        }
        fclose($fp);
    }
    
    if (!$saved) {
        echo json_encode(['success' => true, 'message' => 'This district is compatible with Łuna, but failed to save to the pending list. Please contact a developer.']);
        exit;
    }
    
    echo json_encode(['success' => true, 'message' => 'This district is compatible with Łuna and has been saved for review. It will be approved within 1–2 days, then users can log in.']);
    exit;


    } elseif ($json['error'] === 'Failed to log in') {
        echo json_encode(['success' => false, 'message' => 'This district is not compatible with Łuna.']);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'API returned: ' . $json['error']]);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Unexpected API response.']);
    exit;
}
?>
