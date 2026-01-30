<?php
session_start();
require_once(__DIR__ . '/../_backend-libs.php');

if (empty($_SESSION["name"])) {
    http_response_code(403);
    echo "<div class='alert alert-danger'>Not logged in.</div>";
    exit;
}

$infoData = api_call('schedule', [], false);

if (!$infoData || !is_array($infoData) || empty($infoData['schedule'])) {
    echo "<div class='alert alert-warning'>Unable to load schedule.</div>";
    exit;
}

// todo: move rendering to frontend
echo "<h2 class='mb-4'>" . htmlspecialchars($infoData['term'] ?? '') . "</h2>";

echo '<div class="card">';
echo '<div class="card-body p-0">';
echo '<div class="table-responsive">';
echo '<table class="table table-striped table-hover mb-0">';
echo '<thead class="table-dark">
        <tr>
            <th scope="col" class="fw-semibold">Period</th>
            <th scope="col" class="fw-semibold">Course</th>
            <th scope="col" class="fw-semibold">Teacher</th>
            <th scope="col" class="fw-semibold">Room</th>
        </tr>
      </thead>
      <tbody>';

foreach ($infoData['schedule'] as $class) {
    echo '<tr>';
    echo '<td class="fw-medium">' . htmlspecialchars($class['period'] ?? '') . '</td>';
    echo '<td><strong>' . htmlspecialchars($class['description'] ?? '') . '</strong></td>';
    echo '<td>' . htmlspecialchars($class['teacher'] ?? '') . '</td>';
    echo '<td>' . htmlspecialchars($class['room'] ?? '') . '</td>';
    echo '</tr>';
}

echo '</tbody></table></div></div></div>';

if (!empty($infoData['schedule'])) {
    $totalClasses = count($infoData['schedule']);
    echo '<div class="mt-3 text-muted small">';
    echo '<i class="bi bi-info-circle me-1"></i>';
    echo "Showing {$totalClasses} classes for " . htmlspecialchars($infoData['term'] ?? 'current term');
    echo '</div>';
}
?>