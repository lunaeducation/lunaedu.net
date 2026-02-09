<?php

session_start();

if (isset($_SESSION['id'])) {
    header("Location: /dash");
    exit;
}

if (isset($_POST['demo_login'])) {
    $_SESSION['name'] = "John Doe";
    $_SESSION['user'] = "D0000001";
    $_SESSION['pass'] = 0;
    $_SESSION['url'] = "demodist";
    $_SESSION['id'] = "D0000001";
    header("Location: /dash");
    exit;
}

include_once("_h.php");
require_once("_backend-libs.php");

$districtsFile = __DIR__ . '/backends/districts.json';
$districtsList = [];
if (file_exists($districtsFile)) {
    $content = file_get_contents($districtsFile);
    $districtsList = json_decode($content, true);
    if (!is_array($districtsList)) $districtsList = [];
}
?>
<style>
  .navbar {
      display:none;
  }
  
  .footer {
      display: none;
  }

    body {
      background-color: #121217;
      color: #e8e8e8;
    }

  body::before {
    display: none !important;
  }

  .signin-container {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1rem;
  }

  .signin-wrapper {
    width: 100%;
    max-width: 480px;
    background: rgba(20, 22, 28, 0.9);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 16px;
    padding: 2rem;
    //box-shadow: 0 8px 30px rgba(0,0,0,0.45);
    backdrop-filter: blur(12px);
  }

  .signin-header h1 {
    font-size: 1.7rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 0.25rem;
  }
  .signin-header p {
    color: #9ea2b3;
  }

  /* --- Inputs --- */
  .form-floating {
    margin-bottom: 1rem;
  }
  .form-control,
  .form-select {
    background: rgba(40,42,48,0.9) !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    color: #e0e0e0 !important;
  }
  .form-control:focus,
  .form-select:focus {
    background: rgba(40,42,48,1) !important;
    border-color: #4e5dff !important;
    box-shadow: 0 0 0 3px rgba(78,93,255,0.25) !important;
  }

  /* Selectpicker */
  .bootstrap-select .dropdown-toggle {
    background: rgba(40,42,48,0.9) !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    color: #e0e0e0 !important;
  }
  .bootstrap-select .dropdown-menu {
    background: #1a1c22 !important;
    border: 1px solid #2a2c32 !important;
  }
  .bootstrap-select .dropdown-item {
    color: #ddd !important;
  }
  .bootstrap-select .dropdown-item:hover {
    background: #2d2f36 !important;
  }

  /* Password toggle */
  .password-group {
    position: relative;
  }
  .password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #888;
    cursor: pointer;
  }
  .password-toggle:hover {
    color: #fff;
  }

.btn-signin {
    background: rgba(78, 93, 255, 0.15); /* subtle blue tint */
    border: 1px solid rgba(78, 93, 255, 0.35);
    color: #cfd4ff;
    padding: 0.9rem;
    border-radius: 10px;
    font-weight: 600;
    transition: background .2s ease, border-color .2s ease;
    box-shadow: none; /* remove glow */
}

.btn-signin:hover {
    background: rgba(78, 93, 255, 0.25);
    border-color: rgba(78, 93, 255, 0.55);
    transform: none !important; /* no lift animation */
}


  .btn-demo {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    color: #e8e8e8;
    padding: .8rem;
    border-radius: 10px;
    font-weight: 600;
  }
  .btn-demo:hover {
    background: rgba(255,255,255,0.15);
  }

  .btn-classlink {
    background: rgba(0, 122, 255, 0.15);
    border: 1px solid rgba(0, 122, 255, 0.3);
    color: #4da6ff;
    padding: .8rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .btn-classlink:hover {
    background: rgba(0, 122, 255, 0.25);
    color: #66b3ff;
  }

  .info-box {
    background: rgba(78,93,255,0.12);
    border: 1px solid rgba(78,93,255,0.25);
    border-radius: 10px;
    padding: .9rem;
    font-size: .875rem;
    color: #c6caff;
  }
 
    .success-box {
        border-radius: 10px !important;
        padding: .9rem !important;
        font-size: .875rem !important;
    }
 
  .divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 1.5rem 0;
    color: #777;
    font-size: .9rem;
  }
  .divider::before,
  .divider::after {
    content: "";
    flex: 1;
    border-bottom: 1px solid rgba(255,255,255,0.1);
  }
  .divider span {
    padding: 0 .75rem;
  }

  .terms-text {
    color: #888;
    font-size: .8rem;
    margin-top: 1.25rem;
    text-align: center;
  }

  .alert-danger {
    background: rgba(255,0,60,0.12);
    border: 1px solid rgba(255,0,60,0.25);
    color: #ff8696;
  }

  #loaderOverlay {
    background: rgba(0,0,0,0.65);
    backdrop-filter: blur(6px);
  }

  #loaderOverlay .loader-content {
    text-align: center;
  }

  #loaderOverlay .spinner-border {
    width: 3rem;
    height: 3rem;
    margin-bottom: 1.5rem;
  }

  #loaderOverlay h3 {
    color: #fff;
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
  }

  #loaderOverlay .status-text {
    color: #9ea2b3;
    font-size: 1rem;
  }
</style>


<!-- Loader overlay -->
<div id="loaderOverlay"
     class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
     style="z-index: 9999;">
  <div class="loader-content">
    <div class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <h3 id="loaderTitle">Signing in...</h3>
    <div class="status-text" id="loaderStatus"></div>
  </div>
</div>

<div class="signin-container">
  <div class="<?= $isInApp ? '' : 'signin-wrapper' ?>">
    <div class="signin-right">
      <div class="text-center mb-4 signin-header">
        <h1>Sign in<?php if (!$isInApp) { ?> to Łuna<?php } ?></h1>
      </div>

      <?php if (isset($_SESSION['signin_error'])): ?>
      <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['signin_error']) ?>
      </div>
      <?php unset($_SESSION['signin_error']); endif; ?>

      <form id="signinForm">
        <!-- District Select -->
        
        <div class="mb-3">
          <select id="districtSelect" name="district" class="selectpicker w-100" data-live-search="true" title="Select your district">
            <?php foreach ($districtsList as $i => $d): ?>
            <option value="<?= $i ?>" data-url="<?= htmlspecialchars($d['url']) ?>">
              <?= htmlspecialchars($d['name']) ?>
            </option>
            <?php endforeach; ?>
          </select>
          <div class="link-text mt-2 text-muted">
            <small><i>Don't see your district? <a href="#" data-bs-toggle="modal" data-bs-target="#districtAddModal">Add it here</a></i></small>
          </div>
        </div>

        <!---<div class="info-box mb-3">
          <strong>Age Verification:</strong> We verify your age using school records. Only users <strong>13+ years old</strong> are permitted. <a href="/op/pvpl">Privacy Policy</a>
        </div>--->

        <!-- User ID -->
        <div class="form-floating">
          <input type="text" class="form-control" name="id" id="idInput" placeholder="User ID" required>
          <label for="idInput">User ID</label>
        </div>

        <!-- Password -->
        <div class="form-floating password-group mb-3">
          <input type="password" class="form-control" name="pass" id="passwordInput" placeholder="Password" required>
          <label for="passwordInput">Password</label>
          <button type="button" class="password-toggle" onclick="togglePassword()" id="togglePasswordBtn">
            <i class="bi bi-eye"></i>
          </button>
        </div>

        <div id="messageContainer"></div>

        <div class="success-box alert-warning alert mb-3">
          <strong>Please help us by answering a short question. It only takes 10 seconds.</strong> If Łuna had a mobile app, would you find it useful? (<a href="https://forms.gle/qxzoqDNz9x2DRAn1A" target="_blank">Answer here</a>)
        </div>

        <div class="d-grid mb-3">
          <button type="submit" class="btn btn-signin">Sign In</button>
        </div>
        
        <?php if (!$isInApp) { ?>
        <div class="divider">
          <span>or</span>
        </div>

        <?php /*<div class="d-grid mb-2">
          <a href="/backends/classlink-login.php" class="btn btn-classlink">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign in with ClassLink
          </a>
        </div> */ ?>

        <div class="d-grid">
          <button type="button" class="btn btn-demo" onclick="demoLogin()">Try Demo</button>
        </div>
        <div class="text-center mt-3 text-bold">
            <b><a href="/" class="text-white text-decoration-none"><i class="bi bi-arrow-left"></i> Back to Home</a></b>
        </div>
        <div class="terms-text">
          By signing in, you agree to our <a href="/op/tos" target="_blank">Terms of Service</a> and <a href="/op/pvpl" target="_blank">Privacy Policy</a>
        </div>
        
        <?php } else { ?>
        <div class="terms-text">
          By signing in, you agree to our <a href="/op/tos">Terms of Service</a> and <a href="/op/pvpl">Privacy Policy</a>
        </div>
        <?php } ?>

      </form>
    </div>
  </div>
</div>

<!-- District Add Modal -->
<div class="modal fade" id="districtAddModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content bg-dark text-white border-secondary">
      
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Add a District</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form id="districtForm" class="text-white">

          <p class="text-center">To add a district to Łuna, follow the steps below.</p>

          <ol>
            <li>Go to your student portal (like myKatyCloud), where you normally visit HomeAccessCenter from.</li>
            <br>
            <li>Copy only the domain of the URL. Use the photo below as a guide.<br>
              <img src="/img/districtref.png" width=100%>
            </li>
            <br>
            <li>Paste the URL into the box below, and our system will check if it's valid.</li>
          </ol>

          <p class="text-left text-muted">
            <small>
              <i>If your district doesn't use HomeAccessCenter (or you have trouble signing in), 
                 please <a href="/op/contact" target="_blank">contact support</a>.
              </i>
            </small>
          </p>

          <!-- URL Input -->
          <div class="mb-3">
            <label for="districtUrl" class="form-label">HomeAccessCenter Link</label>
            <div class="input-group">
              <span class="input-group-text bg-secondary text-white border-0">https://</span>
              <input type="text" id="districtUrl" class="form-control bg-dark text-white border-secondary"
                    name="url" placeholder="homeaccess.katyisd.org" required>
            </div>
          </div>

          <div class="d-grid">
            <button type="button" id="addButton" class="btn btn-success fw-bold">Submit for Review</button>
          </div>

          <div id="loader" class="d-none align-items-center gap-3 my-4">
            <div class="spinner-border" role="status" aria-hidden="true"></div>
            <span>Checking district link... Please wait.</span>
          </div>

          <div id="statusMessage" class="mt-3"></div>

        </form>
      </div>

      <div class="modal-footer border-secondary">
        <button class="btn btn-outline-light" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

<script>
// Pass PHP variable to JavaScript
const isInApp = <?php echo $isInApp ? 'true' : 'false'; ?>;

$(function(){
  $('.selectpicker').selectpicker({
    style: 'bg-dark text-white border-secondary',
    styleBase: 'form-control'
  });
});

// Only auto-fill from saved credentials if in app
if (isInApp) {
    (async function() {
        if (window.Capacitor && window.Capacitor.Plugins.Preferences) {
            try {
                const { Preferences } = window.Capacitor.Plugins;
                
                const savedId = await Preferences.get({ key: 'luna_id' });
                const savedPass = await Preferences.get({ key: 'luna_pass' });
                const savedUrl = await Preferences.get({ key: 'luna_url' });
                
                if (savedId.value) {
                    document.getElementById('idInput').value = savedId.value;
                }
                if (savedPass.value) {
                    document.getElementById('passwordInput').value = savedPass.value;
                }
                if (savedUrl.value) {
                    // Find matching district by URL
                    const districtSelect = document.getElementById('districtSelect');
                    for (let i = 0; i < districtSelect.options.length; i++) {
                        if (districtSelect.options[i].dataset.url === savedUrl.value) {
                            districtSelect.value = districtSelect.options[i].value;
                            $('.selectpicker').selectpicker('refresh');
                            break;
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading saved credentials:', error);
            }
        }
    })();
}

// Check for ClassLink verification on page load
(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const verifyClassLink = urlParams.get('verify_classlink');
    
    if (verifyClassLink === '1') {
        // Show loader
        document.getElementById('loaderOverlay').classList.remove('d-none');
        document.getElementById('loaderTitle').textContent = 'Verifying Credentials...';
        
        let statusIndex = 0;
        const statusMessages = [
            'Connecting to your district...',
            'Verifying your credentials...',
            'Loading your data...'
        ];
        
        const statusInterval = setInterval(() => {
            statusIndex = (statusIndex + 1) % statusMessages.length;
            document.getElementById('loaderStatus').textContent = statusMessages[statusIndex];
        }, 2000);
        
        // Make verification request
        fetch('/backends/classlink-verify-backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            clearInterval(statusInterval);
            
            if (data.success) {
                document.getElementById('loaderStatus').textContent = 'Success! Redirecting...';
                window.location.href = '/dash';
            } else if (data.password_changed) {
                document.getElementById('loaderStatus').textContent = 'Password changed. Redirecting...';
                setTimeout(() => {
                    window.location.href = '/classlink-district-select.php?password_changed=1';
                }, 1000);
            } else {
                document.getElementById('loaderStatus').textContent = 'Error. Redirecting...';
                setTimeout(() => {
                    window.location.href = '/classlink-district-select.php';
                }, 1000);
            }
        })
        .catch(error => {
            clearInterval(statusInterval);
            document.getElementById('loaderStatus').textContent = 'Error occurred. Redirecting...';
            setTimeout(() => {
                window.location.href = '/classlink-district-select.php';
            }, 2000);
        });
    }
})();

function demoLogin() {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '/signin';
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'demo_login';
  input.value = '1';
  form.appendChild(input);
  document.body.appendChild(form);
  form.submit();
}

function togglePassword() {
  const passField = document.getElementById('passwordInput');
  const btn = document.getElementById('togglePasswordBtn');
  const hidden = passField.type === 'password';
  passField.type = hidden ? 'text' : 'password';
  btn.innerHTML = hidden ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
}

document.getElementById('signinForm').addEventListener('submit', async function(e) {
  e.preventDefault();

  const districtSelect = document.getElementById('districtSelect');
  const districtIndex = districtSelect.value;
  const selectedOption = districtSelect.options[districtSelect.selectedIndex];
  const districtUrl = selectedOption?.dataset.url;

  const id = document.getElementById('idInput').value.toUpperCase().trim();
  const password = document.getElementById('passwordInput').value;

  if (!districtIndex) {
    showMessage('Please select a district.', 'danger');
    return;
  }
  if (!districtUrl) {
    showMessage('Invalid district selection.', 'danger');
    return;
  }

  const loader = document.getElementById('loaderOverlay');
  loader.classList.remove('d-none');
  this.style.opacity = '0.7';
  document.getElementById('messageContainer').innerHTML = '';

  try {
    console.log('Attempting login...');
    const resp = await fetch('/backends/signin-backend.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        district: districtIndex,
        districtUrl: districtUrl,
        id: id,
        pass: password
      })
    });
    const data = await resp.json();
    console.log('Login response:', data);
    
    loader.classList.add('d-none');
    this.style.pointerEvents = 'auto';
    this.style.opacity = '1';

    if (data.success) {
      console.log('Login successful!');
      console.log('isInApp:', isInApp);
      console.log('Capacitor available:', !!window.Capacitor);
      console.log('Capacitor.Plugins available:', !!(window.Capacitor && window.Capacitor.Plugins));
      console.log('Preferences available:', !!(window.Capacitor && window.Capacitor.Plugins && window.Capacitor.Plugins.Preferences));
      
      if (isInApp && window.Capacitor && window.Capacitor.Plugins && window.Capacitor.Plugins.Preferences) {
        console.log('Attempting to save credentials...');
        try {
          const { Preferences } = window.Capacitor.Plugins;
          
          await Preferences.set({ key: 'luna_id', value: id });
          console.log('Saved luna_id:', id);
          
          await Preferences.set({ key: 'luna_pass', value: password });
          console.log('Saved luna_pass');
          
          await Preferences.set({ key: 'luna_url', value: districtUrl });
          console.log('Saved luna_url:', districtUrl);
          
          // Also save name if available in response
          if (data.name) {
            await Preferences.set({ key: 'luna_name', value: data.name });
            console.log('Saved luna_name:', data.name);
          }
          
          console.log('All credentials saved successfully');
          
          // Verify they were saved
          const verify = await Preferences.get({ key: 'luna_id' });
          console.log('Verification - luna_id retrieved:', verify.value);
          
        } catch (error) {
          console.error('ERROR saving credentials:', error);
          alert('Warning: Failed to save credentials. Error: ' + error.message);
        }
      } else {
        console.log('NOT saving credentials - isInApp:', isInApp);
      }
      
      // Small delay to ensure save completes
      setTimeout(() => {
        window.location.href = data.redirect || '/dash';
      }, 100);
      
    } else if (data.needs_password_migration) {
      showPasswordMigrationModal(data.temp_id, data.temp_password, data.temp_url);
    } else {
      showMessage(data.error || 'Login failed', 'danger');
    }
  } catch (err) {
    loader.classList.add('d-none');
    this.style.pointerEvents = 'auto';
    this.style.opacity = '1';
    showMessage('Network error. Please try again.', 'danger');
    console.error('Login error:', err);
  }
});

function showMessage(msg, type) {
  const container = document.getElementById('messageContainer');
  container.innerHTML = `
    <div class="alert alert-${type} alert-dismissible fade show mb-3" role="alert">
      ${msg}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;
}

function showPasswordMigrationModal(id, newPass, url) {
  const html = `
    <div class="modal fade" id="passwordMigrationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Password Changed</h5>
          </div>
          <div class="modal-body">
            <p class="mb-3">Your password has changed since your last login. Please enter your <strong>old password</strong> to update cached data.</p>
            <div class="form-floating">
              <input type="password" class="form-control" id="oldPasswordInput" placeholder="Old password" required>
              <label for="oldPasswordInput">Old Password</label>
            </div>
            <div id="migrationError" class="mt-3"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-signin w-100"
                    onclick="migratePassword('${id}', '${newPass}', '${url}')">Continue</button>
          </div>
        </div>
      </div>
    </div>`;
  document.body.insertAdjacentHTML('beforeend', html);
  const modal = new bootstrap.Modal(document.getElementById('passwordMigrationModal'));
  modal.show();
}

async function migratePassword(tempId, tempPassword, tempUrl) {
  const oldPass = document.getElementById('oldPasswordInput').value;
  if (!oldPass) {
    document.getElementById('migrationError').innerHTML =
      '<div class="alert alert-danger">Please enter your old password.</div>';
    return;
  }
  try {
    const resp = await fetch('/backends/migrate-password.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        old_password: oldPass,
        new_password: tempPassword,
        id: tempId,
        district_url: tempUrl
      })
    });
    const data = await resp.json();
    if (data.success) {
      window.location.href = data.redirect || '/dash';
    } else {
      document.getElementById('migrationError').innerHTML =
        `<div class="alert alert-danger">${data.error || 'Migration failed.'}</div>`;
    }
  } catch (err) {
    document.getElementById('migrationError').innerHTML =
      '<div class="alert alert-danger">Network error. Please try again.</div>';
    console.error('Migration error:', err);
  }
}

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
        statusMessage.innerHTML = `
          <div class="alert ${alertClass}" role="alert">${data.message}</div>
        `;
      })
      .catch(err => {
        loader.classList.remove('d-flex');
        loader.classList.add('d-none');
        statusMessage.innerHTML = `
          <div class="alert alert-danger" role="alert">Error checking district link.</div>
        `;
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
