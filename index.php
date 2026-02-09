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
    * {
      scroll-behavior: smooth;
    }
    
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
      transition: transform 0.3s ease;
      transform-style: preserve-3d;
    }
    .feature-card {
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      border: 1px solid rgba(255, 255, 255, 0.05);
      position: relative;
      overflow: hidden;
    }
    
    .feature-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, transparent 100%);
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .feature-card:hover {
      transform: translateY(-5px);
      border-color: rgba(59, 130, 246, 0.2);
    }
    
    .feature-card:hover::before {
      opacity: 1;
    }
    
    .fade-in {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .fade-in.visible {
      opacity: 1;
      transform: translateY(0);
    }
    
    .stagger-1 { transition-delay: 0.1s; }
    .stagger-2 { transition-delay: 0.2s; }
    .stagger-3 { transition-delay: 0.3s; }
    .stagger-4 { transition-delay: 0.4s; }
    
    .tilt-image {
      transition: transform 0.3s ease;
      transform-style: preserve-3d;
    }
    
    .image-container {
      perspective: 1000px;
      transition: transform 0.3s ease;
      transform-style: preserve-3d;
    }
  </style>

<!-- Hero Section -->
<section class="py-5 section-bg">
  <div class="container">
    <div class="row align-items-center min-vh-75 py-5">
      <div class="col-lg-6 mb-5 mb-lg-0">
        <h1 class="display-3 fw-bold mb-4 fade-in">Łuna</h1>
        <p class="lead fs-4 mb-4 fade-in stagger-1">
          The smarter way to track, predict, and plan your academic success.
        </p>
        <p class="text-muted mb-5 fade-in stagger-2">
          Join thousands of students who trust Łuna to stay organized, predict grades, and take control of their academic journey.
        </p>
        <div class="d-flex gap-3 flex-wrap fade-in stagger-3">
          <a href="/signin" class="btn btn-primary btn-lg px-4 py-3 fw-bold">Sign In</a>
          <form method="POST" class="d-inline">
            <button type="submit" name="demo_login" class="btn btn-outline-secondary btn-lg px-4 py-3">Try Demo</button>
          </form>
        </div>
      </div>
      <div class="col-lg-6 fade-in stagger-2">
        <div class="image-container">
          <img src="/img/dashn.png" alt="Łuna Dashboard" class="img-fluid rounded-3 hero-image tilt-image" data-tilt>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Why Łuna Section -->
<section class="py-5 border-top border-secondary">
  <div class="container py-4">
    <div class="text-center mb-5 fade-in">
      <h2 class="display-6 fw-bold mb-3">Why students love Łuna</h2>
      <p class="lead text-muted">All the tools you need, designed for students who want clarity and control</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4 fade-in stagger-1">
        <div class="p-4 card-bg rounded-3 h-100 feature-card">
          <div class="fs-1 mb-3 text-primary"><i class="bi bi-speedometer2"></i></div>
          <h5 class="fw-bold mb-3">Instant Overview</h5>
          <p class="mb-0 text-muted">See all your grades and course averages at a glance—clean, clear, zero clutter.</p>
        </div>
      </div>
      <div class="col-md-4 fade-in stagger-2">
        <div class="p-4 card-bg rounded-3 h-100 feature-card">
          <div class="fs-1 mb-3 text-success"><i class="bi bi-calculator"></i></div>
          <h5 class="fw-bold mb-3">GPA & What‑If Predictions</h5>
          <p class="mb-0 text-muted">Calculate your GPA now, and simulate future grades to plan ahead.</p>
        </div>
      </div>
      <div class="col-md-4 fade-in stagger-3">
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
    <div class="text-center mb-5 fade-in">
      <h2 class="display-6 fw-bold">Powerful Features</h2>
    </div>

    <div class="row align-items-center mb-5 pb-5">
      <div class="col-lg-6 mb-4 mb-lg-0 fade-in">
        <h3 class="h2 fw-bold mb-3">Grade Prediction & What‑If Calculator</h3>
        <p class="text-muted mb-4">See how upcoming assignments could affect your final grade or GPA.</p>
        <ul class="list-unstyled">
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Simulate hypothetical grades</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Set target grades for courses</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Instant course average updates</li>
        </ul>
      </div>
      <div class="col-lg-6 fade-in stagger-1">
        <div class="image-container card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/predn.png" alt="Grade Prediction" class="img-fluid tilt-image" data-tilt>
        </div>
      </div>
    </div>

    <div class="row align-items-center mb-5 pb-5">
      <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0 fade-in">
        <h3 class="h2 fw-bold mb-3">Performance Tracking & Analytics</h3>
        <p class="text-muted mb-4">Visualize performance over time—track improvements, detect patterns, plan better.</p>
        <ul class="list-unstyled">
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Category-wise breakdowns</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Grade trends across semesters</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Strengths & weaknesses overview</li>
        </ul>
      </div>
      <div class="col-lg-6 order-lg-1 fade-in stagger-1">
        <div class="image-container card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/analyse.png" alt="Performance Analysis" class="img-fluid tilt-image" data-tilt>
        </div>
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-6 mb-4 mb-lg-0 fade-in">
        <h3 class="h2 fw-bold mb-3">GPA Tracking & Semester Planning</h3>
        <p class="text-muted mb-4">Stay on top of your academic standing with GPA calculators and semester projections.</p>
        <ul class="list-unstyled">
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Weighted & unweighted GPA support</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Semester forecast tools</li>
          <li class="mb-2 text-muted"><i class="bi bi-check-circle-fill text-success me-2"></i>Course-by-course GPA breakdowns</li>
        </ul>
      </div>
      <div class="col-lg-6 fade-in stagger-1">
        <div class="image-container card border-0 card-bg rounded-3 overflow-hidden shadow">
          <img src="/img/gpa.png" alt="GPA Tracking" class="img-fluid tilt-image" data-tilt>
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
        <div class="text-center mb-5 fade-in">
          <h2 class="display-6 fw-bold mb-3">Feature Comparison</h2>
          <p class="lead text-muted">See how Łuna stacks up against the competition</p>
        </div>
        
        <div class="table-responsive rounded-3 shadow-sm fade-in stagger-1" style="overflow: hidden; border: 1px solid #2a2d36;">
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

        <div class="mt-4 text-center fade-in stagger-2">
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
    <h2 class="display-5 fw-bold mb-4 fade-in">Ready to Get Started?</h2>
    <p class="lead text-muted mb-5 mx-auto fade-in stagger-1" style="max-width: 700px;">
      Take control of your academic journey with powerful tools designed to help you succeed.
    </p>
    <div class="d-flex gap-3 justify-content-center flex-wrap fade-in stagger-2">
      <a href="/signin" class="btn btn-primary btn-lg px-5 py-3 fw-bold">Sign In</a>
      <form method="POST" class="d-inline">
        <button type="submit" name="demo_login" class="btn btn-outline-secondary btn-lg px-5 py-3">Try Demo</button>
      </form>
    </div>
  </div>
</section>




<script>
document.addEventListener('DOMContentLoaded', function() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, observerOptions);

  document.querySelectorAll('.fade-in').forEach(el => {
    observer.observe(el);
  });


  document.querySelectorAll('[data-tilt]').forEach(el => {
    const container = el.closest('.image-container');
    const target = container || el;
    
    el.addEventListener('mousemove', function(e) {
      const rect = target.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      
      const rotateX = (y - centerY) / centerY * -5;
      const rotateY = (x - centerX) / centerX * 5;
      
      target.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
    });

    
    el.addEventListener('mouseleave', function() {
      target.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale3d(1, 1, 1)';
    });
  });
});
</script>



<?php include_once("_f.php"); ?>