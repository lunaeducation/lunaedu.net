<?php

// old signin page 

session_start();

if(isset($_SESSION['id'])) {
    header("Location: /user");
    exit;
}

require_once("_backend-libs.php");

$enteredId  = $_POST['id'] ?? '';
$enteredPass = $_POST['pass'] ?? '';
$selectedUrl = '';

$districtsFile = __DIR__ . '/backends/districts.json';
$districtsList = [];
if (file_exists($districtsFile)) {
    $content = file_get_contents($districtsFile);
    $districtsList = json_decode($content, true);
    if (!is_array($districtsList)) $districtsList = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['district'], $_POST['id'], $_POST['pass'])) {
    $selectedDistrictIndex = (int)$_POST['district'];
    if (isset($districtsList[$selectedDistrictIndex])) {
        $selectedUrl = $districtsList[$selectedDistrictIndex]['url'];
        $_SESSION["url"] = $selectedUrl;
    } else {
        $selectedUrl = '';
    }
    
    $_SESSION["id"] = strtoupper(trim($_POST['id']));
    $_SESSION["pass"] = $_POST["pass"] ?? '';

    $data = api_call('name', [], true);

    if (!$data) {
        echo "<div class='alert alert-danger'>Invalid username/password. If you believe this to be an error, please contact support.</div>";
    } elseif (isset($data['error'])) {
        echo "<div class='alert alert-danger'>API Error: " . htmlspecialchars($data['error']) . "</div>";
    } elseif (isset($data['name'])) {
        session_regenerate_id(true);
        $_SESSION["name"] = htmlspecialchars($data['name']);

        // Save prefs
        $prefs = loadPrefs($_SESSION["id"]);
        $prefs['name'] = $_SESSION["name"];
        if (!isset($prefs['theme'])) $prefs['theme'] = 'dark';
        if (!isset($prefs['dashwelcome'])) $prefs['dashwelcome'] = '1';
        if (!isset($prefs['ads'])) $prefs['ads'] = '0';

        savePrefs($_SESSION["id"], $prefs);

        header("Location: /dash");
        exit;
    } else {
        echo "<div class='alert alert-warning'>Unexpected API response. Please try again.</div>";
    }
}
include_once("_h.php");

?>

<form action="signin" method="POST" class="text-white p-4 rounded-3 border border-secondary bg-dark shadow-sm" style="max-width: 420px; margin: auto;">
  <h3 class="text-center mb-4">Login to Łuna</h3>

<!-- Search input -->
<div class="mb-3">
  <label for="districtSearch" class="form-label">Search District</label>
  <input type="text" id="districtSearch" class="form-control" placeholder="Type to filter districts...">
</div>

<!-- District Select -->
<div class="mb-3">
  <label for="districtSelect" class="form-label">Select Your District</label>
  <select id="districtSelect" name="district" class="form-select" size="6" required>
    <option value="" disabled <?= !isset($_POST['district']) ? 'selected' : '' ?>>-- Choose district --</option>
  </select>
</div>

<script>
  const districtsList = <?php
    echo json_encode(array_map(function($d, $i) {
      return ['name' => $d['name'], 'index' => $i];
    }, $districtsList, array_keys($districtsList)));
  ?>;

  const districtSelect = document.getElementById('districtSelect');
  const searchInput = document.getElementById('districtSearch');

  const selectedDistrictIndex = <?= isset($_POST['district']) ? (int)$_POST['district'] : 'null' ?>;

  function rebuildOptions(filter) {
    districtSelect.innerHTML = '<option value="" disabled>-- Choose district --</option>';

    districtsList.forEach(district => {
      if (district.name.toLowerCase().includes(filter.toLowerCase())) {
        const option = document.createElement('option');
        option.value = district.index;
        option.textContent = district.name;
        if (district.index === selectedDistrictIndex) {
          option.selected = true;
        }
        districtSelect.appendChild(option);
      }
    });

    if (districtSelect.options.length === 1) {
      const noOption = document.createElement('option');
      noOption.textContent = 'No matching districts';
      noOption.disabled = true;
      districtSelect.appendChild(noOption);
    }
  }

  rebuildOptions('');

  searchInput.addEventListener('input', () => {
    rebuildOptions(searchInput.value);
  });
</script>

<p class="text-left text-muted"><small><i>Don't see your district? You can add it to Łuna <a href="/district-add">here</a>.</i></small></p>

    <div class="alert alert-warning text-center">Łuna is currently in beta. If you encounter any bugs or have feature suggestions, please send an email.</div>

  <!-- User ID -->
  <div class="mb-3">
    <input type="text" class="form-control bg-dark text-white border-secondary"
           name="id" placeholder="User ID"
           value="<?= htmlspecialchars($enteredId) ?>" required>
  </div>

  <!-- Password -->
  <div class="mb-3">
    <div class="input-group">
      <input type="password" class="form-control bg-dark text-white border-secondary" 
             name="pass" id="passwordInput" placeholder="Password" 
             value="<?= htmlspecialchars($enteredPass) ?>" required>
      <button class="btn btn-outline-secondary border-secondary text-white" type="button" onclick="togglePassword()" id="togglePasswordBtn">
        <i class="bi bi-eye"></i>
      </button>
    </div>
  </div>

  <div class="d-grid">
    <button type="submit" class="btn btn-primary fw-bold">Sign In &raquo;</button>
  </div>
</form>
    
<script>
  function togglePassword() {
    const passField = document.getElementById('passwordInput');
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const isHidden = passField.type === 'password';

    passField.type = isHidden ? 'text' : 'password';
    toggleBtn.innerHTML = isHidden ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
  }
</script>

<?php include_once("_f.php"); ?>