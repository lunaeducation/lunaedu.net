<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

session_start();

$isInApp = isset($_COOKIE['isInApp']) && $_COOKIE['isInApp'] === 'true';

$logout_token = md5(uniqid(rand(), true));

$_SESSION = array();

if (session_status() === PHP_SESSION_ACTIVE) {
    session_unset();
    session_destroy();
}

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 3600,
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );
}

setcookie('PHPSESSID', '', time() - 3600, '/');

// Capacitor app i was going to make, but didnt have enough users to justify spending 100usd on an apple developer account -_-
if ($isInApp) {
include_once "../_h.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signing out...</title>
<style>
html, body {
  margin: 0;
  padding: 0;
  height: 100%;
  background: #121217;
  overflow: hidden;
}

#loaderOverlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.65);
  backdrop-filter: blur(6px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

.loader-content {
  text-align: center;
}

.spinner-border {
  width: 3rem;
  height: 3rem;
  border: 0.25em solid currentColor;
  border-right-color: transparent;
  border-radius: 50%;
  animation: spin .75s linear infinite;
  color: #4e5dff;
  margin-bottom: 1.5rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

#loaderOverlay h3 {
  color: #fff;
  margin-bottom: 0.5rem;
  font-size: 1.5rem;
  font-weight: 600;
}

.status-text {
  color: #9ea2b3;
  font-size: 1rem;
}
</style>

</head>
<body>

<div id="loaderOverlay">
  <div class="loader-content">
    <div class="spinner-border text-light" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <h3 id="loaderTitle"></h3>
    <div class="status-text" id="loaderStatus"></div>
  </div>
</div>

    <script>
    (async function() {
        //const statusText = document.getElementById('statusText');
        
        console.log('Logout: Clearing saved credentials...');
        //statusText.textContent = 'Clearing saved data';
        
        try {
            await new Promise(resolve => setTimeout(resolve, 200));
            
            if (window.Capacitor && window.Capacitor.Plugins && window.Capacitor.Plugins.Preferences) {
                const { Preferences } = window.Capacitor.Plugins;
                
                await Preferences.remove({ key: 'luna_id' });
                await Preferences.remove({ key: 'luna_pass' });
                await Preferences.remove({ key: 'luna_url' });
                await Preferences.remove({ key: 'luna_name' });
                
                console.log('All saved credentials cleared');
                statusText.textContent = 'Credentials cleared';
            } else {
                console.log('Capacitor Preferences not available, skipping...');
            }
        } catch (error) {
            console.error('Error clearing credentials:', error);
        }
        
        setTimeout(() => {
            window.location.href = '/signin';
        }, 500);
    })();
    </script>
</body>
</html>
<?php
} else {
    header("Location: /");
    exit;
}
?>