<?php
session_start();
include_once("_h.php");
require_once("_backend-libs.php");

if (empty($_SESSION["name"])) {
    header("Location: /signin");
    exit;
}

$prefs = loadPrefs($_SESSION['id']);
?>



<style>
    .theme-preview {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        border: 2px solid var(--bs-border-color);
        flex-shrink: 0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .theme-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid var(--bs-border-color);
        border-radius: 0.75rem;
        padding: 1rem;
        background: var(--bs-body-bg);
    }

    .theme-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        border-color: var(--bs-primary);
    }

    .theme-card.active {
        border-color: var(--bs-primary);
        background: rgba(var(--bs-primary-rgb), 0.05);
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.2);
    }

    .theme-card input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .theme-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .theme-badge.light {
        background: rgba(255, 193, 7, 0.15);
        color: #f59e0b;
    }

    .theme-badge.dark {
        background: rgba(139, 92, 246, 0.15);
        color: #a855f7;
    }

    .theme-section-title {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--bs-border-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-switch .form-check-input {
        width: 2.5em;
        height: 1.3em;
    }
</style>

<h2 class="">Settings</h2>

<hr>

<h4 class="mb-3">Misc.</h4>

<!-- Welcome Toggle -->
<div class="settings-card mb-3">
    <form action="/backends/user-dashwelcome" method="POST" class="form-switch">
        <input type="hidden" name="dashwelcome" value="0">
        <input
            class="form-check-input"
            type="checkbox"
            role="switch"
            name="dashwelcome"
            value="1"
            id="welcomeSwitch"
            <?= $_SESSION['dashwelcome'] === '1' ? 'checked' : '' ?>
            onchange="this.form.submit()"
        >
        <label class="form-check-label ms-2" for="welcomeSwitch">
            Show dashboard welcome message
        </label>
    </form>
</div>

<hr>

<!-- Ads -->
<div class="settings-card">
    <h4 class="mb-3">Advertisements</h4>
    <form action="/backends/user-ads" method="POST" class="vstack gap-2">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="ads" value="0"
                   id="adsNone"
                   <?= $_SESSION['ads'] === '0' ? 'checked' : '' ?>
                   onchange="this.form.submit()">
            <label class="form-check-label" for="adsNone">
                None (Off)
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="ads" value="1"
                   id="adsFooter"
                   <?= $_SESSION['ads'] === '1' ? 'checked' : '' ?>
                   onchange="this.form.submit()">
            <label class="form-check-label" for="adsFooter">
                Footer Ads
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="ads" value="3"
                   id="adsFull"
                   <?= $_SESSION['ads'] === '3' ? 'checked' : '' ?>
                   onchange="this.form.submit()">
            <label class="form-check-label" for="adsFull">
                Sellout (Footer + Margins)
            </label>
        </div>

        <p class="text-muted mt-0 mb-0"><small>Ads help keep Łuna running, but feel free to disable them anytime.</small></p>
    </form>
</div>

<hr>

<!-- Theme Selector -->
<div class="settings-card mb-4" id="themes">
    <h4 class="mb-4">Themes</h4>

    <?php
    // Load themes from JSON file
    $themesJson = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/themes.json');
    $themesData = json_decode($themesJson, true);
    $themeStyles = $themesData['themes'];

    // Separate themes by mode
    $lightThemes = [];
    $darkThemes = [];
    foreach ($themeStyles as $themeKey => $style) {
        if ($style['mode'] === 'light') $lightThemes[$themeKey] = $style;
        else $darkThemes[$themeKey] = $style;
    }

    $themeSections = [
        'Light' => ['icon' => '<i class="bi bi-sun fs-5"></i>️', 'themes' => $lightThemes],
        'Dark'  => ['icon' => '<i class="bi bi-moon fs-5"></i>', 'themes' => $darkThemes],
    ];
    ?>

    <form action="/backends/user-theme" method="POST">
        <?php foreach ($themeSections as $sectionName => $sectionData): ?>
            <!-- Section Title -->
            <div class="mb-3 d-flex align-items-center gap-2 fw-bold text-uppercase text-muted">
                <span><?= $sectionData['icon'] ?></span> <?= $sectionName ?>
            </div>

            <!-- Theme Grid -->
            <div class="row g-3 mb-4">
                <?php foreach ($sectionData['themes'] as $themeKey => $style):
                    $isActive = $_SESSION['theme'] === $themeKey;
                ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card border <?= $isActive ? 'border-primary bg-primary bg-opacity-10' : 'border-secondary' ?>">
                            <div class="card-body p-3 d-flex align-items-center gap-3"
                                 onclick="document.getElementById('theme_<?= $themeKey ?>').click()" style="cursor:pointer;">
                                
                                <!-- Hidden Radio -->
                                <input type="radio" class="d-none" name="theme" id="theme_<?= $themeKey ?>"
                                       value="<?= $themeKey ?>" <?= $isActive ? 'checked' : '' ?>
                                       onchange="changeTheme('<?= $themeKey ?>')">

                                <!-- Preview -->
                                <div class="flex-shrink-0 rounded" style="width:48px; height:48px; background: <?= $style['preview_gradient'] ?? $style['bg'] ?>;"></div>

                                <!-- Theme Name & Status -->
                                <div class="flex-grow-1">
                                    <div class="fw-bold"><?= $style['name'] ?></div>
                                    <?php if ($isActive): ?>
                                        <small class="text-success fw-semibold">
                                            <i class="bi bi-check-circle-fill"></i> Active
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </form>
</div>

<!-- Logout -->
<div class="settings-card">
    <form action="/backends/user-logout" method="POST">
        <button type="submit" class="btn btn-outline-danger">Logout</button>
    </form>
</div>

<script>
// doesn't work, fix.
// maybe just hot-reload the css or something later?
function changeTheme(themeKey) {
    localStorage.setItem("scrollPos", window.scrollY);

    fetch("/backends/user-theme", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "theme=" + encodeURIComponent(themeKey)
    })
    .then(res => {
        location.reload();
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const pos = localStorage.getItem("scrollPos");
    if (pos !== null) {
        window.scrollTo(0, parseInt(pos));
        localStorage.removeItem("scrollPos");
    }
});
</script>


<?php include_once("_f.php"); ?>
