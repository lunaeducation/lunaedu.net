<?php
session_start();
include_once("_h.php");

/*function getAvatarBase64($url) {
    $img = @file_get_contents($url);
    if ($img === false) return '';
    $mime = 'image/png';
    return 'data:' . $mime . ';base64,' . base64_encode($img);
}

$calvinkAvatar = getAvatarBase64('https://avatars.githubusercontent.com/u/107697031');
$gwLogo = getAvatarBase64('https://www.gradeway.app/public/img/logo-200h.png');
$fcLogo = getAvatarBase64('https://avatars.githubusercontent.com/u/13825204');*/

$calvinkAvatar = '/img/me.png';
$gwLogo = '/img/gw.png';
$fcLogo = '/img/fc.png';
$bsLogo = '/img/bs.svg';
$cjsLogo = '/img/cjs.svg';

?>

<style>
credits {
  display: block;
  max-width: 750px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.credit-card {
  display: flex;
  align-items: center;
  background: var(--bs-body-bg);
  border: 1px solid var(--bs-border-color);
  border-radius: 1rem;
  padding: 1.25rem 1.5rem;
  margin-bottom: 1rem;
  color: var(--bs-body-color);
  text-decoration: none;
  transition: background-color 0.2s ease, transform 0.15s ease, border-color 0.2s ease;
}

.credit-card:hover {
  background-color: color-mix(in srgb, var(--bs-body-bg) 85%, var(--bs-primary) 10%);
  border-color: var(--bs-primary-border-subtle, var(--bs-primary));
  transform: translateY(-2px);
}

.credit-card:active {
  transform: translateY(0);
}

.credit-img {
  width: 70px;
  height: 70px;
  object-fit: cover;
  border-radius: 50%;
  margin-right: 1.25rem;
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.credit-title {
  font-weight: 600;
  font-size: 1.05rem;
}

.section-title {
  font-weight: 600;
  margin-bottom: 1rem;
  border-bottom: 2px solid var(--bs-border-color);
  padding-bottom: 0.5rem;
}

.text-muted {
  color: var(--bs-secondary-color) !important;
}
</style>

<credits>
  <div class="pb-5">
    <h2 class="fw-bold mb-4 text-center">Credits</h2>

    <!-- Developers Section -->
    <?php /*<div class="mb-5">

      <a target="_blank" class="credit-card">
        <div>
          <div class="credit-title">Calvin K</div>
          <p class="mb-0 text-muted small">Builds and maintains Łuna</p>
        </div>
      </a>
    </div>
    */ ?>

    <!-- Thanks Section -->
    <div>

      <a href="https://getbootstrap.com" target="_blank" class="credit-card">
        <div>
          <div class="credit-title">Bootstrap</div>
          <p class="mb-0 text-muted small">Frontend framework used to build Łuna</p>
        </div>
      </a>

      <a href="https://gradeway.app" target="_blank" class="credit-card">
        <div>
          <div class="credit-title">GradeWay</div>
          <p class="mb-0 text-muted small">Major inspiration for design and features</p>
        </div>
      </a>

      <a href="https://fullcalendar.io" target="_blank" class="credit-card">
        <div>
          <div class="credit-title">FullCalendar</div>
          <p class="mb-0 text-muted small">Used for the calendar layout</p>
        </div>
      </a>

      <a href="https://www.chartjs.org" target="_blank" class="credit-card">
        <div>
          <div class="credit-title">Chart.js</div>
          <p class="mb-0 text-muted small">Used for the grade analysis graphs</p>
        </div>
      </a>
    </div>
  </div>
</credits>

<?php include_once("_f.php"); ?>
