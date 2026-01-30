<?php
session_start();
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once(__DIR__ . '/../_backend-libs.php');

define('PREFS_FILE', __DIR__ . '/prefs.json');
define('CACHE_DURATION', 30 * 24 * 60 * 60);

function encodeDataForUrl($plaintext) {
    return base64_encode($plaintext);
}

function decodeDataFromUrl($b64data) {
    return base64_decode($b64data);
}

function loadPreferences() {
    if (!file_exists(PREFS_FILE)) {
        $defaultPrefs = [
            'marking_period_cache' => [],
            'class_credits' => [],
            'gpa_scales' => [],
            'last_updated' => time()
        ];
        file_put_contents(PREFS_FILE, json_encode($defaultPrefs, JSON_PRETTY_PRINT));
        return $defaultPrefs;
    }
    
    $content = file_get_contents(PREFS_FILE);
    $prefs = json_decode($content, true);
    
    if (!is_array($prefs)) {
        $prefs = [];
    }
    if (!isset($prefs['marking_period_cache'])) {
        $prefs['marking_period_cache'] = [];
    }
    if (!isset($prefs['class_credits'])) {
        $prefs['class_credits'] = [];
    }
    if (!isset($prefs['gpa_scales'])) {
        $prefs['gpa_scales'] = [];
    }
    
    return $prefs;
}

function savePreferences($prefs) {
    $prefs['last_updated'] = time();
    $json = json_encode($prefs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    
    $fp = fopen(PREFS_FILE, 'c');
    if ($fp) {
        if (flock($fp, LOCK_EX)) {
            ftruncate($fp, 0);
            fwrite($fp, $json);
            fflush($fp);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }
}

function getCachedMarkingPeriod($run, $type = 'assignments') {
    $prefs = loadPreferences();
    $cacheKey = $type . '_' . $run;
    
    if (isset($prefs['marking_period_cache'][$cacheKey])) {
        $cached = $prefs['marking_period_cache'][$cacheKey];
        
        $cacheTime = $cached['timestamp'] ?? 0;
        if (time() - $cacheTime < CACHE_DURATION) {
            return $cached['data'];
        } else {
            unset($prefs['marking_period_cache'][$cacheKey]);
            savePreferences($prefs);
        }
    }
    return null;
}

function cacheMarkingPeriod($run, $type, $data) {
    $prefs = loadPreferences();
    $cacheKey = $type . '_' . $run;
    
    $prefs['marking_period_cache'][$cacheKey] = [
        'data' => $data,
        'timestamp' => time(),
        'run' => $run,
        'type' => $type
    ];
    
    savePreferences($prefs);
}

function getClassCredits($classCode) {
    $prefs = loadPreferences();
    return $prefs['class_credits'][$classCode] ?? null;
}

function getAllClassCredits() {
    $prefs = loadPreferences();
    return $prefs['class_credits'];
}

function saveClassCredits($classCode, $credits) {
    $prefs = loadPreferences();
    $prefs['class_credits'][$classCode] = floatval($credits);
    savePreferences($prefs);
}

function getGPAScale($classCode) {
    $prefs = loadPreferences();
    return $prefs['gpa_scales'][$classCode] ?? '4.0';
}

function saveGPAScale($classCode, $scale) {
    $prefs = loadPreferences();
    $prefs['gpa_scales'][$classCode] = $scale;
    savePreferences($prefs);
}

function shouldUseCache($run, $type, $forceRefresh = false) {
    if ($forceRefresh) {
        return false;
    }
    
    $cachedData = getCachedMarkingPeriod($run, $type);
    return $cachedData !== null;
}

function getApiType() {
    if (isset($_GET['type'])) {
        $type = $_GET['type'];
        if ($type === 'rc') {
            return 'reportcard';
        } else if ($type === 'ipr') {
            return 'ipr';
        }
    }
    return 'assignments';
}

$forceRefresh = isset($_GET['refresh']) && $_GET['refresh'] === 'true';
$apiType = getApiType();
$run = $_GET['run'] ?? '';
$date = $_GET['date'] ?? '';
$action = $_GET['action'] ?? '';

if ($action) {
    $prefs = loadPreferences();
    
    switch ($action) {
        case 'get_credits':
            $classCode = $_GET['class_code'] ?? '';
            if ($classCode) {
                $credits = getClassCredits($classCode);
                echo json_encode([
                    'success' => true,
                    'credits' => $credits,
                    'class_code' => $classCode
                ]);
            } else {
                // Get all credits
                echo json_encode([
                    'success' => true,
                    'credits' => $prefs['class_credits']
                ]);
            }
            exit;
            
        case 'save_credits':
            $classCode = $_POST['class_code'] ?? '';
            $credits = $_POST['credits'] ?? 1.0;
            
            if ($classCode) {
                saveClassCredits($classCode, $credits);
                echo json_encode([
                    'success' => true,
                    'message' => 'Credits saved',
                    'class_code' => $classCode,
                    'credits' => $credits
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No class code provided']);
            }
            exit;
            
        case 'get_gpa_scales':
            echo json_encode([
                'success' => true,
                'scales' => $prefs['gpa_scales']
            ]);
            exit;
            
        case 'save_gpa_scale':
            $classCode = $_POST['class_code'] ?? '';
            $scale = $_POST['gpa_scale'] ?? '4.0';
            
            if ($classCode) {
                saveGPAScale($classCode, $scale);
                echo json_encode([
                    'success' => true,
                    'message' => 'GPA scale saved',
                    'class_code' => $classCode,
                    'scale' => $scale
                ]);
            } else {
                echo json_encode(['success' => false, 'error' => 'No class code provided']);
            }
            exit;
    }
}

$apiParams = [];
if ($run) {
    $apiParams['run'] = $run;
}
if ($date) {
    $apiParams['date'] = $date;
}

if ($apiType === 'ipr' || $apiType === 'reportcard') {
    $debugInfo = [
        'type' => $apiType,
        'run' => $run ?: 'none',
        'date' => $date ?: 'none (API default)',
        'force_refresh' => 'yes (always fresh)'
    ];
    error_log("Grade Request: " . json_encode($debugInfo));
}

$apiData = api_call($apiType, $apiParams, true, false);

if (($apiType === 'ipr' || $apiType === 'reportcard') && isset($apiData['selected_date'])) {
    error_log("API Response - selected_date from API: " . json_encode($apiData['selected_date']));
}

if ($apiType === 'assignments' && empty($run)) {
    $apiData['is_default_run'] = true;
    if (isset($apiData['report_card_run']['selected'])) {
        $apiData['default_run'] = $apiData['report_card_run']['selected'];
    }
}

if ($apiType === 'ipr' && empty($date)) {
    $apiData['is_default_date'] = true;
    if (isset($apiData['selected_date'])) {
        $apiData['default_ipr_date'] = $apiData['selected_date'];
    }
}

if ($apiType === 'assignments') {
    if (isset($apiData['classes']) && is_array($apiData['classes'])) {
        foreach ($apiData['classes'] as $classCode => &$item) {
            $classCredits = getClassCredits($classCode);
            $item['credits'] = $classCredits !== null ? floatval($classCredits) : 1.0;
            
            $item['gpa_scale'] = getGPAScale($classCode);
            
            $classDetailData = [
                'class_code' => $classCode,
                'class_name' => $item['class_name'] ?? $classCode,
                'average' => $item['average'] ?? 'N/A',
                'dropped' => $item['dropped'] ?? false,
                'dropped_date' => $item['dropped_date'] ?? null,
                'credits' => $item['credits'],
                'gpa_scale' => $item['gpa_scale'],
                'assignments' => $item['assignments'] ?? [],
                'categories' => $item['categories'] ?? [],
                'report_card_run' => $apiData['report_card_run'] ?? [],
                'timestamp' => time()
            ];

            $json = json_encode($classDetailData);
            $encodedB64 = encodeDataForUrl($json);
            $item['encoded_data'] = $encodedB64;
        }
        unset($item);
    }
}

$apiData['api_type'] = $_GET['type'] ?? 'assignments';
$apiData['cached'] = false;
$apiData['cache_timestamp'] = time();

echo json_encode($apiData);
exit;
?>