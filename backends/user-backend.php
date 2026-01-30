<?php

// the first page that fetched data from the API.
// originally on the settings page (which is why it is still called /user) but removed

session_start();
require_once(__DIR__ . '/../_backend-libs.php');

if (empty($_SESSION["name"])) {
    http_response_code(403);
    echo "<div class='alert alert-danger'>Not logged in.</div>";
    exit;
}

$infoData = api_call('info');

if (!$infoData || !is_array($infoData)) {
    echo "<div class='alert alert-warning'>Unable to load student info.</div>";
    exit;
}

echo '<table class="table table-bordered table-striped">';
echo '<tr><th>Name</th><td>' . htmlspecialchars($infoData['name'] ?? '') . '</td></tr>';
echo '<tr><th>Grade</th><td>' . htmlspecialchars($infoData['grade'] ?? '') . '</td></tr>';
echo '<tr><th>School</th><td>' . htmlspecialchars($infoData['school'] ?? '') . '</td></tr>';
echo '<tr><th>Date of Birth</th><td>' . htmlspecialchars($infoData['dob'] ?? '') . '</td></tr>';
echo '<tr><th>Counselor</th><td>' . htmlspecialchars($infoData['counselor'] ?? '') . '</td></tr>';
echo '<tr><th>Language</th><td>' . htmlspecialchars($infoData['language'] ?? '') . '</td></tr>';
echo '<tr><th>Cohort Year</th><td>' . htmlspecialchars($infoData['cohort-year'] ?? '') . '</td></tr>';
echo '</table>';
