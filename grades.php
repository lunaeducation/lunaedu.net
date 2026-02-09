<?php session_start(); include_once("_h.php"); ?>

<style>

/* Mobile navbar title truncation */
#mobile-grades-navbar-title {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
}

@media (max-width: 768px) {
    #backToClasses {
        font-size: 0;
        padding: 0.375rem 0.75rem;
    }
    
    #backToClasses i {
        font-size: 1rem;
    }
}

/* New Detail View Styles */
.detail-header {
    position: sticky;
    top: 56px; /* Below navbar */
    z-index: 90;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
    background: var(--bs-body-bg);
    border-bottom: 2px solid var(--bs-border-color);
    padding: 1rem 0;
}

.detail-header.scrolled {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.mode-toggle-container {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.mode-btn {
    position: relative;
    overflow: hidden;
}

.mode-btn.active {
    font-weight: 600;
}

.predict-mode-active .predict-mode-instructions {
    display: block !important;
}

.predict-mode-instructions {
    display: none;
    background: linear-gradient(135deg, rgba(255, 243, 205, 0.15) 0%, rgba(255, 234, 167, 0.15) 100%);
    border-left: 4px solid var(--bs-warning);
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.375rem;
    backdrop-filter: blur(10px);
}

.predict-mode-instructions h5 {
    color: var(--bs-warning);
}

.predict-mode-instructions .step-indicator {
    background: var(--bs-warning);
    color: var(--bs-dark);
}

.analyze-mode-content {
    display: none;
}

.analyze-mode-active .analyze-mode-content {
    display: block;
}

.category-card {
    border: 2px solid var(--bs-border-color);
    border-radius: 0.5rem;
    padding: 1.5rem;
    transition: all 0.2s ease;
    background: var(--bs-body-bg);
}

.category-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.category-progress-circle {
    width: 120px;
    height: 120px;
    margin: 0 auto;
}

.category-progress-circle svg {
    transform: rotate(-90deg);
    width: 100%;
    height: 100%;
}

.category-progress-bg {
    fill: none;
    stroke: var(--bs-border-color);
    stroke-width: 8;
    opacity: 0.3;
}

.category-progress-fill {
    fill: none;
    stroke-width: 8;
    stroke-linecap: round;
    stroke-dasharray: 282.74; /* 2 * π * 45 */
    stroke-dashoffset: 282.74;
    transition: stroke-dashoffset 0.8s ease, stroke 0.3s ease;
}

.category-progress-fill.color-success { stroke: var(--bs-success); }
.category-progress-fill.color-primary { stroke: var(--bs-primary); }
.category-progress-fill.color-warning { stroke: var(--bs-warning); }
.category-progress-fill.color-danger { stroke: var(--bs-danger); }
.category-progress-fill.color-secondary { stroke: var(--bs-secondary); }

.category-progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    font-size: 1.5rem;
    font-weight: 700;
}

.category-progress-text.color-success { color: var(--bs-success) !important; }
.category-progress-text.color-primary { color: var(--bs-primary) !important; }
.category-progress-text.color-warning { color: #ffc107 !important; }
.category-progress-text.color-danger { color: var(--bs-danger) !important; }
.category-progress-text.color-secondary { color: var(--bs-secondary) !important; }

.assignment-edit-card {
    border-left: 4px solid var(--bs-warning);
    background: rgba(255, 193, 7, 0.05);
}

.assignment-edit-card .score-input {
    width: 80px;
    display: inline-block;
}

.quick-help-card {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left: 4px solid #2196f3;
    padding: 1rem;
    border-radius: 0.375rem;
}

.step-indicator {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    background: var(--bs-warning);
    color: var(--bs-dark);
    border-radius: 50%;
    font-weight: bold;
    font-size: 0.875rem;
    margin-right: 0.5rem;
}

/* broken ahh */
.sticky-grade-badge {
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 99999999 !important;
}

.detail-header.scrolled .sticky-grade-badge {
    opacity: 1;
}


.opacity-fade {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}

.sticky-grade-floating {
    position: relative;
    z-index: 110;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Mini circular progress for sticky badge */
.mini-circular-progress {
    width: 70px;
    height: 70px;
    position: relative;
    display: inline-block;
}

.mini-circular-progress svg {
    transform: rotate(-90deg);
    width: 100%;
    height: 100%;

}

.mini-progress-bg {
    fill: none;
    stroke: var(--bs-border-color);
    stroke-width: 4;
    opacity: 0.3;
}

.mini-progress-fill {
    fill: none;
    stroke-width: 4;
    stroke-linecap: round;
    stroke-dasharray: 157.08; /* 2 * 3.1416 * 25 = 157.08 */
    stroke-dashoffset: 157.08;
    transition: stroke-dashoffset 0.5s ease, stroke 0.3s ease;
}


.mini-progress-fill.color-success { stroke: var(--bs-success); }
.mini-progress-fill.color-primary { stroke: var(--bs-primary); }
.mini-progress-fill.color-warning { stroke: var(--bs-warning); }
.mini-progress-fill.color-danger { stroke: var(--bs-danger); }
.mini-progress-fill.color-secondary { stroke: var(--bs-secondary); }

.mini-progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1;
    white-space: nowrap;
    width: 100%;
    padding: 0 8px;
    box-sizing: border-box;
}

/* Match text color to circle color */
.mini-progress-text.color-success { color: var(--bs-success) !important; }
.mini-progress-text.color-primary { color: var(--bs-primary) !important; }
.mini-progress-text.color-warning { color: var(--bs-warning) !important; }
.mini-progress-text.color-danger { color: var(--bs-danger) !important; }
.mini-progress-text.color-secondary { color: var(--bs-secondary) !important; }
.circular-progress-container {
    position: relative;
    display: inline-block;
}

.circular-progress {
    transform: rotate(-90deg);
}

.progress-bg {
    fill: none;
    stroke: var(--bs-border-color);
    stroke-width: 8;
    opacity: 0.3;
}

.progress-fill {
    fill: none;
    stroke: var(--bs-success);
    stroke-width: 8;
    stroke-linecap: round;
    stroke-dasharray: 314.159;
    stroke-dashoffset: 314.159;
    transition: stroke-dashoffset 0.8s ease, stroke 0.3s ease;
}

.progress-fill.color-success { stroke: var(--bs-success); }
.progress-fill.color-primary { stroke: var(--bs-primary); }
.progress-fill.color-warning { stroke: var(--bs-warning); }
.progress-fill.color-danger { stroke: var(--bs-danger); }
.progress-fill.color-secondary { stroke: var(--bs-secondary); }

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    max-width: 90px;
    width: 90px;
}

.progress-text .display-4 {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1.2;
    word-break: keep-all;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: clip;
}


#detailAvgBadge.text-success { color: var(--bs-success) !important; }
#detailAvgBadge.text-primary { color: var(--bs-primary) !important; }
#detailAvgBadge.text-warning { color: var(--bs-warning) !important; }
#detailAvgBadge.text-danger { color: var(--bs-danger) !important; }
#detailAvgBadge.text-secondary { color: var(--bs-secondary) !important; }

.bg-purple {
    background-color: #6f42c1 !important;
}

/* View transition animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideOutUp {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.view-transition-enter {
    animation: fadeIn 0.4s ease-out;
}

.view-transition-exit {
    animation: fadeOut 0.3s ease-in;
}

.class-card-enter {
    animation: fadeIn 0.3s ease-out;
}

.detail-view-enter {
    animation: slideInRight 0.4s ease-out;
}

.predict-mode-enter {
    animation: pulse 0.5s ease-out;
}

.fade-slide-in {
    animation: slideInDown 0.4s ease-out;
}

.fade-slide-out {
    animation: slideOutUp 0.3s ease-out;
}

/* Predict mode activation overlay */
.predict-mode-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 193, 7, 0.1);
    z-index: 9999;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.predict-mode-overlay.active {
    opacity: 1;
}

/* Category tables in sidebar */
#categoryWeights td:first-child,
#categoryAverages td:first-child {
    font-weight: 500;
}

#categoryWeights td:last-child,
#categoryAverages td:last-child {
    font-weight: 600;
    white-space: nowrap;
}

/* Smooth scrolling for sticky header */
html {
    scroll-behavior: smooth;
}

/* Assignment Card Styles */
.assignment-card-compact {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 0.375rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    transition: all 0.2s ease;
}

.assignment-card-compact:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    transform: translateY(-1px);
}

.assignment-card-compact.border-grade-success {
    border-left: 3px solid var(--bs-success);
}

.assignment-card-compact.border-grade-primary {
    border-left: 3px solid var(--bs-primary);
}

.assignment-card-compact.border-grade-warning {
    border-left: 3px solid var(--bs-warning);
}

.assignment-card-compact.border-grade-danger {
    border-left: 3px solid var(--bs-danger);
}

.assignment-card-compact.border-grade-secondary {
    border-left: 3px solid var(--bs-secondary);
}

.assignment-name {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.4rem;
    line-height: 1.2;
}

.assignment-meta {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-bottom: 0.4rem;
    font-size: 0.8rem;
    color: var(--bs-secondary-color);
}

.assignment-meta-item {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.assignment-meta-item i {
    font-size: 0.85rem;
}

.assignment-score-display {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid var(--bs-border-color);
}

.score-fraction {
    font-size: 0.9rem;
    font-weight: 600;
}

.score-badge-large {
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
}

/* Detailed Card View */
.assignment-card-detailed {
    height: 100%;
    border: 1px solid var(--bs-border-color);
    border-radius: 0.375rem;
    overflow: hidden;
    transition: all 0.2s ease;
}

.assignment-card-detailed:hover {
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    transform: translateY(-2px);
}

.assignment-card-detailed.border-grade-success {
    border-top: 3px solid var(--bs-success);
}

.assignment-card-detailed.border-grade-primary {
    border-top: 3px solid var(--bs-primary);
}

.assignment-card-detailed.border-grade-warning {
    border-top: 3px solid var(--bs-warning);
}

.assignment-card-detailed.border-grade-danger {
    border-top: 3px solid var(--bs-danger);
}

.assignment-card-detailed.border-grade-secondary {
    border-top: 3px solid var(--bs-secondary);
}

.assignment-card-header {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid var(--bs-border-color);
}

.assignment-card-body {
    padding: 1rem;
}

.assignment-detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--bs-border-color-translucent);
}

.assignment-detail-row:last-child {
    border-bottom: none;
}

.assignment-detail-label {
    font-size: 0.875rem;
    color: var(--bs-secondary-color);
}

.assignment-detail-value {
    font-weight: 600;
}

/* Performance chart responsive sizing */
@media (max-width: 768px) {
    .card-body canvas {
        height: 250px !important;
    }
}

@media (min-width: 769px) {
    #performanceChart {
        height: 350px !important;
    }
}

#gradesTabs .active {
    cursor: default;
}

@media (max-width: 768px) {
  #gradesTabs {
    display: flex;
    width: 100%;
  }

  #gradesTabs .nav-item {
    flex: 1 1 33.3333%;
    text-align: center;
  }

  #gradesTabs .nav-link {
    width: 100%;
  }
  
  /* Stack cards vertically on mobile */
  .detail-header {
    margin: 0;
    border-radius: 0;
  }
  
  /* Make assignment cards more compact on mobile */
  .assignment-card-compact {
    padding: 0.75rem;
    margin-bottom: 0.5rem;
  }
  
  .assignment-name {
    font-size: 1rem;
  }
  
  .assignment-meta {
    gap: 1rem;
  }
  
  .score-badge-large {
    font-size: 1rem;
    padding: 0.4rem 0.8rem;
  }
}


/* Mobile-specific chart styles */
@media (max-width: 768px) {
    
    .card-body {
        padding: 0.75rem;
    }
    
    /* Stack overview cards nicely */
    .col-md-4 {
        margin-bottom: 0.75rem;
    }
}

    /* Custom styles for improved UI */
    .class-card {
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        //border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .class-card:hover {
        //transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .grade-badge {
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
    }
    
    .dropped-class {
        opacity: 0.6;
    }
    
    .header-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    
    .nav-tabs-card {
        border-bottom: 1px solid var(--bs-border-color);
    }
    
    .table-card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .table-card .table {
        margin-bottom: 0;
    }
    
    .assignment-row:hover {
        background-color: rgba(0,0,0,0.02);
    }
    
    .detail-header {
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .analysis-card {
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
#performanceChart {
    min-height: 250px;
    max-height: 300px;
    width: 100%;
}

.analysis-card .card-header {
    border-bottom: 1px solid var(--bs-border-color);
    font-weight: 600;
    background: var(--bs-secondary-bg) !important;
    color: var(--bs-body-color) !important;
}

    
    .grade-distribution-chart .progress {
        height: 25px;
    }
    
    .grade-distribution-chart .progress-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .toast-container {
        z-index: 1090;
    }
    
    .mobile-assignment-card {
        border-left: 4px solid;
    }
    
    .predict-mode .editable-score {
        cursor: pointer;
        border-bottom: 2px dashed var(--bs-warning);
        padding: 2px 4px;
        transition: all 0.2s ease;
    }
    
    .predict-mode .editable-score:hover {
        background-color: rgba(255,193,7,0.1);
    }
    
    .mock-assignment-row {
        //background-color: rgba(111,66,193,0.05) !important;
        //border-left: 4px solid var(--bs-purple) !important;
    }
    
    .section-header {
        //background-color: var(--bs-light);
        padding: 0.75rem;
        border-bottom: 1px solid var(--bs-border-color);
        font-weight: 600;
        //color: var(--bs-purple);
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .header-actions {
            justify-content: space-between;
            width: 100%;
        }
        
        .grade-badge {
            font-size: 1rem;
            padding: 0.4rem 0.8rem;
        }
        
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .detail-header {
            padding: 0.75rem;
        }
    }
    
    /* Grade color borders */
    .border-success {
        border-left: 4px solid var(--bs-success) !important;
    }
    
    .border-primary {
        border-left: 4px solid var(--bs-primary) !important;
    }
    
    .border-warning {
        border-left: 4px solid var(--bs-warning) !important;
    }
    
    .border-danger {
        border-left: 4px solid var(--bs-danger) !important;
    }
    
    .border-dark {
        border-left: 4px solid var(--bs-dark) !important;
    }
</style>

<div class="">
    <!-- Toast Notification -->
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header bg-success text-white">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Operation completed successfully!
            </div>
        </div>
    </div>

    <?php if ($isInApp): ?>
    <!-- Mobile App Top Navbar -->
    <div id="mobile-grades-navbar" class="mobile-app-navbar sticky-top d-lg-none" style="top: 0; z-index: 1000; border-bottom: 1px solid var(--bs-border-color); margin-left: -12px; margin-right: -12px; margin-top: -12px; margin-bottom: 20px;">
        <div class="d-flex flex-column px-3 py-2">
            <!-- Top row: Back button, Title, Main actions -->
            <div class="d-flex align-items-center">
                <!-- Back button (hidden initially, shown in class detail view) -->
                <button id="mobile-grades-back-btn" class="btn btn-link p-0 text-decoration-none d-none" style="font-size: 1.2rem; margin-right: 12px; color: var(--bs-body-color);">
                    <i class="bi bi-chevron-left"></i>
                </button>
                
                <!-- Title -->
                <h5 id="mobile-grades-navbar-title" class="mb-0 fw-semibold" style="flex: 1;">Grades</h5>
                
                <!-- Right actions for main view (grades list) -->
                <div id="mobile-grades-navbar-main-actions" class="d-flex gap-3 align-items-center" style="min-height:35px">
                    <!-- GPA Button -->
                    <button class="btn btn-link btn-sm text-decoration-none" id="mobile-gpa-button" data-bs-toggle="modal" data-bs-target="#gpaModal" style="font-size: 0.85rem; color: var(--bs-body-color);">
                        <i class="bi bi-graph-up"></i> GPA
                    </button>
                    
                    <!-- Teacher Contact Button -->
                    <a href="/teacher-contact" class="btn btn-link btn-sm text-decoration-none" style="font-size: 0.85rem; color: var(--bs-body-color);">
                        <i class="bi bi-envelope"></i> Teachers
                    </a>
                    
                    <!-- Refresh Button -->
                    <button class="btn btn-link btn-sm text-decoration-none" id="mobile-refresh-grades" style="font-size: 0.85rem; color: var(--bs-body-color);">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
                
                <!-- Right actions for detail view - Grade badge -->
                <div id="mobile-grades-navbar-detail-actions" class="d-none">
                    <span class="badge fs-6" id="mobile-predicted-grade-badge">--</span>
                </div>
            </div>
            
            <!-- Bottom row: Mode toggles (only shown in class detail view) -->
            <div id="mobile-grades-navbar-toggles" class="d-none mt-2">
                <div class="d-flex gap-3">
                    <div class="form-check form-switch" style="font-size: 0.85rem;">
                        <input class="form-check-input" type="checkbox" id="mobile-analyze-mode-toggle" style="cursor: pointer;">
                        <label class="form-check-label" for="mobile-analyze-mode-toggle" style="cursor: pointer;">
                            <i class="bi bi-graph-up"></i> Analyze
                        </label>
                    </div>
                    <div class="form-check form-switch" style="font-size: 0.85rem;">
                        <input class="form-check-input" type="checkbox" id="mobile-predict-mode-toggle" style="cursor: pointer;">
                        <label class="form-check-label" for="mobile-predict-mode-toggle" style="cursor: pointer;">
                            <i class="bi bi-lightbulb"></i> Predict
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Header Section -->
    <div id="grading-header-elements">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center <?php if ($isInApp) {?>mb-2<?php } else { ?>mb-4<?php } ?> gap-3">
            <div>
                <h2 class="mb-1 <?php echo $isInApp ? 'd-none d-lg-block' : ''; ?>">Grades</h2>
                <p class="text-muted mb-0" id="lastUpdatedBadge" style="display: none;">
                    <small><i class="bi bi-clock me-1"></i><span id="lastUpdatedTime">Last Updated: ...</span></small>
                </p>
            </div>
            
            <div class="header-actions" <?php if ($isInApp) {?>style="display:none !important;"<?php } ?>>
                <span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Coming soon">
                  <button type="button"
                          id="gpaButton"
                          class="btn btn-outline-primary"
                          data-bs-toggle="modal"
                          data-bs-target="#gpaModal"
                          >
                      <i class="bi bi-graph-up me-1"></i> GPA
                  </button>
                </span>
                
                <script>
                /*document.addEventListener('DOMContentLoaded', () => {
                  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                  tooltipTriggerList.map(el => new bootstrap.Tooltip(el))
                })*/
                </script>
                
                <a href="/teacher-contact" class="btn btn-outline-success">
                    <i class="bi bi-envelope me-1"></i><span class="d-none d-md-inline"> Contact</span> Teachers
                </a>
                
                <button type="button" id="refreshGrades" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                </button>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="card nav-tabs-card mb-4">
            <div class="card-body py-2">
                <ul class="nav nav-pills" id="gradesTabs">
                    <li class="nav-item">
                        <button class="nav-link active" id="runningAverageTab" data-tab="running-average" data-type="assignments">
                            <i class="bi bi-bookmarks me-1"></i>
                            <span class="d-none d-md-inline">Running Average</span>
                            <span class="d-md-none">RA</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="iprTab" data-tab="ipr" data-type="ipr">
                            <i class="bi bi-journal-bookmark me-1"></i>
                            <span class="d-none d-md-inline">Interim Progress</span>
                            <span class="d-md-none">IPR</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="reportCardTab" data-tab="report-card" data-type="rc">
                            <i class="bi bi-journal-bookmark-fill me-1"></i>
                            <span class="d-none d-md-inline">Report Card</span>
                            <span class="d-md-none">RC</span>
                        </button>
                    </li>
                </ul>
        </div>
    </div>

        <!-- Run Selector -->
        <div class="mb-3" id="runSelectorContainer" style="display:none;">
            <div class="d-none d-md-block">
                <ul class="nav nav-tabs" id="runTabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-run-value="">Loading...</a>
                    </li>
                </ul>
            </div>
            <div class="d-md-none">
                <select class="form-select" id="runDropdown">
                    <option value="">Loading...</option>
                </select>
            </div>
        </div>

        <!-- Date Selector (for IPR with multiple dates) -->
        <div class="mb-3" id="dateSelectorContainer" style="display:none;">
            <div class="d-none d-md-block">
                <ul class="nav nav-tabs date-selector-tabs" id="dateTabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" data-date-value="">Loading dates...</a>
                    </li>
                </ul>
            </div>
            <div class="d-md-none">
                <select class="form-select" id="dateDropdown">
                    <option value="">Loading dates...</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div id="mainContentContainer">
        <!-- Running Average View (Default) -->
        <div id="runningAverageView">
            <!-- Loader -->
            <div id="loader" class="card">
                <div class="card-body text-center py-5">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="spinner-border me-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div>
                            <p class="mb-0 fw-semibold">Loading</p>
                            <small class="text-muted">One moment...</small>
                        </div>
                    </div>
                </div>
            </div>

            <div id="errorContainer">
            </div>

            <!-- Classes Container (Grid View) -->
            <div id="classesGridContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" style="display: none;">
                <!-- Class cards will be rendered here -->
            </div>

            <!-- Class Detail View (hidden by default) -->
            <div id="classDetailView" class="d-none">
                <!-- Header with back button and class name -->
                <div class="detail-header <?php echo $isInApp ? 'd-none d-lg-block' : ''; ?>">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" id="backToClasses" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Back to Classes</span>
                                </button>
                                <h3 class="mb-0" id="detailClassName"></h3>
                            </div>
                        </div>
                        
                        <!-- Mode Toggle Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mode-toggle-container">
                                <div class="form-check form-switch d-inline-block me-3">
                                    <input class="form-check-input" type="checkbox" id="analyzeModeToggle" style="cursor: pointer;">
                                    <label class="form-check-label" for="analyzeModeToggle" style="cursor: pointer;">
                                        <i class="bi bi-graph-up"></i> Analyze Performance
                                    </label>
                                </div>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input" type="checkbox" id="predictModeToggle" style="cursor: pointer;">
                                    <label class="form-check-label" for="predictModeToggle" style="cursor: pointer;">
                                        <i class="bi bi-lightbulb"></i> Predict "What If"
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Floating Predicted Grade Badge (always visible) -->
                            <div class="predicted-grade-badge-header">
                                <span class="badge fs-5" id="predictedGradeBadgeValue">--</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail view loader -->
                <div id="detailLoader" class="d-flex align-items-center gap-3 my-4">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mb-0">Loading class details...<br>One moment...</p>
                </div>

                <!-- Detail view content -->
                <div id="classDetailContent" class="d-none">
                    <!-- Predict Mode Instructions -->
                    <div class="predict-mode-instructions">
                        <h5 class="mb-3"><i class="bi bi-lightbulb-fill text-warning me-2"></i>Predict Mode - How It Works</h5>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-start">
                                    <span class="step-indicator">1</span>
                                    <div>
                                        <strong>Edit Scores</strong>
                                        <p class="mb-0 small">Click any assignment score to change it and see the impact</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-start">
                                    <span class="step-indicator">2</span>
                                    <div>
                                        <strong>Add Mock Assignments</strong>
                                        <p class="mb-0 small">Create hypothetical assignments to see future impact</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-start">
                                    <span class="step-indicator">3</span>
                                    <div>
                                        <strong>Watch Your Grade</strong>
                                        <p class="mb-0 small">See your predicted grade update in real-time</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Grade Overview (Always Visible) -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center py-4">
                                    <h6 class="text-muted mb-3" id="gradeCardTitle">Current Grade</h6>
                                    <div class="circular-progress-container mb-3">
                                        <svg class="circular-progress" width="150" height="150" viewBox="0 0 120 120">
                                            <circle class="progress-bg" cx="60" cy="60" r="50" />
                                            <circle class="progress-fill" id="progressCircle" cx="60" cy="60" r="50" />
                                        </svg>
                                        <div class="progress-text">
                                            <div class="display-3 mb-0" id="detailAvgBadge"></div>
                                        </div>
                                    </div>
                                    <div id="detailLetterGrade" class="h5 text-muted mb-0"></div>
                                    <div id="gradeDifferenceInfo" class="mt-2 d-none"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Weights & Averages (Always Visible) -->
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <h5 class="mb-3"><i class="bi bi-pie-chart me-2"></i>Grade Categories</h5>
                        </div>
                        <div id="categoriesContainer" class="col-12">
                            <!-- Category cards will be rendered here -->
                        </div>
                    </div>

                    <!-- Mock Assignments Section (Predict Mode Only) -->
                    <div class="mock-assignment-section mb-4 d-none" id="mockAssignmentSection">
                        <div class="card border-warning shadow-sm">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bi bi-plus-square me-2"></i>Hypothetical Assignments</h5>
                                <button type="button" class="btn btn-sm btn-dark" id="addMockAssignment">
                                    <i class="bi bi-plus-circle me-1"></i> Add Assignment
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="mockAssignmentsList">
                                    <p class="text-muted text-center py-3">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Add hypothetical assignments to see how they would affect your grade
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Goal Calculator (Predict Mode Only) -->
                    <!-- fix later --->
                    <div class="row g-3 mb-4 d-none" id="gradeGoalCalculatorContainer" style="display:none !important;">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="bi bi-calculator me-2"></i>What Grade Do I Need?</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-3">Calculate what percentage you need on an upcoming assignment</p>
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-5">
                                            <label class="form-label">Assignment Weight</label>
                                            <select class="form-select" id="assignmentWeightSelect">
                                                <option value="">Select category or custom...</option>
                                                <optgroup label="Categories" id="categoryWeightOptions"></optgroup>
                                                <option value="custom">Custom Weight</option>
                                            </select>
                                            <input type="number" class="form-control mt-2 d-none" id="assignmentWeight" min="0.1" max="100" placeholder="Enter weight %" step="0.1">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Target Grade (%)</label>
                                            <input type="number" class="form-control" id="targetGrade" min="0" max="100" value="90" placeholder="90">
                                            <small class="text-muted">Your goal for the class</small>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="btn btn-success w-100" id="calculateRequiredGrade">
                                                <i class="bi bi-calculator"></i> Calculate
                                            </button>
                                        </div>
                                    </div>
                                    <div id="requiredGradeResult" class="mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analyze Mode Content (Hidden by Default) -->
                    <div class="analyze-mode-content">
                        <!-- Performance Analysis Header -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Performance Analysis</h5>
                            </div>
                        </div>

                        <!-- Statistics Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <small class="text-muted d-block mb-1">Average</small>
                                        <h4 class="mb-0" id="analysisAverage">N/A</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <small class="text-muted d-block mb-1">Completed</small>
                                        <h4 class="mb-0" id="analysisCompleted">0/0</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <small class="text-muted d-block mb-1">Highest</small>
                                        <h4 class="mb-0 text-success" id="analysisHighest">N/A</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center">
                                        <small class="text-muted d-block mb-1">Lowest</small>
                                        <h4 class="mb-0 text-danger" id="analysisLowest">N/A</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Chart -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Performance Trend</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="expandChartBtn">
                                            <i class="bi bi-arrows-fullscreen"></i> <span class="d-none d-md-inline">Expand</span>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 300px; position: relative;">
                                            <canvas id="performanceChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grade Distribution -->
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent">
                                        <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Grade Distribution</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="grade-distribution-chart mb-3">
                                            <div class="progress" style="height: 30px;">
                                                <div class="progress-bar bg-success" id="distA" style="width: 0%;" title="A (90-100%)">0%</div>
                                                <div class="progress-bar bg-primary" id="distB" style="width: 0%;" title="B (80-89%)">0%</div>
                                                <div class="progress-bar bg-warning text-dark" id="distC" style="width: 0%;" title="C (70-79%)">0%</div>
                                                <div class="progress-bar bg-danger" id="distDF" style="width: 0%;" title="D/F (0-69%)">0%</div>
                                            </div>
                                        </div>
                                        <div class="grade-distribution-key text-center">
                                            <small>
                                                <span class="me-3"><span class="badge bg-success">A</span> 90-100%</span>
                                                <span class="me-3"><span class="badge bg-primary">B</span> 80-89%</span>
                                                <span class="me-3"><span class="badge bg-warning text-dark">C</span> 70-79%</span>
                                                <span><span class="badge bg-danger">D/F</span> 0-69%</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assignments List (Always Visible) -->
                    <div class="assignments-section">
                        <div class="d-flex justify-content-between align-items-center mb-3 d-none">
                            <h5 class="mb-0"><i class="bi bi-journal-text me-2"></i>All Assignments</h5>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="assignmentView" id="viewCompact" autocomplete="off" checked>
                                <label class="btn btn-outline-secondary" for="viewCompact">
                                    <i class="bi bi-list"></i> <span class="d-none d-md-inline"></span>
                                </label>
                                
                                <input type="radio" class="btn-check" name="assignmentView" id="viewDetailed" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="viewDetailed">
                                    <i class="bi bi-grid-3x2"></i> <span class="d-none d-md-inline"></span>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Compact View (Default) -->
                        <div id="assignmentsCompactView" class="assignments-compact">
                            <div id="assignmentsListCompact">
                                <!-- Assignments will be rendered here -->
                            </div>
                        </div>
                        
                        <!-- Detailed View -->
                        <div id="assignmentsDetailedView" class="assignments-detailed d-none">
                            <div class="row g-3" id="assignmentsGrid">
                                <!-- Assignments will be rendered here -->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="detailTimeoutAlert" class="alert alert-danger d-none mt-3"></div>
            </div>
        </div>

        <!-- IPR/Report Card View -->
        <div id="iprRcView" class="d-none">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="flex-grow-1">
                    <h4 class="mb-0" id="iprRcTitle" style="display: none;"></h4>
                </div>
            </div>

            <div class="card table-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0" id="iprRcTable">
                            <thead class="table-dark">
                                <tr id="iprRcHeaders">
                                    <!-- Headers will be populated dynamically -->
                                </tr>
                            </thead>
                            <tbody id="iprRcData">
                                <!-- Data will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <p class="mt-2"><i><i class="bi bi-info-circle-fill"></i> Columns with no data are not shown.</i></p>
        </div>

        <!-- IPR/RC Loader -->
        <div id="iprRcLoader" class="card d-none">
            <div class="card-body text-center py-5">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border me-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold" id="iprRcLoaderText">Loading</p>
                        <small class="text-muted">One moment...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Mock Assignment Modal -->
<div class="modal fade" id="addMockAssignmentModal" tabindex="-1" aria-labelledby="addMockAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMockAssignmentModalLabel">Add Mock Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="mockAssignmentForm">
                    <div class="mb-3">
                        <label class="form-label">Assignment Name</label>
                        <input type="text" class="form-control" id="mockAssignmentName" placeholder="Unit Test">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" id="mockAssignmentCategory">
                            <option value="Custom">Custom</option>
                            <!-- Categories will be populated dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Weight (%)</label>
                        <input type="number" class="form-control" id="mockAssignmentWeight" min="1" max="100" placeholder="50" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Points</label>
                        <input type="number" class="form-control" id="mockAssignmentTotal" min="1" placeholder="100" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Your Score</label>
                        <input type="number" class="form-control" id="mockAssignmentScore" min="0" placeholder="43" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveMockAssignment">Add Assignment</button>
            </div>
        </div>
    </div>
</div>


<!-- Expanded Chart Modal -->
<div class="modal fade" id="expandedChartModal" tabindex="-1" aria-labelledby="expandedChartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expandedChartModalLabel">
                    <i class="bi bi-graph-up me-2"></i>Performance Analysis
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div style="height: 60vh; position: relative;">
                                    <canvas id="expandedPerformanceChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent">
                                <h6 class="mb-0"><i class="bi bi-clipboard-data me-2"></i>Assignment Impact Analysis</h6>
                            </div>
                            <div class="card-body">
                                <!-- Desktop: Centered layout -->
                                <div class="d-none d-md-block">
                                    <div class="row">
                                        <div class="col-lg-10 offset-lg-1 col-xl-8 offset-xl-2">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-sm">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th style="width: 35%;">Assignment</th>
                                                            <th style="width: 20%;">Category</th>
                                                            <th class="text-end" style="width: 15%;">Score</th>
                                                            <th class="text-end" style="width: 15%;">Grade</th>
                                                            <th class="text-end" style="width: 15%;">Impact</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="expandedAssignmentImpact">
                                                        <!-- Will be populated dynamically -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Mobile: Full width with horizontal scroll -->
                                <div class="d-md-none">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-sm" style="min-width: 600px;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Assignment</th>
                                                    <th>Category</th>
                                                    <th class="text-end">Score</th>
                                                    <th class="text-end">Grade</th>
                                                    <th class="text-end">Impact</th>
                                                </tr>
                                            </thead>
                                            <tbody id="expandedAssignmentImpactMobile">
                                                <!-- Will be populated dynamically -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-arrows-expand me-1"></i>Swipe to scroll horizontally
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Assignment Detail Modal -->
<div class="modal fade" id="mobileAssignmentModal" tabindex="-1" aria-labelledby="mobileAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mobileAssignmentModalLabel">Assignment Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="mobileAssignmentDetails">
                <!-- Content will be populated here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- GPA Modal -->
<div class="modal fade" id="gpaModal" tabindex="-1" aria-labelledby="gpaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gpaModalLabel">
                    <i class="bi bi-graph-up me-2"></i>GPA Summary
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row text-center mb-4">
                    <div class="col-md-6 mb-3">
                        <div class="card border-0">
                            <div class="card-body">
                                <h6 class="text-muted">Unweighted GPA</h6>
                                <div class="display-4 fw-bold text-primary" id="unweightedGpa">--</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card border-0">
                            <div class="card-body">
                                <h6 class="text-muted">Weighted GPA</h6>
                                <div class="display-4 fw-bold text-success" id="weightedGpa">--</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Class Weights Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-sliders me-2"></i>Class Weights</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Class</th>
                                        <th>Current Weight</th>
                                        <th>Set Weight</th>
                                    </tr>
                                </thead>
                                <tbody id="classWeightsTable">
                                    <!-- Class weights will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary d-none" data-bs-toggle="modal" data-bs-target="#gpaPredictionModal" data-bs-dismiss="modal">
                        <i class="bi bi-calculator me-1"></i> Predict GPA
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#gpaWeightModal" data-bs-dismiss="modal">
                        <i class="bi bi-sliders me-1"></i> Configure Weights
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- GPA Prediction Modal -->
<div class="modal fade" id="gpaPredictionModal" tabindex="-1" aria-labelledby="gpaPredictionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gpaPredictionModalLabel">
                    <i class="bi bi-calculator me-2"></i>GPA Prediction
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Current GPA</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Unweighted:</span>
                                    <strong id="currentUnweightedGpa">--</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Weighted:</span>
                                    <strong id="currentWeightedGpa">--</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Add Grade Prediction</h6>
                            </div>
                            <div class="card-body">
                                <form id="gpaPredictionForm">
                                    <div class="mb-2">
                                        <label class="form-label">Class Name</label>
                                        <input type="text" class="form-control" id="predClass" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Class Type</label>
                                        <select class="form-select" id="predClassType" required>
                                            <option value="standard">Standard</option>
                                            <option value="honors">Honors</option>
                                            <option value="ap">AP/Advanced</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Credits</label>
                                        <input type="number" class="form-control" id="predCredits" min="0.5" max="2" step="0.5" value="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Predicted Grade (%)</label>
                                        <input type="number" class="form-control" id="predGrade" min="0" max="100" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">Add Prediction</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">Prediction Results</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <div class="display-4 fw-bold text-primary" id="predictedUnweightedGpa">--</div>
                                    <span class="text-muted">Predicted Unweighted GPA</span>
                                </div>
                                <div class="text-center mb-3">
                                    <div class="display-4 fw-bold text-success" id="predictedWeightedGpa">--</div>
                                    <span class="text-muted">Predicted Weighted GPA</span>
                                </div>
                                
                                <div class="mt-3">
                                    <h6>Predicted Classes:</h6>
                                    <ul id="gpaPredictionList" class="list-group list-group-flush">
                                        <li class="list-group-item small text-muted">No predictions added yet</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button id="resetGpaPredictions" class="btn btn-outline-secondary">Reset Predictions</button>
                            <button id="saveGpaPredictions" class="btn btn-success">Save Predictions</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#gpaModal">
                    <i class="bi bi-arrow-left me-1"></i> Back to GPA Summary
                </button>
            </div>
        </div>
    </div>
</div>

<!-- GPA Weight Configuration Modal -->
<div class="modal fade" id="gpaWeightModal" tabindex="-1" aria-labelledby="gpaWeightModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gpaWeightModalLabel">
                    <i class="bi bi-sliders me-2"></i>GPA Weight Configuration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">Weight Bonuses</h6>
                    </div>
                    <div class="card-body">
                        <form id="weightConfigForm">
                            <div class="mb-3">
                                <label class="form-label">Honors Weight Bonus</label>
                                <input type="number" class="form-control" id="honorsWeight" min="0" max="1" step="0.1" required>
                                <div class="form-text">Added to the base grade points for Honors classes</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">AP/Advanced Weight Bonus</label>
                                <input type="number" class="form-control" id="apWeight" min="0" max="1" step="0.1" required>
                                <div class="form-text">Added to the base grade points for AP/Advanced classes</div>
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Save Weights</button>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-3 border-0 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">Current Configuration</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Honors Bonus:</span>
                            <strong id="currentHonorsWeight">--</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>AP/Advanced Bonus:</span>
                            <strong id="currentApWeight">--</strong>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#gpaModal">
                    <i class="bi bi-arrow-left me-1"></i> Back to GPA Summary
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js for performance charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
(function() {
  'use strict';

  // config and constants

  const CONFIG = {
    CACHE_DURATION: 30 * 60 * 1000, // 30 minutes
    GRADE_SCALE: {
      'A+': { points: 4.3, min: 97 },
      'A': { points: 4.0, min: 93 },
      'A-': { points: 3.7, min: 90 },
      'B+': { points: 3.3, min: 87 },
      'B': { points: 3.0, min: 83 },
      'B-': { points: 2.7, min: 80 },
      'C+': { points: 2.3, min: 77 },
      'C': { points: 2.0, min: 73 },
      'C-': { points: 1.7, min: 70 },
      'D+': { points: 1.3, min: 67 },
      'D': { points: 1.0, min: 63 },
      'D-': { points: 0.7, min: 60 },
      'F': { points: 0.0, min: 0 }
    }
  };

  // utilitityiesis funrctions

  const Utils = {
    setCookie(name, value, days = 365) {
      const expires = new Date();
      expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
      document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    },

    getCookie(name) {
      const nameEQ = name + "=";
      const ca = document.cookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
    },

    formatPercentage(value) {
      if (value == null || isNaN(value)) return 'N/A';
      return `${(Math.round(value * 100) / 100).toFixed(2)}%`;
    },

    formatAverage(value) {
      if (value == null || isNaN(value)) return 'N/A';
      //return Math.round(value * 100) / 100;
      return value;
    },

    percentageToLetter(percentage) {
      if (percentage == null || percentage === 'N/A' || isNaN(parseFloat(percentage))) return null;
      const num = parseFloat(percentage);
      
      for (const [grade, info] of Object.entries(CONFIG.GRADE_SCALE)) {
        if (num >= info.min) return grade;
      }
      return 'F';
    },

    getGradeColor(percentage) {
      if (percentage === 'N/A') return 'dark';
      const num = parseFloat(percentage);
      if (isNaN(num)) return 'dark';
      
      if (num >= 90) return 'success';
      if (num >= 80) return 'primary';
      if (num >= 70) return 'warning text-dark';
      return 'danger';
    },

    getBorderColor(percentage) {
      if (percentage === 'N/A') return 'border-dark';
      const num = parseFloat(percentage);
      if (isNaN(num)) return 'border-dark';
      
      if (num >= 90) return 'border-success';
      if (num >= 80) return 'border-primary';
      if (num >= 70) return 'border-warning';
      return 'border-danger';
    },

calculateGradePoints(percentage, classType = 'standard', scale = 4.0) {
  if (percentage === 'N/A' || isNaN(parseFloat(percentage))) return 0;
  
  const numPercentage = parseFloat(percentage);
  const letter = Utils.percentageToLetter(numPercentage);
  if (!letter) return 0;
  
  // Get base points from letter grade
  let points = CONFIG.GRADE_SCALE[letter].points;
  
  // Important: DON'T apply class type bonuses here if scale is already set
  // The scale from the database already includes the bonus
  // Only apply bonuses if using default 4.0 scale
  const scaleNum = parseFloat(scale);
  
  if (scaleNum === 4.0 && classType !== 'standard') {
    // Only add bonuses if we're using the standard 4.0 scale
    if (classType === 'honors') points += 0.5;
    if (classType === 'ap') points += 1.0;
  }
  
  // Cap at the specified scale
  return Math.min(points, scaleNum);
},

debugGPACalculation(classes, gpaScales) {
  console.group('GPA Calculation Debug');
  console.log('GPA Scales:', gpaScales);
  
  let totalUnweighted = 0;
  let totalWeighted = 0;
  let totalCredits = 0;
  
  classes.forEach(cls => {
    if (cls.dropped) {
      console.log(`${cls.name}: DROPPED`);
      return;
    }
    
    const scale = gpaScales[cls.code] || '4.0';
    const scaleNum = parseFloat(scale);
    
    if (scaleNum === 0.0) {
      console.log(`${cls.name}: EXEMPT (0.0 scale)`);
      return;
    }
    
    if (cls.percentage && cls.percentage !== 'N/A') {
      const percentage = parseFloat(cls.percentage);
      const unweighted = Utils.getUnweightedGradePoints(percentage, 4.0);
      
      let weighted;
      if (scaleNum === 4.5) {
        weighted = Math.min(unweighted + 0.5, 4.5);
      } else if (scaleNum === 5.0) {
        weighted = Math.min(unweighted + 1.0, 5.0);
      } else {
        weighted = unweighted;
      }
      
      console.log(`${cls.name}: ${percentage}% = ${unweighted} unweighted, ${weighted} weighted (scale: ${scale}, credits: ${cls.credits})`);
      
      totalUnweighted += unweighted * cls.credits;
      totalWeighted += weighted * cls.credits;
      totalCredits += cls.credits;
    }
  });
  
  console.log('---');
  console.log(`Total Credits: ${totalCredits}`);
  console.log(`Unweighted GPA: ${(totalUnweighted / totalCredits).toFixed(2)}`);
  console.log(`Weighted GPA: ${(totalWeighted / totalCredits).toFixed(2)}`);
  console.groupEnd();
},

getUnweightedGradePoints(percentage, scale = 4.0) {
  if (percentage === 'N/A' || isNaN(parseFloat(percentage))) return 0;
  
  const numPercentage = parseFloat(percentage);
  const letter = Utils.percentageToLetter(numPercentage);
  if (!letter) return 0;
  
  // Get base points - always use standard 4.0 scale for unweighted
  let points = CONFIG.GRADE_SCALE[letter].points;
  
  // Always cap at 4.0 for unweighted GPA
  return Math.min(points, 4.0);
},

    formatRelativeTime(timestamp) {
      const now = Date.now();
      const diff = now - timestamp;
      const minutes = Math.floor(diff / 60000);
      const hours = Math.floor(diff / 3600000);
      
      if (minutes < 1) return 'Last updated: Just now';
      if (minutes < 60) return `Last updated: ${minutes}m ago`;
      if (hours < 24) return `Last updated: ${hours}h ago`;
      
      const date = new Date(timestamp);
      return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    },

    formatDate(dateString) {
      if (!dateString) return 'Unknown Date';
      try {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
          weekday: 'long', 
          year: 'numeric', 
          month: 'long', 
          day: 'numeric' 
        });
      } catch (e) {
        return dateString;
      }
    },

    encodeDataForUrl(data) {
      return btoa(JSON.stringify(data));
    },

    debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }
  };

  // cache manager (broken asf)

class CacheManager {
  constructor() {
    this.prefix = 'grades_';
    this.duration = CONFIG.CACHE_DURATION;
    this.temporaryCache = new Map(); // In-memory cache for immediate use
  }

  set(key, data, temporary = false) {
    try {
      if (temporary) {
        // Store in memory only for temporary caching
        this.temporaryCache.set(key, {
          data,
          timestamp: Date.now(),
          expires: Date.now() + 60000 // 1 minute for temporary cache
        });
        console.log('Temporarily cached:', key);
        return true;
      }
      
      const item = {
        data,
        timestamp: Date.now(),
        expires: Date.now() + this.duration
      };
      localStorage.setItem(this.prefix + key, JSON.stringify(item));
      console.log('Cached:', key);
      return true;
    } catch (e) {
      console.warn('Cache set failed:', e);
      this.clearExpired();
      return false;
    }
  }

  get(key, includeTemporary = true) {
    // Check temporary cache first
    if (includeTemporary && this.temporaryCache.has(key)) {
      const cached = this.temporaryCache.get(key);
      if (Date.now() <= cached.expires) {
        console.log('Temporary cache hit:', key);
        return cached.data;
      } else {
        this.temporaryCache.delete(key);
      }
    }
    
    try {
      const item = localStorage.getItem(this.prefix + key);
      if (!item) return null;
      
      const cached = JSON.parse(item);
      if (Date.now() > cached.expires) {
        this.remove(key);
        return null;
      }
      
      console.log('Cache hit:', key);
      return cached.data;
    } catch (e) {
      console.warn('Cache get failed:', e);
      return null;
    }
  }

  remove(key) {
    localStorage.removeItem(this.prefix + key);
    this.temporaryCache.delete(key);
  }

  clearAll() {
    console.log('Clearing all cache');
    const keys = Object.keys(localStorage);
    keys.forEach(key => {
      if (key.startsWith(this.prefix)) {
        localStorage.removeItem(key);
      }
    });
    this.temporaryCache.clear();
  }

  clearTemporary() {
    console.log('Clearing temporary cache');
    this.temporaryCache.clear();
  }
  
  cleanupTemporaryCache() {
    // Remove expired items from temporary cache
    const now = Date.now();
    for (const [key, value] of this.temporaryCache.entries()) {
      if (now > value.expires) {
        this.temporaryCache.delete(key);
        console.log('Removed expired temporary cache:', key);
      }
    }
  }

  clearByPattern(pattern) {
    console.log('Clearing cache with pattern:', pattern);
    const keys = Object.keys(localStorage);
    keys.forEach(key => {
      if (key.startsWith(this.prefix) && key.includes(pattern)) {
        localStorage.removeItem(key);
        console.log('Removed:', key);
      }
    });
    
    // Clear matching temporary cache entries
    for (const [key, value] of this.temporaryCache.entries()) {
      if (key.includes(pattern)) {
        this.temporaryCache.delete(key);
      }
    }
  }

  clearExceptCurrentView(currentView) {
    console.log('Clearing cache except for:', currentView);
    const keys = Object.keys(localStorage);
    const keepPatterns = [];
    
    // Define what to keep based on current view
    if (currentView === 'running-average') {
      keepPatterns.push('running_average', 'gpa_scales', 'weight_config');
    } else if (currentView === 'ipr') {
      keepPatterns.push('ipr_', 'gpa_scales', 'weight_config');
    } else if (currentView === 'report-card') {
      keepPatterns.push('rc_', 'gpa_scales', 'weight_config');
    }
    
    keys.forEach(key => {
      if (key.startsWith(this.prefix)) {
        const shouldKeep = keepPatterns.some(pattern => key.includes(pattern));
        if (!shouldKeep) {
          localStorage.removeItem(key);
        }
      }
    });
    
    // Clear temporary cache entirely when switching views
    this.temporaryCache.clear();
  }

  clearExpired() {
    const now = Date.now();
    const keys = Object.keys(localStorage);
    keys.forEach(key => {
      if (key.startsWith(this.prefix)) {
        try {
          const item = JSON.parse(localStorage.getItem(key));
          if (now > item.expires) {
            localStorage.removeItem(key);
          }
        } catch (e) {
          localStorage.removeItem(key);
        }
      }
    });
    
    // Clear expired temporary cache
    for (const [key, value] of this.temporaryCache.entries()) {
      if (now > value.expires) {
        this.temporaryCache.delete(key);
      }
    }
  }

  getInfo() {
    const info = {};
    const keys = Object.keys(localStorage);
    keys.forEach(key => {
      if (key.startsWith(this.prefix)) {
        try {
          const item = JSON.parse(localStorage.getItem(key));
          info[key] = {
            age: Math.round((Date.now() - item.timestamp) / 1000 / 60) + ' min',
            expiresIn: Math.round((item.expires - Date.now()) / 1000 / 60) + ' min'
          };
        } catch (e) {
          info[key] = 'Error';
        }
      }
    });
    
    // Add temporary cache info
    for (const [key, value] of this.temporaryCache.entries()) {
      info['temp_' + key] = {
        age: Math.round((Date.now() - value.timestamp) / 1000) + ' sec',
        expiresIn: Math.round((value.expires - Date.now()) / 1000) + ' sec',
        temporary: true
      };
    }
    
    return info;
  }
}

  // request manager thingamabob

  class RequestManager {
    constructor() {
      this.activeRequests = new Map();
      this.requestQueue = new Map();
    }

    create(requestId) {
      this.abort(requestId);
      const controller = new AbortController();
      this.activeRequests.set(requestId, {
        controller,
        timestamp: Date.now()
      });
      console.log('Created request:', requestId);
      return controller;
    }

    abort(requestId) {
      if (this.activeRequests.has(requestId)) {
        const request = this.activeRequests.get(requestId);
        try {
          request.controller.abort();
          console.log('Aborted request:', requestId);
        } catch (e) {
          console.warn('Error aborting request:', requestId, e);
        }
        this.activeRequests.delete(requestId);
      }
    }

    abortAll() {
      console.log('Aborting all requests:', this.activeRequests.size);
      const requests = Array.from(this.activeRequests.entries());
      requests.forEach(([id, request]) => {
        try {
          request.controller.abort();
        } catch (e) {
          console.warn('Error aborting request:', id, e);
        }
      });
      this.activeRequests.clear();
    }
    
    abortByPattern(pattern) {
      console.log('Aborting requests matching pattern:', pattern);
      const requests = Array.from(this.activeRequests.entries());
      requests.forEach(([id, request]) => {
        if (id.includes(pattern)) {
          try {
            request.controller.abort();
            this.activeRequests.delete(id);
            console.log('Aborted:', id);
          } catch (e) {
            console.warn('Error aborting:', id, e);
          }
        }
      });
    }

    complete(requestId) {
      this.activeRequests.delete(requestId);
      console.log('Completed request:', requestId);
    }

    async fetch(requestId, url, options = {}) {
      const controller = this.create(requestId);
      
      try {
        const response = await fetch(url, {
          ...options,
          signal: controller.signal
        });
        
        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        this.complete(requestId);
        return data;
      } catch (error) {
        if (error.name === 'AbortError') {
          console.log('Request aborted:', requestId);
          return null;
        }
        this.complete(requestId);
        throw error;
      }
    }
    
    isActive(requestId) {
      return this.activeRequests.has(requestId);
    }
    
    cleanup() {
      // Clean up old requests (older than 5 minutes)
      const now = Date.now();
      const timeout = 5 * 60 * 1000;
      const requests = Array.from(this.activeRequests.entries());
      requests.forEach(([id, request]) => {
        if (now - request.timestamp > timeout) {
          this.abort(id);
        }
      });
    }
  }

  // grade calc :bangbang:

  class GradeCalculator {
static debugCategoryWeights(categories, assignments) {
  console.group('Category Weight Debug');
  console.log('Raw categories from API:', categories);
  
  if (categories && categories.length > 1) {
    categories.slice(1).forEach(categoryRow => {
      console.log(`Category: ${categoryRow[0]}, Weight: ${categoryRow[4]}%`);
    });
  }
  
  const categoryCounts = {};
  assignments.forEach(a => {
    categoryCounts[a.category] = (categoryCounts[a.category] || 0) + 1;
  });
  console.log('Assignments per category:', categoryCounts);
  console.groupEnd();
}

static calculateWeightedAverage(assignments, mockAssignments, categories, exemptSet) {
  const categoryPerformance = {};
  
  // Initialize categories from the API data
  if (categories && categories.length > 1) {
    categories.slice(1).forEach(categoryRow => {
      // categoryRow structure: [name, earned, possible, percentage, weight]
      if (categoryRow[0] && categoryRow[0] !== 'Total Points:' && categoryRow[4]) {
        const categoryName = categoryRow[0];
        const weightPercent = parseFloat(categoryRow[4]);
        
        if (!isNaN(weightPercent) && weightPercent > 0) {
          categoryPerformance[categoryName] = {
            points: 0,
            maxPoints: 0,
            weight: weightPercent / 100,
            percentage: 0
          };
        }
      }
    });
  }
  
  // If no categories were found, return NaN
  if (Object.keys(categoryPerformance).length === 0) {
    console.warn('No valid categories found for weighted average calculation');
    return NaN;
  }
  
  // Process regular assignments
  assignments.forEach((assignment, index) => {
    if (exemptSet.has(index)) return;
    
    const hasValidScore = assignment.score !== null && assignment.score !== undefined && assignment.score !== '' && !isNaN(assignment.score);
    const hasValidTotal = !isNaN(assignment.total) && assignment.total > 0;
    
    if (hasValidScore && hasValidTotal) {
      const category = assignment.category;
      
      // Only add to existing categories
      if (categoryPerformance[category]) {
        categoryPerformance[category].points += parseFloat(assignment.score);
        categoryPerformance[category].maxPoints += parseFloat(assignment.total);
      } else {
        console.warn(`Assignment category "${category}" not found in category weights`);
      }
    }
  });
  
  // Process mock assignments
  mockAssignments.forEach(assignment => {
    if (assignment.exempt) return;
    
    const hasValidScore = assignment.score !== null && assignment.score !== undefined && assignment.score !== '' && !isNaN(assignment.score);
    const hasValidTotal = !isNaN(assignment.total) && assignment.total > 0;
    
    if (hasValidScore && hasValidTotal) {
      const category = assignment.category;
      
      // For mock assignments with custom categories or weights
      if (!categoryPerformance[category]) {
        // Use the custom weight provided by the mock assignment
        categoryPerformance[category] = {
          points: 0,
          maxPoints: 0,
          weight: assignment.weight / 100,
          percentage: 0
        };
      }
      
      categoryPerformance[category].points += parseFloat(assignment.score);
      categoryPerformance[category].maxPoints += parseFloat(assignment.total);
    }
  });
  
  // Calculate percentage for each category
  Object.keys(categoryPerformance).forEach(category => {
    const data = categoryPerformance[category];
    if (data.maxPoints > 0) {
      data.percentage = (data.points / data.maxPoints) * 100;
    }
  });
  
  // Calculate weighted average
  let weightedTotal = 0;
  let totalWeight = 0;
  
  Object.values(categoryPerformance).forEach(data => {
    // Only include categories that have assignments
    if (data.maxPoints > 0 && data.weight > 0 && !isNaN(data.percentage)) {
      weightedTotal += data.percentage * data.weight;
      totalWeight += data.weight;
    }
  });
  
  // Return weighted average
  if (totalWeight > 0) {
    return weightedTotal / totalWeight;
  }
  
  return NaN;
}
static calculateGPA(classes, gpaScales) {
  let totalUnweightedPoints = 0;
  let totalWeightedPoints = 0;
  let totalCredits = 0;
  
  console.group('GPA Calculation');
  
  classes.forEach(cls => {
    // Skip dropped classes
    if (cls.dropped) {
      console.log(`${cls.name}: DROPPED - skipping`);
      return;
    }
    
    // Get the scale for this class (defaults to 4.0 if not set)
    const scale = gpaScales[cls.code] || '4.0';
    const scaleNum = parseFloat(scale);
    
    // Skip classes with 0.0 scale (exempt from GPA)
    if (scaleNum === 0.0) {
      console.log(`${cls.name}: EXEMPT (0.0 scale) - skipping`);
      return;
    }
    
    // Validate percentage and credits
    if (cls.percentage && cls.percentage !== 'N/A' && !isNaN(parseFloat(cls.percentage))) {
      const percentage = parseFloat(cls.percentage);
      const credits = parseFloat(cls.credits) || 1.0;
      
      if (credits <= 0) {
        console.log(`${cls.name}: Invalid credits (${credits}) - skipping`);
        return;
      }
      
      // Convert percentage to letter grade
      const letter = Utils.percentageToLetter(percentage);
      if (!letter || !CONFIG.GRADE_SCALE[letter]) {
        console.log(`${cls.name}: Invalid grade (${percentage}%) - skipping`);
        return;
      }
      
      // Get base grade points (on 4.0 scale)
      const basePoints = CONFIG.GRADE_SCALE[letter].points;
      
      // Calculate UNWEIGHTED points (always capped at 4.0)
      const unweightedPoints = Math.min(basePoints, 4.0);
      
      // Calculate WEIGHTED points based on scale
      let weightedPoints = basePoints;
      if (scaleNum === 4.5) {
        // Honors: add 0.5 to base points, cap at 4.5
        weightedPoints = Math.min(basePoints + 0.5, 4.5);
      } else if (scaleNum === 5.0) {
        // AP: add 1.0 to base points, cap at 5.0
        weightedPoints = Math.min(basePoints + 1.0, 5.0);
      } else if (scaleNum !== 4.0) {
        // Custom scale: proportionally scale the points
        weightedPoints = Math.min(basePoints * (scaleNum / 4.0), scaleNum);
      }
      
      console.log(`${cls.name}: ${percentage}% = ${letter} = ${unweightedPoints.toFixed(2)} unweighted, ${weightedPoints.toFixed(2)} weighted (scale: ${scale}, credits: ${credits})`);
      
      totalUnweightedPoints += unweightedPoints * credits;
      totalWeightedPoints += weightedPoints * credits;
      totalCredits += credits;
    } else {
      console.log(`${cls.name}: No valid grade - skipping`);
    }
  });
  
  const unweightedGPA = totalCredits > 0 ? totalUnweightedPoints / totalCredits : 0;
  const weightedGPA = totalCredits > 0 ? totalWeightedPoints / totalCredits : 0;
  
  console.log('---');
  console.log(`Total Credits: ${totalCredits.toFixed(2)}`);
  console.log(`Total Unweighted Points: ${totalUnweightedPoints.toFixed(2)}`);
  console.log(`Total Weighted Points: ${totalWeightedPoints.toFixed(2)}`);
  console.log(`Unweighted GPA: ${unweightedGPA.toFixed(3)} → ${Math.round(unweightedGPA * 100) / 100}`);
  console.log(`Weighted GPA: ${weightedGPA.toFixed(3)} → ${Math.round(weightedGPA * 100) / 100}`);
  console.groupEnd();
  
  return {
    unweighted: Math.round(unweightedGPA * 100) / 100,
    weighted: Math.round(weightedGPA * 100) / 100,
    credits: totalCredits
  };
}
    static calculateRequiredScore(currentAverage, targetAverage, assignmentWeight) {
      return (targetAverage - (currentAverage * (1 - assignmentWeight))) / assignmentWeight;
    }
  }

  // the bread and butter: main application

  class GradeManager {
    constructor() {
      this.cache = new CacheManager();
      this.requests = new RequestManager();
      
      // State
      this.state = {
        currentView: 'running-average',
        currentDataType: 'assignments',
        currentClasses: [],
        allClassesData: [],
        availableRuns: {},
        currentRun: '',
        gpaScales: {},
        weightConfig: { honors: 0.5, ap: 1.0 },
        gpaPredictions: [],
        fullApiResponse: null,
        currentDetailClass: null,
        assignmentsData: [],
        mockAssignmentsData: [],
        currentDetailAverage: 0,
        predictMode: false,
        originalScores: new Map(),
        exemptAssignments: new Set(),
        lastUpdated: null,
        mockCounter: 1,
        availableDates: [],
        selectedDate: null,
        currentIprRcType: null,
        performanceChartInstance: null,
        expandedChartInstance: null
      };
      
      this.elements = this.cacheElements();
      
      // Initialise
      this.init();
    }

    cacheElements() {
      return {
        loader: document.getElementById('loader'),
        errorContainer: document.getElementById('errorContainer'),
        classesGridContainer: document.getElementById('classesGridContainer'),
        classDetailView: document.getElementById('classDetailView'),
        iprRcView: document.getElementById('iprRcView'),
        runSelectorContainer: document.getElementById('runSelectorContainer'),
        dateSelectorContainer: document.getElementById('dateSelectorContainer'),
        gradesTabs: document.getElementById('gradesTabs'),
        gpaButton: document.getElementById('gpaButton'),
        unweightedGpa: document.getElementById('unweightedGpa'),
        weightedGpa: document.getElementById('weightedGpa'),
        backToClasses: document.getElementById('backToClasses'),
        detailClassName: document.getElementById('detailClassName'),
        detailAvgBadge: document.getElementById('detailAvgBadge'),
        detailLetterGrade: document.getElementById('detailLetterGrade'),
        detailLoader: document.getElementById('detailLoader'),
        classDetailContent: document.getElementById('classDetailContent'),
        assignmentsListCompact: document.getElementById('assignmentsListCompact'),
        assignmentsGrid: document.getElementById('assignmentsGrid'),
        assignmentsCompactView: document.getElementById('assignmentsCompactView'),
        assignmentsDetailedView: document.getElementById('assignmentsDetailedView'),
        mockAssignmentsTable: document.getElementById('mockAssignmentsTable'),
        mockAssignmentSection: document.getElementById('mockAssignmentSection'),
        iprRcHeaders: document.getElementById('iprRcHeaders'),
        iprRcData: document.getElementById('iprRcData'),
        iprRcLoader: document.getElementById('iprRcLoader'),
        iprRcTitle: document.getElementById('iprRcTitle'),
        gradeGoalCalculator: document.getElementById('gradeGoalCalculator'),
        addMockAssignment: document.getElementById('addMockAssignment'),
        saveMockAssignment: document.getElementById('saveMockAssignment'),
        mockAssignmentName: document.getElementById('mockAssignmentName'),
        mockAssignmentCategory: document.getElementById('mockAssignmentCategory'),
        mockAssignmentWeight: document.getElementById('mockAssignmentWeight'),
        mockAssignmentTotal: document.getElementById('mockAssignmentTotal'),
        mockAssignmentScore: document.getElementById('mockAssignmentScore'),
        calculateRequiredGrade: document.getElementById('calculateRequiredGrade'),
        targetGrade: document.getElementById('targetGrade'),
        targetWeight: document.getElementById('targetWeight'),
        requiredGradeResult: document.getElementById('requiredGradeResult'),
        analysisAverage: document.getElementById('analysisAverage'),
        analysisCompleted: document.getElementById('analysisCompleted'),
        analysisHighest: document.getElementById('analysisHighest'),
        analysisLowest: document.getElementById('analysisLowest'),
        analysisStdDev: document.getElementById('analysisStdDev'),
        categoryWeights: document.getElementById('categoryWeights'),
        categoryAverages: document.getElementById('categoryAverages'), 
        distA: document.getElementById('distA'),
        distB: document.getElementById('distB'),
        distC: document.getElementById('distC'),
        distDF: document.getElementById('distDF'),
        successToast: document.getElementById('successToast'),
        classWeightsTable: document.getElementById('classWeightsTable'),
        currentUnweightedGpa: document.getElementById('currentUnweightedGpa'),
        currentWeightedGpa: document.getElementById('currentWeightedGpa'),
        predictedUnweightedGpa: document.getElementById('predictedUnweightedGpa'),
        predictedWeightedGpa: document.getElementById('predictedWeightedGpa'),
        gpaPredictionList: document.getElementById('gpaPredictionList'),
        gpaPredictionForm: document.getElementById('gpaPredictionForm'),
        saveGpaPredictions: document.getElementById('saveGpaPredictions'),
        resetGpaPredictions: document.getElementById('resetGpaPredictions'),
        weightConfigForm: document.getElementById('weightConfigForm'),
        currentHonorsWeight: document.getElementById('currentHonorsWeight'),
        currentApWeight: document.getElementById('currentApWeight')
      };
    }

    init() {
      this.setupEventListeners();
      this.setupNavigationHandling();
      
      // Set default view based on device and saved preference
      this.initializeViewPreference();
      
      this.cache.clearAll();
      console.log('Cleared all cache on page load');
      
      this.loadInitialData();
      
      this.lastUpdatedTimer = setInterval(() => {
        this.updateLastUpdatedDisplay();
      }, 15000);
      
      // Periodic cleanup of old requests
      this.cleanupTimer = setInterval(() => {
        this.requests.cleanup();
        this.cache.cleanupTemporaryCache();
      }, 60000); // Every minute
    }
    
    initializeViewPreference() {
      const savedView = Utils.getCookie('assignmentView');
      const isMobile = window.innerWidth < 768;
      
      let defaultView = 'compact';
      if (!isMobile && !savedView) {
        defaultView = 'detailed';
      } else if (savedView) {
        defaultView = savedView;
      }
      
      const viewCompact = document.getElementById('viewCompact');
      const viewDetailed = document.getElementById('viewDetailed');
      
      if (defaultView === 'detailed') {
        if (viewDetailed) viewDetailed.checked = true;
        if (this.elements.assignmentsCompactView) {
          this.elements.assignmentsCompactView.classList.add('d-none');
        }
        if (this.elements.assignmentsDetailedView) {
          this.elements.assignmentsDetailedView.classList.remove('d-none');
        }
      } else {
        if (viewCompact) viewCompact.checked = true;
        if (this.elements.assignmentsCompactView) {
          this.elements.assignmentsCompactView.classList.remove('d-none');
        }
        if (this.elements.assignmentsDetailedView) {
          this.elements.assignmentsDetailedView.classList.add('d-none');
        }
      }
    }

    setupEventListeners() {
      // Tab switching
      if (this.elements.gradesTabs) {
        this.elements.gradesTabs.addEventListener('click', (e) => {
          const tab = e.target.closest('.nav-link');
          if (tab && !tab.classList.contains('active')) {
            e.preventDefault();
            this.handleTabChange(tab);
          }
        });
      }

      // Mode switching buttons
      const analyzeModeToggle = document.getElementById('analyzeModeToggle');
      const predictModeToggle = document.getElementById('predictModeToggle');
      const mobileAnalyzeModeToggle = document.getElementById('mobile-analyze-mode-toggle');
      const mobilePredictModeToggle = document.getElementById('mobile-predict-mode-toggle');

      if (analyzeModeToggle) {
        analyzeModeToggle.addEventListener('change', (e) => {
          console.log('Analyze mode toggle changed:', e.target.checked);
          // Sync mobile toggle
          if (mobileAnalyzeModeToggle) mobileAnalyzeModeToggle.checked = e.target.checked;
          
          if (analyzeModeToggle.checked) {
            this.enableAnalyzeMode();
          } else {
            this.disableAnalyzeMode();
          }
        });
      }
      
      if (predictModeToggle) {
        predictModeToggle.addEventListener('change', (e) => {
          console.log('Predict mode toggle changed:', e.target.checked);
          // Sync mobile toggle
          if (mobilePredictModeToggle) mobilePredictModeToggle.checked = e.target.checked;
          
          if (predictModeToggle.checked) {
            this.enablePredictMode();
          } else {
            this.disablePredictMode();
          }
        });
      }
      
      // Mobile mode toggles - sync with desktop
      if (mobileAnalyzeModeToggle) {
        mobileAnalyzeModeToggle.addEventListener('change', (e) => {
          console.log('Mobile analyze mode toggle changed:', e.target.checked);
          // Sync desktop toggle
          if (analyzeModeToggle) analyzeModeToggle.checked = e.target.checked;
          
          if (mobileAnalyzeModeToggle.checked) {
            this.enableAnalyzeMode();
          } else {
            this.disableAnalyzeMode();
          }
        });
      }
      
      if (mobilePredictModeToggle) {
        mobilePredictModeToggle.addEventListener('change', (e) => {
          console.log('Mobile predict mode toggle changed:', e.target.checked);
          // Sync desktop toggle
          if (predictModeToggle) predictModeToggle.checked = e.target.checked;
          
          if (mobilePredictModeToggle.checked) {
            this.enablePredictMode();
          } else {
            this.disablePredictMode();
          }
        });
      }

      // Refresh button
      const refreshBtn = document.getElementById('refreshGrades');
      if (refreshBtn) {
        refreshBtn.addEventListener('click', () => this.handleRefreshButton());
      }
      
      // Mobile refresh button
      const mobileRefreshBtn = document.getElementById('mobile-refresh-grades');
      if (mobileRefreshBtn) {
        mobileRefreshBtn.addEventListener('click', () => this.handleRefreshButton());
      }

      // Back button
      if (this.elements.backToClasses) {
        this.elements.backToClasses.addEventListener('click', () => this.closeClassDetail());
      }
      
      // Mobile back button
      const mobileBackBtn = document.getElementById('mobile-grades-back-btn');
      if (mobileBackBtn) {
        mobileBackBtn.addEventListener('click', () => this.closeClassDetail());
      }

      // Run selector
      const runTabs = document.getElementById('runTabs');
      if (runTabs) {
        runTabs.addEventListener('click', (e) => {
          const tab = e.target.closest('a.nav-link');
          if (tab) {
            e.preventDefault();
            const selectedRun = tab.getAttribute('data-run-value');
            this.handleRunChange(selectedRun);
          }
        });
      }

      const runDropdown = document.getElementById('runDropdown');
      if (runDropdown) {
        runDropdown.addEventListener('change', (e) => {
          this.handleRunChange(e.target.value);
        });
      }

      // Date selector
      const dateTabs = document.getElementById('dateTabs');
      if (dateTabs) {
        dateTabs.addEventListener('click', (e) => {
          const tab = e.target.closest('a.nav-link');
          if (tab) {
            e.preventDefault();
            const selectedDate = tab.getAttribute('data-date-value');
            this.handleDateChange(selectedDate);
          }
        });
      }

      const dateDropdown = document.getElementById('dateDropdown');
      if (dateDropdown) {
        dateDropdown.addEventListener('change', (e) => {
          this.handleDateChange(e.target.value);
        });
      }

      // Mock assignments
      if (this.elements.addMockAssignment) {
        this.elements.addMockAssignment.addEventListener('click', () => this.showAddMockAssignmentModal());
      }

      if (this.elements.saveMockAssignment) {
        this.elements.saveMockAssignment.addEventListener('click', () => this.saveMockAssignment());
      }

      if (this.elements.mockAssignmentCategory) {
        this.elements.mockAssignmentCategory.addEventListener('change', (e) => {
          this.handleMockCategoryChange(e.target.value);
        });
      }

      // Grade calculator
      if (this.elements.calculateRequiredGrade) {
        this.elements.calculateRequiredGrade.addEventListener('click', () => this.calculateRequiredGrade());
      }

      // GPA modal
      const gpaModal = document.getElementById('gpaModal');
      if (gpaModal) {
        gpaModal.addEventListener('show.bs.modal', () => {
          this.renderClassWeightsTable();
        });
      }

      // GPA predictions
      if (this.elements.gpaPredictionForm) {
        this.elements.gpaPredictionForm.addEventListener('submit', (e) => {
          e.preventDefault();
          this.handleGpaPredictionSubmit();
        });
      }

      if (this.elements.saveGpaPredictions) {
        this.elements.saveGpaPredictions.addEventListener('click', () => this.saveGpaPredictions());
      }

      if (this.elements.resetGpaPredictions) {
        this.elements.resetGpaPredictions.addEventListener('click', () => this.resetGpaPredictions());
      }

      // Weight config
      if (this.elements.weightConfigForm) {
        this.elements.weightConfigForm.addEventListener('submit', (e) => {
          e.preventDefault();
          this.saveWeightConfig();
        });
      }
      
      // Expand chart button
      const expandChartBtn = document.getElementById('expandChartBtn');
      if (expandChartBtn) {
        expandChartBtn.addEventListener('click', () => this.showExpandedChart());
      }
      
      // Assignment view toggle
      const viewCompact = document.getElementById('viewCompact');
      const viewDetailed = document.getElementById('viewDetailed');
      
      if (viewCompact) {
        viewCompact.addEventListener('change', () => {
          if (this.elements.assignmentsCompactView) {
            this.elements.assignmentsCompactView.classList.remove('d-none');
          }
          if (this.elements.assignmentsDetailedView) {
            this.elements.assignmentsDetailedView.classList.add('d-none');
          }
          Utils.setCookie('assignmentView', 'compact');
        });
      }
      
      if (viewDetailed) {
        viewDetailed.addEventListener('change', () => {
          if (this.elements.assignmentsCompactView) {
            this.elements.assignmentsCompactView.classList.add('d-none');
          }
          if (this.elements.assignmentsDetailedView) {
            this.elements.assignmentsDetailedView.classList.remove('d-none');
          }
          Utils.setCookie('assignmentView', 'detailed');
        });
      }
      
      // Scroll handler for sticky header
      this.setupScrollHandler();
    }
    
    setupScrollHandler() {
      let ticking = false;
      
      const handleScroll = () => {
        if (!ticking) {
          window.requestAnimationFrame(() => {
            const detailHeader = document.querySelector('.detail-header');
            
            if (detailHeader && !this.elements.classDetailView.classList.contains('d-none')) {
              const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
              
              if (scrollTop > 150) {
                detailHeader.classList.add('scrolled');
              } else {
                detailHeader.classList.remove('scrolled');
              }
            }
            ticking = false;
          });
          ticking = true;
        }
      };
      
      window.addEventListener('scroll', handleScroll, { passive: true });
    }

updateFloatingBadge() {
  const stickyGradeValue = document.getElementById('stickyGradeValue');
  const miniProgressCircle = document.getElementById('miniProgressCircle');
  
  if (!stickyGradeValue || !miniProgressCircle) {
    return;
  }
  
  let formattedAverage = this.state.currentDetailAverage;
  
  // Only round if more than 2 decimal places
  if (!isNaN(formattedAverage)) {
    const decimalPart = formattedAverage.toString().split('.')[1];
    if (decimalPart && decimalPart.length > 2) {
      formattedAverage = Math.round(formattedAverage * 100) / 100;
    }
  }
  
  stickyGradeValue.textContent = isNaN(formattedAverage) ? 'N/A' : formattedAverage + '%';
  
  // Update mini circle progress - use setAttribute for SVG elements
  const colorClass = Utils.getGradeColor(this.state.currentDetailAverage);
  miniProgressCircle.setAttribute('class', `mini-progress-fill color-${colorClass}`);
  
  // Calculate stroke offset (circle circumference = 2 * π * r = 2 * π * 20 = 125.664)
  const percentage = isNaN(formattedAverage) ? 0 : formattedAverage;
  const circumference = 125.664;
  const offset = circumference - (percentage / 100) * circumference;
  miniProgressCircle.style.strokeDashoffset = offset;
}
setupNavigationHandling() {
  // Cleanup on page unload
  window.addEventListener('beforeunload', () => {
    console.log('Page unloading - cleaning up');
    this.requests.abortAll();
    if (this.lastUpdatedTimer) {
      clearInterval(this.lastUpdatedTimer);
    }
    if (this.cleanupTimer) {
      clearInterval(this.cleanupTimer);
    }
  });
  
  // Abort requests on visibility change (tab switching)
  document.addEventListener('visibilitychange', () => {
    /*if (document.hidden) {
      console.log('Page hidden - aborting active requests');
      this.requests.abortAll();
    }*/
  });

  // Intercept navigation clicks
  document.addEventListener('click', (e) => {
    const link = e.target.closest('a');
    if (link && link.href && !link.hasAttribute('data-bs-toggle') && 
        !link.classList.contains('class-detail-link') &&
        link.target !== '_blank') {
      const href = link.getAttribute('href');
      if (href && !href.startsWith('#')) {
        console.log('Navigation detected - aborting requests');
        this.requests.abortAll();
      }
    }
  });
  
  // Handle browser back/forward
  window.addEventListener('popstate', () => {
    console.log('Popstate - aborting requests');
    this.requests.abortAll();
  });
}


    async loadInitialData() {
      this.showLoader();
      
      try {
        await Promise.all([
          this.loadGPAScales(),
          this.loadWeightConfig()
        ]);
        
        const result = await this.loadClasses();
        if (result) {
          this.state.allClassesData = result.classesData;
          this.state.availableRuns = result.runs;
          this.populateRunSelector(result.runs, result.selectedRun);
          this.renderClasses(result.classesData, result.selectedRun);
        }
        
        this.hideLoader();
        
        // Load saved predictions
        this.loadSavedPredictions();
        
      } catch (error) {
        console.error('Error loading initial data:', error);
        this.showError('Failed to load initial data. Please refresh the page.');
      }
    }

    async loadClasses(run = null) {
      this.elements.errorContainer.style.display = 'none';
      const cacheKey = `running_average${run ? "_" + run : ""}`;
  
      // Check cache
      const cached = this.cache.get(cacheKey);
      if (cached) {
        return cached;
      }
      
      // Fetch from API
      const url = run ? `/backends/classes-backend.php?run=${encodeURIComponent(run)}` : '/backends/classes-backend.php';
      const data = await this.requests.fetch('load-classes', url);
      
      if (data) {
        this.state.fullApiResponse = data;
        
        let classesData = [];
        if (data.classes && typeof data.classes === 'object' && !Array.isArray(data.classes)) {
          classesData = Object.values(data.classes);
        } else if (data.classes && Array.isArray(data.classes)) {
          classesData = data.classes;
        } else if (data.data && Array.isArray(data.data)) {
          classesData = data.data;
        }
        
        const result = {
          classesData,
          runs: data.report_card_run?.available || {},
          selectedRun: data.report_card_run?.selected || ''
        };
        
        // Cache this result
        this.cache.set(cacheKey, result);
        
        // If this is the default run (no run parameter), also cache it with the default key
        if (!run && data.is_default_run && data.default_run) {
          const defaultCacheKey = `running_average_${data.default_run}`;
          console.log(`Caching default run: ${data.default_run} with key: ${defaultCacheKey}`);
          this.cache.set(defaultCacheKey, result);
        }
        
        this.updateLastUpdatedTimestamp();
        return result;
      }
      
      return { classesData: [], runs: {}, selectedRun: '' };
    }

async loadGPAScales() {
  const cached = this.cache.get('gpa_scales');
  if (cached) {
    this.state.gpaScales = cached;
    return;
  }
  
  const data = await this.requests.fetch('load-gpa-scales', '/backends/grades-gpa-scale-backend.php?action=get_scales');
  
  if (data?.success) {
    const formattedScales = {};
    for (const [classCode, scale] of Object.entries(data.scales)) {
      // Always format to one decimal place for consistency
      formattedScales[classCode] = parseFloat(scale).toFixed(1);
    }
    this.state.gpaScales = formattedScales;
    this.cache.set('gpa_scales', formattedScales);
  }
}
    async loadWeightConfig() {
      const cached = this.cache.get('weight_config');
      if (cached) {
        this.state.weightConfig = cached;
        this.updateWeightConfigDisplay();
        return;
      }
      
      const data = await this.requests.fetch('load-weights', '/backends/classes-gpa-backend.php?action=get_weights');
      
      if (data?.success) {
        this.state.weightConfig = data.weights;
        this.cache.set('weight_config', data.weights);
        this.updateWeightConfigDisplay();
      }
    }

    updateWeightConfigDisplay() {
      if (this.elements.currentHonorsWeight) {
        this.elements.currentHonorsWeight.textContent = this.state.weightConfig.honors.toString();
      }
      if (this.elements.currentApWeight) {
        this.elements.currentApWeight.textContent = this.state.weightConfig.ap.toString();
      }
      
      const honorsInput = document.getElementById('honorsWeight');
      const apInput = document.getElementById('apWeight');
      if (honorsInput) honorsInput.value = this.state.weightConfig.honors;
      if (apInput) apInput.value = this.state.weightConfig.ap;
    }

    async saveWeightConfig() {
      const honorsWeight = parseFloat(document.getElementById('honorsWeight').value);
      const apWeight = parseFloat(document.getElementById('apWeight').value);
      
      const formData = new FormData();
      formData.append('action', 'save_weights');
      formData.append('honors', honorsWeight);
      formData.append('ap', apWeight);
      
      // one day ill condense ts into one file
      const data = await this.requests.fetch('save-weights', '/backends/classes-gpa-backend.php', {
        method: 'POST',
        body: formData
      });
      
      if (data?.success) {
        this.state.weightConfig.honors = honorsWeight;
        this.state.weightConfig.ap = apWeight;
        this.cache.set('weight_config', this.state.weightConfig);
        this.updateWeightConfigDisplay();
        this.updateGPADisplay();
        this.showToast('Weight configuration saved!');
      }
    }

renderClasses(classesData, selectedRun = null) {
  if (!this.elements.classesGridContainer) return;
  
  this.elements.classesGridContainer.innerHTML = '';
  this.state.currentClasses = [];
  this.state.currentRun = selectedRun || '';
  
  if (classesData && classesData.length > 0) {
    classesData.forEach(item => {
      const avg = item.average || 'N/A';
      const className = item.class_name || item.class || 'Unknown Class';
      const classCode = item.class_code || 'Unknown Code';
      
      const badgeClass = Utils.getGradeColor(avg);
      const borderClass = Utils.getBorderColor(avg);
      const letterGrade = Utils.percentageToLetter(avg);
      
      // Get credits from item if available, default to 1
      const credits = parseFloat(item.credits) || 1.0;
      
      // Determine class type from the GPA scale
      const scale = this.state.gpaScales[classCode] || '4.0';
      const scaleNum = parseFloat(scale);
      let classType = 'standard';
      
      if (scaleNum === 4.5) {
        classType = 'honors';
      } else if (scaleNum === 5.0) {
        classType = 'ap';
      }
      
      const dropped = item.dropped === true;
      
      this.state.currentClasses.push({
        name: className,
        percentage: avg,
        credits: credits,
        classType: classType,
        code: classCode,
        dropped: dropped
      });
          
          let finalEncodedData = item.encoded_data;
          if (!finalEncodedData) {
            const classDetailData = {
              class_code: classCode,
              class_name: className,
              average: avg,
              assignments: item.assignments || [],
              categories: item.categories || [],
              report_card_run: this.state.fullApiResponse?.report_card_run || {},
              timestamp: Math.floor(Date.now() / 1000)
            };
            finalEncodedData = Utils.encodeDataForUrl(classDetailData);
          }
          
          const col = document.createElement('div');
          col.className = 'col';
          
          const card = document.createElement('div');
          card.className = `card h-100 class-card ${dropped ? 'dropped-class opacity-75' : ''} ${borderClass}`;
          
          card.innerHTML = `
            <a href="#" class="text-decoration-none text-reset class-detail-link">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <h5 class="card-title mb-0 text-truncate">${dropped ? '<s>' : ''}${className}${dropped ? '</s>' : ''}</h5>
                  <div>
                    <span class="badge bg-${badgeClass} fs-6">${Utils.formatAverage(parseFloat(avg) || 0)}</span>
                  </div>
                </div>
              </div>
            </a>
          `;
          
          const detailLink = card.querySelector('.class-detail-link');
          detailLink.addEventListener('click', (e) => {
            e.preventDefault();
            this.loadClassDetail(classCode, className, finalEncodedData, selectedRun);
          });
          
          col.appendChild(card);
          this.elements.classesGridContainer.appendChild(col);
        });
        
        this.elements.classesGridContainer.style.display = 'flex';
        if (this.elements.gpaButton) {
          this.elements.gpaButton.classList.remove('disabled');
        }
        this.elements.errorContainer.style.display = 'none';
        this.updateGPADisplay();
        
      } else {
        this.elements.errorContainer.innerHTML = `
          <div class="alert alert-warning">
            <p class="mb-0"><i class="bi bi-journal-x"></i>
              Unable to get class data. Please try again in a few minutes.</p>
          </div>
        `;
        this.elements.errorContainer.style.display = 'block';
        this.elements.classesGridContainer.style.display = 'none';
        if (this.elements.gpaButton) {
          this.elements.gpaButton.classList.add('disabled');
        }
      }
    }

    populateRunSelector(runs, selectedRun) {
      const runTabs = document.getElementById('runTabs');
      const runDropdown = document.getElementById('runDropdown');
      
      if (!runTabs || !runDropdown) return;
      
      runTabs.innerHTML = '';
      runDropdown.innerHTML = '';
      
      let firstTab = true;
      
      for (const [value, text] of Object.entries(runs)) {
        if (value === 'ALL' || text === '(All Runs)') continue;
        
        const isSelected = selectedRun === value;
        const isActive = (firstTab && !selectedRun) || isSelected;
        
        const tab = document.createElement('li');
        tab.className = 'nav-item';
        
        const tabLink = document.createElement('a');
        tabLink.className = `nav-link ${isActive ? 'active' : ''}`;
        tabLink.href = '#';
        tabLink.textContent = `MP${text}`;
        tabLink.setAttribute('data-run-value', value);
        
        tab.appendChild(tabLink);
        runTabs.appendChild(tab);
        
        const option = document.createElement('option');
        option.value = value;
        option.textContent = `MP${text}`;
        if (isActive) option.selected = true;
        runDropdown.appendChild(option);
        
        firstTab = false;
      }
      
      if (this.elements.runSelectorContainer) {
        this.elements.runSelectorContainer.style.display = 'block';
      }
    }

    updateGPADisplay() {
      const gpa = GradeCalculator.calculateGPA(this.state.currentClasses, this.state.gpaScales);
      
      if (this.elements.unweightedGpa) {
        this.elements.unweightedGpa.textContent = gpa.unweighted.toString();
      }
      if (this.elements.weightedGpa) {
        this.elements.weightedGpa.textContent = gpa.weighted.toString();
      }
      if (this.elements.currentUnweightedGpa) {
        this.elements.currentUnweightedGpa.textContent = gpa.unweighted.toString();
      }
      if (this.elements.currentWeightedGpa) {
        this.elements.currentWeightedGpa.textContent = gpa.weighted.toString();
      }
    }

hideFloatingBadges() {
  // Badge visibility now handled by CSS based on .detail-header.scrolled class
}
showFloatingBadges() {
  // Badge visibility now handled by CSS based on .detail-header.scrolled class
}

    async loadClassDetail(classCode, className, encodedData, runParam = null) {
  this.elements.errorContainer.style.display = 'none';
  this.elements.errorContainer.innerHTML = '';
  
  // Reset grade badge
  if (this.elements.detailAvgBadge) {
    this.elements.detailAvgBadge.textContent = '';
  }
  if (this.elements.detailLetterGrade) {
    this.elements.detailLetterGrade.textContent = '';
  }
  
  // Reset analysis elements
  this.resetAnalysisModal();
  
  this.requests.abortAll();
  
  this.state.currentDetailClass = { code: classCode, name: className };
  this.elements.detailClassName.textContent = className;
  this.elements.detailLoader.classList.remove('d-none');
  this.elements.classDetailContent.classList.add('d-none');
  
  this.showClassDetailView();
      
      let classData = null;
      
      if (this.state.fullApiResponse && this.state.fullApiResponse.classes) {
        classData = this.state.fullApiResponse.classes[classCode];
      }
      
      if (!classData && encodedData) {
        try {
          const decodedJson = atob(encodedData);
          classData = JSON.parse(decodedJson);
        } catch (e) {
          console.error('Error decoding class data:', e);
        }
      }
      
      if (classData) {
        this.elements.detailLoader.classList.add('d-none');
        this.elements.classDetailContent.classList.remove('d-none');
        
        this.state.currentDetailClass = { ...this.state.currentDetailClass, ...classData };
        this.processClassData(classData);
      } else {
        this.elements.detailLoader.classList.add('d-none');
        this.elements.classDetailContent.classList.add('d-none');
        this.showError('Class data not available. Please go back and try again.');
      }
    }


resetAnalysisModal() {
  // Reset all analysis displays
  if (this.elements.analysisAverage) this.elements.analysisAverage.textContent = 'N/A';
  if (this.elements.analysisCompleted) this.elements.analysisCompleted.textContent = '0 of 0';
  if (this.elements.analysisHighest) this.elements.analysisHighest.textContent = 'N/A';
  if (this.elements.analysisLowest) this.elements.analysisLowest.textContent = 'N/A';
  if (this.elements.analysisStdDev) this.elements.analysisStdDev.textContent = 'N/A';
  
  // Reset grade distribution
  if (this.elements.distA) {
    this.elements.distA.style.width = '0%';
    this.elements.distA.textContent = '0%';
  }
  if (this.elements.distB) {
    this.elements.distB.style.width = '0%';
    this.elements.distB.textContent = '0%';
  }
  if (this.elements.distC) {
    this.elements.distC.style.width = '0%';
    this.elements.distC.textContent = '0%';
  }
  if (this.elements.distDF) {
    this.elements.distDF.style.width = '0%';
    this.elements.distDF.textContent = '0%';
  }
  
  // Clear assignment analysis
  if (this.elements.assignmentAnalysis) {
    this.elements.assignmentAnalysis.innerHTML = '<tr><td colspan="4" class="text-center">No data available</td></tr>';
  }
  
  // Clear category data
  if (this.elements.categoryWeights) {
    this.elements.categoryWeights.innerHTML = '<tr><td colspan="2" class="text-center">No data available</td></tr>';
  }
  if (this.elements.categoryAverages) {
    this.elements.categoryAverages.innerHTML = '<tr><td colspan="2" class="text-center">No data available</td></tr>';
  }
  
  // Destroy chart if it exists
  if (this.state.performanceChartInstance) {
    this.state.performanceChartInstance.destroy();
    this.state.performanceChartInstance = null;
  }
}

    processClassData(classData) {
      // Clear assignment containers
      if (this.elements.assignmentsListCompact) {
        this.elements.assignmentsListCompact.innerHTML = '';
      }
      if (this.elements.assignmentsGrid) {
        this.elements.assignmentsGrid.innerHTML = '';
      }
      if (this.elements.mockAssignmentsTable) {
        this.elements.mockAssignmentsTable.innerHTML = '';
      }
      
      // Reset grade goal calculator
      if (this.elements.targetGrade) this.elements.targetGrade.value = 90;
      if (this.elements.targetWeight) this.elements.targetWeight.value = 10;
      if (this.elements.requiredGradeResult) this.elements.requiredGradeResult.innerHTML = '';
      
      if (classData.categories) {
        this.state.currentDetailClass.categories = classData.categories;
        GradeCalculator.debugCategoryWeights(classData.categories, this.state.assignmentsData);
      }
      
      if (classData.assignments && classData.assignments.length > 1) {
        const assignmentRows = classData.assignments.slice(1);
        
        this.state.assignmentsData = [];
        this.state.exemptAssignments.clear();
        this.state.mockAssignmentsData = [];
        this.state.mockCounter = 1;
        this.populateMockAssignmentCategories();
        
        assignmentRows.forEach(assignment => {
          const score = (assignment[4] === '' || assignment[4] === null || assignment[4] === undefined) ? null : parseFloat(assignment[4]);
          const total = parseFloat(assignment[5]) || 0;
          const category = assignment[3] || 'Other';
          const assignmentName = assignment[2] || 'N/A';
          const dateAssigned = assignment[1] || 'N/A';
          const dateDue = assignment[0] || 'N/A';
          
          const isNa = assignment[4] === '' || assignment[4] === null || assignment[4] === undefined;
          const percentage = isNa ? NaN : (score / total) * 100;
          
          const assignmentData = {
            name: assignmentName,
            date: dateDue,
            dateAssigned: dateAssigned,
            score: isNa ? null : score,
            total: total,
            percentage: percentage,
            category: category,
            isMock: false
          };
          
          this.state.assignmentsData.push(assignmentData);
        });
        
        this.renderAssignmentCards();
        this.calculateCurrentGrade();
        this.updateGradeDisplay();
        
        if (classData.average && classData.average !== 'N/A') {
          this.state.currentDetailAverage = parseFloat(classData.average);
          const badgeClass = Utils.getGradeColor(this.state.currentDetailAverage);
          let formattedAverage = this.state.currentDetailAverage;
          
          // Only round if more than 2 decimal places
          if (!isNaN(formattedAverage)) {
            const decimalPart = formattedAverage.toString().split('.')[1];
            if (decimalPart && decimalPart.length > 2) {
              formattedAverage = Math.round(formattedAverage * 100) / 100;
            }
          }
          
          const letterGrade = Utils.percentageToLetter(this.state.currentDetailAverage);
          
          const displayText = isNaN(formattedAverage) ? 'N/A' : formattedAverage + '%';
          this.elements.detailAvgBadge.textContent = displayText;
          this.elements.detailAvgBadge.className = `text-${badgeClass}`;
          
          // Adjust font size to prevent wrapping
          this.adjustCircleTextSize(this.elements.detailAvgBadge, displayText);
          
          if (this.elements.detailLetterGrade && letterGrade) {
            this.elements.detailLetterGrade.textContent = `Letter Grade: ${letterGrade}`;
          }
          
          // Update circular progress
          this.updateCircularProgress(formattedAverage, badgeClass);
          
          // Initialize the floating badge with current values
          this.updateFloatingBadge();
        }
        
        this.initializeAnalysis();
      } else {
        this.elements.classDetailContent.classList.add('d-none');
        this.showError('No assignments found for this class.');
      }
    }

    calculateCurrentGrade() {
      const average = GradeCalculator.calculateWeightedAverage(
        this.state.assignmentsData,
        this.state.mockAssignmentsData,
        this.state.currentDetailClass?.categories,
        this.state.exemptAssignments
      );
      
      this.state.currentDetailAverage = average;
      this.updateFloatingBadge();
    }
    renderAssignmentCards() {
      if (!this.elements.assignmentsListCompact || !this.elements.assignmentsGrid) return;
      
      this.elements.assignmentsListCompact.innerHTML = '';
      this.elements.assignmentsGrid.innerHTML = '';
      
      this.state.assignmentsData.forEach((assignment, index) => {
        const isNa = (assignment.score === null || assignment.score === undefined || assignment.score === '') && isNaN(assignment.percentage);
        const percentage = assignment.percentage;
        const badgeClass = isNa ? 'secondary' : Utils.getGradeColor(percentage);
        const displayScore = isNa ? 'N/A' : assignment.score;
        const displayPercentage = isNa ? 'N/A' : Utils.formatPercentage(percentage);
        
        // Determine border class
        let borderClass = 'border-grade-secondary';
        if (!isNa) {
          if (percentage >= 90) borderClass = 'border-grade-success';
          else if (percentage >= 80) borderClass = 'border-grade-primary';
          else if (percentage >= 70) borderClass = 'border-grade-warning';
          else borderClass = 'border-grade-danger';
        }
        
        // Compact View Card
        const compactCard = document.createElement('div');
        compactCard.className = `assignment-card-compact ${borderClass}`;
        compactCard.innerHTML = `
          <div class="assignment-name">${assignment.name}</div>
          <div class="assignment-meta">
            <div class="assignment-meta-item">
              <i class="bi bi-folder"></i>
              <span>${assignment.category}</span>
            </div>
            <div class="assignment-meta-item">
              <i class="bi bi-calendar-check"></i>
              <span>${assignment.dateAssigned || 'N/A'}</span>
            </div>
            <div class="assignment-meta-item">
              <i class="bi bi-calendar-event"></i>
              <span>Due: ${assignment.date || 'N/A'}</span>
            </div>
          </div>
          <div class="assignment-score-display">
            <div class="score-fraction">
              <i class="bi bi-clipboard-data me-2"></i>
              ${displayScore} / ${assignment.total || 'N/A'}
            </div>
            <span class="badge bg-${badgeClass} score-badge-large">${displayPercentage}</span>
          </div>
        `;
        this.elements.assignmentsListCompact.appendChild(compactCard);
        
        // Detailed View Card
        const detailedCard = document.createElement('div');
        detailedCard.className = 'col-md-6 col-lg-4';
        detailedCard.innerHTML = `
          <div class="card assignment-card-detailed ${borderClass}">
            <div class="card-header assignment-card-header">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">${assignment.name}</h6>
                <span class="badge bg-${badgeClass}" style="--bs-badge-font-size: 0.9em;">${displayPercentage}</span>
              </div>
            </div>
            <div class="card-body assignment-card-body">
              <div class="assignment-detail-row">
                <span class="assignment-detail-label">
                  <i class="bi bi-folder me-1"></i> Category
                </span>
                <span class="assignment-detail-value">${assignment.category}</span>
              </div>
              <div class="assignment-detail-row">
                <span class="assignment-detail-label">
                  <i class="bi bi-calendar-check me-1"></i> Assigned
                </span>
                <span class="assignment-detail-value">${assignment.dateAssigned || 'N/A'}</span>
              </div>
              <div class="assignment-detail-row">
                <span class="assignment-detail-label">
                  <i class="bi bi-calendar-event me-1"></i> Due Date
                </span>
                <span class="assignment-detail-value">${assignment.date || 'N/A'}</span>
              </div>
              <div class="assignment-detail-row">
                <span class="assignment-detail-label">
                  <i class="bi bi-clipboard-data me-1"></i> Score
                </span>
                <span class="assignment-detail-value">${displayScore} / ${assignment.total || 'N/A'}</span>
              </div>
            </div>
          </div>
        `;
        this.elements.assignmentsGrid.appendChild(detailedCard);
      });
    }

    updateGradeDisplay() {
      const badgeClass = Utils.getGradeColor(this.state.currentDetailAverage);
      let formattedAverage = this.state.currentDetailAverage;
      
      // Only round if more than 2 decimal places
      if (!isNaN(formattedAverage)) {
        const decimalPart = formattedAverage.toString().split('.')[1];
        if (decimalPart && decimalPart.length > 2) {
          formattedAverage = Math.round(formattedAverage * 100) / 100;
        }
      }
      
      const letterGrade = Utils.percentageToLetter(this.state.currentDetailAverage);
      
      if (this.elements.detailAvgBadge) {
        const displayText = isNaN(formattedAverage) ? 'N/A' : formattedAverage + '%';
        this.elements.detailAvgBadge.textContent = displayText;
        this.elements.detailAvgBadge.className = `text-${badgeClass} display-3 mb-0`;
      }
      
      if (this.elements.detailLetterGrade && letterGrade) {
        this.elements.detailLetterGrade.textContent = `Letter Grade: ${letterGrade}`;
      }
      
      // Update circular progress
      this.updateCircularProgress(formattedAverage, badgeClass);
      
      // Update floating badge (desktop)
      const floatingBadge = document.getElementById('predictedGradeBadgeValue');
      if (floatingBadge) {
        const displayText = isNaN(formattedAverage) ? '--' : formattedAverage + '%';
        floatingBadge.textContent = displayText;
        floatingBadge.className = `badge fs-5 bg-${badgeClass}`;
      }
      
      // Update mobile floating badge
      const mobileBadge = document.getElementById('mobile-predicted-grade-badge');
      if (mobileBadge) {
        const displayText = isNaN(formattedAverage) ? '--' : formattedAverage + '%';
        mobileBadge.textContent = displayText;
        mobileBadge.className = `badge fs-6 bg-${badgeClass}`;
      }
      
      // Show grade difference in predict mode
      const gradeDiffInfo = document.getElementById('gradeDifferenceInfo');
      if (this.state.predictMode && this.state.currentDetailClass?.average && gradeDiffInfo) {
        const actualAverage = parseFloat(this.state.currentDetailClass.average);
        const predictedAverage = this.state.currentDetailAverage;
        
        if (!isNaN(actualAverage) && !isNaN(predictedAverage) && Math.abs(actualAverage - predictedAverage) > 0.01) {
          const difference = predictedAverage - actualAverage;
          let roundedDiff = difference;
          
          const decimalPart = difference.toString().split('.')[1];
          if (decimalPart && decimalPart.length > 2) {
            roundedDiff = Math.round(difference * 100) / 100;
          }
          
          const diffColor = difference > 0 ? 'success' : 'danger';
          const diffIcon = difference > 0 ? 'arrow-up' : 'arrow-down';
          const diffText = difference > 0 ? `+${Math.abs(roundedDiff)}` : `-${Math.abs(roundedDiff)}`;
          
          gradeDiffInfo.className = `mt-2 alert alert-${diffColor} py-2`;
          gradeDiffInfo.innerHTML = `
            <i class="bi bi-${diffIcon} me-1"></i>
            <strong>${diffText}%</strong> from current grade (${actualAverage.toFixed(2)}%)
          `;
          gradeDiffInfo.classList.remove('d-none');
        } else {
          gradeDiffInfo.classList.add('d-none');
        }
      } else if (gradeDiffInfo) {
        gradeDiffInfo.classList.add('d-none');
      }
      
      this.initializeAnalysis();
    }
    
    updateGradeCardTitle() {
      const cardTitle = document.querySelector('.col-md-4:first-child .card-body h6');
      if (!cardTitle) return;
      
      if (this.state.predictMode && this.state.currentDetailClass?.average) {
        const actualAverage = parseFloat(this.state.currentDetailClass.average);
        const predictedAverage = this.state.currentDetailAverage;
        
        if (!isNaN(actualAverage) && !isNaN(predictedAverage) && Math.abs(actualAverage - predictedAverage) > 0.01) {
          const difference = predictedAverage - actualAverage;
          const actualColor = Utils.getGradeColor(actualAverage).replace(/\btext-dark\b/, '').trim();
          const predictedColor = Utils.getGradeColor(predictedAverage).replace(/\btext-dark\b/, '').trim();
          
          let roundedActual = actualAverage;
          let roundedPredicted = predictedAverage;
          let roundedDiff = difference;
          
          // Apply smart rounding
          [roundedActual, roundedPredicted, roundedDiff].forEach((val, idx) => {
            const decimalPart = val.toString().split('.')[1];
            if (decimalPart && decimalPart.length > 2) {
              const rounded = Math.round(val * 100) / 100;
              if (idx === 0) roundedActual = rounded;
              if (idx === 1) roundedPredicted = rounded;
              if (idx === 2) roundedDiff = rounded;
            }
          });
          
          const diffText = difference > 0 ? `+${Math.abs(roundedDiff)}` : `-${Math.abs(roundedDiff)}`;
          const diffColor = difference > 0 ? 'success' : 'danger';
          
          cardTitle.innerHTML = `
            <span class="text-muted">Predicted Grade</span>
            <div class="mt-1" style="font-size: 0.75rem;">
              <span class="text-${predictedColor} fw-semibold">${roundedPredicted}%</span> 
              <span class="text-muted">vs</span> 
              <span class="text-${actualColor} fw-semibold">${roundedActual}%</span>
              <span class="badge bg-${diffColor} text-white ms-1">${diffText} pts</span>
            </div>
          `;
        } else {
          cardTitle.innerHTML = '<span class="text-muted">Predicted Grade</span>';
        }
      } else {
        cardTitle.innerHTML = '<span class="text-muted">Current Grade</span>';
      }
    }
    
    adjustCircleTextSize(element, text) {
      // Start with default size
      const maxWidth = 90; // Container width
      let fontSize = 20; // 1.25rem = 20px
      
      // Temporarily set to measure
      element.style.fontSize = fontSize + 'px';
      
      // Reduce font size until text fits
      while (element.scrollWidth > maxWidth && fontSize > 10) {
        fontSize -= 1;
        element.style.fontSize = fontSize + 'px';
      }
    }
    
    updateCircularProgress(percentage, colorClass) {
      const circle = document.getElementById('progressCircle');
      if (!circle) return;
      
      const radius = 50;
      const circumference = 2 * Math.PI * radius;
      
      // Calculate offset (100% = no offset, 0% = full offset)
      const validPercentage = isNaN(percentage) ? 0 : Math.min(Math.max(percentage, 0), 100);
      const offset = circumference - (validPercentage / 100) * circumference;
      
      circle.style.strokeDashoffset = offset;
      
      // Update color class using setAttribute instead of className
      circle.setAttribute('class', `progress-fill color-${colorClass}`);
    }

initializeAnalysis() {
  if (this.state.assignmentsData.length > 0 || this.state.mockAssignmentsData.length > 0) {
    this.analyzeGrades();
    this.populateCategoryWeights();
    this.populateCategoryAverages();
  } else {
    this.resetAnalysisModal();
  }
}

    analyzeGrades() {
      const allAssignments = [...this.state.assignmentsData, ...this.state.mockAssignmentsData];
      
      if (allAssignments.length === 0) {
        this.setAnalysisDefaults();
        return;
      }
      
      const validAssignments = allAssignments.filter((assignment, index) => {
        if (index < this.state.assignmentsData.length && this.state.exemptAssignments.has(index)) return false;
        if (index >= this.state.assignmentsData.length && assignment.exempt) return false;
        
        const hasValidScore = assignment.score !== null && assignment.score !== undefined && assignment.score !== '' && !isNaN(assignment.score);
        const hasValidTotal = !isNaN(assignment.total) && assignment.total > 0;
        return hasValidScore && hasValidTotal;
      });
      
      if (validAssignments.length === 0) {
        this.setAnalysisDefaults();
        return;
      }
      
      const percentages = validAssignments.map(a => (a.score / a.total) * 100);
      const highest = Math.max(...percentages);
      const lowest = Math.min(...percentages);
      
      const mean = this.state.currentDetailAverage;
      const squareDiffs = percentages.map(value => Math.pow(value - mean, 2));
      const avgSquareDiff = squareDiffs.reduce((a, b) => a + b, 0) / validAssignments.length;
      const stdDev = Math.sqrt(avgSquareDiff);
      
      if (this.elements.analysisAverage) {
        let roundedAvg = this.state.currentDetailAverage;
        // Smart rounding
        if (!isNaN(roundedAvg)) {
          const decimalPart = roundedAvg.toString().split('.')[1];
          if (decimalPart && decimalPart.length > 2) {
            roundedAvg = Math.round(roundedAvg * 100) / 100;
          }
        }
        this.elements.analysisAverage.textContent = isNaN(roundedAvg) ? 'N/A' : roundedAvg + '%';
      }
      if (this.elements.analysisCompleted) {
        this.elements.analysisCompleted.textContent = `${validAssignments.length} of ${allAssignments.length}`;
      }
      if (this.elements.analysisHighest) {
        this.elements.analysisHighest.textContent = Utils.formatPercentage(highest);
      }
      if (this.elements.analysisLowest) {
        this.elements.analysisLowest.textContent = Utils.formatPercentage(lowest);
      }
      if (this.elements.analysisStdDev) {
        this.elements.analysisStdDev.textContent = Utils.formatPercentage(stdDev);
      }
      
      // Grade distribution
      const distA = percentages.filter(p => p >= 90).length;
      const distB = percentages.filter(p => p >= 80 && p < 90).length;
      const distC = percentages.filter(p => p >= 70 && p < 80).length;
      const distDF = percentages.filter(p => p < 70).length;
      const total = percentages.length;
      
      if (total > 0) {
        const aPercent = (distA / total) * 100;
        const bPercent = (distB / total) * 100;
        const cPercent = (distC / total) * 100;
        const dfPercent = (distDF / total) * 100;
        
        if (this.elements.distA) {
          this.elements.distA.style.width = `${aPercent}%`;
          this.elements.distA.textContent = `${Math.round(aPercent)}%`;
        }
        if (this.elements.distB) {
          this.elements.distB.style.width = `${bPercent}%`;
          this.elements.distB.textContent = `${Math.round(bPercent)}%`;
        }
        if (this.elements.distC) {
          this.elements.distC.style.width = `${cPercent}%`;
          this.elements.distC.textContent = `${Math.round(cPercent)}%`;
        }
        if (this.elements.distDF) {
          this.elements.distDF.style.width = `${dfPercent}%`;
          this.elements.distDF.textContent = `${Math.round(dfPercent)}%`;
        }
      }
      
      this.createPerformanceChart(validAssignments);
      // this.populateAssignmentAnalysis(validAssignments);
    }

    setAnalysisDefaults() {
      if (this.elements.analysisAverage) this.elements.analysisAverage.textContent = 'N/A';
      if (this.elements.analysisCompleted) this.elements.analysisCompleted.textContent = '0 of 0';
      if (this.elements.analysisHighest) this.elements.analysisHighest.textContent = 'N/A';
      if (this.elements.analysisLowest) this.elements.analysisLowest.textContent = 'N/A';
      if (this.elements.analysisStdDev) this.elements.analysisStdDev.textContent = 'N/A';
    }

    createPerformanceChart(assignments) {
      const ctx = document.getElementById('performanceChart');
      if (!ctx) return;
      
      const isMobile = window.innerWidth < 768;
      
      // Separate real and mock assignments
      const realAssignments = assignments.filter(a => !a.isMock);
      const mockAssignments = this.state.mockAssignmentsData.filter(m => !isNaN(m.percentage));
      
      // Real assignments first (chronologically), then mock assignments
      const allAssignments = [...realAssignments.reverse(), ...mockAssignments];
      
      if (allAssignments.length === 0) {
        if (this.state.performanceChartInstance) {
          this.state.performanceChartInstance.destroy();
          this.state.performanceChartInstance = null;
        }
        return;
      }
      
      const displayAssignments = [...allAssignments];
      
      // Chart labels and data (reversed order)
      const labels = displayAssignments.map((a, index) => {
        const prefix = a.isMock ? '' : '';
        if (isMobile) {
          if (index === 0 || index === displayAssignments.length - 1 || index % 3 === 0) {
            return `${prefix}#${displayAssignments.length - index}`;
          }
          return '';
        } else {
          return prefix + a.name.substring(0, 20) + (a.name.length > 20 ? '...' : '');
        }
      });
      
      const percentages = displayAssignments.map(a => (a.score / a.total) * 100);
      
      // Calculate cumulative averages (reversed for display)
      const cumulativeAverages = [];
      displayAssignments.forEach((assignment, i) => {
        // Calculate from the original order up to this point
        const upToThis = allAssignments.slice(0, i + 1);
        const realAssignments = upToThis.filter(a => !a.isMock);
        const mockAssignments = upToThis.filter(a => a.isMock);
        
        const avg = GradeCalculator.calculateWeightedAverage(
          realAssignments,
          mockAssignments,
          this.state.currentDetailClass?.categories,
          this.state.exemptAssignments
        );
        cumulativeAverages.push(avg);
      });
      
      // Create point styles to differentiate mock assignments
      const pointStyles = displayAssignments.map(a => a.isMock ? 'rect' : 'circle');
      const pointBorderWidths = displayAssignments.map(a => a.isMock ? 2 : 1);
      
      if (this.state.performanceChartInstance) {
        this.state.performanceChartInstance.destroy();
      }
      
      this.state.performanceChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: isMobile ? 'Scores' : 'Assignment Score',
              data: percentages,
              borderColor: 'rgb(40, 167, 69)',
              backgroundColor: 'rgba(40, 167, 69, 0.1)',
              tension: 0.3,
              fill: true,
              pointRadius: isMobile ? 4 : 6,
              pointHoverRadius: isMobile ? 6 : 8,
              pointStyle: pointStyles,
              pointBorderWidth: pointBorderWidths,
              segment: {
                borderDash: ctx => {
                  const idx = ctx.p0DataIndex;
                  return displayAssignments[idx]?.isMock ? [5, 5] : [];
                }
              }
            },
            {
              label: isMobile ? 'Avg' : 'Cumulative Average',
              data: cumulativeAverages,
              borderColor: 'rgb(0, 123, 255)',
              backgroundColor: 'rgba(0, 123, 255, 0.1)',
              tension: 0.3,
              fill: true,
              pointRadius: isMobile ? 4 : 6,
              pointHoverRadius: isMobile ? 6 : 8,
              pointStyle: pointStyles,
              pointBorderWidth: pointBorderWidths
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
              labels: {
                font: { size: isMobile ? 10 : 12 }
              }
            },
            tooltip: {
              callbacks: {
                title: (items) => {
                  const idx = items[0].dataIndex;
                  const assignment = displayAssignments[idx];
                  return assignment?.isMock ? `${assignment.name} (Mock)` : assignment?.name;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: false,
              title: { 
                display: true, 
                text: isMobile ? '%' : 'Percentage'
              },
              ticks: {
                callback: (value) => value.toFixed(1) + '%',
                font: { size: isMobile ? 9 : 11 }
              }
            },
            x: {
              title: { 
                display: !isMobile, 
                text: 'Assignments (Oldest → Newest)'
              },
              ticks: {
                font: { size: isMobile ? 8 : 10 },
                maxRotation: 45,
                minRotation: 45
              }
            }
          }
        }
      });
    }

populateAssignmentAnalysis(assignments) {
  if (!this.elements.assignmentAnalysis) return;

  this.elements.assignmentAnalysis.innerHTML = '';

  if (assignments.length === 0) {
    this.elements.assignmentAnalysis.innerHTML = '<tr><td colspan="4" class="text-center">No valid assignments</td></tr>';
    return;
  }

  const isMobile = window.innerWidth < 768;

  // Hide/show table headers depending on screen size
  const table = this.elements.assignmentAnalysis.closest('table');
  if (table) {
    const thead = table.querySelector('thead');
    if (thead) {
      thead.style.display = isMobile ? 'none' : '';
    }
  }

  assignments.forEach(assignment => {
    const percentage = assignment.percentage;
    const impact = !isNaN(percentage) && !isNaN(this.state.currentDetailAverage) ? 
      (percentage - this.state.currentDetailAverage) : 'N/A';

    let impactClass = '';
    let impactSymbol = '';

    if (!isNaN(impact)) {
      impactClass = impact > 0 ? 'text-success' : impact < 0 ? 'text-danger' : '';
      impactSymbol = impact > 0 ? '+' : '';
    }

    const row = document.createElement('tr');

    if (isMobile) {
      row.innerHTML = `
        <td>
          <div class="fw-bold">${assignment.name.substring(0, 20)}${assignment.name.length > 20 ? '...' : ''}</div>
          <small class="text-muted">Score: ${assignment.score || 'N/A'}/${assignment.total || 'N/A'}</small>
        </td>
        <td class="text-end align-middle ${impactClass} fw-bold">
          ${!isNaN(impact) ? impactSymbol + Utils.formatAverage(impact) : 'N/A'}%
        </td>
      `;
    } else {
      row.innerHTML = `
        <td>${assignment.name}</td>
        <td class="text-end">${assignment.score || 'N/A'}/${assignment.total || 'N/A'}</td>
        <td class="text-end">${!isNaN(percentage) ? Utils.formatPercentage(percentage) : 'N/A'}</td>
        <td class="text-end ${impactClass} fw-bold">
          ${!isNaN(impact) ? impactSymbol + Utils.formatAverage(impact) : 'N/A'}%
        </td>
      `;
    }

    this.elements.assignmentAnalysis.appendChild(row);
  });
}

    populateCategoryWeights() {
      const categoryWeightsCompact = document.getElementById('categoryWeightsCompact');
      if (!categoryWeightsCompact) return;
      
      categoryWeightsCompact.innerHTML = '';
      
      if (!this.state.currentDetailClass?.categories) {
        categoryWeightsCompact.innerHTML = '<small class="text-muted">No category data</small>';
        return;
      }
      
      let hasValidCategories = false;
      
      if (this.state.currentDetailClass.categories.length > 1) {
        this.state.currentDetailClass.categories.slice(1).forEach(categoryRow => {
          if (categoryRow[0] && categoryRow[0] !== 'Total Points:' && categoryRow[4]) {
            const categoryName = categoryRow[0];
            const weightPercent = parseFloat(categoryRow[4]);
            
            if (!isNaN(weightPercent)) {
              const div = document.createElement('div');
              div.className = 'd-flex justify-content-between mb-1';
              div.innerHTML = `
                <span class="text-muted">${categoryName}:</span>
                <span class="fw-semibold">${weightPercent}%</span>
              `;
              categoryWeightsCompact.appendChild(div);
              hasValidCategories = true;
            }
          }
        });
      }
      
      if (!hasValidCategories) {
        categoryWeightsCompact.innerHTML = '<small class="text-muted">No categories</small>';
      }
    }

populateCategoryAverages() {
  const categoryAveragesCompact = document.getElementById('categoryAveragesCompact');
  const categoryAveragesDetail = document.getElementById('categoryAveragesDetail');
  const categoriesContainer = document.getElementById('categoriesContainer');
  
  // Clear all containers
  if (categoryAveragesCompact) categoryAveragesCompact.innerHTML = '';
  if (categoryAveragesDetail) categoryAveragesDetail.innerHTML = '';
  if (categoriesContainer) categoriesContainer.innerHTML = '';
  
  if (!this.state.currentDetailClass?.categories) {
    if (categoryAveragesCompact) categoryAveragesCompact.innerHTML = '<small class="text-muted">No category data</small>';
    if (categoryAveragesDetail) categoryAveragesDetail.innerHTML = '<small class="text-muted">No category data</small>';
    if (categoriesContainer) categoriesContainer.innerHTML = '<div class="col-12"><p class="text-muted text-center">No category data available</p></div>';
    return;
  }
  
  // Calculate average for each category
  const categoryPerformance = {};
  
  // Initialize categories from API
  if (this.state.currentDetailClass.categories.length > 1) {
    this.state.currentDetailClass.categories.slice(1).forEach(categoryRow => {
      if (categoryRow[0] && categoryRow[0] !== 'Total Points:' && categoryRow[4]) {
        const categoryName = categoryRow[0];
        const weightPercent = parseFloat(categoryRow[4]);
        
        if (!isNaN(weightPercent) && weightPercent > 0) {
          categoryPerformance[categoryName] = {
            points: 0,
            maxPoints: 0,
            weight: weightPercent,
            percentage: null,
            count: 0
          };
        }
      }
    });
  }
  
  // Combine regular and mock assignments
  const allAssignments = [...this.state.assignmentsData, ...this.state.mockAssignmentsData];
  
  // Process assignments by category
  allAssignments.forEach((assignment, index) => {
    // Skip exempt assignments
    if (index < this.state.assignmentsData.length && this.state.exemptAssignments.has(index)) return;
    if (index >= this.state.assignmentsData.length && assignment.exempt) return;
    
    const hasValidScore = !isNaN(assignment.score) && assignment.score !== null && assignment.score !== '';
    const hasValidTotal = !isNaN(assignment.total) && assignment.total > 0;
    
    if (hasValidScore && hasValidTotal) {
      const category = assignment.category;
      
      if (categoryPerformance[category]) {
        categoryPerformance[category].points += parseFloat(assignment.score);
        categoryPerformance[category].maxPoints += parseFloat(assignment.total);
        categoryPerformance[category].count++;
      }
    }
  });
  
  // Calculate percentages
  Object.keys(categoryPerformance).forEach(categoryName => {
    const data = categoryPerformance[categoryName];
    if (data.maxPoints > 0) {
      data.percentage = (data.points / data.maxPoints) * 100;
    }
  });
  
  // Render category cards (new main view)
  if (categoriesContainer) {
    const row = document.createElement('div');
    row.className = 'row g-3';
    
    Object.keys(categoryPerformance).forEach(categoryName => {
      const data = categoryPerformance[categoryName];
      const hasData = data.maxPoints > 0;
      const percentage = hasData ? data.percentage : 0;
      const badgeClass = hasData ? Utils.getGradeColor(percentage) : 'secondary';
      const displayPercentage = hasData ? Utils.formatPercentage(percentage) : 'N/A';
      
      const col = document.createElement('div');
      col.className = 'col-md-6 col-lg-4';
      
      col.innerHTML = `
        <div class="category-card card">
          <h6 class="text-center mb-3">${categoryName}</h6>
          <div class="category-progress-circle position-relative">
            <svg width="120" height="120" viewBox="0 0 100 100">
              <circle class="category-progress-bg" cx="50" cy="50" r="45" />
              <circle class="category-progress-fill color-${badgeClass}" 
                      cx="50" cy="50" r="45" 
                      style="stroke-dashoffset: ${hasData ? 282.74 - (percentage / 100) * 282.74 : 282.74};" />
            </svg>
            <div class="category-progress-text color-${badgeClass}">
              ${hasData ? Math.round(percentage) + '%' : 'N/A'}
            </div>
          </div>
          <div class="text-center mt-3">
            <div class="d-flex justify-content-between align-items-center px-2">
              <small class="text-muted">Weight:</small>
              <strong>${data.weight}%</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center px-2 mt-1">
              <small class="text-muted">Assignments:</small>
              <strong>${data.count}</strong>
            </div>
            ${hasData ? `
              <div class="d-flex justify-content-between align-items-center px-2 mt-1">
                <small class="text-muted">Score:</small>
                <strong>${data.points.toFixed(1)} / ${data.maxPoints.toFixed(1)}</strong>
              </div>
            ` : ''}
          </div>
        </div>
      `;
      
      row.appendChild(col);
    });
    
    categoriesContainer.appendChild(row);
  }
  
  // Legacy compact and detail views (for backwards compatibility)
  let hasValidCategories = false;
  
  Object.keys(categoryPerformance).forEach(categoryName => {
    const data = categoryPerformance[categoryName];
    
    if (data.maxPoints > 0) {
      data.percentage = (data.points / data.maxPoints) * 100;
      
      const badgeClass = Utils.getGradeColor(data.percentage);
      const formattedPercentage = Utils.formatPercentage(data.percentage);
      
      // Compact view (in Performance card)
      if (categoryAveragesCompact) {
        const divCompact = document.createElement('div');
        divCompact.className = 'd-flex justify-content-between align-items-center mb-1';
        divCompact.innerHTML = `
          <span class="text-muted" style="font-size: 0.8rem;">${categoryName} (${data.count}):</span>
          <span class="badge bg-${badgeClass}" style="font-size: 0.75rem;">${formattedPercentage}</span>
        `;
        categoryAveragesCompact.appendChild(divCompact);
      }
      
      // Detail view (in right column)
      if (categoryAveragesDetail) {
        const divDetail = document.createElement('div');
        divDetail.className = 'd-flex justify-content-between align-items-center mb-2';
        divDetail.innerHTML = `
          <div>
            <span class="fw-semibold">${categoryName}</span>
            <small class="text-muted d-block">${data.count} assignment${data.count !== 1 ? 's' : ''}</small>
          </div>
          <span class="badge bg-${badgeClass}">${formattedPercentage}</span>
        `;
        categoryAveragesDetail.appendChild(divDetail);
      }
      
      hasValidCategories = true;
    } else if (data.count === 0) {
      // Show categories with no assignments
      if (categoryAveragesCompact) {
        const divCompact = document.createElement('div');
        divCompact.className = 'd-flex justify-content-between align-items-center mb-1';
        divCompact.innerHTML = `
          <span class="text-muted" style="font-size: 0.8rem;">${categoryName} (0):</span>
          <span class="badge bg-secondary" style="font-size: 0.75rem;">N/A</span>
        `;
        categoryAveragesCompact.appendChild(divCompact);
      }
      
      if (categoryAveragesDetail) {
        const divDetail = document.createElement('div');
        divDetail.className = 'd-flex justify-content-between align-items-center mb-2';
        divDetail.innerHTML = `
          <div>
            <span class="fw-semibold">${categoryName}</span>
            <small class="text-muted d-block">0 assignments</small>
          </div>
          <span class="badge bg-secondary">N/A</span>
        `;
        categoryAveragesDetail.appendChild(divDetail);
      }
      
      hasValidCategories = true;
    }
  });
  
  if (!hasValidCategories) {
    if (categoryAveragesCompact) categoryAveragesCompact.innerHTML = '<small class="text-muted">No categories</small>';
    if (categoryAveragesDetail) categoryAveragesDetail.innerHTML = '<small class="text-muted">No categories</small>';
  }
}

    togglePredictMode() {
      // Legacy function - redirect to new toggle system
      const toggle = document.getElementById('predictModeToggle');
      if (toggle) {
        toggle.checked = !toggle.checked;
        toggle.dispatchEvent(new Event('change'));
      }
    }

    enableAnalyzeMode() {
      document.body.classList.add('analyze-mode-active');
      this.initializeAnalysis();
    }

    disableAnalyzeMode() {
      document.body.classList.remove('analyze-mode-active');
    }

    enablePredictMode() {
      console.log('enablePredictMode called, current state:', this.state.predictMode);
      if (this.state.predictMode) return; // Already enabled
      
      this.state.predictMode = true;
      document.body.classList.add('predict-mode-active');
      console.log('Added predict-mode-active class to body');
      
      // Show predict mode elements
      const calculatorContainer = document.getElementById('gradeGoalCalculatorContainer');
      const mockSection = document.getElementById('mockAssignmentSection');
      
      console.log('Calculator container:', calculatorContainer);
      console.log('Mock section:', mockSection);
      
      if (calculatorContainer) {
        calculatorContainer.classList.remove('d-none');
        console.log('Showed calculator container');
      }
      if (mockSection) {
        mockSection.classList.remove('d-none');
        console.log('Showed mock section');
      }
      
      // Update grade card title
      const gradeCardTitle = document.getElementById('gradeCardTitle');
      if (gradeCardTitle) {
        gradeCardTitle.innerHTML = '<i class="bi bi-lightbulb text-warning me-2"></i>Predicted Grade';
      }
      
      // Populate category dropdown in grade goal calculator
      this.populateGradeGoalCategories();
      
      // Store original scores
      this.state.originalScores.clear();
      this.state.assignmentsData.forEach((assignment, index) => {
        this.state.originalScores.set(index, assignment.score);
      });
      
      this.renderAssignmentCardsForPredictMode();
      this.renderMockAssignments();
      console.log('Predict mode enabled successfully');
    }

    populateGradeGoalCategories() {
      const weightSelect = document.getElementById('assignmentWeightSelect');
      const categoryOptions = document.getElementById('categoryWeightOptions');
      const customInput = document.getElementById('assignmentWeight');
      
      if (!weightSelect || !categoryOptions) return;
      
      categoryOptions.innerHTML = '';
      
      // Populate categories from API
      if (this.state.currentDetailClass?.categories && this.state.currentDetailClass.categories.length > 1) {
        this.state.currentDetailClass.categories.slice(1).forEach(categoryRow => {
          if (categoryRow[0] && categoryRow[0] !== 'Total Points:' && categoryRow[4]) {
            const categoryName = categoryRow[0];
            const weightPercent = parseFloat(categoryRow[4]);
            
            if (!isNaN(weightPercent) && weightPercent > 0) {
              const option = document.createElement('option');
              option.value = weightPercent;
              option.textContent = `${categoryName} (${weightPercent}%)`;
              categoryOptions.appendChild(option);
            }
          }
        });
      }
      
      // Handle dropdown change
      weightSelect.addEventListener('change', () => {
        if (weightSelect.value === 'custom') {
          customInput.classList.remove('d-none');
          customInput.value = '';
        } else {
          customInput.classList.add('d-none');
          customInput.value = weightSelect.value;
        }
      });
    }

    disablePredictMode() {
      if (!this.state.predictMode) return; // Not enabled
      
      this.state.predictMode = false;
      document.body.classList.remove('predict-mode-active');
      
      // Hide predict mode elements
      const calculatorContainer = document.getElementById('gradeGoalCalculatorContainer');
      const mockSection = document.getElementById('mockAssignmentSection');
      
      if (calculatorContainer) {
        calculatorContainer.classList.add('d-none');
      }
      if (mockSection) {
        mockSection.classList.add('d-none');
      }
      
      // Reset grade card title
      const gradeCardTitle = document.getElementById('gradeCardTitle');
      if (gradeCardTitle) {
        gradeCardTitle.textContent = 'Current Grade';
      }
      
      // Reset scores
      this.state.originalScores.forEach((originalScore, index) => {
        if (this.state.assignmentsData[index]) {
          this.state.assignmentsData[index].score = originalScore;
          this.state.assignmentsData[index].percentage = originalScore ? 
            (originalScore / this.state.assignmentsData[index].total) * 100 : NaN;
        }
      });
      
      this.state.exemptAssignments.clear();
      this.state.mockAssignmentsData = [];
      this.state.mockCounter = 1;
      
      // Reset to API average
      if (this.state.currentDetailClass?.average && this.state.currentDetailClass.average !== 'N/A') {
        this.state.currentDetailAverage = parseFloat(this.state.currentDetailClass.average);
        this.updateGradeDisplay();
      }
      
      // Re-render normal view
      this.renderAssignmentCards();
      this.state.originalScores.clear();
    }

    makeScoresEditable() {
      // Re-render cards with editable functionality in predict mode
      this.renderAssignmentCardsForPredictMode();
    }
    
    renderAssignmentCardsForPredictMode() {
      if (!this.elements.assignmentsListCompact || !this.elements.assignmentsGrid) return;
      
      this.elements.assignmentsListCompact.innerHTML = '';
      this.elements.assignmentsGrid.innerHTML = '';
      
      this.state.assignmentsData.forEach((assignment, index) => {
        const isExempt = this.state.exemptAssignments.has(index);
        const isNa = (assignment.score === null || assignment.score === undefined || assignment.score === '') && isNaN(assignment.percentage);
        const percentage = assignment.percentage;
        const badgeClass = isNa ? 'secondary' : Utils.getGradeColor(percentage);
        const displayScore = isNa ? 'N/A' : assignment.score;
        const displayPercentage = isNa ? 'N/A' : Utils.formatPercentage(percentage);
        const isEdited = this.state.originalScores.has(index) && 
                        this.state.originalScores.get(index) !== assignment.score;
        
        // Determine border class
        let borderClass = 'border-grade-secondary';
        if (!isNa) {
          if (percentage >= 90) borderClass = 'border-grade-success';
          else if (percentage >= 80) borderClass = 'border-grade-primary';
          else if (percentage >= 70) borderClass = 'border-grade-warning';
          else borderClass = 'border-grade-danger';
        }
        
        // Compact View Card with Predict Mode Controls
        const compactCard = document.createElement('div');
        compactCard.className = `assignment-card-compact ${borderClass} ${isExempt ? 'opacity-50' : ''}`;
        compactCard.innerHTML = `
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="assignment-name flex-grow-1">${assignment.name}</div>
            <button class="btn btn-sm ${isExempt ? 'btn-warning' : 'btn-outline-warning'} exempt-btn-card" data-index="${index}" title="${isExempt ? 'Click to include in grade calculation' : 'Click to exclude from grade calculation'}">
              <i class="bi ${isExempt ? 'bi-eye-slash' : 'bi-eye'}"></i> ${isExempt ? 'Excluded' : 'Include'}
            </button>
          </div>
          <div class="assignment-meta">
            <div class="assignment-meta-item">
              <i class="bi bi-folder"></i>
              <span>${assignment.category}</span>
            </div>
            <div class="assignment-meta-item">
              <i class="bi bi-calendar-event"></i>
              <span>Due: ${assignment.date || 'N/A'}</span>
            </div>
          </div>
          <div class="assignment-score-display">
            <div class="score-fraction editable-score-card" data-index="${index}" style="cursor: pointer;">
              <i class="bi bi-pencil me-2 text-warning"></i>
              <span class="score-value">${displayScore}</span> / ${assignment.total || 'N/A'}
              ${isEdited ? '<small class="text-warning ms-1">(Edited)</small>' : ''}
            </div>
            <div class="d-flex align-items-center gap-2">
              ${isEdited ? `<button class="btn btn-sm btn-outline-secondary reset-score-btn" data-index="${index}" title="Reset to original"><i class="bi bi-arrow-counterclockwise"></i></button>` : ''}
              <span class="badge bg-${badgeClass} score-badge-large">${displayPercentage}</span>
            </div>
          </div>
        `;
        
        this.elements.assignmentsListCompact.appendChild(compactCard);
        
        // Add event listeners
        const exemptBtn = compactCard.querySelector('.exempt-btn-card');
        const editableScore = compactCard.querySelector('.editable-score-card');
        const resetBtn = compactCard.querySelector('.reset-score-btn');
        
        exemptBtn.addEventListener('click', (e) => {
          e.stopPropagation();
          const idx = parseInt(e.currentTarget.getAttribute('data-index'));
          
          if (this.state.exemptAssignments.has(idx)) {
            this.state.exemptAssignments.delete(idx);
          } else {
            this.state.exemptAssignments.add(idx);
          }
          
          this.calculateCurrentGrade();
          this.updateGradeDisplay();
          this.renderAssignmentCardsForPredictMode();
        });
        
        editableScore.addEventListener('click', (e) => {
          e.stopPropagation();
          this.startEditingScoreCard(index);
        });
        
        if (resetBtn) {
          resetBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const idx = parseInt(e.currentTarget.getAttribute('data-index'));
            this.resetAssignmentScore(idx);
          });
        }
        
        // Detailed View Card with Predict Mode Controls
        const detailedCard = document.createElement('div');
        detailedCard.className = `col-md-6 col-lg-4 ${isExempt ? 'opacity-50' : ''}`;
        detailedCard.innerHTML = `
          <div class="card assignment-card-detailed ${borderClass}">
            <div class="card-header assignment-card-header">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 flex-grow-1">${assignment.name}</h6>
                <button class="btn btn-sm ${isExempt ? 'btn-warning' : 'btn-outline-warning'} exempt-btn-card-detailed" data-index="${index}" title="${isExempt ? 'Click to include in grade calculation' : 'Click to exclude from grade calculation'}">
                  <i class="bi ${isExempt ? 'bi-eye-slash' : 'bi-eye'}"></i> ${isExempt ? 'Excluded' : 'Include'}
                </button>
              </div>
            </div>
            <div class="card-body assignment-card-body">
              <div class="assignment-detail-row">
                <span class="assignment-detail-label">
                  <i class="bi bi-folder me-1"></i> Category
                </span>
                <span class="assignment-detail-value">${assignment.category}</span>
              </div>
              <div class="assignment-detail-row">
                <span class="assignment-detail-label">
                  <i class="bi bi-calendar-event me-1"></i> Due Date
                </span>
                <span class="assignment-detail-value">${assignment.date || 'N/A'}</span>
              </div>
              <div class="assignment-detail-row editable-score-card-detailed" data-index="${index}" style="cursor: pointer; background: rgba(255,193,7,0.1);">
                <span class="assignment-detail-label">
                  <i class="bi bi-pencil text-warning me-1"></i> Score (Click to Edit)
                </span>
                <span class="assignment-detail-value">
                  <span class="score-value">${displayScore}</span> / ${assignment.total || 'N/A'}
                  ${isEdited ? '<small class="text-warning ms-1">(Edited)</small>' : ''}
                </span>
              </div>
              <div class="mt-2 d-flex justify-content-between align-items-center">
                ${isEdited ? `<button class="btn btn-sm btn-outline-secondary reset-score-btn-detailed" data-index="${index}"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>` : '<span></span>'}
                <span class="badge bg-${badgeClass} score-badge-large">${displayPercentage}</span>
              </div>
            </div>
          </div>
        `;
        
        this.elements.assignmentsGrid.appendChild(detailedCard);
        
        // Add event listeners for detailed card
        const exemptBtnDetailed = detailedCard.querySelector('.exempt-btn-card-detailed');
        const editableScoreDetailed = detailedCard.querySelector('.editable-score-card-detailed');
        const resetBtnDetailed = detailedCard.querySelector('.reset-score-btn-detailed');
        
        exemptBtnDetailed.addEventListener('click', (e) => {
          e.stopPropagation();
          const idx = parseInt(e.currentTarget.getAttribute('data-index'));
          
          if (this.state.exemptAssignments.has(idx)) {
            this.state.exemptAssignments.delete(idx);
          } else {
            this.state.exemptAssignments.add(idx);
          }
          
          this.calculateCurrentGrade();
          this.updateGradeDisplay();
          this.renderAssignmentCardsForPredictMode();
        });
        
        editableScoreDetailed.addEventListener('click', (e) => {
          e.stopPropagation();
          this.startEditingScoreCard(index);
        });
        
        if (resetBtnDetailed) {
          resetBtnDetailed.addEventListener('click', (e) => {
            e.stopPropagation();
            const idx = parseInt(e.currentTarget.getAttribute('data-index'));
            this.resetAssignmentScore(idx);
          });
        }
      });
    }
    
    resetAssignmentScore(index) {
      if (this.state.originalScores.has(index)) {
        const originalScore = this.state.originalScores.get(index);
        if (this.state.assignmentsData[index]) {
          this.state.assignmentsData[index].score = originalScore;
          this.state.assignmentsData[index].percentage = originalScore ? 
            (originalScore / this.state.assignmentsData[index].total) * 100 : NaN;
        }
        this.calculateCurrentGrade();
        this.updateGradeDisplay();
        this.renderAssignmentCardsForPredictMode();
      }
    }
    
    startEditingScoreCard(index) {
      const assignment = this.state.assignmentsData[index];
      if (!assignment) return;
      
      const currentScore = assignment.score || 0;
      const totalPoints = assignment.total || 100;
      
      const newScore = prompt(
        `Edit score for: ${assignment.name}\n\nTotal Points: ${totalPoints}\nCurrent Score: ${currentScore}`,
        currentScore
      );
      
      if (newScore !== null) {
        const scoreValue = parseFloat(newScore);
        //if (!isNaN(scoreValue) && scoreValue >= 0) {
          assignment.score = scoreValue;
          assignment.percentage = (scoreValue / assignment.total) * 100;
          this.calculateCurrentGrade();
          this.updateGradeDisplay();
          this.renderAssignmentCardsForPredictMode();
        //} else {
        //  alert(`Please enter a valid score between 0 and ${totalPoints}`);
        //}
      }
    }

    
    populateMockAssignmentCategories() {
      const categorySelect = this.elements.mockAssignmentCategory;
      if (!categorySelect) return;
      
      categorySelect.innerHTML = '<option value="Custom">Custom</option>';
      
      if (!this.state.currentDetailClass?.categories) return;
      
      const categories = new Set();
      
      if (this.state.currentDetailClass.categories.length > 1) {
        this.state.currentDetailClass.categories.slice(1).forEach(categoryRow => {
          if (categoryRow[0] && categoryRow[0] !== 'Total Points:') {
            categories.add(categoryRow[0]);
          }
        });
      }
      
      this.state.assignmentsData.forEach(assignment => {
        if (assignment.category) {
          categories.add(assignment.category);
        }
      });
      
      categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category;
        option.textContent = category;
        categorySelect.appendChild(option);
      });
    }

    handleMockCategoryChange(selectedCategory) {
      const weightInput = this.elements.mockAssignmentWeight;
      
      if (selectedCategory === 'Custom') {
        weightInput.disabled = false;
        weightInput.value = 10;
        weightInput.focus();
      } else {
        weightInput.disabled = true;
        
        let weight = 10;
        
        if (this.state.currentDetailClass?.categories) {
          for (let i = 1; i < this.state.currentDetailClass.categories.length; i++) {
            const categoryRow = this.state.currentDetailClass.categories[i];
            if (categoryRow[0] === selectedCategory && categoryRow[4]) {
              const weightPercent = parseFloat(categoryRow[4]);
              if (!isNaN(weightPercent)) {
                weight = weightPercent;
                break;
              }
            }
          }
        }
        
        weightInput.value = weight;
      }
    }

    showAddMockAssignmentModal() {
      this.populateMockAssignmentCategories();
      
      this.elements.mockAssignmentCategory.value = 'Custom';
      this.elements.mockAssignmentWeight.value = '';
      this.elements.mockAssignmentWeight.disabled = false;
      this.elements.mockAssignmentName.value = `Mock Assignment #${this.state.mockCounter}`;
      this.elements.mockAssignmentTotal.value = '';
      this.elements.mockAssignmentScore.value = '';
      
      const modal = new bootstrap.Modal(document.getElementById('addMockAssignmentModal'));
      modal.show();
    }

    saveMockAssignment() {
      const name = this.elements.mockAssignmentName.value || `Mock Assignment #${this.state.mockCounter}`;
      const category = this.elements.mockAssignmentCategory.value;
      const weight = parseFloat(this.elements.mockAssignmentWeight.value);
      const total = parseFloat(this.elements.mockAssignmentTotal.value);
      const score = parseFloat(this.elements.mockAssignmentScore.value);
      
      if (isNaN(weight) || isNaN(total) || isNaN(score)) {
        alert('Please enter valid numbers for weight, total points, and score.');
        return;
      }
      
      const mockAssignment = {
        name: name,
        category: category,
        weight: weight,
        total: total,
        score: score,
        percentage: (score / total) * 100,
        isMock: true,
        exempt: false
      };
      
      this.state.mockAssignmentsData.push(mockAssignment);
      this.state.mockCounter++;
      
      this.elements.mockAssignmentName.value = `Mock Assignment #${this.state.mockCounter}`;
      this.elements.mockAssignmentCategory.value = 'Custom';
      this.elements.mockAssignmentWeight.value = '';
      this.elements.mockAssignmentTotal.value = '';
      this.elements.mockAssignmentScore.value = '';
      
      bootstrap.Modal.getInstance(document.getElementById('addMockAssignmentModal')).hide();
      
      this.renderMockAssignments();
      this.calculateCurrentGrade();
      this.updateGradeDisplay();
      
      // Update the chart to include new mock assignment
      this.initializeAnalysis();
    }

renderMockAssignments() {
  const mockList = document.getElementById('mockAssignmentsList');
  if (!mockList) return;
  
  mockList.innerHTML = '';
  
  if (this.state.mockAssignmentsData.length === 0) {
    mockList.innerHTML = `
      <p class="text-muted text-center py-3">
        <i class="bi bi-info-circle me-2"></i>
        No hypothetical assignments yet. Click "Add Assignment" to get started.
      </p>
    `;
    return;
  }
  
  this.state.mockAssignmentsData.forEach((assignment, index) => {
    const percentage = (assignment.score / assignment.total) * 100;
    const badgeClass = Utils.getGradeColor(percentage);
    const displayPercentage = Utils.formatPercentage(percentage);
    
    const card = document.createElement('div');
    card.className = `card mb-2 ${assignment.exempt ? 'opacity-50' : ''}`;
    card.innerHTML = `
      <div class="card-body p-3">
        <div class="row align-items-center">
          <div class="col-md-4">
            <strong>${assignment.name}</strong>
            <div class="small text-muted">${assignment.category} • ${assignment.weight}% weight</div>
          </div>
          <div class="col-md-3">
            <div class="editable-score-mock" data-mock-index="${index}" style="cursor: pointer; padding: 0.25rem 0.5rem; border: 2px dashed var(--bs-warning); border-radius: 0.25rem; display: inline-block;">
              <i class="bi bi-pencil text-warning me-1"></i>
              <strong>${assignment.score}</strong> / ${assignment.total}
            </div>
          </div>
          <div class="col-md-2 text-center">
            <span class="badge bg-${badgeClass}">${displayPercentage}</span>
          </div>
          <div class="col-md-3 text-end">
            <button type="button" class="btn btn-sm ${assignment.exempt ? 'btn-warning' : 'btn-outline-warning'} me-1" data-action="toggle-exempt" data-index="${index}" title="${assignment.exempt ? 'Click to include in grade' : 'Click to exclude from grade'}">
              <i class="bi ${assignment.exempt ? 'bi-eye-slash' : 'bi-eye'}"></i> ${assignment.exempt ? 'Excluded' : 'Include'}
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" data-action="delete" data-index="${index}">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>
    `;
    
    mockList.appendChild(card);
    
    // Add event listeners
    const editBtn = card.querySelector('.editable-score-mock');
    const exemptBtn = card.querySelector('[data-action="toggle-exempt"]');
    const deleteBtn = card.querySelector('[data-action="delete"]');
    
    editBtn.addEventListener('click', () => {
      const newScore = prompt(
        `Edit score for: ${assignment.name}\n\nTotal Points: ${assignment.total}\nCurrent Score: ${assignment.score}`,
        assignment.score
      );
      
      if (newScore !== null) {
        const scoreValue = parseFloat(newScore);
        assignment.score = scoreValue;
        assignment.percentage = (scoreValue / assignment.total) * 100;
        this.calculateCurrentGrade();
        this.updateGradeDisplay();
        this.renderMockAssignments();
      }
    });
    
    exemptBtn.addEventListener('click', () => {
      assignment.exempt = !assignment.exempt;
      this.calculateCurrentGrade();
      this.updateGradeDisplay();
      this.renderMockAssignments();
    });
    
    deleteBtn.addEventListener('click', () => {
      if (confirm(`Delete "${assignment.name}"?`)) {
        this.state.mockAssignmentsData.splice(index, 1);
        this.calculateCurrentGrade();
        this.updateGradeDisplay();
        this.renderMockAssignments();
        this.initializeAnalysis();
      }
    });
  });
}

startEditingMockScore(element, index) {
  if (!this.state.predictMode) return;
  
  const assignment = this.state.mockAssignmentsData[index];
  if (!assignment) return;
  
  const currentScore = assignment.score || 0;
  const totalPoints = assignment.total || 100;
  
  element.classList.add('editing');
  element.innerHTML = `
    <input type="number" 
           class="score-input-predict" 
           value="${currentScore}" 
           min="0" 
           max="${totalPoints}" 
           step="0.1"
           data-mock-index="${index}"
           style="width: 80px; border: 1px solid #17a2b8; border-radius: 4px; padding: 2px 4px;">
  `;
  
  const input = element.querySelector('input');
  input.focus();
  input.select();
  
  const finishEdit = () => this.finishEditingMockScore(input, index);
  
  input.addEventListener('change', finishEdit);
  input.addEventListener('blur', finishEdit);
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') finishEdit();
    else if (e.key === 'Escape') this.cancelEditingMockScore(element, index);
  });
}

finishEditingMockScore(input, index) {
  const newScore = parseFloat(input.value);
  const assignment = this.state.mockAssignmentsData[index];
  
  if (!isNaN(newScore) && assignment) {
    assignment.score = newScore;
    assignment.percentage = (newScore / assignment.total) * 100;
    
    const element = input.closest('.editable-score');
    element.classList.remove('editing');
    element.textContent = newScore;
    
    // Update the percentage badge
    const percentage = (assignment.score / assignment.total) * 100;
    const badgeClass = Utils.getGradeColor(percentage);
    const percentageStr = Utils.formatPercentage(percentage);
    
    const percentageBadge = document.querySelector(`.mock-percentage-badge[data-mock-index="${index}"]`);
    if (percentageBadge) {
      percentageBadge.className = `badge bg-${badgeClass} mock-percentage-badge`;
      percentageBadge.setAttribute('data-mock-index', index);
      percentageBadge.textContent = percentageStr;
    }
    
    this.calculateCurrentGrade();
    this.updateGradeDisplay();
  }
}

cancelEditingMockScore(element, index) {
  const assignment = this.state.mockAssignmentsData[index];
  if (assignment) {
    element.classList.remove('editing');
    element.textContent = assignment.score || 'N/A';
  }
}

startMobileMockScoreEditing(index) {
  const assignment = this.state.mockAssignmentsData[index];
  if (!assignment) return;
  
  const newScore = prompt(`Enter new score for "${assignment.name}":\nTotal points: ${assignment.total}`, assignment.score || 0);
  if (newScore !== null) {
    const scoreValue = parseFloat(newScore);
    //if (!isNaN(scoreValue) && scoreValue >= 0 && scoreValue <= assignment.total) {
      assignment.score = scoreValue;
      assignment.percentage = (scoreValue / assignment.total) * 100;
      this.calculateCurrentGrade();
      this.updateGradeDisplay();
      this.renderMockAssignments();
    //} else {
    //  alert(`Please enter a valid score between 0 and ${assignment.total}`);
    //}
  }
}

    calculateRequiredGrade() {
      const targetGrade = parseFloat(this.elements.targetGrade.value);
      const weightSelect = document.getElementById('assignmentWeightSelect');
      const assignmentWeightPercent = parseFloat(document.getElementById('assignmentWeight').value);
      
      // Validate inputs
      if (!weightSelect || !weightSelect.value) {
        this.elements.requiredGradeResult.innerHTML = 
          '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Please select an assignment weight</div>';
        return;
      }
      
      if (isNaN(assignmentWeightPercent) || assignmentWeightPercent <= 0 || assignmentWeightPercent > 100) {
        this.elements.requiredGradeResult.innerHTML = 
          '<div class="alert alert-warning"><i class="bi bi-exclamation-triangle me-2"></i>Please enter a valid assignment weight (0.1-100%)</div>';
        return;
      }
      
      if (isNaN(targetGrade) || targetGrade < 0 || targetGrade > 100) {
        this.elements.requiredGradeResult.innerHTML = 
          '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Please enter a valid target grade (0-100)</div>';
        return;
      }
      
      if (isNaN(this.state.currentDetailAverage)) {
        this.elements.requiredGradeResult.innerHTML = 
          '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i>Current average is not available</div>';
        return;
      }
      
      // Use binary search to find the required score
      // We'll simulate adding a mock assignment and calculate the resulting grade
      let low = 0;
      let high = 100;
      let requiredScore = -1;
      const tolerance = 0.001; // Within 0.01%
      
      // Binary search for the score that gets us to the target
      while (high - low > 0.001) {
        const mid = (low + high) / 2;
        const resultingGrade = this.calculateGradeWithMockAssignment(mid, assignmentWeightPercent);
        
        if (Math.abs(resultingGrade - targetGrade) < tolerance) {
          requiredScore = mid;
          break;
        } else if (resultingGrade < targetGrade) {
          low = mid;
        } else {
          high = mid;
        }
      }
      
      if (requiredScore === -1) {
        requiredScore = (low + high) / 2;
      }
      
      // Verify the result
      const actualResult = this.calculateGradeWithMockAssignment(requiredScore, assignmentWeightPercent);
      
      const formattedScore = Math.round(requiredScore * 1000) / 1000;
      const formattedResult = Math.round(actualResult * 1000) / 1000;
      
      let resultHTML = '';
      if (requiredScore <= 100 && requiredScore >= 0) {
        const badgeClass = Utils.getGradeColor(requiredScore);
        resultHTML = `
          <div class="alert alert-success">
            <h6 class="alert-heading"><i class="bi bi-check-circle me-2"></i>You Need: <span class="badge bg-${badgeClass} fs-4">${formattedScore}%</span></h6>
            <hr>
            <p class="mb-1"><strong>Breakdown:</strong></p>
            <ul class="mb-0">
              <li>Current Grade: <strong>${this.state.currentDetailAverage.toFixed(2)}%</strong></li>
              <li>Target Grade: <strong>${targetGrade}%</strong></li>
              <li>Assignment Weight: <strong>${assignmentWeightPercent}%</strong> of overall grade</li>
              <li>Predicted Result: <strong>${formattedResult}%</strong></li>
            </ul>
          </div>
        `;
      } else if (requiredScore < 0) {
        resultHTML = `
          <div class="alert alert-success">
            <h6 class="alert-heading"><i class="bi bi-check-circle me-2"></i>You're Already There!</h6>
            <p class="mb-0">Even scoring <strong>0%</strong> on this assignment, you'll achieve your target grade of <strong>${targetGrade}%</strong>.</p>
          </div>
        `;
      } else {
        resultHTML = `
          <div class="alert alert-danger">
            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle me-2"></i>Not Possible</h6>
            <p class="mb-2">You would need <strong>${formattedScore}%</strong> to reach <strong>${targetGrade}%</strong>, which is above 100%.</p>
            <small class="mb-0">Try lowering your target grade or add more hypothetical assignments to improve your grade first.</small>
          </div>
        `;
      }
      
      this.elements.requiredGradeResult.innerHTML = resultHTML;
    }
    
    calculateGradeWithMockAssignment(score, weightPercent) {
      // Create a temporary mock assignment with the given score and weight
      // We'll use the same calculation method as the actual mock assignments
      
      // Get all current assignments
      const allAssignments = [...this.state.assignmentsData];
      
      // Add our test mock assignment
      const mockAssignment = {
        name: 'Test Assignment',
        category: 'Mock Category',
        score: score,
        total: 100,
        percentage: score,
        dateAssigned: '',
        date: '',
        exempt: false,
        isMock: true
      };
      
      allAssignments.push(mockAssignment);
      
      // Calculate weighted average using the actual grading formula
      if (!this.state.currentDetailClass?.categories || this.state.currentDetailClass.categories.length <= 1) {
        return this.state.currentDetailAverage;
      }
      
      const categoryData = {};
      
      // Initialize categories from API
      this.state.currentDetailClass.categories.slice(1).forEach(categoryRow => {
        if (categoryRow[0] && categoryRow[0] !== 'Total Points:' && categoryRow[4]) {
          const categoryName = categoryRow[0];
          const categoryWeight = parseFloat(categoryRow[4]);
          
          categoryData[categoryName] = {
            weight: categoryWeight / 100,
            points: 0,
            maxPoints: 0
          };
        }
      });
      
      // Add mock category with specified weight
      categoryData['Mock Category'] = {
        weight: weightPercent / 100,
        points: 0,
        maxPoints: 0
      };
      
      // Adjust other category weights proportionally
      const totalExistingWeight = Object.values(categoryData).reduce((sum, cat) => {
        if (cat.weight > 0) return sum + cat.weight;
        return sum;
      }, 0) - (weightPercent / 100);
      
      const adjustmentFactor = (1 - weightPercent / 100) / totalExistingWeight;
      
      Object.keys(categoryData).forEach(catName => {
        if (catName !== 'Mock Category') {
          categoryData[catName].weight *= adjustmentFactor;
        }
      });
      
      // Calculate points for each category
      allAssignments.forEach((assignment, index) => {
        if (index < this.state.assignmentsData.length && this.state.exemptAssignments.has(index)) return;
        
        const category = assignment.category;
        if (!categoryData[category]) return;
        
        const hasValidScore = !isNaN(assignment.score) && assignment.score !== null && assignment.score !== '';
        const hasValidTotal = !isNaN(assignment.total) && assignment.total > 0;
        
        if (hasValidScore && hasValidTotal) {
          categoryData[category].points += parseFloat(assignment.score);
          categoryData[category].maxPoints += parseFloat(assignment.total);
        }
      });
      
      // Calculate weighted average
      let totalWeightedScore = 0;
      let totalWeight = 0;
      
      Object.values(categoryData).forEach(category => {
        if (category.maxPoints > 0) {
          const categoryAverage = (category.points / category.maxPoints) * 100;
          totalWeightedScore += categoryAverage * category.weight;
          totalWeight += category.weight;
        }
      });
      
      if (totalWeight === 0) return this.state.currentDetailAverage;
      
      return totalWeightedScore / totalWeight;
    }

    showMobileAssignmentModal(assignment, index) {
      const modal = document.getElementById('mobileAssignmentModal');
      document.getElementById('mobileAssignmentModalLabel').textContent = assignment.name;
      
      const badgeClass = Utils.getGradeColor(assignment.percentage);
      const percentageStr = Utils.formatPercentage(assignment.percentage);
      const isExempt = this.state.exemptAssignments.has(index);
      
      const detailsContainer = document.getElementById('mobileAssignmentDetails');
      detailsContainer.innerHTML = `
        <div class="mb-3">
          <strong>Assignment:</strong> ${assignment.name}
        </div>
        <div class="mb-2">
          <strong>Date Assigned:</strong> ${assignment.dateAssigned || 'N/A'}
        </div>
        <div class="mb-2">
          <strong>Date Due:</strong> ${assignment.date || 'N/A'}
        </div>
        <div class="mb-2">
          <strong>Category:</strong> ${assignment.category}
        </div>
        <div class="mb-2">
          <strong>Score:</strong> ${assignment.score || 'N/A'}/${assignment.total || 'N/A'}
        </div>
        <div class="mb-2">
          <strong>Percentage:</strong> <span class="badge bg-${badgeClass}">${percentageStr}</span>
        </div>
        ${isExempt ? '<div class="alert alert-warning mt-2"><i class="bi bi-exclamation-triangle"></i> This assignment is excluded from calculation</div>' : ''}
      `;
    }

    handleTabChange(tab) {
  // Abort active requests before switching tabs
  this.requests.abortByPattern('load-');
  
  // Reset error displays
  this.elements.errorContainer.style.display = 'none';
  this.elements.errorContainer.innerHTML = '';
  
  const tabType = tab.getAttribute('data-tab');
  const dataType = tab.getAttribute('data-type');
  
  document.querySelectorAll('#gradesTabs .nav-link').forEach(t => {
    t.classList.remove('active');
  });
  tab.classList.add('active');
  
  this.state.currentView = tabType;
  this.state.currentDataType = dataType;
  this.state.currentIprRcType = dataType;
  
  if (tabType === 'running-average') {
    this.showRunningAverageView();
  } else if (tabType === 'ipr') {
    this.showIPRView();
  } else if (tabType === 'report-card') {
    this.showReportCardView();
  }
}

    showRunningAverageView() {
  // Reset error displays
  this.elements.errorContainer.style.display = 'none';
  this.elements.errorContainer.innerHTML = '';
  
  document.getElementById('runningAverageView').classList.remove('d-none');
  this.elements.iprRcView.classList.add('d-none');
  this.elements.runSelectorContainer.style.display = 'block';
  if (this.elements.dateSelectorContainer) {
    this.elements.dateSelectorContainer.style.display = 'none';
  }
  document.getElementById('lastUpdatedBadge')?.classList.remove('d-none');
  document.body.classList.remove('ipr-rc-active');
  
  // Update mobile navbar for running average view
  const mobileBackBtn = document.getElementById('mobile-grades-back-btn');
  const mobileTitle = document.getElementById('mobile-grades-navbar-title');
  const mobileNavbar = document.getElementById('mobile-grades-navbar');
  const mobileMainActions = document.getElementById('mobile-grades-navbar-main-actions');
  const mobileDetailActions = document.getElementById('mobile-grades-navbar-detail-actions');
  
  if (mobileBackBtn) mobileBackBtn.classList.add('d-none');
  if (mobileTitle) mobileTitle.textContent = 'Grades';
  if (mobileNavbar) mobileNavbar.style.display = 'block';
  if (mobileMainActions) mobileMainActions.classList.remove('d-none');
  if (mobileDetailActions) mobileDetailActions.classList.add('d-none');
  
  const cacheKey = `running_average${this.state.currentRun ? '_' + this.state.currentRun : ''}`;
  const cached = this.cache.get(cacheKey);
  
  if (cached) {
    this.renderClasses(cached.classesData, this.state.currentRun);
  } else {
    this.loadRunningAverageData();
  }
}

    async loadRunningAverageData() {
  this.showLoader();
  this.elements.classesGridContainer.innerHTML = '';
  
  const result = await this.loadClasses(this.state.currentRun);
  if (result) {
    this.hideLoader();
    this.renderClasses(result.classesData, this.state.currentRun);
  }
}

    async showIPRView() {
  this.elements.errorContainer.style.display = 'none';
  this.elements.errorContainer.innerHTML = '';

  document.getElementById('runningAverageView').classList.add('d-none');
  this.elements.iprRcView.classList.remove('d-none');
  this.elements.runSelectorContainer.style.display = 'none';
  document.getElementById('lastUpdatedBadge')?.classList.add('d-none');
  document.body.classList.add('ipr-rc-active');
  
  // Update mobile navbar for IPR view
  const mobileBackBtn = document.getElementById('mobile-grades-back-btn');
  const mobileTitle = document.getElementById('mobile-grades-navbar-title');
  const mobileNavbar = document.getElementById('mobile-grades-navbar');
  const mobileMainActions = document.getElementById('mobile-grades-navbar-main-actions');
  const mobileDetailActions = document.getElementById('mobile-grades-navbar-detail-actions');
  
  if (mobileBackBtn) mobileBackBtn.classList.add('d-none');
  if (mobileTitle) mobileTitle.textContent = 'Grades';
  if (mobileNavbar) mobileNavbar.style.display = 'block';
  if (mobileMainActions) mobileMainActions.classList.remove('d-none');
  if (mobileDetailActions) mobileDetailActions.classList.add('d-none');
  
  this.showIPRLoader();
  
  if (!this.state.selectedDate && this.state.availableDates.length > 0) {
    this.state.selectedDate = this.state.availableDates[0].value;
  }
  
  await this.loadIPRData();
}

    async showReportCardView() {
  this.elements.errorContainer.style.display = 'none';
  this.elements.errorContainer.innerHTML = '';
  
  document.getElementById('runningAverageView').classList.add('d-none');
  this.elements.iprRcView.classList.remove('d-none');
  this.elements.runSelectorContainer.style.display = 'none';
  if (this.elements.dateSelectorContainer) {
    this.elements.dateSelectorContainer.style.display = 'none';
  }
  document.getElementById('lastUpdatedBadge')?.classList.add('d-none');
  document.body.classList.add('ipr-rc-active');
  
  // Update mobile navbar for Report Card view
  const mobileBackBtn = document.getElementById('mobile-grades-back-btn');
  const mobileTitle = document.getElementById('mobile-grades-navbar-title');
  const mobileNavbar = document.getElementById('mobile-grades-navbar');
  const mobileMainActions = document.getElementById('mobile-grades-navbar-main-actions');
  const mobileDetailActions = document.getElementById('mobile-grades-navbar-detail-actions');
  
  if (mobileBackBtn) mobileBackBtn.classList.add('d-none');
  if (mobileTitle) mobileTitle.textContent = 'Grades';
  if (mobileNavbar) mobileNavbar.style.display = 'block';
  if (mobileMainActions) mobileMainActions.classList.remove('d-none');
  if (mobileDetailActions) mobileDetailActions.classList.add('d-none');
  
  this.showIPRLoader();
  await this.loadReportCardData();
}
    showIPRLoader() {
      this.elements.iprRcLoader.classList.remove('d-none');
      this.elements.iprRcView.classList.add('d-none');
    }

async loadIPRData() {
  // Show loader specifically for IPR loading
  this.showIPRLoader();
  
  // FIX: Use the actual selected date, not "default"
  const selectedDate = this.state.selectedDate || (this.state.availableDates.length > 0 ? this.state.availableDates[0].value : "default");
  const cacheKey = `ipr_${this.state.currentRun || "default"}_${selectedDate}`;

  // Check cache first
  const cached = this.cache.get(cacheKey);
  if (cached) {
    console.log('Loading IPR from cache:', cacheKey);
    this.renderIPRData(cached);
    return;
  }
  
  console.log('Fetching IPR data:', cacheKey);
  const params = new URLSearchParams({ type: 'ipr' });
  if (this.state.currentRun) params.append('run', this.state.currentRun);
  if (selectedDate && selectedDate !== "default") params.append('date', selectedDate);
  
  try {
    const data = await this.requests.fetch('load-ipr', `/backends/classes-backend.php?${params.toString()}`);
    
    if (data) {
      // Cache this result
      this.cache.set(cacheKey, data);
      
      // If this is the default date (no date parameter), also cache it with the default date key
      if ((!selectedDate || selectedDate === "default") && data.is_default_date && data.default_ipr_date) {
        const defaultCacheKey = `ipr_${this.state.currentRun || "default"}_${data.default_ipr_date.value}`;
        console.log(`Caching default IPR date: ${data.default_ipr_date.text} with key: ${defaultCacheKey}`);
        this.cache.set(defaultCacheKey, data);
      }
      
      this.renderIPRData(data);
    } else {
      this.showError('Failed to load IPR data');
      this.elements.iprRcLoader.classList.add('d-none');
    }
  } catch (error) {
    console.error('Error loading IPR data:', error);
    this.showError('Error loading IPR data');
    this.elements.iprRcLoader.classList.add('d-none');
  }
}
async loadReportCardData() {
  this.showIPRLoader();
  
  const cacheKey = `rc_${this.state.currentRun || "default"}`;
  
  // Check cache first
  const cached = this.cache.get(cacheKey);
  if (cached) {
    console.log('Loading RC from cache:', cacheKey);
    this.renderIPRData(cached);
    return;
  }
  
  console.log('Fetching RC data:', cacheKey);
  const params = new URLSearchParams({ type: 'rc' });
  if (this.state.currentRun) params.append('run', this.state.currentRun);
  
  try {
    const data = await this.requests.fetch('load-rc', `/backends/classes-backend.php?${params.toString()}`);
    
    if (data) {
      this.cache.set(cacheKey, data);
      this.renderIPRData(data);
    } else {
      this.showError('Failed to load report card data');
      this.elements.iprRcLoader.classList.add('d-none');
    }
  } catch (error) {
    console.error('Error loading report card data:', error);
    this.showError('Error loading report card data');
    this.elements.iprRcLoader.classList.add('d-none');
  }
}

renderIPRData(data) {
  if (!data) return;
  
  this.elements.iprRcHeaders.innerHTML = '';
  this.elements.iprRcData.innerHTML = '';
  
  // POPULATE DATE SELECTOR IF MULTIPLE DATES AVAILABLE - FIXED
  if (data.multiple_dates_available && data.available_dates && data.available_dates.length > 0) {
    this.populateDateSelector(data.available_dates, data.selected_date);
    if (this.elements.dateSelectorContainer) {
      this.elements.dateSelectorContainer.style.display = "block";
    }
  } else {
    if (this.elements.dateSelectorContainer) {
      this.elements.dateSelectorContainer.style.display = "none";
    }
  }
  
  const headers = data.headers || [];
  const rows = data.data || [];
  
  // DETERMINE EMPTY COLUMNS
  const emptyColumns = [];
  if (headers.length > 0 && rows.length > 0) {
    for (let colIndex = 0; colIndex < headers.length; colIndex++) {
      let hasData = false;
      for (let rowIndex = 0; rowIndex < rows.length; rowIndex++) {
        const cell = rows[rowIndex][colIndex];
        if (cell !== undefined && cell !== null && cell !== '' && String(cell).trim() !== '') {
          hasData = true;
          break;
        }
      }
      if (!hasData) {
        emptyColumns.push(colIndex);
      }
    }
  }
  
  // FILTER HEADERS TO EXCLUDE EMPTY COLUMNS
  const filteredHeaders = headers.filter((header, index) => !emptyColumns.includes(index));
  
  // Render filtered headers
  filteredHeaders.forEach(header => {
    const th = document.createElement('th');
    th.textContent = header;
    th.scope = 'col';
    this.elements.iprRcHeaders.appendChild(th);
  });
  
  // Separate class data from comments - IMPROVED COMMENT DETECTION
  const classDataRows = [];
  const commentRows = [];
  
  rows.forEach(row => {
    if (!Array.isArray(row)) return;
    
    // Check if this is a comment row - MORE PERMISSIVE CONDITIONS
    const isCommentRow = 
      (row[0] === 'Comment' && row[1] === 'Description') ||
      (row.length === 2 && 
       typeof row[0] === 'string' && 
       typeof row[1] === 'string' &&
       row[0].trim().length > 0 &&
       row[1].trim().length > 0 &&
       // Allow letter codes (A-F, S, E, etc.) and number codes
       (/^[A-Za-z0-9]{1,3}$/.test(row[0].trim()) && row[1].trim().length > 5));
    
    if (isCommentRow) {
      commentRows.push(row);
    } else {
      // Filter out empty columns from class data rows
      const filteredRow = row.filter((cell, index) => !emptyColumns.includes(index));
      classDataRows.push(filteredRow);
    }
  });
  
  // Render class data with filtered columns
  classDataRows.forEach(row => {
    const tr = document.createElement('tr');
    
    filteredHeaders.forEach((header, i) => {
      const cell = row[i] !== undefined ? row[i] : '';
      const td = document.createElement('td');
      
      if (i === 0) {
        td.innerHTML = `<b>${cell || ''}</b>`;
    } else if (typeof cell === 'number' || (!isNaN(parseFloat(cell)) && cell !== '' && cell !== null)) {
      const numericValue = parseFloat(cell);
      if (!isNaN(numericValue)) {
        const gradeHeaders = ["PRG", "1ST", "2ND", "3RD", "4TH", "5TH", "6TH", "EXM1", "SEM1", "EXM2", "SEM2"];
        if (gradeHeaders.includes(header.toUpperCase())) {
          const badgeClass = Utils.getGradeColor(numericValue.toString());
          td.innerHTML = `<span class="badge bg-${badgeClass}">${numericValue}</span>`;
        } else {
          td.textContent = numericValue;
        }
      } else {
        td.textContent = cell || '';
      }
    } else if (cell && typeof cell === 'string') {
      const trimmedCell = cell.trim();
      const isLetterGrade = /^[A-FS]$/i.test(trimmedCell);
      const isCommentCode = /^\d{2,3}$/.test(trimmedCell);
      const gradeHeaders = ["PRG", "1ST", "2ND", "3RD", "4TH", "5TH", "6TH", "EXM1", "SEM1", "EXM2", "SEM2"];
      
      if (isLetterGrade && gradeHeaders.includes(header.toUpperCase())) {
        const badgeClass = Utils.getGradeColor(trimmedCell);
        td.innerHTML = `<span class="badge bg-${badgeClass}">${trimmedCell}</span>`;
      } else if (isCommentCode && gradeHeaders.includes(header.toUpperCase())) {
        td.innerHTML = `<span class="badge bg-info text-dark">${trimmedCell}</span>`;
      } else {
        td.textContent = cell || '';
      }
    } else {
        td.textContent = cell || '';
      }
      
      tr.appendChild(td);
    });
    
    this.elements.iprRcData.appendChild(tr);
  });
  
  const displayComments = data.comments && data.comments.length > 0 ? 
    data.comments : 
    commentRows.filter(comment => 
      !(comment[0] === 'Comment' && comment[1] === 'Description')
    );
  
  if (displayComments.length > 0) {
    const commentsSection = document.createElement('tr');
    commentsSection.innerHTML = `
      <td colspan="${filteredHeaders.length}" class="p-0" style="background-color: var(--bs-table-bg) !important;">
        <div class="comments-section border-top">
          <div class="p-3">
            <h6 class="mb-3"><i class="bi bi-chat-text me-2"></i>Comment Legend</h6>
            <div class="table-responsive">
              <table class="table table-sm table-bordered mb-0">
                <thead class="">
                  <tr>
                    <th>Code</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>
                  ${displayComments.map(comment => `
                    <tr>
                      <td class="fw-bold">${comment[0] || ''}</td>
                      <td>${comment[1] || ''}</td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </td>
    `;
    this.elements.iprRcData.appendChild(commentsSection);
  }
  
  this.elements.iprRcLoader.classList.add('d-none');
  this.elements.iprRcView.classList.remove('d-none');
}
populateDateSelector(dates, selectedDate) {
  const dateTabs = document.getElementById('dateTabs');
  const dateDropdown = document.getElementById('dateDropdown');
  
  if (!dateTabs || !dateDropdown) return;
  
  dateTabs.innerHTML = '';
  dateDropdown.innerHTML = '';
  
  if (dates.length === 0) {
    dateTabs.innerHTML = '<li class="nav-item"><a class="nav-link active" href="#">No dates available</a></li>';
    dateDropdown.innerHTML = '<option value="">No dates available</option>';
    return;
  }
  
  // Store available dates in state
  this.state.availableDates = dates;
  
  let firstTab = true;
  let defaultDate = null;
  
  dates.forEach(date => {
    const isSelected = selectedDate && selectedDate.value === date.value;
    const isActive = (firstTab && !selectedDate) || isSelected;
    
    if (isActive) {
      defaultDate = date.value;
    }
    
    // Create tab for desktop
    const tab = document.createElement('li');
    tab.className = 'nav-item';
    
    const tabLink = document.createElement('a');
    tabLink.className = `nav-link ${isActive ? 'active' : ''}`;
    tabLink.href = '#';
    tabLink.textContent = `${date.text}`;
    tabLink.setAttribute('data-date-value', date.value);
    tabLink.setAttribute('data-date-number', date.number);
    
    tab.appendChild(tabLink);
    dateTabs.appendChild(tab);
    
    // Create option for mobile dropdown
    const option = document.createElement('option');
    option.value = date.value;
    option.textContent = `${date.text}`;
    if (isActive) {
      option.selected = true;
    }
    dateDropdown.appendChild(option);
    
    firstTab = false;
  });
  
  // Show the date selector container
  if (this.elements.dateSelectorContainer) {
    this.elements.dateSelectorContainer.style.display = "block";
  }
  
  // ts does NOT work
  if (selectedDate && selectedDate.value) {
    this.state.selectedDate = selectedDate.value;
  } else if (defaultDate) {
    this.state.selectedDate = defaultDate;
  } else if (dates.length > 0) {
    this.state.selectedDate = dates[0].value;
  }
  
  console.log('Selected date set to:', this.state.selectedDate);
}
showClassDetailView() {
    this.state.currentView = 'class-detail';
    this.elements.classesGridContainer.classList.add('d-none');
    this.elements.classDetailView.classList.remove('d-none');
    this.elements.iprRcView.classList.add('d-none');
    if (this.elements.runSelectorContainer) {
        this.elements.runSelectorContainer.style.display = 'none';
    }
    const gradingHeader = document.getElementById('grading-header-elements');
    if (gradingHeader) {
        gradingHeader.style.display = 'none';
    }
    
    // Update mobile navbar for class detail view
    const mobileBackBtn = document.getElementById('mobile-grades-back-btn');
    const mobileTitle = document.getElementById('mobile-grades-navbar-title');
    const mobileNavbar = document.getElementById('mobile-grades-navbar');
    const mobileMainActions = document.getElementById('mobile-grades-navbar-main-actions');
    const mobileDetailActions = document.getElementById('mobile-grades-navbar-detail-actions');
    const mobileToggles = document.getElementById('mobile-grades-navbar-toggles');
    
    if (mobileBackBtn) mobileBackBtn.classList.remove('d-none');
    if (mobileTitle && this.state.currentDetailClass) {
        mobileTitle.textContent = this.state.currentDetailClass.name || 'Class';
    }
    if (mobileNavbar) mobileNavbar.style.display = 'block';
    
    // Hide main actions (GPA, Email, Refresh) and show detail actions in class detail view
    if (mobileMainActions) mobileMainActions.classList.add('d-none');
    if (mobileDetailActions) mobileDetailActions.classList.remove('d-none');
    if (mobileToggles) mobileToggles.classList.remove('d-none');
    
    document.body.classList.add('class-detail-active');
    document.body.classList.remove('ipr-rc-active');
    
    // Scroll to top on mobile
    if (window.innerWidth <= 768) {
        window.scrollTo(0, 0);
    }
    const stickyBadge = document.getElementById('stickyGradeBadge');
    if (stickyBadge) {
      stickyBadge.classList.remove('visible');
    }
}

closeClassDetail() {
      this.requests.abortAll();
      
      // Reset error displays
      this.elements.errorContainer.style.display = 'none';
      this.elements.errorContainer.innerHTML = '';
      
      // Reset scroll state
      const detailHeader = document.querySelector('.detail-header');
      if (detailHeader) {
        detailHeader.classList.remove('scrolled');
      }
      
      // Reset mode toggles
      const analyzeModeToggle = document.getElementById('analyzeModeToggle');
      const predictModeToggle = document.getElementById('predictModeToggle');
      const mobileAnalyzeModeToggle = document.getElementById('mobile-analyze-mode-toggle');
      const mobilePredictModeToggle = document.getElementById('mobile-predict-mode-toggle');
      
      if (analyzeModeToggle) analyzeModeToggle.checked = false;
      if (predictModeToggle) predictModeToggle.checked = false;
      if (mobileAnalyzeModeToggle) mobileAnalyzeModeToggle.checked = false;
      if (mobilePredictModeToggle) mobilePredictModeToggle.checked = false;
      
      // Reset modes
      if (this.state.predictMode) {
        this.disablePredictMode();
      }
      
      document.body.classList.remove('analyze-mode-active', 'predict-mode-active');
    
    if (this.state.performanceChartInstance) {
        this.state.performanceChartInstance.destroy();
        this.state.performanceChartInstance = null;
    }
    
    // Reset all analysis data
    this.resetAnalysisModal();
    
    // Update mobile navbar back to grades list view
    const mobileBackBtn = document.getElementById('mobile-grades-back-btn');
    const mobileTitle = document.getElementById('mobile-grades-navbar-title');
    const mobileNavbar = document.getElementById('mobile-grades-navbar');
    const mobileMainActions = document.getElementById('mobile-grades-navbar-main-actions');
    const mobileDetailActions = document.getElementById('mobile-grades-navbar-detail-actions');
    const mobileToggles = document.getElementById('mobile-grades-navbar-toggles');
    
    if (mobileBackBtn) mobileBackBtn.classList.add('d-none');
    if (mobileTitle) mobileTitle.textContent = 'Grades';
    if (mobileNavbar) mobileNavbar.style.display = 'block';
    
    // Show main actions (GPA, Email, Refresh) and hide detail actions
    if (mobileMainActions) mobileMainActions.classList.remove('d-none');
    if (mobileDetailActions) mobileDetailActions.classList.add('d-none');
    if (mobileToggles) mobileToggles.classList.add('d-none');
    
    this.state.currentView = 'running-average';
    this.state.currentDetailClass = null;
    this.state.assignmentsData = [];
    this.state.mockAssignmentsData = [];
    this.state.exemptAssignments.clear();
    this.state.originalScores.clear();
    
    this.elements.classesGridContainer.classList.remove('d-none');
    this.elements.classDetailView.classList.add('d-none');
    if (this.elements.runSelectorContainer) {
        this.elements.runSelectorContainer.style.display = 'block';
    }
    const gradingHeader = document.getElementById('grading-header-elements');
    if (gradingHeader) {
        gradingHeader.style.display = 'block';
    }
    document.body.classList.remove('class-detail-active');
    document.body.classList.remove('ipr-rc-active');
    document.getElementById('lastUpdatedBadge')?.classList.remove('d-none');
    
    // Scroll to top on mobile
    if (window.innerWidth <= 768) {
        window.scrollTo(0, 0);
    }
}
handleRefreshButton() {
  this.cache.clearAll();
  this.handleRefresh();
}

async handleRefresh() {
  this.requests.abortAll();
  
  // Clear cache for current view only
  if (this.state.currentView === 'running-average') {
    this.cache.clearByPattern('running_average');
  } else if (this.state.currentView === 'ipr') {
    this.cache.clearByPattern('ipr_');
  } else if (this.state.currentView === 'report-card') {
    this.cache.clearByPattern('rc_');
  }
  
  if (this.state.currentView === 'running-average') {
    this.showLoader();
    await this.loadInitialData();
  } else if (this.state.currentView === 'ipr') {
    this.showIPRLoader();
    await this.loadIPRData();
  } else if (this.state.currentView === 'report-card') {
    this.showIPRLoader();
    await this.loadReportCardData();
  }
}
async loadCurrentIprRcData() {
  if (this.state.currentIprRcType === 'ipr') {
    await this.loadIPRData();
  } else if (this.state.currentIprRcType === 'report-card') {
    await this.loadReportCardData();
  }
}


async handleRunChange(selectedRun) {
  this.requests.abortAll();
  this.state.currentRun = selectedRun;
  
  // Update UI indicators
  const runTabs = document.querySelectorAll('#runTabs .nav-link');
  runTabs.forEach(tab => {
    const tabRun = tab.getAttribute('data-run-value');
    if (tabRun === selectedRun) {
      tab.classList.add('active');
    } else {
      tab.classList.remove('active');
    }
  });
  
  const dropdown = document.getElementById('runDropdown');
  if (dropdown) {
    dropdown.value = selectedRun;
  }
  
  // Reload data based on current view
  if (this.state.currentView === 'running-average') {
    this.showLoader();
    this.elements.classesGridContainer.innerHTML = '';
    
    const result = await this.loadClasses(selectedRun);
    if (result) {
      this.hideLoader();
      this.renderClasses(result.classesData, selectedRun);
    }
  } else if (this.state.currentView === 'ipr') {
    this.showIPRLoader();
    await this.loadIPRData();
  } else if (this.state.currentView === 'report-card') {
    this.showIPRLoader();
    await this.loadReportCardData();
  }
}

async handleDateChange(selectedDate) {
  console.log('Date changed to:', selectedDate);
  
  // Show loader specifically for date changes
  if (this.elements.iprRcLoader) {
    this.elements.iprRcLoader.classList.remove('d-none');
    this.elements.iprRcView.classList.add('d-none');
  }

  const dateTabs = document.querySelectorAll('#dateTabs .nav-link');
  dateTabs.forEach(tab => {
    const tabDate = tab.getAttribute('data-date-value');
    if (tabDate === selectedDate) {
      tab.classList.add('active');
    } else {
      tab.classList.remove('active');
    }
  });
  
  const dropdown = document.getElementById('dateDropdown');
  if (dropdown) {
    dropdown.value = selectedDate;
  }
  
  this.state.selectedDate = selectedDate;
  
  if (this.state.currentIprRcType === 'ipr') {
    await this.loadIPRData();
  }
}
renderClassWeightsTable() {
  if (!this.elements.classWeightsTable) return;
  
  this.elements.classWeightsTable.innerHTML = '';
  
  this.state.currentClasses.forEach(cls => {
    if (cls.dropped) return;
    
    const currentScale = this.state.gpaScales[cls.code] || '4.0';
    // Normalize the scale to a string with one decimal place
    const normalizedScale = parseFloat(currentScale).toFixed(1);
    
    const row = document.createElement('tr');
    row.innerHTML = `
      <td class="align-middle">${cls.name}</td>
      <td class="align-middle"><span class="badge bg-secondary">${normalizedScale}</span></td>
      <td>
        <select class="form-select form-select-sm class-weight-select" data-class-code="${cls.code}">
          <option value="0.0" ${normalizedScale === '0.0' ? 'selected' : ''}>Exempt</option>
          <option value="4.0" ${normalizedScale === '4.0' ? 'selected' : ''}>Standard (4.0)</option>
          <option value="4.5" ${normalizedScale === '4.5' ? 'selected' : ''}>Honors (4.5)</option>
          <option value="5.0" ${normalizedScale === '5.0' ? 'selected' : ''}>AP/Advanced (5.0)</option>
        </select>
      </td>
    `;
    this.elements.classWeightsTable.appendChild(row);
  });
  
  document.querySelectorAll('.class-weight-select').forEach(select => {
    select.addEventListener('change', async (e) => {
      const classCode = e.target.getAttribute('data-class-code');
      const scale = e.target.value;
      await this.saveGPAScale(classCode, scale);
    });
  });
}
async saveGPAScale(classCode, scale) {
  // Ensure consistent formatting - always one decimal place
  const formattedScale = parseFloat(scale).toFixed(1);
  
  const response = await fetch('/backends/grades-gpa-scale-backend.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      class_code: classCode,
      gpa_scale: formattedScale
    })
  });
  
  const data = await response.json();
  
  if (data.success) {
    // Store with consistent formatting
    this.state.gpaScales[classCode] = formattedScale;
    this.cache.set('gpa_scales', this.state.gpaScales);
    
    // Update the class type in currentClasses array
    const classIndex = this.state.currentClasses.findIndex(c => c.code === classCode);
    if (classIndex !== -1) {
      const scaleNum = parseFloat(formattedScale);
      let classType = 'standard';
      if (scaleNum === 4.5) classType = 'honors';
      else if (scaleNum === 5.0) classType = 'ap';
      
      this.state.currentClasses[classIndex].classType = classType;
    }
    
    this.updateGPADisplay();
    this.renderClassWeightsTable(); // Re-render to show updated badge
    this.showToast('GPA scale updated!');
  } else {
    alert('Error saving GPA scale: ' + (data.error || 'Unknown error'));
  }
}
    handleGpaPredictionSubmit() {
      const className = document.getElementById('predClass').value;
      const classType = document.getElementById('predClassType').value;
      const credits = parseFloat(document.getElementById('predCredits').value);
      const grade = parseFloat(document.getElementById('predGrade').value);
      
      this.state.gpaPredictions.push({
        className,
        classType,
        credits,
        grade
      });
      
      this.updateGpaPredictionList();
      this.updatePredictedGPA();
      this.elements.gpaPredictionForm.reset();
    }

    updateGpaPredictionList() {
      if (!this.elements.gpaPredictionList) return;
      
      if (this.state.gpaPredictions.length === 0) {
        this.elements.gpaPredictionList.innerHTML = '<li class="list-group-item small text-muted">No predictions added yet</li>';
        return;
      }
      
      this.elements.gpaPredictionList.innerHTML = '';
      this.state.gpaPredictions.forEach((prediction, index) => {
        const letterGrade = Utils.percentageToLetter(prediction.grade);
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item';
        listItem.innerHTML = `
          <div class="d-flex justify-content-between align-items-center">
            <div class="flex-grow-1">
              <span class="fw-bold">${prediction.className}</span>
              <small class="text-muted d-block">${prediction.classType} - ${prediction.credits} credit(s)</small>
            </div>
            <div class="d-flex align-items-center gap-2">
              <input type="number" class="form-control form-control-sm prediction-grade-input" 
                     value="${prediction.grade}" min="0" max="100" step="0.1" 
                     style="width: 80px;" data-index="${index}">
              <span class="small">%</span>
              <button class="btn btn-sm btn-outline-danger remove-prediction" data-index="${index}">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
          <div class="small text-muted mt-1">Letter Grade: ${letterGrade}</div>
        `;
        this.elements.gpaPredictionList.appendChild(listItem);
      });
      
      document.querySelectorAll('.prediction-grade-input').forEach(input => {
        input.addEventListener('change', (e) => {
          const index = parseInt(e.target.getAttribute('data-index'));
          const newGrade = parseFloat(e.target.value);
          if (!isNaN(newGrade) && newGrade >= 0 && newGrade <= 100) {
            this.state.gpaPredictions[index].grade = newGrade;
            this.updatePredictedGPA();
          }
        });
      });
      
      document.querySelectorAll('.remove-prediction').forEach(button => {
        button.addEventListener('click', (e) => {
          const index = parseInt(e.target.getAttribute('data-index'));
          this.state.gpaPredictions.splice(index, 1);
          this.updateGpaPredictionList();
          this.updatePredictedGPA();
        });
      });
    }

    updatePredictedGPA() {
      const allClasses = [...this.state.currentClasses, ...this.state.gpaPredictions];
      const gpa = GradeCalculator.calculateGPA(allClasses, this.state.gpaScales);
      
      if (this.elements.predictedUnweightedGpa) {
        this.elements.predictedUnweightedGpa.textContent = gpa.unweighted.toString();
      }
      if (this.elements.predictedWeightedGpa) {
        this.elements.predictedWeightedGpa.textContent = gpa.weighted.toString();
      }
    }

    saveGpaPredictions() {
      localStorage.setItem('gpaPredictions_' + encodeURIComponent(window.sessionUserId || 'user'), 
        JSON.stringify(this.state.gpaPredictions));
      this.showToast('Predictions saved!');
    }

    resetGpaPredictions() {
      this.state.gpaPredictions = [];
      this.updateGpaPredictionList();
      this.updatePredictedGPA();
    }

    loadSavedPredictions() {
      const saved = localStorage.getItem('gpaPredictions_' + encodeURIComponent(window.sessionUserId || 'user'));
      if (saved) {
        try {
          this.state.gpaPredictions = JSON.parse(saved);
          this.updateGpaPredictionList();
          this.updatePredictedGPA();
        } catch (e) {
          console.error('Error loading saved predictions:', e);
          this.state.gpaPredictions = [];
        }
      }
    }

    showLoader() {
      if (this.elements.loader) {
        this.elements.loader.style.display = 'block';
      }
      if (this.elements.classesGridContainer) {
        this.elements.classesGridContainer.style.display = 'none';
      }
    }

    hideLoader() {
      if (this.elements.loader) {
        this.elements.loader.style.display = 'none';
      }
      document.getElementById('lastUpdatedBadge')?.classList.remove('d-none');
    }

    showError(message) {
      if (this.elements.errorContainer) {
        this.elements.errorContainer.innerHTML = `
          <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            ${message}
          </div>
        `;
        this.elements.errorContainer.style.display = 'block';
      }
    }

    showToast(message) {
      if (this.elements.successToast) {
        const toastBody = this.elements.successToast.querySelector('.toast-body');
        if (toastBody) {
          toastBody.textContent = message;
        }
        const toast = new bootstrap.Toast(this.elements.successToast);
        toast.show();
      }
    }

    updateLastUpdatedTimestamp() {
      this.state.lastUpdated = Date.now();
      localStorage.setItem('gradesLastUpdated', this.state.lastUpdated.toString());
      this.updateLastUpdatedDisplay();
    }

    updateLastUpdatedDisplay() {
      if (!this.state.lastUpdated) return;
      
      const timeElement = document.getElementById('lastUpdatedTime');
      const badge = document.getElementById('lastUpdatedBadge');
      
      if (timeElement) {
        const formattedTime = Utils.formatRelativeTime(this.state.lastUpdated);
        timeElement.textContent = formattedTime;
      }
    }

    showExpandedChart() {
      const modal = new bootstrap.Modal(document.getElementById('expandedChartModal'));
      modal.show();
      
      // Wait for modal to be shown before creating chart
      document.getElementById('expandedChartModal').addEventListener('shown.bs.modal', () => {
        this.createExpandedChart();
        this.populateExpandedImpactAnalysis();
      }, { once: true });
    }
    
    createExpandedChart() {
      const ctx = document.getElementById('expandedPerformanceChart');
      if (!ctx) return;
      
      const allAssignments = [...this.state.assignmentsData, ...this.state.mockAssignmentsData];
      const validAssignments = allAssignments.filter((assignment, index) => {
        if (index < this.state.assignmentsData.length && this.state.exemptAssignments.has(index)) return false;
        if (index >= this.state.assignmentsData.length && assignment.exempt) return false;
        
        const hasValidScore = !isNaN(assignment.score) && assignment.score !== null && assignment.score !== '';
        const hasValidTotal = !isNaN(assignment.total) && assignment.total > 0;
        return hasValidScore && hasValidTotal;
      });
      
      if (validAssignments.length === 0) return;
      
      const realAssignments = validAssignments.filter(a => !a.isMock);
      const mockAssignments = this.state.mockAssignmentsData.filter(m => !isNaN(m.percentage));
      const displayAssignments = [...realAssignments.reverse(), ...mockAssignments];
      
      const labels = displayAssignments.map(a => a.name);
      const percentages = displayAssignments.map(a => (a.score / a.total) * 100);
      
      const cumulativeAverages = [];
      displayAssignments.forEach((assignment, i) => {
        const upToThis = [...realAssignments, ...mockAssignments].slice(0, i + 1);
        const realOnly = upToThis.filter(a => !a.isMock);
        const mockOnly = upToThis.filter(a => a.isMock);
        
        const avg = GradeCalculator.calculateWeightedAverage(
          realOnly,
          mockOnly,
          this.state.currentDetailClass?.categories,
          this.state.exemptAssignments
        );
        cumulativeAverages.push(avg);
      });
      
      const pointStyles = displayAssignments.map(a => a.isMock ? 'rect' : 'circle');
      const pointBorderWidths = displayAssignments.map(a => a.isMock ? 2 : 1);
      
      if (this.state.expandedChartInstance) {
        this.state.expandedChartInstance.destroy();
      }
      
      this.state.expandedChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Assignment Score',
              data: percentages,
              borderColor: 'rgb(40, 167, 69)',
              backgroundColor: 'rgba(40, 167, 69, 0.1)',
              tension: 0.3,
              fill: true,
              pointRadius: 8,
              pointHoverRadius: 12,
              pointStyle: pointStyles,
              pointBorderWidth: pointBorderWidths,
              segment: {
                borderDash: ctx => {
                  const idx = ctx.p0DataIndex;
                  return displayAssignments[idx]?.isMock ? [5, 5] : [];
                }
              }
            },
            {
              label: 'Cumulative Average',
              data: cumulativeAverages,
              borderColor: 'rgb(0, 123, 255)',
              backgroundColor: 'rgba(0, 123, 255, 0.1)',
              tension: 0.3,
              fill: true,
              pointRadius: 8,
              pointHoverRadius: 12,
              pointStyle: pointStyles,
              pointBorderWidth: pointBorderWidths
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
              labels: {
                font: { size: 14 }
              }
            },
            tooltip: {
              callbacks: {
                title: (items) => {
                  const idx = items[0].dataIndex;
                  const assignment = displayAssignments[idx];
                  return assignment?.isMock ? `${assignment.name} (Mock)` : assignment?.name;
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: false,
              title: { 
                display: true, 
                text: 'Percentage',
                font: { size: 14 }
              },
              ticks: {
                callback: (value) => value.toFixed(1) + '%',
                font: { size: 12 }
              }
            },
            x: {
              title: { 
                display: true, 
                text: 'Assignments (Oldest → Newest)',
                font: { size: 14 }
              },
              ticks: {
                font: { size: 12 },
                maxRotation: 45,
                minRotation: 45
              }
            }
          }
        }
      });
    }
    
    populateExpandedImpactAnalysis() {
      const tbody = document.getElementById('expandedAssignmentImpact');
      const tbodyMobile = document.getElementById('expandedAssignmentImpactMobile');
      if (!tbody && !tbodyMobile) return;
      
      if (tbody) tbody.innerHTML = '';
      if (tbodyMobile) tbodyMobile.innerHTML = '';
      
      const allAssignments = [...this.state.assignmentsData, ...this.state.mockAssignmentsData];
      const validAssignments = allAssignments.filter((assignment, index) => {
        if (index < this.state.assignmentsData.length && this.state.exemptAssignments.has(index)) return false;
        if (index >= this.state.assignmentsData.length && assignment.exempt) return false;
        
        const hasValidScore = !isNaN(assignment.score) && assignment.score !== null && assignment.score !== '';
        const hasValidTotal = !isNaN(assignment.total) && assignment.total > 0;
        return hasValidScore && hasValidTotal;
      });
      
      if (validAssignments.length === 0) {
        const emptyRow = '<tr><td colspan="5" class="text-center text-muted">No valid assignments</td></tr>';
        if (tbody) tbody.innerHTML = emptyRow;
        if (tbodyMobile) tbodyMobile.innerHTML = emptyRow;
        return;
      }
      
      const realAssignments = validAssignments.filter(a => !a.isMock);
      const mockAssignments = this.state.mockAssignmentsData.filter(m => !isNaN(m.percentage));
      const displayAssignments = [...realAssignments.reverse(), ...mockAssignments];
      
      displayAssignments.forEach((assignment, i) => {
        const percentage = (assignment.score / assignment.total) * 100;
        const badgeClass = Utils.getGradeColor(percentage);
        
        // Calculate average up to this point
        const upToThis = displayAssignments.slice(0, i + 1);
        const realOnly = upToThis.filter(a => !a.isMock);
        const mockOnly = upToThis.filter(a => a.isMock);
        
        const avgUpTo = GradeCalculator.calculateWeightedAverage(
          realOnly,
          mockOnly,
          this.state.currentDetailClass?.categories,
          this.state.exemptAssignments
        );
        
        // Calculate average before this assignment
        let avgBefore = 0;
        if (i > 0) {
          const beforeThis = displayAssignments.slice(0, i);
          const realBefore = beforeThis.filter(a => !a.isMock);
          const mockBefore = beforeThis.filter(a => a.isMock);
          
          avgBefore = GradeCalculator.calculateWeightedAverage(
            realBefore,
            mockBefore,
            this.state.currentDetailClass?.categories,
            this.state.exemptAssignments
          );
        }
        
        const impact = avgUpTo - avgBefore;
        const impactClass = impact > 0 ? 'text-success' : impact < 0 ? 'text-danger' : 'text-muted';
        const impactSymbol = impact > 0 ? '+' : '';
        
        // Smart rounding for impact
        let roundedImpact = impact;
        const decimalPart = impact.toString().split('.')[1];
        if (decimalPart && decimalPart.length > 2) {
          roundedImpact = Math.round(impact * 100) / 100;
        }
        
        // Desktop row (with better styling)
        const rowDesktop = document.createElement('tr');
        rowDesktop.innerHTML = `
          <td class="align-middle">
            ${assignment.isMock ? '<i class="bi bi-plus-square text-purple me-1" title="Mock Assignment"></i>' : ''}
            <span class="fw-medium">${assignment.name}</span>
          </td>
          <td class="align-middle">
            <span class="badge bg-secondary bg-opacity-10">${assignment.category}</span>
          </td>
          <td class="text-end align-middle">
            <span class="text-muted">${assignment.score}</span>/<span class="fw-medium">${assignment.total}</span>
          </td>
          <td class="text-end align-middle">
            <span class="badge bg-${badgeClass}">${Utils.formatPercentage(percentage)}</span>
          </td>
          <td class="text-end align-middle">
            <span class="${impactClass} fw-bold" style="font-size: 1.1rem;">
              ${impactSymbol}${roundedImpact.toFixed(2)}%
            </span>
          </td>
        `;
        if (tbody) tbody.appendChild(rowDesktop);
        
        // Mobile row (simplified for smaller screens)
        const rowMobile = document.createElement('tr');
        rowMobile.innerHTML = `
          <td class="align-middle" style="white-space: nowrap;">
            ${assignment.isMock ? '<i class="bi bi-plus-square text-purple me-1"></i>' : ''}
            ${assignment.name}
          </td>
          <td class="align-middle" style="white-space: nowrap;">
            <small>${assignment.category}</small>
          </td>
          <td class="text-end align-middle" style="white-space: nowrap;">
            ${assignment.score}/${assignment.total}
          </td>
          <td class="text-end align-middle">
            <span class="badge bg-${badgeClass}">${Utils.formatPercentage(percentage)}</span>
          </td>
          <td class="text-end align-middle">
            <span class="${impactClass} fw-bold">
              ${impactSymbol}${roundedImpact.toFixed(2)}%
            </span>
          </td>
        `;
        if (tbodyMobile) tbodyMobile.appendChild(rowMobile);
      });
    }
    
    cleanup() {
      if (this.state.performanceChartInstance) {
        this.state.performanceChartInstance.destroy();
        this.state.performanceChartInstance = null;
      }
      
      if (this.state.expandedChartInstance) {
        this.state.expandedChartInstance.destroy();
        this.state.expandedChartInstance = null;
      }
      
      if (this.lastUpdatedTimer) {
        clearInterval(this.lastUpdatedTimer);
        this.lastUpdatedTimer = null;
      }
      
      this.state.assignmentsData = [];
      this.state.mockAssignmentsData = [];
      this.state.originalScores.clear();
      this.state.exemptAssignments.clear();
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing Grade Manager...');
    window.gradeManager = new GradeManager();
    console.log('Grade Manager initialized successfully');
  });

})();
</script>

<?php include_once("_f.php"); ?>