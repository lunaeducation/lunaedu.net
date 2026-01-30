<?php

// I should probably consolidate this into one file

session_start();
header('Content-Type: application/json');
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once(__DIR__ . '/../_backend-libs.php');

function encodeDataForUrl($plaintext) {
    return base64_encode($plaintext);
}

function decodeDataFromUrl($b64data) {
    return base64_decode($b64data);
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

$apiParams = [];
if (isset($_GET['run'])) {
    $apiParams['run'] = $_GET['run'];
}
if (isset($_GET['date'])) {
    $apiParams['date'] = $_GET['date'];
}

$apiType = getApiType();

if ($apiType === 'assignments' && (isset($_GET['code']) || isset($_GET['data']))) {
    if (isset($_GET['data'])) {
        $encodedData = $_GET['data'];
        try {
            $decodedJson = decodeDataFromUrl($encodedData);
            $classData = json_decode($decodedJson);

            if ($classData && isset($classData['class_code'])) {
                $currentTime = time();
                $dataTime = $classData['timestamp'] ?? 0;
                $timeDiff = $currentTime - $dataTime;

                //if ($timeDiff <= 300) {
                    $className = $classData['class_name'] ?? 'Unknown Class';
                    $response = [
                        $className => $classData
                    ];
                    echo json_encode($response);
                    exit;
                //}
            }
        } catch (Exception $e) {
            error_log("Data decoding failed: " . $e->getMessage());
        }
    }
    
    if (isset($_GET['code'])) {
        $classCode = urldecode($_GET['code']);
        $allAssignments = api_call("assignments", $apiParams, false);
        $response = [];

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
    }
}

$apiData = api_call($apiType, $apiParams, false);

$run = $_GET['run'] ?? '';
$date = $_GET['date'] ?? '';

if ($apiType === 'assignments' && empty($run)) {
    $apiData['is_default_run'] = true;
    if (isset($apiData['report_card_run']['selected'])) {
        $apiData['default_run'] = $apiData['report_card_run']['selected'];
    }
}

if ($apiType === 'ipr') {
    if (empty($date)) {
        $apiData['is_default_date'] = true;
        if (isset($apiData['selected_date'])) {
            $apiData['default_ipr_date'] = $apiData['selected_date'];
        }
    }
    
    if (isset($apiData['multiple_dates_available']) && $apiData['multiple_dates_available']) {
        $formattedData = [
            'title' => 'Interim Progress Report',
            'report_date' => $apiData['selected_date']['text'] ?? date('Y-m-d'),
            'headers' => $apiData['headers'] ?? [],
            'data' => $apiData['data'] ?? [],
            'comments' => [],
            'multiple_dates_available' => true,
            'selected_date' => $apiData['selected_date'] ?? [],
            'available_dates' => $apiData['available_dates'] ?? [],
            'comment_legend' => $apiData['comment_legend'] ?? []
        ];
        
        if (!empty($apiData['comment_legend'])) {
            foreach ($apiData['comment_legend'] as $code => $description) {
                $formattedData['comments'][] = [$code, $description];
            }
        }
        
        $apiData = $formattedData;
    } else {
        $formattedData = [
            'title' => 'Interim Progress Report',
            'report_date' => date('Y-m-d'),
            'headers' => $apiData['headers'] ?? [],
            'data' => $apiData['data'] ?? [],
            'comments' => [],
            'multiple_dates_available' => false,
            'selected_date' => [],
            'available_dates' => [],
            'comment_legend' => []
        ];
        
        if (isset($apiData['data']) && is_array($apiData['data'])) {
            $classData = [];
            $comments = [];
            
            foreach ($apiData['data'] as $row) {
                if (is_array($row)) {
                    if (count($row) === 2 && 
                        is_string($row[0]) && 
                        is_string($row[1]) && 
                        strlen(trim($row[0])) <= 3 && 
                        strlen(trim($row[1])) > 5 &&
                        !in_array(trim($row[0]), ['Course', 'Comment']) && 
                        !preg_match('/\d{2}\/\d{2}\/\d{4}/', $row[0])
                    ) {
                        $comments[] = $row;
                    } else {
                        $classData[] = $row;
                    }
                }
            }
            
            $formattedData['data'] = $classData;
            $formattedData['comments'] = $comments;
        }
        
        $apiData = $formattedData;
    }
} else if ($apiType === 'assignments') {
    if (isset($apiData['classes']) && is_array($apiData['classes'])) {
        foreach ($apiData['classes'] as $classCode => &$item) {
            $classDetailData = [
                'class_code' => $classCode,
                'class_name' => $item['class_name'] ?? $classCode,
                'average' => $item['average'] ?? 'N/A',
                'dropped' => $item['dropped'] ?? false,
                'dropped_date' => $item['dropped_date'] ?? null,
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

// Add type to response for frontend identification
$originalType = $_GET['type'] ?? 'assignments';
$apiData['api_type'] = $originalType;

echo json_encode($apiData);
exit;
?>