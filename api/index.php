<?php

require_once 'endpoints.php';
require_once 'helpers.php';

ini_set('log_errors', 1);
ini_set('error_log', 'sch.log');

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);

if (strpos($path, '/api/') === 0) {
    $endpoint = substr($path, 5); // Remove '/api/' prefix
    
    //$endpoint = $path;
    
    switch($endpoint) {
        case 'name':
            getName();
            break;
        case 'attendance':
            getAttendance();
            break;
        case 'assignments':
            getAssignments();
            break;
        case 'info':
            getInfo();
            break;
        case 'averages':
            getAverages();
            break;
        case 'classes':
            getClasses();
            break;
        case 'reportcard':
            getReport();
            break;
        case 'ipr':
            getProgressReport();
            break;
        case 'transcript':
            getTranscript();
            break;
        case 'schedule':
            getSchedule();
            break;
        case 'rank':
            getRank();
            break;
        case 'help':
            showHelp();
        case 'contact':
            getTeacherEmail();
            break;
        case '/':
            showHelp();
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
} else {
    showHelp();
}

function showHelp() {
    $message = [];
    header('Content-Type: application/json');
    echo json_encode($message);
}
?>