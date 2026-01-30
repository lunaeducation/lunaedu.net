<?php
session_start();
header('Content-Type: application/json');

require_once(__DIR__ . '/../_backend-libs.php');

$contacts = api_call("contact");

if (!isset($contacts['teachers']) || !is_array($contacts['teachers'])) {
    echo json_encode(["teachers" => []]);
    exit;
}

$unique = [];
$result = [];

foreach ($contacts['teachers'] as $item) {
    $teacher = trim($item['teacher'] ?? '');
    $email   = strtolower(trim($item['email'] ?? ''));
    $subject = trim($item['subject'] ?? '');

    if ($teacher === '' || $teacher === 'Staff' || $email === '') {
        continue;
    }

    $key = $teacher . '|' . $email;
    if (!isset($unique[$key])) {
        $unique[$key] = [
            "teacher" => $teacher,
            "email"   => $email,
            "subjects" => []
        ];
    }

    if (!in_array($subject, $unique[$key]["subjects"])) {
        $unique[$key]["subjects"][] = $subject;
    }
}

foreach ($unique as $t) {
    $result[] = $t;
}

echo json_encode(["teachers" => $result]);
exit;
