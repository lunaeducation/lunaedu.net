<?php

ob_start();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();


header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$preservedData = [];
if (isset($_SESSION['classlink_temp'])) {
    $preservedData['classlink_temp'] = $_SESSION['classlink_temp'];
}
if (isset($_SESSION['oauth_state'])) {
    $preservedData['oauth_state'] = $_SESSION['oauth_state'];
}

unset($_SESSION['id']);
unset($_SESSION['name']);
unset($_SESSION['user']);
unset($_SESSION['pass']);
unset($_SESSION['url']);
unset($_SESSION['email']);
unset($_SESSION['auth_method']);

foreach ($preservedData as $key => $value) {
    $_SESSION[$key] = $value;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['district'], $input['districtUrl'], $input['id'], $input['pass'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$districtIndex = (int)$input['district'];
$districtUrl = trim($input['districtUrl']);
$id = strtoupper(trim($input['id']));
$password = $input['pass'];

if (!preg_match('~^https?://~', $districtUrl)) {
    $districtUrl = 'https://' . $districtUrl;
}

$parsed = parse_url($districtUrl);
if (!isset($parsed['host'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid district URL']);
    exit;
}

$cleanUrl = 'https://' . $parsed['host'];

if (!filter_var($cleanUrl, FILTER_VALIDATE_URL)) {
    echo json_encode(['success' => false, 'error' => 'Invalid URL format']);
    exit;
}

$_SESSION['url'] = $cleanUrl;
$_SESSION['id'] = $id;
$_SESSION['pass'] = $password;

require_once('../_backend-libs.php');

$data = api_call('info', [], true, false, $hacCookies);

if (!$data) {
    unset($_SESSION['id']);
    unset($_SESSION['pass']);
    unset($_SESSION['url']);
    unset($_SESSION['user']);
    echo json_encode(['success' => false, 'error' => 'Invalid username/password.']);
    exit;
} elseif (str_contains($data['error'] ?? '', '500')) {
    unset($_SESSION['id']);
    unset($_SESSION['pass']);
    unset($_SESSION['url']);
    unset($_SESSION['user']);
    echo json_encode(['success' => false, 'error' => 'Invalid username/password.']);
    exit;
} elseif (str_contains($data['error'] ?? '', 'timed out')) {
    unset($_SESSION['id']);
    unset($_SESSION['pass']);
    unset($_SESSION['url']);
    unset($_SESSION['user']);
    echo json_encode(['success' => false, 'error' => 'Sorry, we couldn\'t verify your login (time-out error). Please try again in later as HAC might be in high demand.']);
    exit;
} elseif (isset($data['error'])) {
    unset($_SESSION['id']);
    unset($_SESSION['pass']);
    unset($_SESSION['url']);
    unset($_SESSION['user']);
    $errorMsg = 'API Error: ' . $data['error'];
    if (isset($data['raw_response'])) {
        $errorMsg .= ' | Response: ' . $data['raw_response'];
    }
    echo json_encode(['success' => false, 'error' => $errorMsg]);
    exit;
}

if (!isset($data['name']) || !isset($data['dob']) || empty($data['dob'])) {
    unset($_SESSION['id']);
    unset($_SESSION['pass']);
    unset($_SESSION['url']);
    unset($_SESSION['user']);
    echo json_encode(['success' => false, 'error' => 'Sorry, we couldn\'t verify your information.']);
    exit;
}

function normalizeName($name) {
    $name = trim($name);
    
    if (strpos($name, ',') !== false) {
        $parts = explode(',', $name, 2);
        $lastName = trim($parts[0]);
        $firstMiddle = trim($parts[1]);
        
        return $firstMiddle . ' ' . $lastName;
    }
    
    // not gonna happen, but you just never know...
    return $name;
}

$normalizedName = normalizeName($data['name']);

$tempUrl = $cleanUrl;
$tempId = $id;
$tempPassword = $password;

$_SESSION['url'] = $tempUrl;
$_SESSION['id'] = $tempId;

$prefs = is_array(loadPrefs($tempId)) ? loadPrefs($tempId) : [];
$needsPasswordMigration = false;

if (!empty($prefs['api_cache'])) {
    foreach ($prefs['api_cache'] as $endpoint => $cache) {
        if (isset($cache['encrypted']) && $cache['encrypted'] === true) {
            $testDecrypt = decryptData($cache['response'], $tempPassword);
            if ($testDecrypt === false) {
                $needsPasswordMigration = true;
                break;
            }
        }
    }
}

/*if ($needsPasswordMigration) {
    // Password changed - need old password to decrypt
    // Clear only user session variables
    unset($_SESSION['id']);
    unset($_SESSION['pass']);
    unset($_SESSION['url']);
    unset($_SESSION['user']);
    
    echo json_encode([
        'success' => false,
        'needs_password_migration' => true,
        'temp_id' => $tempId,
        'temp_password' => $tempPassword,
        'temp_url' => $tempUrl,
        'error' => 'Password has changed. Please enter your old password to migrate your data.'
    ]);
    exit;
}*/
$dobString = $data['dob'];

$dob = DateTime::createFromFormat('m/d/Y', $dobString);
if ($dob === false) {
    unset($_SESSION['id']);
    unset($_SESSION['pass']);
    unset($_SESSION['url']);
    unset($_SESSION['user']);
    echo json_encode(['success' => false, 'error' => 'Sorry, we couldn\'t verify your date of birth.']);
    exit;
}

$minBirthDate = new DateTime();
$minBirthDate->modify('-13 years -1 day'); 

$isOver13 = ($dob < $minBirthDate);

if (!$isOver13) {
    unset($_SESSION['id']);
    unset($_SESSION['pass']);
    unset($_SESSION['url']);
    unset($_SESSION['user']);
    echo json_encode(['success' => false, 'error' => 'You can only use Łuna if you\'re 13 years or older. Read the Privacy Policy for more information.']);
    exit;
}

session_destroy();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();
session_regenerate_id(true);

$_SESSION['url'] = $cleanUrl;
$_SESSION['id'] = $id;
$_SESSION['pass'] = $password;

$_SESSION['name'] = htmlspecialchars($normalizedName);

$prefs = is_array(loadPrefs($_SESSION['id'])) ? loadPrefs($_SESSION['id']) : [];

$prefs['name'] = $_SESSION['name'];

if ($isOver13) {
    if (isset($data['school'])) {
        $prefs['school'] = $data['school'];
    }
    if (isset($data['grade'])) {
        $prefs['grade'] = $data['grade'];
    }
    if (isset($data['counselor'])) {
        $prefs['counselor'] = $data['counselor'];
    }
    if (isset($data['language'])) {
        $prefs['language'] = $data['language'];
    }
    if (isset($data['cohort-year'])) {
        $prefs['cohort_year'] = $data['cohort-year'];
    }
}

if (!empty($hacCookies)) {
    $encryptedCookies = encryptData($hacCookies, $password);
    $prefs['hac_cookies'] = [
        'data' => $encryptedCookies,
        'timestamp' => time(),
        'encrypted' => true
    ];
}

$prefs += ['theme' => 'dark', 'dashwelcome' => '1', 'ads' => '1'];

if (!isset($prefs['leaderboard'])) {
    $prefs['leaderboard'] = [
        'participate' => false,
        'alias' => '',
        'show_nav' => false
    ];
}

savePrefs($_SESSION['id'], $prefs);

echo json_encode([
    'success' => true, 
    'redirect' => '/dash',
    'name' => $_SESSION['name']
]);
exit;
?>