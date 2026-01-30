<?php
session_start();
$isInApp = isset($_COOKIE['isInApp']) && $_COOKIE['isInApp'] === 'true';
if ($isInApp) {
    echo '<script>window.location.href = "/signin";</script>';
    exit;
}

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
?>
  <style>
    body {
      background-color: #121217;
      color: #e8e8e8;
    }
    .section-bg {
      background-color: #121217;
    }
    .card-bg {
      background-color: #1a1d23;
    }
    .feature-icon {
      font-size: 1.5rem;
    }
    .table tbody tr {
      border-bottom: 1px solid #2a2d36;
    }
    thead, tbody, tr {
      background-color: #121217 !important;
    }
    .lead {
      color: #aaa;
    }
    a {
      text-decoration: none;
    }
    .hero-image {
      box-shadow: 0 20px 60px rgba(0,0,0,0.4);
    }
    .feature-card {
      transition: transform 0.2s;
    }
    .feature-card:hover {
      transform: translateY(-5px);
    }
  </style>

<!-- Hero Section -->
<section class="py-5 section-bg">
  <div class="container">
    <div class="row align-items-center min-vh-75 py-5">
      <div class="col-lg-6 mb-5 mb-lg-0">
        <h1 class="display-3 fw-bold mb-4">Łuna</h1>
        <p class="lead fs-4 mb-4">
          The smarter way to track, predict, and plan your academic success.
        </p>
        <p class="text-muted mb-5">
          Join thousands of students who trust Łuna to stay organized, predict grades, and take control of their academic journey.
        </p>
        <div class="d-flex gap-3 flex-wrap">
          <a href="/signin" class="btn btn-primary btn-lg px-4 py-3 fw-bold">Sign In</a>
          <form method="POST" class="d-inline">
            <button type="submit" name="demo_login" class="btn btn-outline-secondary btn-lg px-4 py-3">Try Demo</button>
          </form>
        </div>
      </div>
      <div class="col-lg-6">
        <img src="/img/dashn.png" alt="Łuna Dashboard" class="img-fluid rounded-3 hero-image">
      </div>
    </div>
  </div>
</section>

<!-- Why Łuna Section -->
<section class="py-5 border-top border-secondary">
  <div class="container py-4">
    <div class="text-center mb-5">
      <h2 class="display-6 fw-bold mb-3">Why students love Łuna</h2>
      <p class="lead text-muted">All the tools you need, designed for students who want clarity and control</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-4 card-bg rounded-3 h-100 feature-card">
          <div class="fs-1 mb-3 text-primary"><i class="bi bi-speedometer2"></i></div>
          <h5 class="fw-bold mb-3">Instant Overview</h5>
          <p class="mb-0 text-muted">See all your grades and course averages at a glance—clean, clear, zero clutter.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 card-bg rounded-3 h-100 feature-card">
          <div class="fs-1 mb-3 text-success"><i class="bi bi-calculator"></i></div>
          <h5 class="fw-bold mb-3">GPA & What‑If Predictions</h5>
          <p class="mb-0 text-muted">Calculate your GPA now, and simulate future grades to plan ahead.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 card-bg rounded-3 h-100 feature-card">
          <div class="fs-1 mb-3 text-info"><i class="bi bi-graph-up"></i></div>
          <h5 class="fw-bold mb-3">Performance Insights</h5>
          <p class="mb-0 text-muted">Track your progress over time and identify strengths and weaknesses.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 border-top border-secondary">
  <div class="container py-5">
    <div class="text-center mb-5">
      <h2 class="display-6 fw-bold">Powerful Features</h2>
    </div>

    <div class="row align-items-center mb-5 pb-5">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h3 class="h2 fw-bold mb-3">Grade Prediction & What‑If Calculator</h3>
        <p class="text-muted mb-4">See how upcoming assignments could affect your final grade or GPA.</p>
        <ul class="list-unstyled">
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Simulate hypothetical grades</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Set target grades for courses</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Instant course average updates</li>
        </ul>
      </div>
      <div class="col-lg-6">
        <div class="card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/predn.png" alt="Grade Prediction" class="img-fluid">
        </div>
      </div>
    </div>

    <div class="row align-items-center mb-5 pb-5">
      <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
        <h3 class="h2 fw-bold mb-3">Performance Tracking & Analytics</h3>
        <p class="text-muted mb-4">Visualize performance over time—track improvements, detect patterns, plan better.</p>
        <ul class="list-unstyled">
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Category-wise breakdowns</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Grade trends across semesters</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Strengths & weaknesses overview</li>
        </ul>
      </div>
      <div class="col-lg-6 order-lg-1">
        <div class="card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/analyse.png" alt="Performance Analysis" class="img-fluid">
        </div>
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0">
        <h3 class="h2 fw-bold mb-3">GPA Tracking & Semester Planning</h3>
        <p class="text-muted mb-4">Stay on top of your academic standing with GPA calculators and semester projections.</p>
        <ul class="list-unstyled">
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Weighted & unweighted GPA support</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Semester forecast tools</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Course-by-course GPA breakdowns</li>
        </ul>
      </div>
      <div class="col-lg-6">
        <div class="card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/gpa.png" alt="GPA Tracking" class="img-fluid">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Comparison Section -->
<section class="py-5 border-top border-secondary">
  <div class="container py-5">
    <div class="row">
      <div class="col-lg-8 mx-auto">
        <div class="text-center mb-5">
          <h2 class="display-6 fw-bold mb-3">Feature Comparison</h2>
          <p class="lead text-muted">See how Łuna stacks up against the competition</p>
        </div>
        
        <div class="table-responsive rounded-3 shadow-sm" style="overflow: hidden; border: 1px solid #2a2d36;">
          <table class="table table-dark mb-0 table-striped">
            <thead>
              <tr class="border-bottom border-secondary">
                <th class="ps-4 py-3">Features</th>
                <th class="text-center py-3">Łuna</th>
                <th class="text-center py-3">GradeWay</th>
                <th class="text-center py-3">HAC</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="ps-4"><i class="bi bi-laptop me-2"></i>Chromebook-friendly</td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi text-danger fs-5"></i></td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
              </tr>
              <tr>
                <td class="ps-4"><i class="bi bi-graph-up-arrow me-2"></i>Grade Prediction</td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi bi-dash text-warning fs-5"></i></td>
                <td class="text-center"><i class="bi text-danger fs-5"></i></td>
              </tr>
              <tr>
                <td class="ps-4"><i class="bi bi-calculator me-2"></i>GPA Tracking</td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi bi-dash text-warning fs-5"></i></td>
                <td class="text-center"><i class="bi text-danger fs-5"></i></td>
              </tr>
              <tr>
                <td class="ps-4"><i class="bi bi-bar-chart-line me-2"></i>Performance Analysis</td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi bi-dash text-warning fs-5"></i></td>
                <td class="text-center"><i class="bi text-danger fs-5"></i></td>
              </tr>
              <tr>
                <td class="ps-4"><i class="bi bi-calendar-week me-2"></i>Collaborative Calendars</td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi text-danger fs-5"></i></td>
                <td class="text-center"><i class="bi text-danger fs-5"></i></td>
              </tr>
              <tr>
                <td class="ps-4"><i class="bi bi-ui-checks-grid me-2"></i>Intuitive UI</td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi text-danger fs-5"></i></td>
              </tr>
              <tr>
                <td class="ps-4"><i class="bi bi-phone me-2"></i>Mobile-friendly</td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi text-danger fs-5"></i></td>
              </tr>
              <tr>
                <td class="ps-4"><i class="bi bi-gift me-2"></i>Free</td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
                <td class="text-center"><i class="bi bi-dash text-warning fs-5"></i></td>
                <td class="text-center"><i class="bi bi-check text-success fs-5"></i></td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-4 text-center">
          <small class="text-muted d-block mb-1">
            [<i class="bi bi-check text-success"></i>] Available / Free
          </small>
          <small class="text-muted d-block mb-1">
            [<i class="bi bi-dash text-warning"></i>] Partially Available / Limited / Paid
          </small>
          <small class="text-muted d-block">
            [<i class="bi text-danger me-3"></i>] Not Available
          </small>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Final CTA -->
<section class="py-5 border-top border-secondary">
  <div class="container text-center py-5">
    <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
    <p class="lead text-muted mb-5 mx-auto" style="max-width: 700px;">
      Take control of your academic journey with powerful tools designed to help you succeed.
    </p>
    <div class="d-flex gap-3 justify-content-center flex-wrap">
      <a href="/signin" class="btn btn-primary btn-lg px-5 py-3 fw-bold">Sign In</a>
      <form method="POST" class="d-inline">
        <button type="submit" name="demo_login" class="btn btn-outline-secondary btn-lg px-5 py-3">Try Demo</button>
      </form>
    </div>
  </div>
</section>

<?php include_once("_f.php"); ?>