<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$maintenance = false;

$isInApp = isset($_COOKIE['isInApp']);

if (isset($_POST['bypass_maintenance'])) {
    $_SESSION['maintenance_bypass'] = true;
}

if ($maintenance && empty($_SESSION['maintenance_bypass'])) {
    echo "
<!DOCTYPE html>
<html data-bs-theme='";

if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'dark';
}

echo $bootstrapMode;

echo "'>
<head>
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\" />

    <meta name=\"apple-mobile-web-app-capable\" content=\"yes\">
    <meta name=\"apple-mobile-web-app-status-bar-style\" content=\"black-translucent\">
    <meta name=\"apple-mobile-web-app-title\" content=\"Łuna\">
    <meta name=\"mobile-web-app-capable\" content=\"yes\">
    <meta name=\"robots\" content=\"noarchive\">

    <meta name=\"robots\" content=\"noindex, nofollow\" />

    <style>
        :root[data-bs-theme=\"dark\"] {
            --bs-body-bg: #121212;
            --bs-body-color: #f8f9fa;
            --bs-card-bg: #1e1e1e;
            --bs-border-color: #2d2d2d;
            --bs-primary: #6c5ce7;
            --bs-code-color: #ffffff;
        }
        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }
    </style>

    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css\" 
          rel=\"stylesheet\" crossorigin=\"anonymous\">
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css\">

    <title>Łuna :: Redirection</title>
    <meta name=\"darkreader-lock\">
</head>

<body class=\"d-flex align-items-center justify-content-center vh-100\">

    <div class=\"container\">
        <div class=\"row justify-content-center\">
            <div class=\"col-md-6\">
                <div class=\"card shadow-sm border-0 text-center p-4\">
                    <div class=\"card-body\">

                        <h1 class=\"h3 mb-3 fw-bold\">Temporary Shutdown</h1>

                        <p class=\"mb-2 text-muted\">
                            Will be back soon (within 1-2 days). Apologies for the downtime.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>";

    exit;
}

date_default_timezone_set('US/Central');

define('SCRIPT_START_TIME', microtime(true));

include_once("_backend-libs.php");
include_once("_0b-f.php");

if (isset($_SESSION['id'])) {
    // Regenerate session ID periodically to prevent session hijacking
    $regenerateTime = 28800; // 5m
    
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
        session_regenerate_id(true);
    } elseif (time() - $_SESSION['last_regeneration'] > $regenerateTime) {
        $_SESSION['last_regeneration'] = time();
        session_regenerate_id(true);
    }
    
    // Validate session integrity
    /*if (isset($_SESSION['user_agent'])) {
        if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            // Possible session hijacking - destroy session
            session_unset();
            session_destroy();
            session_start();
            session_regenerate_id(true);
            header("Location: /signin");
            exit;
        }
    } else {
        // Store user agent for future validation
    }*/

    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
}

if (empty($_SESSION["name"])) {
    $uri = $_SERVER['REQUEST_URI'];

    $restricted_urls = [
        '/dash',
        '/grades',
        '/grades-detail',
        '/calendars',
        '/attendance',
        '/user',
        '/schedule'
    ];

    if (in_array($uri, $restricted_urls)) {
        header('Location: /');
        exit;
    }
}

// Default to dark if not set
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'dark';
}

if (!isset($_SESSION['dashwelcome'])) {
    $_SESSION['dashwelcome'] = '1';
}

if (!isset($_SESSION['ads'])) {
    $_SESSION['ads'] = '0';
}

$leaderboard_prefs = $leaderboard_prefs ?? [];

// "removed" leaderboard. idk.
$_SESSION['leaderboard_show_nav'] = false;
$_SESSION['leaderboard_enabled'] = false;

// _f-prefs
if (empty($_SESSION['id'])) {
    
} else {

    $prefs = loadPrefs($_SESSION['id']);

    // init lb set if they don't exist
    if (!isset($prefs['leaderboard'])) {
        $prefs['leaderboard'] = [
            'enabled' => false,
            'participate' => false,
            'alias' => '',
            'show_nav' => true
        ];
        savePrefs($_SESSION['id'], $prefs);
    }

    if (isset($prefs['theme'])) $_SESSION['theme'] = $prefs['theme'];
    if (isset($prefs['dashwelcome'])) $_SESSION['dashwelcome'] = $prefs['dashwelcome'];
    if (isset($prefs['ads'])) $_SESSION['ads'] = $prefs['ads'];

    // Also set lb session var if they exist
    if (isset($prefs['leaderboard'])) {
        $_SESSION['leaderboard_enabled'] = $prefs['leaderboard']['enabled'] ?? false; 
        $_SESSION['leaderboard_participate'] = $prefs['leaderboard']['participate'] ?? false;
        $_SESSION['leaderboard_alias'] = $prefs['leaderboard']['alias'] ?? '';
        $_SESSION['leaderboard_show_nav'] = $prefs['leaderboard']['show_nav'] ?? true;
    }
}

function isActive($path) {
    return $_SERVER['REQUEST_URI'] === $path ? 'active' : '';
}

function isActiveMobile($path) {
    return $_SERVER['REQUEST_URI'] === $path ? '-fill' : '';
}


function isCurrent($path) {
    return $_SERVER['REQUEST_URI'] === $path ? '<span class="visually-hidden">(current)</span>' : '';
}

function isMobileDevice() {
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    return preg_match('/(android|iphone|ipad|ipod|blackberry|iemobile|opera mini|mobile)/i', $ua);
}

function isChromebook() {
    return (stripos($_SERVER['HTTP_USER_AGENT'], 'CrOS') !== false);
}

// Load themes configuration
$themesJson = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/themes.json');
$themesData = json_decode($themesJson, true);

// Get the correct Bootstrap mode from the theme
$currentThemeKey = $_SESSION['theme'] ?? 'dark';
$bootstrapMode = isset($themesData['themes'][$currentThemeKey]) 
    ? $themesData['themes'][$currentThemeKey]['mode'] 
    : 'dark';


?>

<!---

    ░██                                          
    ░██                                          
    ░██ ░███    ░██    ░██ ░████████   ░██████   
    ░█████      ░██    ░██ ░██    ░██       ░██  
  ░████         ░██    ░██ ░██    ░██  ░███████  
░██ ░██         ░██   ░███ ░██    ░██ ░██   ░██  
    ░██████████  ░█████░██ ░██    ░██  ░█████░██ 


--->

<!DOCTYPE html>
<html data-bs-theme="<?php echo $bootstrapMode; ?>">
<head>

<?php
$current = basename($_SERVER['PHP_SELF']);

$titles = [
    'dash.php'          => 'Łuna :: Dashboard',
    'panel.php'         => 'Łuna :: Admin Panel',
    'grades.php'        => 'Łuna :: Grades',
    'schedule.php'      => 'Łuna :: Schedule',
    'user.php'          => 'Łuna :: Settings',
    'signin.php'        => 'Łuna :: Sign In',
    'district-add.php'  => 'Łuna :: Add District',
    'tos.php'           => 'Łuna :: Terms of Service',
    'pvpl.php'          => 'Łuna :: Privacy Policy',
    '404.php'           => 'Łuna :: 404',
    '403.php'           => 'Łuna :: 404',
    'lb.php'            => 'Łuna :: Leaderboard',
    'about.php'         => 'Łuna :: About',
    'credits.php'       => 'Łuna :: Credits',
    'calendars.php'     => 'Łuna :: Calendars',
    'attendance.php'    => 'Łuna :: Attendance',
    'index.php'         => 'Łuna'
];

$title = $titles[$current] ?? 'Łuna';

if ($current === 'grades-detail.php' && isset($_GET['name'])) {
    $courseName = htmlspecialchars(urldecode($_GET['name']));
    $title = "Łuna :: {$courseName}";
}

$ogTitle = $title;

echo "<title>{$title}</title>";
?>

    <!-- NProgress CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />

    <!-- NProgress JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/img/logo2.png">

    <link rel="icon" href="/img/logo2-d.svg" media="(prefers-color-scheme: light)">
    <link rel="icon" href="/img/logo2-l.svg" media="(prefers-color-scheme: dark)">
    <link rel="apple-touch-icon" href="/img/logo2-l.png" media="(prefers-color-scheme: light)">
    <link rel="apple-touch-icon" href="/img/logo2-d.png" media="(prefers-color-scheme: dark)">
    
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2d337f">

    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="robots" content="noarchive">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Łuna">
    <meta name="mobile-web-app-capable" content="yes">
    
    <?php if (basename($_SERVER['PHP_SELF']) !== 'index.php') echo '<meta name="robots" content="noindex, nofollow">'; ?>
    
    <!-- Primary Meta Tags -->
    <meta charset="utf-8">
    <meta property="title" content="Łuna — Simple, Powerful Academic Management">
    <meta name="description" content="Łuna is a modern academic dashboard for students. Track grades, predict outcomes, analyze performance, manage collaborative calendars, and monitor GPA — all in a fast, clean, mobile-ready platform.">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://lunaedu.net/">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Łuna — Simple, Powerful Academic Management">
    <meta property="og:description" content="Stay on top of your academic progress with grade prediction, analytics, GPA tracking, and collaborative calendars; all in one intuitive dashboard.">
    <meta property="og:url" content="https://lunaedu.net/">
    <meta property="og:site_name" content="Łuna">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Łuna — Simple, Powerful Academic Management">
    <meta name="twitter:description" content="A fast, clean, mobile-ready platform for grade tracking, prediction, performance analytics, and shared academic calendars.">
    <meta name="twitter:domain" content="lunaedu.net">
    <meta name="twitter:url" content="https://lunaedu.net/">


<?php if (isMobileDevice()): ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php endif; ?>


<style>

/* --- Base button --- */
.btn {
  border-radius: 0.375rem; /* Bootstrap 5 default */
  font-weight: 600;
  font-size: 1rem;
  line-height: 1.5;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  transition: all 0.15s ease-in-out;
  border: 1px solid transparent;
  padding: 0.375rem 0.75rem; /* default size */
}

.btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
.btn-md { padding: 0.375rem 0.75rem; font-size: 1rem; }
.btn-lg { padding: 0.5rem 1rem; font-size: 1.25rem; }

/* ==============================
   DARK THEME BUTTONS (DEFAULT)
   ============================== */


/* --- Filled variants --- */
.btn-primary { background-color: rgba(78, 93, 255, 0.15); border-color: rgba(78, 93, 255, 0.35); color: #cfd4ff; }
.btn-success { background-color: rgba(56, 220, 106, 0.15); border-color: rgba(56, 220, 106, 0.35); color: #c8ffe3; }
.btn-warning { background-color: rgba(255, 193, 7, 0.15); border-color: rgba(255, 193, 7, 0.35); color: #fff5c2; }
.btn-danger  { background-color: rgba(255, 0, 60, 0.15); border-color: rgba(255, 0, 60, 0.35); color: #ffb3b3; }
.btn-info    { background-color: rgba(0, 199, 255, 0.15); border-color: rgba(0, 199, 255, 0.35); color: #b3f0ff; }
.btn-secondary {
  background-color: rgba(100, 100, 110, 0.2); /* slightly darker for outline visibility */
  border-color: rgba(150, 150, 160, 0.35);
  color: #d0d0d0;
}
.btn-light   { background-color: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.25); color: #f0f0f0; }
.btn-dark    { background-color: rgba(40, 42, 48, 0.8); border-color: rgba(40, 42, 48, 0.9); color: #e8e8e8; }
.btn-link    { background-color: transparent; border-color: transparent; color: #4e5dff; text-decoration: underline; }

/* --- Hover / Focus for filled --- */
.btn-primary:hover, .btn-primary:focus { background-color: rgba(78, 93, 255, 0.25); border-color: rgba(78, 93, 255, 0.55); color: #fff; }
.btn-success:hover, .btn-success:focus { background-color: rgba(56, 220, 106, 0.25); border-color: rgba(56, 220, 106, 0.55); color: #fff; }
.btn-warning:hover, .btn-warning:focus { background-color: rgba(255, 193, 7, 0.25); border-color: rgba(255, 193, 7, 0.55); color: #fff; }
.btn-danger:hover, .btn-danger:focus   { background-color: rgba(255, 0, 60, 0.25); border-color: rgba(255, 0, 60, 0.55); color: #fff; }
.btn-info:hover, .btn-info:focus       { background-color: rgba(0, 199, 255, 0.25); border-color: rgba(0, 199, 255, 0.55); color: #fff; }
.btn-secondary:hover, .btn-secondary:focus { background-color: rgba(100,100,110,0.3); border-color: rgba(150,150,160,0.55); color: #fff; }
.btn-light:hover, .btn-light:focus     { background-color: rgba(255, 255, 255, 0.2); border-color: rgba(255, 255, 255, 0.35); color: #000; }
.btn-dark:hover, .btn-dark:focus       { background-color: rgba(40, 42, 48, 0.95); border-color: rgba(40, 42, 48, 1); color: #fff; }
.btn-link:hover, .btn-link:focus       { color: #6666ff; text-decoration: underline; }

/* --- Outline variants --- */
.btn-outline-primary {
  color: rgba(78, 93, 255, 0.85); border-color: rgba(78, 93, 255, 0.85); background-color: transparent;
}
.btn-outline-success { color: rgba(56, 220, 106, 0.85); border-color: rgba(56, 220, 106, 0.85); background-color: transparent; }
.btn-outline-warning { color: rgba(255, 193, 7, 0.85); border-color: rgba(255, 193, 7, 0.85); background-color: transparent; }
.btn-outline-danger  { color: rgba(255, 0, 60, 0.85); border-color: rgba(255, 0, 60, 0.85); background-color: transparent; }
.btn-outline-info    { color: rgba(0, 199, 255, 0.85); border-color: rgba(0, 199, 255, 0.85); background-color: transparent; }
.btn-outline-secondary { color: #d0d0d0; border-color: #d0d0d0; background-color: transparent; }
.btn-outline-light   { color: #f0f0f0; border-color: #f0f0f0; background-color: transparent; }
.btn-outline-dark    { color: #e8e8e8; border-color: #e8e8e8; background-color: transparent; }
.btn-outline-link    { color: #4e5dff; border-color: transparent; background-color: transparent; text-decoration: underline; }

/* --- Hover / Focus for outlines --- */
.btn-outline-primary:hover, .btn-outline-primary:focus { background-color: rgba(78, 93, 255, 0.15); color: #fff; border-color: rgba(78, 93, 255, 0.85); }
.btn-outline-success:hover, .btn-outline-success:focus { background-color: rgba(56, 220, 106, 0.15); color: #fff; border-color: rgba(56, 220, 106, 0.85); }
.btn-outline-warning:hover, .btn-outline-warning:focus { background-color: rgba(255, 193, 7, 0.15); color: #fff; border-color: rgba(255, 193, 7, 0.85); }
.btn-outline-danger:hover, .btn-outline-danger:focus   { background-color: rgba(255, 0, 60, 0.15); color: #fff; border-color: rgba(255, 0, 60, 0.85); }
.btn-outline-info:hover, .btn-outline-info:focus       { background-color: rgba(0, 199, 255, 0.15); color: #fff; border-color: rgba(0, 199, 255, 0.85); }
.btn-outline-secondary:hover, .btn-outline-secondary:focus { background-color: rgba(100,100,110,0.3); color: #fff; border-color: #d0d0d0; }
.btn-outline-light:hover, .btn-outline-light:focus     { background-color: rgba(255, 255, 255, 0.15); color: #000; border-color: #f0f0f0; }
.btn-outline-dark:hover, .btn-outline-dark:focus       { background-color: rgba(40, 42, 48, 0.9); color: #fff; border-color: #e8e8e8; }
.btn-outline-link:hover, .btn-outline-link:focus       { color: #6666ff; text-decoration: underline; }

/* --- Disabled / Focused --- */
.btn:disabled, .btn.disabled { opacity: 0.5; pointer-events: none; }

/* --- Optional Icon spacing --- */
.btn i, .btn svg { margin-right: 0.5rem; font-size: 1.1em; }

/* --- Optional Soft Shadow --- */
.btn-soft { box-shadow: 0 4px 12px rgba(0,0,0,0.25); }
.btn-soft:hover { box-shadow: 0 6px 18px rgba(0,0,0,0.35); }

/* --- Dark mode backdrop --- */
.btn, .btn:hover, .btn:focus { backdrop-filter: blur(4px); }


/* ==============================
   LIGHT THEME BUTTONS
   ============================== */

/* --- Filled variants --- */
body[data-theme="light"] .btn-primary    { background-color: #4e5dff; border-color: #4e5dff; color: #fff; }
body[data-theme="light"] .btn-secondary  { background-color: #6c757d; border-color: #6c757d; color: #fff; }
body[data-theme="light"] .btn-success    { background-color: #38dc6a; border-color: #38dc6a; color: #fff; }
body[data-theme="light"] .btn-danger     { background-color: #ff003c; border-color: #ff003c; color: #fff; }
body[data-theme="light"] .btn-warning    { background-color: #ffc107; border-color: #ffc107; color: #212529; }
body[data-theme="light"] .btn-info       { background-color: #00c7ff; border-color: #00c7ff; color: #212529; }
body[data-theme="light"] .btn-light      { background-color: #f8f9fa; border-color: #f8f9fa; color: #212529; }
body[data-theme="light"] .btn-dark       { background-color: #343a40; border-color: #343a40; color: #fff; }
body[data-theme="light"] .btn-link       { background-color: transparent; border-color: transparent; color: #4e5dff; text-decoration: underline; }

/* --- Hover / Focus for filled variants --- */
body[data-theme="light"] .btn-primary:hover,
body[data-theme="light"] .btn-primary:focus    { background-color: #3d4ee0; color: #fff; border-color: #3d4ee0; }
body[data-theme="light"] .btn-secondary:hover,
body[data-theme="light"] .btn-secondary:focus  { background-color: #5a6268; color: #fff; border-color: #5a6268; }
body[data-theme="light"] .btn-success:hover,
body[data-theme="light"] .btn-success:focus    { background-color: #2ecc55; color: #fff; border-color: #2ecc55; }
body[data-theme="light"] .btn-danger:hover,
body[data-theme="light"] .btn-danger:focus     { background-color: #e60030; color: #fff; border-color: #e60030; }
body[data-theme="light"] .btn-warning:hover,
body[data-theme="light"] .btn-warning:focus    { background-color: #e0a800; color: #212529; border-color: #e0a800; }
body[data-theme="light"] .btn-info:hover,
body[data-theme="light"] .btn-info:focus       { background-color: #00bfff; color: #212529; border-color: #00bfff; }
body[data-theme="light"] .btn-light:hover,
body[data-theme="light"] .btn-light:focus      { background-color: #e2e6ea; color: #212529; border-color: #e2e6ea; }
body[data-theme="light"] .btn-dark:hover,
body[data-theme="light"] .btn-dark:focus       { background-color: #23272b; color: #fff; border-color: #23272b; }
body[data-theme="light"] .btn-link:hover,
body[data-theme="light"] .btn-link:focus       { color: #6666ff; text-decoration: underline; }

/* --- Outline variants --- */
body[data-theme="light"] .btn-outline-primary    { color: #4e5dff; border-color: #4e5dff; background-color: transparent; }
body[data-theme="light"] .btn-outline-secondary  { color: #6c757d; border-color: #6c757d; background-color: transparent; }
body[data-theme="light"] .btn-outline-success    { color: #38dc6a; border-color: #38dc6a; background-color: transparent; }
body[data-theme="light"] .btn-outline-danger     { color: #ff003c; border-color: #ff003c; background-color: transparent; }
body[data-theme="light"] .btn-outline-warning    { color: #ffc107; border-color: #ffc107; background-color: transparent; }
body[data-theme="light"] .btn-outline-info       { color: #00c7ff; border-color: #00c7ff; background-color: transparent; }
body[data-theme="light"] .btn-outline-light      { color: #f8f9fa; border-color: #f8f9fa; background-color: transparent; }
body[data-theme="light"] .btn-outline-dark       { color: #343a40; border-color: #343a40; background-color: transparent; }
body[data-theme="light"] .btn-outline-link       { color: #4e5dff; border-color: transparent; background-color: transparent; text-decoration: underline; }

/* --- Hover / Focus for outline variants --- */
body[data-theme="light"] .btn-outline-primary:hover,
body[data-theme="light"] .btn-outline-primary:focus    { background-color: rgba(78, 93, 255, 0.1); color: #4e5dff; border-color: #4e5dff; }
body[data-theme="light"] .btn-outline-secondary:hover,
body[data-theme="light"] .btn-outline-secondary:focus  { background-color: rgba(108, 117, 125, 0.1); color: #6c757d; border-color: #6c757d; }
body[data-theme="light"] .btn-outline-success:hover,
body[data-theme="light"] .btn-outline-success:focus    { background-color: rgba(56, 220, 106, 0.1); color: #38dc6a; border-color: #38dc6a; }
body[data-theme="light"] .btn-outline-danger:hover,
body[data-theme="light"] .btn-outline-danger:focus     { background-color: rgba(255, 0, 60, 0.1); color: #ff003c; border-color: #ff003c; }
body[data-theme="light"] .btn-outline-warning:hover,
body[data-theme="light"] .btn-outline-warning:focus    { background-color: rgba(255, 193, 7, 0.1); color: #ffc107; border-color: #ffc107; }
body[data-theme="light"] .btn-outline-info:hover,
body[data-theme="light"] .btn-outline-info:focus       { background-color: rgba(0, 199, 255, 0.1); color: #00c7ff; border-color: #00c7ff; }
body[data-theme="light"] .btn-outline-light:hover,
body[data-theme="light"] .btn-outline-light:focus      { background-color: rgba(248, 249, 250, 0.3); color: #212529; border-color: #f8f9fa; }
body[data-theme="light"] .btn-outline-dark:hover,
body[data-theme="light"] .btn-outline-dark:focus       { background-color: rgba(52, 58, 64, 0.1); color: #343a40; border-color: #343a40; }
body[data-theme="light"] .btn-outline-link:hover,
body[data-theme="light"] .btn-outline-link:focus       { color: #6666ff; text-decoration: underline; }


    * {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .navbar {
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
    }

    <?php 
    $theme = $_SESSION['theme'] ?? 'dark';
    $isDefaultTheme = ($theme === 'light' || $theme === 'dark');
    
    // Load themes from JSON file
    $themesJson = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/themes.json');
    $themesData = json_decode($themesJson, true);
    $themes = $themesData['themes'];
    
    if (isset($themes[$theme])):
        $currentTheme = $themes[$theme];
        $isDarkTheme = $currentTheme['mode'] === 'dark';
        $isLightTheme = $currentTheme['mode'] === 'light';
        $hasGradient = (isset($currentTheme['bg_gradient']) && $currentTheme['bg_gradient'] !== 'none') || 
                       (is_string($currentTheme['bg']) && strpos($currentTheme['bg'], 'linear-gradient') !== false);
        
        if (!$isDefaultTheme):
    ?>
    
    /* <?php echo $currentTheme['name']; ?> Theme */
    <?php if ($isDarkTheme): ?>
    /* Dark Theme Styling */
    :root[data-bs-theme="dark"] {
        --bs-body-bg: <?php echo is_string($currentTheme['bg']) && strpos($currentTheme['bg'], 'linear-gradient') === false ? $currentTheme['bg'] : '#212529'; ?>;
        --bs-body-color: <?php echo $currentTheme['text_color']; ?>;
        --bs-dark-bg: #1a1d23;
        --bs-dark-border: #2a2d36;
        --bs-primary: <?php echo $currentTheme['primary']; ?>;
        --bs-primary-rgb: <?php 
            $hex = str_replace('#', '', $currentTheme['primary']);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            echo "$r, $g, $b";
        ?>;
        --bs-secondary: <?php echo $currentTheme['primary']; ?>cc;
        --theme-primary: <?php echo $currentTheme['primary']; ?>;
        --bs-border-color: <?php echo $currentTheme['border']; ?>;
        --bs-card-bg: <?php echo $currentTheme['card_bg']; ?>;
        --bs-card-color: <?php echo $currentTheme['card_text']; ?>;
        --theme-navbar-bg: <?php echo $currentTheme['navbar_bg']; ?>;
        --theme-glow: <?php echo $currentTheme['glow']; ?>;
    }
    <?php else: ?>
    /* Light Theme Styling - Custom colors for non-default themes */
    :root[data-bs-theme="light"] {
        --bs-body-bg: #ffffff;
        --bs-body-color: #212529;
        --bs-light: #f8f9fa;
        --bs-dark: #212529;
        
        --bs-primary: <?php echo $currentTheme['primary']; ?>;
        --bs-primary-rgb: <?php 
            $hex = str_replace('#', '', $currentTheme['primary']);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            echo "$r, $g, $b";
        ?>;
        --theme-primary: <?php echo $currentTheme['primary']; ?>;
        
        --bs-card-bg: #ffffff;
        --bs-card-color: #212529;
        --bs-modal-bg: #ffffff;
        --bs-modal-color: #212529;
        --bs-dropdown-bg: #ffffff;
        --bs-dropdown-color: #212529;
        --bs-navbar-bg: <?php echo $currentTheme['navbar_bg']; ?>;
        --theme-navbar-bg: <?php echo $currentTheme['navbar_bg']; ?>;
        --bs-border-color: <?php echo $currentTheme['border']; ?>;
        --bs-table-bg: transparent;
        --bs-table-color: #212529;
        --theme-glow: <?php echo $currentTheme['glow']; ?>;
    }
    <?php endif; ?>
    
    body {
        background-color: <?php echo is_string($currentTheme['bg']) && strpos($currentTheme['bg'], 'linear-gradient') === false ? $currentTheme['bg'] : ($isDarkTheme ? '#212529' : '#ffffff'); ?>;
        color: <?php echo $currentTheme['text_color']; ?>;
        min-height: 100vh;
        overflow-x: hidden;
        position: static;
    }
    
    /* Background gradient overlay */
    <?php if (isset($currentTheme['bg_gradient']) && $currentTheme['bg_gradient'] !== 'none'): ?>
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: <?php echo $currentTheme['bg_gradient']; ?>;
        pointer-events: none;
        z-index: 0;
    }
    <?php elseif (is_string($currentTheme['bg']) && strpos($currentTheme['bg'], 'linear-gradient') !== false): ?>
    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: <?php echo $currentTheme['bg']; ?>;
        pointer-events: none;
        z-index: 0;
    }
    <?php endif; ?>
    
    /* Glow effect for dark themes with gradients */
    <?php if ($isDarkTheme && $hasGradient): ?>
    html::after {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: radial-gradient(circle at 50% 50%, <?php echo $currentTheme['glow']; ?> 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }
    <?php endif; ?>
    
    /* Status bar styling for light themes with gradients */
    <?php if ($isLightTheme && $hasGradient): ?>
    meta[name="apple-mobile-web-app-status-bar-style"] {
        content: "black-translucent";
    }
    <?php endif; ?>
    
    /* Cards for custom themes */
    .card {
        <?php if ($isDarkTheme): ?>
        background: <?php echo $currentTheme['card_bg']; ?> !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: <?php echo $currentTheme['card_text']; ?>;
        <?php else: ?>
        background: #ffffff !important;
        color: #212529;
        <?php endif; ?>
        border: 1px solid <?php echo $currentTheme['border']; ?> !important;
    }
    
    /* Modals for custom themes */
    .modal-content {
        <?php if ($isDarkTheme): ?>
        background: <?php echo $currentTheme['card_bg']; ?> !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: <?php echo $currentTheme['card_text']; ?>;
        <?php else: ?>
        background: #ffffff !important;
        color: #212529;
        <?php endif; ?>
        border: 1px solid <?php echo $currentTheme['border']; ?> !important;
    }
    
    .modal-header, .modal-footer {
        border-color: <?php echo $currentTheme['border']; ?> !important;
    }
    
    /* Dropdowns for custom themes */
    .dropdown-menu {
        <?php if ($isDarkTheme): ?>
        background: <?php echo $currentTheme['card_bg']; ?> !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: <?php echo $currentTheme['card_text']; ?>;
        <?php else: ?>
        background: #ffffff !important;
        color: #212529;
        <?php endif; ?>
        border: 1px solid <?php echo $currentTheme['border']; ?> !important;
    }
    
    .dropdown-item {
        color: <?php echo $isDarkTheme ? $currentTheme['card_text'] : '#212529'; ?>;
    }
    
    .dropdown-item:hover, .dropdown-item:focus {
        background-color: <?php echo $currentTheme['primary']; ?>15;
        color: <?php echo $currentTheme['primary']; ?>;
    }
    
    /* Form controls for custom themes */
    <?php if ($isDarkTheme): ?>
    .form-control, .form-select, input[type="text"], input[type="email"], 
    input[type="password"], input[type="number"], textarea {
        background-color: <?php echo $currentTheme['card_bg']; ?>;
        border-color: <?php echo $currentTheme['border']; ?>;
        color: <?php echo $currentTheme['text_color']; ?>;
    }
    
    .form-control:focus, .form-select:focus {
        background-color: <?php echo $currentTheme['card_bg']; ?>;
        border-color: <?php echo $currentTheme['primary']; ?>;
        color: <?php echo $currentTheme['text_color']; ?>;
        box-shadow: 0 0 0 0.25rem <?php echo $currentTheme['primary']; ?>40;
    }
    
    .form-control::placeholder {
        color: <?php echo $currentTheme['text_color']; ?>80;
    }
    <?php endif; ?>
    
    /* Buttons - enhanced theming */
    .btn-primary {
        background-color: <?php echo $currentTheme['primary']; ?>;
        border-color: <?php echo $currentTheme['primary']; ?>;
        color: #ffffff;
    }
    
    .btn-primary:hover, .btn-primary:focus {
        background-color: <?php echo $currentTheme['primary']; ?>dd;
        border-color: <?php echo $currentTheme['primary']; ?>dd;
        color: #ffffff;
    }
    
    .btn-outline-primary {
        color: <?php echo $currentTheme['primary']; ?>;
        border-color: <?php echo $currentTheme['primary']; ?>;
    }
    
    .btn-outline-primary:hover, .btn-outline-primary:focus {
        background-color: <?php echo $currentTheme['primary']; ?>;
        border-color: <?php echo $currentTheme['primary']; ?>;
        color: #ffffff;
    }
    
    /* Badges */
    .badge.bg-primary {
        background-color: <?php echo $currentTheme['primary']; ?> !important;
    }
    
    
    /* Tables for custom themes */
    .table {
        --bs-table-bg: transparent;
        <?php if ($isDarkTheme): ?>
        color: <?php echo $currentTheme['table_text']; ?>;
        <?php else: ?>
        color: #212529;
        <?php endif; ?>
    }
    
    .table thead th {
        <?php if ($isDarkTheme): ?>
        background: <?php echo $currentTheme['card_bg']; ?> !important;
        backdrop-filter: blur(10px);
        color: <?php echo $currentTheme['table_text']; ?> !important;
        <?php else: ?>
        background: rgba(0, 0, 0, 0.03) !important;
        color: #212529 !important;
        <?php endif; ?>
        border-bottom: 1px solid <?php echo $currentTheme['border']; ?> !important;
    }
    
    .table tbody tr {
        <?php if ($isDarkTheme): ?>
        background: <?php echo $currentTheme['card_bg']; ?> !important;
        backdrop-filter: blur(10px);
        <?php else: ?>
        background: rgba(0, 0, 0, 0.01) !important;
        <?php endif; ?>
    }
    
    .table tbody td {
        border-color: <?php echo $currentTheme['border']; ?> !important;
        <?php if ($isDarkTheme): ?>
        color: <?php echo $currentTheme['table_text']; ?>;
        <?php else: ?>
        color: #212529;
        <?php endif; ?>
    }
    
    /* Navbar for custom themes - with blurback for ALL */
    .navbar {
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        <?php if ($isDarkTheme): ?>
        background: <?php echo $currentTheme['navbar_bg']; ?> !important;
        color: <?php echo $currentTheme['text_color']; ?>;
        <?php else: ?>
        background: <?php echo $currentTheme['navbar_bg']; ?> !important;
        color: #212529;
        <?php endif; ?>
    }
    
    /* Navbar text color fix for light themes with gradients */
    <?php if ($isLightTheme && $hasGradient): ?>
    .navbar .navbar-brand,
    .navbar .nav-link,
    .navbar .navbar-text,
    .navbar .text-muted {
        color: #212529 !important;
    }
    
    .navbar .nav-link:hover {
        color: <?php echo $currentTheme['primary']; ?> !important;
    }
    
    .navbar .nav-link.active {
        color: <?php echo $currentTheme['primary']; ?> !important;
    }
    <?php endif; ?>
    
    /* Navbar active/hover states for dark themes */
    <?php if ($isDarkTheme): ?>
    .navbar .nav-link:hover {
        color: <?php echo $currentTheme['primary']; ?> !important;
    }
    
    .navbar .nav-link.active {
        color: <?php echo $currentTheme['primary']; ?> !important;
        font-weight: 600;
    }
    <?php endif; ?>
    
    <?php endif; /* End of custom theme styling */ ?>
    
    <?php else: /* Default Bootstrap themes - add blurback */ ?>
    
    /* Default themes navbar with blurback */
    .navbar {
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        <?php if ($theme === 'dark'): ?>
        background: rgba(26, 28, 31, 0.9) !important;
        <?php else: ?>
        background: rgba(255, 255, 255, 0.9) !important;
        <?php endif; ?>
    }
    
    body {
        min-height: 100vh;
        overflow-x: hidden;
        position: static;
    }
    
    <?php endif; /* End of if isset($themes[$theme]) */ ?>
    
    /* Apply to all themes - ensure sticky works */
    body {
        min-height: 100vh;
        overflow-x: hidden;
        position: static;
    }
    
    .card,
    .table,
    .main-content-page {
        position: relative;
        z-index: 1;
    }
    
    /* Containers should NOT create stacking context */
    .container,
    .container-xl,
    .container-fluid {
        position: relative;
        z-index: auto;
    }

    /* Force navbar below modals */
    nav.navbar,
    .navbar-blurback,
    .mobile-navbar {
        z-index: 100 !important;
    }

    /* Modal backdrop - must be above navbar */
    div.modal-backdrop,
    .modal-backdrop.show,
    .modal-backdrop.fade {
        position: fixed !important;
        z-index: 10000 !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
    }

    /* Modal and all its children - must be above backdrop */
    div.modal,
    .modal.show,
    .modal.fade,
    .modal.fade.show {
        position: fixed !important;
        z-index: 10001 !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        transform: none !important;
    }
    
    div.modal .modal-dialog,
    .modal.show .modal-dialog {
        z-index: 10001 !important;
        position: relative !important;
        transform: none !important;
    }
    
    div.modal .modal-content,
    .modal.show .modal-content {
        z-index: 10001 !important;
        position: relative !important;
    }

    body.modal-open {
        overflow: hidden !important;
    }
    
    /* Ensure modal is always on top */
    body.modal-open .navbar {
        z-index: 100 !important;
    }

    
    /* Modal Styling - Apply to all themes */
    .modal-content {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    /* Navbar - Apply blur to ALL themes */
    .navbar {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--bs-border-color) !important;
        contain: layout style;
    }
    
    /* Override any transforms that might break sticky */
    .navbar,
    nav.navbar {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
        width: 100% !important;
        left: 0 !important;
    }
    
    /* Mobile navbar styling - Apply to ALL themes with theme colors */
    .navbar-blurback {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        <?php if (isset($themes[$theme]) && !$isDefaultTheme): ?>
        background-color: <?php echo $currentTheme['navbar_bg']; ?>;
        border-top: 1px solid <?php echo $currentTheme['border']; ?>;
        <?php elseif ($theme === 'dark'): ?>
        background-color: rgba(26, 28, 31, 0.95);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        <?php else: ?>
        background-color: rgba(255, 255, 255, 0.95);
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        <?php endif; ?>
        z-index: 100;
    }
    
    .mobile-navbar {
        min-height: 70px;
        box-shadow: 0 -2px 10px <?php echo isset($themes[$theme]) && !$isDefaultTheme ? $currentTheme['glow'] : 'rgba(0, 0, 0, 0.1)'; ?>;
        //border-top-left-radius: 16px;
        //border-top-right-radius: 16px;
        padding-bottom: 10px;
    }
    
    .mobile-navbar .nav-pills {
        padding: 0 0.5rem;
    }
    
    .mobile-navbar .nav-item {
        flex: 1;
        display: flex;
        justify-content: center;
    }
    
    
    /* Mobile navbar link styling - clean and organized */
    .mobile-navbar .nav-link {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.5rem 0.25rem;
        margin: 0 0.15rem;
        transition: all 0.2s ease;
        color: <?php echo isset($themes[$theme]) && !$isDefaultTheme ? ($isDarkTheme ? $currentTheme['text_color'] . '80' : '#6c757d') : 'var(--bs-body-color)'; ?> !important;
        -webkit-tap-highlight-color: transparent;
        background: none !important;
    }
    
    .mobile-navbar .nav-link.active {
        <?php if (isset($themes[$theme]) && !$isDefaultTheme): ?>
        color: <?php echo $currentTheme['primary']; ?> !important;
        <?php else: ?>
        color: var(--bs-primary) !important;
        <?php endif; ?>
        font-weight: bold;
        background: none !important;
    }
    
    /* Active dot - always visible and matches active item color */
    .mobile-navbar .nav-link.active::after {
        content: "";
        position: absolute;
        left: 50%;
        bottom: 6px;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        border-radius: 50%;
        <?php if (isset($themes[$theme]) && !$isDefaultTheme): ?>
        background: <?php echo $currentTheme['primary']; ?>;
        <?php else: ?>
        background: var(--bs-primary);
        <?php endif; ?>
    }
    
    .mobile-navbar .nav-link i {
        font-size: 1.4rem;
        margin-bottom: 0.25rem;
        transition: color 0.2s ease;
        color: inherit;
    }
    
    .mobile-navbar .nav-link.active i {
        color: inherit !important;
    }
    
    /* Mobile app top navbar (calendars.php/grades.php) - match desktop navbar styling */
    .mobile-app-navbar {
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        <?php if (isset($themes[$theme]) && !$isDefaultTheme): ?>
            <?php if ($isDarkTheme): ?>
            background: <?php echo $currentTheme['navbar_bg']; ?> !important;
            <?php else: ?>
            background: <?php echo $currentTheme['navbar_bg']; ?> !important;
            <?php endif; ?>
        <?php elseif ($theme === 'dark'): ?>
        background: rgba(26, 28, 31, 0.9) !important;
        <?php else: ?>
        background: rgba(255, 255, 255, 0.9) !important;
        <?php endif; ?>
    }
    
    html {
        touch-action: manipulation;
        overflow-x: hidden;
        overflow-y: auto;
        height: 100%;
    }
    
    body {
        overflow-x: hidden;
        overflow-y: auto;
        min-height: 100vh;
        height: 100%;
        position: static !important;
    }
    
    * {
        scrollbar-width: thin;
        scrollbar-color: var(--bs-secondary) transparent;
    }
    
    ::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }
    
    ::-webkit-scrollbar-track {
        background: transparent;
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--bs-secondary);
        border-radius: 6px;
        border: 3px solid transparent;
        background-clip: padding-box;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: var(--bs-body-color);
        border: 3px solid transparent;
        background-clip: padding-box;
    }
    
    /* Dark theme scrollbar */
    <?php if (isset($themes[$theme]) && !$isDefaultTheme && $isDarkTheme): ?>
    * {
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }
    
    ::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }
    <?php else: ?>
    * {
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
    }
    
    ::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.2);
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.4);
    }
    <?php endif; ?>
    
    nav.navbar,
    .navbar.sticky-top {
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
        width: 100%;
    }

.nav-link:hover,
.class-card,
.card-body,
.mobile-app-navbar,
a:hover {
    color: inherit !important;
    background-color: inherit !important;
    background: inherit !important;
    transform: none !important;
    box-shadow: inherit !important;
}


    .main-content-page {
        <?php if (!$isInApp) { ?>margin-top: 30px;<?php } ?>
    }
    
    <?php if (isset($themes[$theme]) && $theme === 'ys'): ?>
    * {
        background: url("https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQehHjxu7XM23MaCAUZmzSPeevZk4gmIpMBw&s");
    }
    <?php endif; ?>
</style>
<meta name="darkreader-lock">

</head>
<body>

<?php if (basename($_SERVER['PHP_SELF']) !== 'grades-detail.php'): ?>

<?php if (!empty($_SESSION["name"])): ?>

<?php if (isMobileDevice()): ?>

<nav data-bs-theme="<?php echo $_SESSION['theme']; ?>" 
     class="mobile-navbar navbar-blurback fixed-bottom d-flex">
  <ul class="nav nav-pills justify-content-around w-100">
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('/dash'); ?>" href="/dash">
        <i class="bi bi-speedometer2"></i>
        <span></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('/grades'); ?>" href="/grades">
        <i class="bi bi-book<?php echo isActiveMobile('/grades'); ?>"></i>
        <span></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('/calendars'); ?>" href="/calendars">
        <i class="bi bi-calendar<?php echo isActiveMobile('/calendars'); ?>"></i>
        <span></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('/attendance'); ?>" href="/attendance">
        <i class="bi bi-clipboard-check<?php echo isActiveMobile('/attendance'); ?>"></i>
        <span></span>
      </a>
    </li>
    <?php if (false === true): ?>
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('/lb'); ?>" href="/lb">
        <i class="bi bi-trophy<?php echo isActiveMobile('/lb'); ?>"></i>
        <span></span>
      </a>
    </li>
    <?php endif; ?>
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('/schedule'); ?>" href="/schedule">
        <i class="bi bi-clock<?php echo isActiveMobile('/schedule'); ?>"></i>
        <span></span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo isActive('/user'); ?>" href="/user">
        <i class="bi bi-gear<?php echo isActiveMobile('/user'); ?>"></i>
        <span></span>
      </a>
    </li>
  </ul>
</nav>

<?php else: ?>

<nav class="navbar navbar-expand-lg 
    <?php 
    // Check if using a light theme (either default light or custom light mode)
    $isLightNav = ($bootstrapMode === 'light') || (isset($isLightTheme) && $isLightTheme);
    echo $isLightNav ? 'navbar-light bg-light border-bottom shadow-sm' : 'navbar-dark bg-dark'; 
    ?> 
    sticky-top py-2 nav-underline">
  <div class="container-xl">
    <a class="navbar-brand position-relative d-flex align-items-center fw-bold me-2" href="/">
      Łuna  
      <small><span class="position-absolute top-0 start-50 translate-middle badge text-bg-warning" style="margin-top:10px;font-size:0.5em;margin-left:1.3em;padding:0.2em 0.3em;">
        Beta
      </span></small>

      <?= $maintenance ? '<span class="text-warning">Under Maintenance</span>' : '' ?>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?php echo isActive('/dash'); ?> fw-semibold" href="/dash">
            <i class="bi <?php echo isActive('/dash') ? 'bi-speedometer2' : 'bi-speedometer2'; ?> me-1"></i>
            Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isActive('/grades'); ?> fw-semibold" href="/grades">
            <i class="bi <?php echo isActive('/grades') ? 'bi-book-fill' : 'bi-book'; ?> me-1"></i>
            Grades
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isActive('/calendars'); ?> fw-semibold" href="/calendars">
            <i class="bi <?php echo isActive('/calendars') ? 'bi-calendar-fill' : 'bi-calendar'; ?> me-1"></i>
            Calendars
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isActive('/attendance'); ?> fw-semibold" href="/attendance">
            <i class="bi <?php echo isActive('/attendance') ? 'bi-clipboard-check-fill' : 'bi-clipboard-check'; ?> me-1"></i>
            Attendance
          </a>
        </li>
        <?php if (false === true): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo isActive('/lb'); ?> fw-semibold" href="/lb">
                <i class="bi <?php echo isActive('/lb') ? 'bi-trophy-fill' : 'bi-trophy'; ?> me-1"></i>
                Leaderboard
            </a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link <?php echo isActive('/schedule'); ?> fw-semibold" href="/schedule">
            <i class="bi <?php echo isActive('/schedule') ? 'bi-clock-fill' : 'bi-clock'; ?> me-1"></i>
            Schedule
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo isActive('/user'); ?> fw-semibold" href="/user">
            <i class="bi <?php echo isActive('/user') ? 'bi-gear-fill' : 'bi-gear'; ?> me-1"></i>
            Settings
          </a>
        </li>

      </ul>
      
    <ul class="navbar-nav navbar-right">

<?php if (isset($_SESSION['url']) && $_SESSION['url'] === 'demodist'): ?>
    <a href="/backends/user-logout" class="btn btn-outline-danger btn-sm">Exit Demo</a>
<?php else: ?>

<?php
$name = $_SESSION['name'] ?? '';

if ($name) {
    $words = preg_split('/\s+/', trim($name));
    
    // show only first and last name of user
    if (count($words) > 1) {
        $displayName = $words[0] . ' ' . $words[count($words) - 1];
    } else {
        $displayName = $words[0];
    }
} else {
    $displayName = '';
}
?>
<span class="text-muted ms-2">
  <small><b><?php echo htmlspecialchars($displayName); ?></b></small>
</span>

<?php endif; ?>
    </ul>

    </div>
  </div>
</nav>

<?php endif; ?> 

<?php else: ?>

<!-- Navbar for logged-out users -->
<nav class="navbar navbar-expand-lg 
    <?php echo $bootstrapMode === 'light' ? 'navbar-light bg-light border-bottom shadow-sm' : 'navbar-dark bg-dark'; ?> 
    sticky-top py-2 nav-underline">
    <div class="container">
    <a class="navbar-brand fw-bold" href="/">Łuna</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link fw-bold" href="/#features">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-info fw-bold" href="/signin">Sign In</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<?php endif; ?>

<?php endif; ?>


<?php
$currentPage = basename($_SERVER['PHP_SELF']); 
$ads = $_SESSION['ads'] ?? '1';

$containerClasses = $currentPage !== 'grades-detail.php' ? 'container-xl main-content-page' : '';

$adClasses = '';
if ($currentPage !== 'grades-detail.php' && isset($_SESSION['id']) && $ads === '3' && !(isMobileDevice())) {
    $adClasses = 'mx-3';
}
?>

<style>
.in-app {
  padding-bottom: 90px;   /* increase bottom padding */
  position: relative;
}
</style>

<div class="<?= $containerClasses ?> <?php if ($isInApp) { echo 'in-app'; } ?>">
<div class="<?= $adClasses ?>">
