<?php
session_start();
include_once("_h.php");
?>

<form id="districtForm" class="text-white p-4 rounded-3 border border-secondary bg-dark shadow-sm" style="max-width: 420px; margin: auto;">

  <p class="text-center"><button class="btn btn-outline-primary btn-sm" onclick="history.back();">&laquo; Go back</button></p>

  <h3 class="text-center mb-4">Add a District</h3>

  <p class="text-center">To add a district to Łuna, follow the steps below.</p>
  
  <ol>
    <li>Go to your student portal (like myKatyCloud), where you normally visit HomeAccessCenter from.</li>
    <br>
    <li>Copy the URL, but without the slashes. Use the photo below as a guide.<br><img src="/img/districtref.png" style="width:100%"></li>
    <br>
    <li>Paste the URL into the box below, and our system will check if it's valid.</li>
  </ol>
  
  <p class="text-left text-muted"><small><i>If your district doesn't use HomeAccessCenter (or you have trouble signing in), please <a href="/op/contact">contact support</a>.</i></small></p>

  <!-- URL -->
  <div class="mb-3">
    <label for="districtUrl" class="form-label">HomeAccessCenter Link</label>
    <div class="input-group">
      <span class="input-group-text bg-secondary text-white border-0">https://</span>
      <input type="text" id="districtUrl" class="form-control border-secondary" 
             name="url" placeholder="homeaccess.katyisd.org"
             required>
    </div>
  </div>

  <div class="d-grid">
    <button type="button" id="addButton" class="btn btn-success fw-bold">Add &raquo;</button>
  </div>
  
  <div id="loader" class="d-none align-items-center gap-3 my-4">
    <div class="spinner-border" role="status" aria-hidden="true"></div>
    <span>Checking district link... Please wait.</span>
  </div>

  <div id="statusMessage" class="mt-3"></div>

</form>

<script>
function handleFormSubmission() {
    const loader = document.getElementById('loader');
    const statusMessage = document.getElementById('statusMessage');
    const inputVal = document.getElementById('districtUrl').value.trim();

    statusMessage.innerHTML = '';
    loader.classList.remove('d-none');
    loader.classList.add('d-flex');

    fetch('/backends/district-add-backend?url=' + encodeURIComponent(inputVal))
      .then(res => res.json())
      .then(data => {
        loader.classList.remove('d-flex');
        loader.classList.add('d-none');

        let alertClass = data.success ? 'alert-success' : 'alert-danger';

        statusMessage.innerHTML = `<div class="alert ${alertClass}" role="alert">${data.message}</div>`;
      })
      .catch(err => {
        loader.classList.remove('d-flex');
        loader.classList.add('d-none');
        statusMessage.innerHTML = `<div class="alert alert-danger" role="alert">Error checking district link.</div>`;
        console.error(err);
      });
}

document.getElementById('addButton').addEventListener('click', handleFormSubmission);

document.getElementById('districtUrl').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        handleFormSubmission();
    }
});
</script>

<?php include_once("_f.php"); ?>
