<?php

// To force new fetching, add true as the 3rd argument.
// Pass $cookies by reference to capture fresh login cookies
function api_call($endpoint, $extra_query = [], $force = false, $saveCache = true, &$cookies = null) {
    if (empty($_SESSION['id'])) {
        return ['error' => 'Session ID is not set.'];
    }

    $prefs = loadPrefs($_SESSION['id']);

    $district = $_SESSION['url'] ?? '';
    if (strpos($district, 'demodist') !== false) {
        $cached = getCachedApi($prefs, $endpoint);
        if ($cached !== null) {
            return json_decode($cached, true);
        } else {
            return ['error' => 'No cached data available for demodist district'];
        }
    }
    if (!$force) {
        $cached = getCachedApi($prefs, $endpoint);
        if ($cached !== null) {
            return json_decode($cached, true);
        }
    }

    $hacCookies = null;
    if (isset($prefs['hac_cookies']) && !empty($prefs['hac_cookies']['data'])) {
        $password = $_SESSION['pass'] ?? '';
        if ($prefs['hac_cookies']['encrypted']) {
            $decrypted = decryptData($prefs['hac_cookies']['data'], $password);
            if ($decrypted !== false) {
                $hacCookies = $decrypted;
            }
        } else {
            $hacCookies = $prefs['hac_cookies']['data'];
        }
    }

    $base = "http://127.0.0.1/api/";
    $query = array_merge([
        'link' => $_SESSION['url'] ?? '',
        'user' => $_SESSION['id'],
        'pass' => $_SESSION['pass'] ?? '',
    ], $extra_query);
    
    if ($hacCookies !== null) {
        $query['cookies'] = $hacCookies;
    }

    $url = $base . $endpoint . '?' . http_build_query($query);

    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Host: lunaedu.net' 
    ]);

    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return ['error' => 'API request failed: ' . $error];
    }

    if ($httpCode !== 200) {
        return ['error' => "API returned HTTP code: $httpCode"];
    }

    $data = json_decode($response, true);
    if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'Invalid API response format', 'raw_response' => substr($response, 0, 500)];
    }
    
    if (isset($data['_fresh_cookies'])) {
        $cookies = $data['_fresh_cookies'];
        unset($data['_fresh_cookies']);
        
        if (strpos($district, 'demodist') === false) {
            $password = $_SESSION['pass'] ?? '';
            $encryptedCookies = encryptData($cookies, $password);
            $prefs['hac_cookies'] = [
                'data' => $encryptedCookies,
                'timestamp' => time(),
                'encrypted' => true
            ];
            savePrefs($_SESSION['id'], $prefs);
        }
    }

    if (strpos($district, 'demodist') === false && $saveCache == true) {
        setCachedApi($prefs, $endpoint, $response);
        savePrefs($_SESSION['id'], $prefs);
    }

    return $data;
}

function getPrefsFilePath($id) {
    $url = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    $safe_id = preg_replace('/[^A-Za-z0-9_\-]/', '_', $id);
    return __DIR__ . "/userdata/users/$url/$safe_id/prefs.json";
}

function loadPrefs($id) {
    $file = getPrefsFilePath($id);
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $decoded = json_decode($json, true);
        return is_array($decoded) ? $decoded : [];
    }
    return [];
}

function savePrefs($id, array $prefs): bool {
    $district = $_SESSION['url'] ?? '';
    if (strpos($district, 'demodist') !== false) {
        return true;
    }
    
    $path = getPrefsFilePath($id);
    $folder = dirname($path);

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    return file_put_contents($path, json_encode($prefs, JSON_PRETTY_PRINT)) !== false;
}

function getCachedApi($prefs, $endpoint) {
    if (isset($prefs['api_cache'][$endpoint])) {
        $cache = $prefs['api_cache'][$endpoint];
        $isOld = time() - $cache['time'] >= 86400;
        
        if (($_SESSION['url'] ?? '') === "demodist") {
            return $cache['response'];
        }
        
        if (!$isOld) {
            if (isset($cache['encrypted']) && $cache['encrypted'] === true) {
                $password = $_SESSION['pass'] ?? '';
                $decrypted = decryptData($cache['response'], $password);
                
                if ($decrypted === false) {
                    return null;
                }
                
                return $decrypted;
            }
            
            return $cache['response'];
        }
    }
    return null;
}

function setCachedApi(&$prefs, $endpoint, $response) {
    $password = $_SESSION['pass'] ?? '';
    $encrypted = false;
    $dataToStore = $response;
    
    if (!empty($password) && ($_SESSION['url'] ?? '') !== 'demodist') {
        // encrypts any saved data from the api with the user's password session.
        $encryptedData = encryptData($response, $password);
        if ($encryptedData !== $response) {
            $dataToStore = $encryptedData;
            $encrypted = true;
        }
    }
    
    $prefs['api_cache'][$endpoint] = [
        'time' => time(),
        'response' => $dataToStore,
        'encrypted' => $encrypted
    ];
}

// Notifications

function getTodos($id) {
    $prefs = loadPrefs($id);
    return isset($prefs['todos']) ? $prefs['todos'] : [];
}

function saveTodo($id, $todo) {
    $prefs = loadPrefs($id);
    
    if (!isset($todo['id'])) {
        $todo['id'] = uniqid();
    }
    
    if (!isset($todo['created'])) {
        $todo['created'] = time();
    }
    
    if (!isset($prefs['todos'])) {
        $prefs['todos'] = [];
    }
    
    $prefs['todos'][$todo['id']] = $todo;
    
    return savePrefs($id, $prefs);
}

function deleteTodo($id, $todoId) {
    $prefs = loadPrefs($id);
    
    if (isset($prefs['todos'][$todoId])) {
        unset($prefs['todos'][$todoId]);
        return savePrefs($id, $prefs);
    }
    
    return false;
}

function getPendingNotifications($id) {
    $prefs = loadPrefs($id);
    $notifications = isset($prefs['notifications']) ? $prefs['notifications'] : [];
    
    $pending = array_filter($notifications, function($n) {
        return !isset($n['dismissed']) || !$n['dismissed'];
    });
    
    return $pending;
}

function addNotification($id, $title, $message, $type = 'info') {
    $prefs = loadPrefs($id);
    
    if (!isset($prefs['notifications'])) {
        $prefs['notifications'] = [];
    }
    
    $notification = [
        'id' => uniqid(),
        'title' => $title,
        'message' => $message,
        'type' => $type,
        'timestamp' => time(),
        'dismissed' => false
    ];
    
    $prefs['notifications'][] = $notification;
    return savePrefs($id, $prefs);
}

function dismissNotification($id, $notificationId) {
    $prefs = loadPrefs($id);
    
    if (isset($prefs['notifications'])) {
        foreach ($prefs['notifications'] as &$notification) {
            if ($notification['id'] === $notificationId) {
                $notification['dismissed'] = true;
                break;
            }
        }
        
        return savePrefs($id, $prefs);
    }
    
    return false;
}

function getLinks($userId) {
    $prefs = loadPrefs($userId);
    return isset($prefs['links']) ? $prefs['links'] : [];
}

function saveLink($userId, $linkData, $linkId = null) {
    $prefs = loadPrefs($userId);
    
    if (!isset($prefs['links'])) {
        $prefs['links'] = [];
    }
    
    if ($linkId === null) {
        $linkId = uniqid();
    }
    
    $prefs['links'][$linkId] = $linkData;
    
    return savePrefs($userId, $prefs);
}

function deleteLink($userId, $linkId) {
    $prefs = loadPrefs($userId);
    
    if (!isset($prefs['links'][$linkId])) {
        return false;
    }
    
    unset($prefs['links'][$linkId]);
    
    return savePrefs($userId, $prefs);
}

function encryptData($data, $password) {
    if (empty($password)) {
        return $data;
    }
    
    $key = hash('sha256', $password, true);
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    
    if ($encrypted === false) {
        return $data;
    }
    
    return base64_encode($iv . $encrypted);
}

function decryptData($encryptedData, $password) {
    if (empty($password) || empty($encryptedData)) {
        return $encryptedData;
    }
    
    $data = base64_decode($encryptedData, true);
    if ($data === false) {
        return false;
    }
    
    if (strlen($data) < 16) {
        return false;
    }
    
    $key = hash('sha256', $password, true);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    
    $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    return $decrypted;
}
