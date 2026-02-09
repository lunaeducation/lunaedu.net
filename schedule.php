<?php
session_start();
include_once("_h.php");
require_once("_backend-libs.php");

if (empty($_SESSION["name"])) {
    header("Location: /signin");
    exit;
}
?>

<div id="info-loading">
    <div id="loader" class="d-flex align-items-center gap-2 my-4">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...<br>
        Please be patient.</span>
      </div>
      <p class="mb-0">Loading...<br>
        Please be patient.</p>
    </div>
</div>

<div id="info-table"></div>

<script>
  window.addEventListener('DOMContentLoaded', () => {
    fetch('backends/schedule-backend')
      .then(res => res.text())
      .then(html => {
        document.getElementById('info-loading').style.display = 'none';
        document.getElementById('info-table').innerHTML = html;
      })
      .catch(() => {
        document.getElementById('info-loading').innerHTML = '<div class="alert alert-danger">Failed to load schedule.</div>';
      });
  });
</script>

<?php include_once("_f.php"); ?>