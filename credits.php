<?php
session_start();
include_once("_h.php");
?>

<style>
  /* Make all cards transparent */
.transparent-card {
  background-color: transparent !important;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}


  /* Hover effect: subtle background and scale */
  .transparent-card:hover {
    background-color: rgba(255, 255, 255, 0);
    transform: translateY(-3px);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);

  }

  /* Text styling inside cards */
  .transparent-card .card-body span {
    font-weight: 600;
  }

  /* Optional: Developer card slightly distinct */
  .developer-card {
    background-color: transparent !important;
    border-radius: 0.5rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }

  .developer-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
</style>

<div class="container py-4">

  <!-- Header -->
  <div class="text-center mb-5">
    <h1 class="fw-bold">Credits</h1>
    <p class="text-muted">Everyone who helped shape and support Łuna</p>
  </div>

  <!-- Developer Compact Card -->
  <div class="row justify-content-center mb-5">
    <div class="col-md-6 col-lg-4">
      <div class="card developer-card text-center">
        <div class="card-body py-3">
          <h5 class="fw-bold mb-1">Developer</h5>
          <p class="text-muted mb-2 small">Builds and maintains Łuna</p>
          <p class="mb-0 fw-semibold">CK</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Appreciation Section -->
  <h3 class="fw-bold text-center mb-3">Appreciation</h3>
  <p class="text-muted text-center mb-4">Thank you to everyone who offered time, feedback, and support</p>

  <!-- Grid of Person Cards -->
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 text-center">

    <?php
      $people = ["Marco S", "Sifat P", "Gabe G", "Koi H", "Lucas C"];
      foreach ($people as $person) {
        echo '<div class="col">';
        echo '  <div class="card transparent-card h-100 text-center">';
        echo '    <div class="card-body d-flex align-items-center justify-content-center">';
        echo "      <span>$person</span>";
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
      }
    ?>

  </div>

</div>

<?php include_once("_f.php"); ?>
