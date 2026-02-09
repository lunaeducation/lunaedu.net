<?php
session_start();

include_once("../_h.php");
require_once("../_backend-libs.php");
?>

<style>
.section-bg {
    //background-color: #1a1d23;
}
.card-bg {
    //background-color: #252a33;
}
</style>

<!-- Features Hero Section -->
<section class="py-5 section-bg">
  <div class="container py-5 text-center">
    <h1 class="display-3 fw-bold mb-3">Łuna's Features</h1>
    <p class="lead mb-4 text-muted fs-4">
      Everything you need to stay on top of your academic performance.
    </p>
    <p class="text-muted mb-0">
      Explore the tools designed to make grade tracking and planning easier than ever.
    </p>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 mb-0">
  <div class="container py-4 mb-0">
    <div class="text-center mb-5">
      <h2 class="display-5 fw-bold mb-3">Powerful Features</h2>
      <p class="lead text-muted">Tools to help you plan, track, and improve your academic performance.</p>
    </div>

    <!-- Grade Prediction -->
    <div class="row align-items-center mb-5">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
            <i class="bi bi-graph-up-arrow text-primary fs-3"></i>
          </div>
          <h3 class="fw-bold mb-0">Grade Prediction</h3>
        </div>
        <p class="text-muted mb-3">
          Plan ahead and see how upcoming assignments affect your grades.
        </p>
        <ul class="list-unstyled text-muted">
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Scenario testing</li>
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Target grade planning</li>
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Assignment impact analysis</li>
        </ul>
      </div>
      <div class="col-lg-6 text-center">
        <div class="card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/predn.png" alt="Grade Prediction" class="img-fluid">
        </div>
      </div>
    </div>

    <!-- Performance Analysis -->
    <div class="row align-items-center mb-5">
      <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-success bg-opacity-10 rounded p-2 me-3">
            <i class="bi bi-bar-chart-line text-success fs-3"></i>
          </div>
          <h3 class="fw-bold mb-0">Performance Analysis</h3>
        </div>
        <p class="text-muted mb-3">
          Break down your grades by category and assignment type to track trends.
        </p>
        <ul class="list-unstyled text-muted">
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Category-wise breakdown</li>
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Progress tracking over time</li>
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Strength and weakness analysis</li>
        </ul>
      </div>
      <div class="col-lg-6 order-lg-1 text-center">
        <div class="card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/analyse.png" alt="Performance Analysis" class="img-fluid">
        </div>
      </div>
    </div>

    <!-- Collaborative Calendars -->
    <div class="row align-items-center mb-5">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-info bg-opacity-10 rounded p-2 me-3">
            <i class="bi bi-calendar-week text-info fs-3"></i>
          </div>
          <h3 class="fw-bold mb-0">Collaborative Calendars</h3>
        </div>
        <p class="text-muted mb-3">
          Keep track of school events, assignments, and exams with shared calendars.
        </p>
        <ul class="list-unstyled text-muted">
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Shared class calendars</li>
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Test and exam scheduling</li>
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Assignment tracking</li>
        </ul>
      </div>
      <div class="col-lg-6 text-center">
        <div class="card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/caln.png" alt="Collaborative Calendars" class="img-fluid">
        </div>
      </div>
    </div>

    <!-- GPA Tracking -->
    <div class="row align-items-center mb-0">
      <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
        <div class="d-flex align-items-center mb-3">
          <div class="bg-warning bg-opacity-10 rounded p-2 me-3">
            <i class="bi bi-calculator text-warning fs-3"></i>
          </div>
          <h3 class="fw-bold mb-0">GPA Tracking</h3>
        </div>
        <p class="text-muted mb-3">
          Monitor your cumulative GPA and project how current grades impact your academic standing.
        </p>
        <ul class="list-unstyled text-muted">
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Real-time GPA calculation</li>
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Semester projection tools</li>
          <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Graduation requirement tracking</li>
        </ul>
      </div>
      <div class="col-lg-6 order-lg-1 text-center">
        <div class="card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/gpa.png" alt="GPA Tracking" class="img-fluid">
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once("../_f.php"); ?>
