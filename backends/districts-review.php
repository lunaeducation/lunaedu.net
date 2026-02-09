<?php
session_start();
include_once(__DIR__ . "/../_h.php");

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: /backends/");
    exit;
}

$pendingFile = __DIR__ . '/pending_districts.json';
$districtsFile = __DIR__ . '/districts.json';

$pendingDistricts = [];
if (file_exists($pendingFile)) {
    $content = file_get_contents($pendingFile);
    $pendingDistricts = json_decode($content, true);
    if (!is_array($pendingDistricts)) $pendingDistricts = [];
}

$districts = [];
if (file_exists($districtsFile)) {
    $content = file_get_contents($districtsFile);
    $districts = json_decode($content, true);
    if (!is_array($districts)) $districts = [];
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $url = $_POST['url'] ?? '';
    $url = trim($url);
    $url = rtrim($url, '/');
    $name = trim($_POST['name'] ?? '');

    if ($url === '') {
        $message = "<div class='alert alert-danger'>Invalid district URL.</div>";
    } elseif (!in_array($url, $pendingDistricts)) {
        $message = "<div class='alert alert-warning'>District URL not found in pending list.</div>";
    } else {
        if ($action === 'accept') {
            if ($name === '') {
                $message = "<div class='alert alert-danger'>Please enter a district name before accepting.</div>";
            } else {
                $exists = false;
                foreach ($districts as $d) {
                    if (strcasecmp($d['url'], $url) === 0) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $districts[] = ['name' => $name, 'url' => $url];
                    file_put_contents($districtsFile, json_encode($districts, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                }
                $pendingDistricts = array_filter($pendingDistricts, fn($u) => $u !== $url);
                file_put_contents($pendingFile, json_encode(array_values($pendingDistricts), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
                $message = "<div class='alert alert-success'>District accepted and saved.</div>";
            }
        } elseif ($action === 'deny') {
            // Remove from pending
            $pendingDistricts = array_filter($pendingDistricts, fn($u) => $u !== $url);
            file_put_contents($pendingFile, json_encode(array_values($pendingDistricts), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $message = "<div class='alert alert-info'>District denied and removed from pending list.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Invalid action.</div>";
        }
    }
}
?>

<style>
  .district-row { margin-bottom: 1rem; padding: 1rem; background: white; border-radius: 0.375rem; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
</style>

<h1 class="mb-4">Pending Districts Review</h1>

<?php if ($message) echo $message; ?>

<?php if (empty($pendingDistricts)): ?>
<div class="alert alert-success">No pending districts to review.</div>
<?php else: ?>

<form method="POST" action="/backends/districts-review.php">
  <?php foreach ($pendingDistricts as $url): ?>
    <div class="district-row row align-items-center">
      <div class="col-md-4 mb-2 mb-md-0">
        <a href="<?= htmlspecialchars($url) ?>" target="_blank" class="btn btn-outline-primary w-100" rel="noopener noreferrer"><?= $url ?></a>
      </div>
      <div class="col-md-4 mb-2 mb-md-0">
        <input type="text" name="name" class="form-control" placeholder="Enter District Name (ISD)">
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button type="submit" name="action" value="accept" class="btn btn-success flex-fill" onclick="return confirm('Accept this district?')">Accept</button>
        <button type="submit" name="action" value="deny" class="btn btn-danger flex-fill" onclick="return confirm('Deny and remove this district?')">Deny</button>
        <input type="hidden" name="url" value="<?= htmlspecialchars($url) ?>">
      </div>
    </div>
  <?php endforeach; ?>
</form>

<?php endif; ?>

<?php include_once(__DIR__ . "/../_f.php"); ?>
