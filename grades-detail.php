<?php

// remnant, used before moving classes list and assignments into grades.php

session_start();
include_once("_h.php");

$class = $_GET['code'] ?? '';
if (!$class) {
    echo "<div class='alert alert-danger'>No class selected.</div>";
    include_once("_f.php");
    exit;
}
?>
<meta name="autocomplete" content="off">
<!-- Analysis Modal -->
<div class="modal fade" id="analysisModal" tabindex="-1" aria-labelledby="analysisModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="analysisModalLabel">Grade Analysis - <?= htmlspecialchars($class) ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
        <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Performance Summary</h6>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span>Current Average:</span>
                  <strong id="analysisAverage">0.0%</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Assignments Completed:</span>
                  <strong id="analysisCompleted">0 of 0</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Highest Score:</span>
                  <strong id="analysisHighest">0.0%</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Lowest Score:</span>
                  <strong id="analysisLowest">0.0%</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Standard Deviation:</span>
                  <strong id="analysisStdDev">0.0</strong>
                </div>
              </div>
            </div>
            
            
          <div class="card">
              <div class="card-header bg-secondary text-white">
                <h6 class="mb-0">Assignment Analysis</h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-sm">
                    <thead>
                      <tr>
                        <th>Assignment</th>
                        <th class="text-end">Score</th>
                        <th class="text-end">Percentage</th>
                        <th class="text-end">Impact</th>
                      </tr>
                    </thead>
                    <tbody id="assignmentAnalysis">
                    </tbody>
                  </table>
                </div>
              </div>
            </div></div>
          
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header bg-success text-white">
                <h6 class="mb-0">Performance Trend</h6>
              </div>
              <div class="card-body">
                <canvas id="performanceChart" height="422" style="display: block; box-sizing: border-box; height: 422px; width: 506px;" width="506"></canvas>
              </div>
            </div>
            
            <div class="card">
              <div class="card-header bg-info text-white">
                <h6 class="mb-0">Grade Distribution</h6>
              </div>
              <div class="card-body">
                <div class="grade-distribution-chart" id="gradeDistributionChart">
                  <div class="d-flex align-items-center mb-2">
                    <span class="me-2" style="width: 50px;">A (90-100%)</span>
                    <div class="progress flex-grow-1" style="height: 20px;">
                      <div class="progress-bar bg-success" role="progressbar" id="distA" style="width: 0%;">0%</div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center mb-2">
                    <span class="me-2" style="width: 50px;">B (80-89%)</span>
                    <div class="progress flex-grow-1" style="height: 20px;">
                      <div class="progress-bar bg-warning" role="progressbar" id="distB" style="width: 0%;">0%</div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center mb-2">
                    <span class="me-2" style="width: 50px;">C (70-79%)</span>
                    <div class="progress flex-grow-1" style="height: 20px;">
                      <div class="progress-bar bg-info" role="progressbar" id="distC" style="width: 0%">0%</div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center">
                    <span class="me-2" style="width: 50px;">D/F (0-69%)</span>
                    <div class="progress flex-grow-1" style="height: 20px;">
                      <div class="progress-bar bg-danger" role="progressbar" id="distDF" style="width: 0%;">0%</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Prediction Modal -->
<div class="modal fade" id="predictionModal" tabindex="-1" aria-labelledby="predictionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="predictionModalLabel">Grade Prediction - <?= htmlspecialchars($class) ?></h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header bg-primary text-white">
                <h6 class="mb-0">Current Performance</h6>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <span>Current Average:</span>
                  <strong id="currentAverage">Loading...</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Assignments Completed:</span>
                  <strong id="completedCount">Loading...</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Total Points Earned:</span>
                  <strong id="totalEarned">Loading...</strong>
                </div>
                <div class="d-flex justify-content-between">
                  <span>Total Points Possible:</span>
                  <strong id="totalPossible">Loading...</strong>
                </div>
              </div>
            </div>
            
            <div class="card">
              <div class="card-header bg-info text-white">
                <h6 class="mb-0">Add Prediction</h6>
              </div>
              <div class="card-body">
                <form id="predictionForm">
                    <div class="mb-2">
                      <label class="form-label">Assignment Name</label>
                      <input type="text" class="form-control" id="predName" placeholder="Unit Exam" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Weight (decimal, e.g., 0.1 for 10%)</label>
                      <input type="number" class="form-control" id="predWeight" min="0.01" max="1" step="0.01" placeholder="1.000" required>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Points Possible</label>
                      <input type="number" class="form-control" id="predTotal" min="1" placeholder="100" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Your Expected Score</label>
                      <input type="number" class="form-control" id="predScore" min="0" placeholder="52" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Add to Prediction</button>
                </form>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="card mb-3">
              <div class="card-header bg-success text-white">
                <h6 class="mb-0">Prediction Results</h6>
              </div>
              <div class="card-body">
                <div class="text-center mb-3">
                  <div class="display-4 fw-bold" id="predictedGrade">--%</div>
                  <span class="text-muted">Predicted Final Grade</span>
                </div>
                
                <div class="progress mb-2" style="height: 20px;">
                  <div id="gradeProgress" class="progress-bar" role="progressbar" style="width: 0%;" 
                       aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <div class="mt-3">
                  <h6>Predicted Assignments:</h6>
                  <ul id="predictionList" class="list-group list-group-flush">
                    <li class="list-group-item small text-muted">No predictions added yet</li>
                  </ul>
                </div>
              </div>
            </div>
            
            <div class="d-grid gap-2">
              <button id="resetPredictions" class="btn btn-outline-secondary btn-sm">Reset Predictions</button>
              <button id="savePredictions" class="btn btn-success btn-sm">Save Prediction</button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0"><?= htmlspecialchars($class) ?></h2>
    <div>
        <span id="avgBadge" class="badge fs-4 px-4 py-2 d-none"></span>
    </div>
</div>
<button type="button" class="btn btn-primary --ms-2" data-bs-toggle="modal" data-bs-target="#analysisModal">
  <i class="bi bi-graph-up"></i> Analyze
</button>
<button type="button" class="btn btn-success --ms-2" data-bs-toggle="modal" data-bs-target="#predictionModal">
  <i class="bi bi-percent"></i> Predict
</button>


<div id="loader" class="d-flex align-items-center gap-3 my-4">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mb-0">Loading...<br>Please be patient.</p>
</div>

<div id="classDetail" class="mt-4 d-none">
    <!-- Desktop table (hidden on mobile) -->
    <div id="desktopView" class="d-none d-m-block">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Assignment</th>
                        <th>Date Assigned</th>
                        <th>Date Due</th>
                        <th>Category</th>
                        <th class="text-end">Score</th>
                        <th class="text-end">Total Points</th>
                        <th class="text-end">Percentage</th>
                    </tr>
                </thead>
                <tbody id="assignmentsTable">
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Mobile cards (visible on mobile) -->
    <div class="d-md-none" id="mobileView">
        <div class="list-group" id="mobileAssignmentsList">
            <!-- Mobile items will be inserted here -->
        </div>
    </div>
</div>

<div id="timeoutAlert" class="alert alert-danger d-none mt-2"></div>

<?php include_once("_f.php"); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {

    const loader = document.getElementById('loader');
    const classDetail = document.getElementById('classDetail');
    const avgBadge = document.getElementById('avgBadge');
    const timeoutAlert = document.getElementById('timeoutAlert');
    const assignmentsTable = document.getElementById('assignmentsTable');

    const urlParams = new URLSearchParams(window.location.search);
    const classCode = urlParams.get('code');
    const className = urlParams.get('name'); 
    const encodedData = urlParams.get('data');
    const runParam = urlParams.get('run'); 

    // Check if this is a manual reload (page has been visited before)
    const isManualReload = sessionStorage.getItem(`pageVisited_${classCode}`) !== null;
    
    // Mark this page as visited for future reload detection
    sessionStorage.setItem(`pageVisited_${classCode}`, 'true');
    
    // If this is a manual reload, don't use cached data
    //const useCachedData = encodedData && !isManualReload;
    const useCachedData = true;

    if (className) {
        document.querySelector('h2.mb-0').textContent = className;
        document.getElementById('analysisModalLabel').textContent = `Grade Analysis - ${className}`;
        document.getElementById('predictionModalLabel').textContent = `Grade Prediction - ${className}`;
    }

    // Prediction modal elements
    const currentAverageEl = document.getElementById('currentAverage');
    const completedCountEl = document.getElementById('completedCount');
    const totalEarnedEl = document.getElementById('totalEarned');
    const totalPossibleEl = document.getElementById('totalPossible');
    const predictedGradeEl = document.getElementById('predictedGrade');
    const gradeProgressEl = document.getElementById('gradeProgress');
    const predictionListEl = document.getElementById('predictionList');
    const predictionForm = document.getElementById('predictionForm');
    const resetPredictionsBtn = document.getElementById('resetPredictions');
    const savePredictionsBtn = document.getElementById('savePredictions');
    
    // Analysis modal elements
    const analysisAverageEl = document.getElementById('analysisAverage');
    const analysisCompletedEl = document.getElementById('analysisCompleted');
    const analysisHighestEl = document.getElementById('analysisHighest');
    const analysisLowestEl = document.getElementById('analysisLowest');
    const analysisStdDevEl = document.getElementById('analysisStdDev');
    const assignmentAnalysisEl = document.getElementById('assignmentAnalysis');
    
    // Grade distribution elements
    const distAEl = document.getElementById('distA');
    const distBEl = document.getElementById('distB');
    const distCEl = document.getElementById('distC');
    const distDFEl = document.getElementById('distDF');
    
    let currentAverage = 0;
    let currentEarned = 0;
    let currentPossible = 0;
    let predictedAssignments = [];
    let assignmentsData = [];

    // Utility functions
    function getBadgeClass(percentage) {
        if (percentage === 'N/A') return 'dark text-white';
        const num = parseFloat(percentage);
        if (isNaN(num)) return 'dark text-white';
        if (num >= 90) return 'success';
        if (num >= 80) return 'primary';
        if (num >= 70) return 'warning text-dark';
        if (num < 70) return 'danger';
        return 'dark text-white';
    }
    
    function formatPercentage(value) {
        return isNaN(value) ? 'N/A' : value.toFixed(1);
    }

    // Prediction functions
    function calculatePredictedGrade() {
        let totalWeightedScore = 0;
        let totalWeight = 0;
        
        predictedAssignments.forEach(assignment => {
            const assignmentPercentage = (assignment.score / assignment.total) * 100;
            totalWeightedScore += assignmentPercentage * assignment.weight;
            totalWeight += assignment.weight;
        });
        
        if (!isNaN(currentAverage)) {
            const remainingWeight = 1 - totalWeight;
            const predictedPercentage = (currentAverage * remainingWeight) + totalWeightedScore;
            
            predictedGradeEl.textContent = formatPercentage(predictedPercentage);
            gradeProgressEl.style.width = `${Math.min(100, predictedPercentage)}%`;
            gradeProgressEl.textContent = formatPercentage(predictedPercentage);
            
            const badgeClass = getBadgeClass(predictedPercentage);
            gradeProgressEl.className = `progress-bar bg-${badgeClass}`;
        } else {
            predictedGradeEl.textContent = 'N/A';
            gradeProgressEl.textContent = 'N/A';
            gradeProgressEl.className = 'progress-bar bg-secondary';
        }
    }

    function updatePredictionList() {
        if (predictedAssignments.length === 0) {
            predictionListEl.innerHTML = '<li class="list-group-item small text-muted">No predictions added yet</li>';
            return;
        }
        
        predictionListEl.innerHTML = '';
        predictedAssignments.forEach((assignment, index) => {
            const percentage = (assignment.score / assignment.total) * 100;
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            listItem.innerHTML = `
                <div>
                    <span class="fw-bold">${assignment.name}</span>
                    <br>
                    <small class="text-muted">Weight: ${(assignment.weight * 100).toFixed(1)}% - ${assignment.score}/${assignment.total} (${formatPercentage(percentage)})</small>
                </div>
                <button class="btn btn-sm btn-outline-danger remove-prediction" data-index="${index}">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            predictionListEl.appendChild(listItem);
        });
        
        document.querySelectorAll('.remove-prediction').forEach(button => {
            button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                predictedAssignments.splice(index, 1);
                updatePredictionList();
                calculatePredictedGrade();
            });
        });
    }

    // Analysis functions
    function analyzeGrades() {
        if (assignmentsData.length === 0) return;
        
        const validAssignments = assignmentsData.filter(a => !isNaN(a.percentage));
        
        if (validAssignments.length === 0) {
            analysisAverageEl.textContent = 'N/A';
            analysisCompletedEl.textContent = '0';
            analysisHighestEl.textContent = 'N/A';
            analysisLowestEl.textContent = 'N/A';
            analysisStdDevEl.textContent = 'N/A';
            return;
        }
        
        const percentages = validAssignments.map(a => a.percentage);
        const completed = percentages.length;
        const average = percentages.reduce((a, b) => a + b, 0) / completed;
        const highest = Math.max(...percentages);
        const lowest = Math.min(...percentages);
        
        const squareDiffs = percentages.map(value => Math.pow(value - average, 2));
        const avgSquareDiff = squareDiffs.reduce((a, b) => a + b, 0) / completed;
        const stdDev = Math.sqrt(avgSquareDiff).toFixed(1);
        
        analysisAverageEl.textContent = formatPercentage(average);
        analysisCompletedEl.textContent = `${completed} of ${assignmentsData.length}`;
        analysisHighestEl.textContent = formatPercentage(highest);
        analysisLowestEl.textContent = formatPercentage(lowest);
        analysisStdDevEl.textContent = stdDev;
        
        const distA = percentages.filter(p => p >= 90).length;
        const distB = percentages.filter(p => p >= 80 && p < 90).length;
        const distC = percentages.filter(p => p >= 70 && p < 80).length;
        const distDF = percentages.filter(p => p < 70).length;
        
        const total = percentages.length;
        distAEl.style.width = `${(distA / total) * 100}%`;
        distAEl.textContent = `${Math.round((distA / total) * 100)}%`;
        distBEl.style.width = `${(distB / total) * 100}%`;
        distBEl.textContent = `${Math.round((distB / total) * 100)}%`;
        distCEl.style.width = `${(distC / total) * 100}%`;
        distCEl.textContent = `${Math.round((distC / total) * 100)}%`;
        distDFEl.style.width = `${(distDF / total) * 100}%`;
        distDFEl.textContent = `${Math.round((distDF / total) * 100)}%`;
        
        createPerformanceChart(validAssignments);
        populateAssignmentAnalysis(validAssignments);
    }

    function createPerformanceChart(assignments) {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const validAssignments = assignments.filter(a => !isNaN(a.percentage));
        
        if (validAssignments.length === 0) {
            ctx.font = "16px Arial";
            ctx.fillText("No valid grade data available", 10, 50);
            return;
        }
        
        const sortedAssignments = [...validAssignments].sort((a, b) => {
            return new Date(a.date) - new Date(b.date);
        });
        
        const labels = sortedAssignments.map(a => a.name);
        const percentages = sortedAssignments.map(a => a.percentage);
        
        const cumulativeAverages = [];
        let runningTotal = 0;
        
        percentages.forEach((p, i) => {
            runningTotal += p;
            cumulativeAverages.push(runningTotal / (i + 1));
        });
        
        if (window.performanceChartInstance) {
            window.performanceChartInstance.destroy();
        }
        
        window.performanceChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Assignment Score',
                        data: percentages,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1,
                        fill: false
                    },
                    {
                        label: 'Cumulative Average',
                        data: cumulativeAverages,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1,
                        fill: false,
                        borderDash: [5, 5]
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 0,
                        max: 100,
                        title: { display: true, text: 'Percentage' }
                    },
                    x: {
                        title: { display: true, text: 'Assignments' }
                    }
                }
            }
        });
    }

    function populateAssignmentAnalysis(assignments) {
        assignmentAnalysisEl.innerHTML = '';
        const validAssignments = assignments.filter(a => !isNaN(a.percentage));
        
        if (validAssignments.length === 0) {
            assignmentAnalysisEl.innerHTML = '<tr><td colspan="4" class="text-center">No valid assignments to analyze</td></tr>';
            return;
        }
        
        validAssignments.forEach(assignment => {
            const percentage = assignment.percentage;
            const impact = !isNaN(percentage) && !isNaN(currentAverage) ? 
                (percentage - currentAverage).toFixed(1) : 'N/A';
            let impactClass = '';
            
            if (!isNaN(impact)) {
                impactClass = impact > 0 ? 'text-success' : impact < 0 ? 'text-danger' : '';
            }
            
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${assignment.name}</td>
                <td class="text-end">${assignment.score || 'N/A'}/${assignment.total || 'N/A'}</td>
                <td class="text-end">${!isNaN(percentage) ? formatPercentage(percentage) : 'N/A'}</td>
                <td class="text-end ${impactClass}">${!isNaN(impact) ? (impact > 0 ? '+' : '') + impact + '%' : 'N/A'}</td>
            `;
            assignmentAnalysisEl.appendChild(row);
        });
    }

    // Main data processing function
    function processClassData(classData) {
        // Process assignments
        if (classData.assignments && classData.assignments.length > 1) {
            const assignmentRows = classData.assignments.slice(1);
            const mobileAssignmentsList = document.getElementById('mobileAssignmentsList');
            
            currentEarned = 0;
            currentPossible = 0;
            let completedAssignments = 0;
            let validAssignments = [];
            
            assignmentRows.forEach(assignment => {
                const score = parseFloat(assignment[4]) || 0;
                const total = parseFloat(assignment[5]) || 0;
                const category = assignment[3] || 'Other';
                const percentageStr = assignment[9] || 'N/A';
                const assignmentName = assignment[2] || 'N/A';
                const dateAssigned = assignment[1] || 'N/A';
                const dateDue = assignment[0] || 'N/A';
                
                const isNa = percentageStr === 'N/A' || isNaN(parseFloat(percentageStr));
                
                if (!isNa) {
                    validAssignments.push({
                        name: assignmentName,
                        date: dateDue,
                        score: score,
                        total: total,
                        percentage: parseFloat(percentageStr),
                        category: category
                    });
                    
                    if (!isNaN(score)) currentEarned += score;
                    if (!isNaN(total)) currentPossible += total;
                    if (score > 0 || total > 0) completedAssignments++;
                }
                
                const badgeClass = getBadgeClass(percentageStr.toString().replace('%', ''));
                
                // Desktop row
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${assignmentName}</td>
                    <td>${dateAssigned}</td>
                    <td>${dateDue}</td>
                    <td><span class="">${category}</span></td>
                    <td class="text-end">${assignment[4] || 'N/A'}</td>
                    <td class="text-end">${assignment[5] || 'N/A'}</td>
                    <td class="text-end">
                        <span class="badge bg-${badgeClass}">${percentageStr}</span>
                    </td>
                `;
                assignmentsTable.appendChild(row);
                
                // Mobile card
                const mobileItem = document.createElement('a');
                mobileItem.href = '#';
                mobileItem.className = 'list-group-item list-group-item-action';
                mobileItem.setAttribute('data-bs-toggle', 'modal');
                mobileItem.setAttribute('data-bs-target', '#mobileAssignmentModal');
                mobileItem.innerHTML = `
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">${assignmentName}</h6>
                        <span class="badge bg-${badgeClass}">${percentageStr}</span>
                    </div>
                    <small class="text-muted">Score: ${assignment[4] || 'N/A'}/${assignment[5] || 'N/A'}</small>
                `;
                
                mobileItem.addEventListener('click', function() {
                    document.getElementById('mobileAssignmentModalLabel').textContent = assignmentName;
                    document.getElementById('mobileAssignmentDetails').innerHTML = `
                        <div class="mb-2"><strong>Assignment:</strong> ${assignmentName}</div>
                        <div class="mb-2"><strong>Date Assigned:</strong> ${dateAssigned}</div>
                        <div class="mb-2"><strong>Date Due:</strong> ${dateDue}</div>
                        <div class="mb-2"><strong>Category:</strong> ${category}</div>
                        <div class="mb-2"><strong>Score:</strong> ${assignment[4] || 'N/A'}/${assignment[5] || 'N/A'}</div>
                        <div class="mb-2"><strong>Percentage:</strong> <span class="badge bg-${badgeClass}">${percentageStr}</span></div>
                    `;
                });
                
                mobileAssignmentsList.appendChild(mobileItem);
            });
            
            assignmentsData = validAssignments;
            analyzeGrades();
        
            if (classData.average) {
                currentAverage = parseFloat(classData.average);
                const badgeClass = getBadgeClass(currentAverage);
                avgBadge.textContent = formatPercentage(currentAverage);
                avgBadge.className = `badge fs-4 px-4 py-2 bg-${badgeClass}`;
                avgBadge.classList.remove('d-none');
                currentAverageEl.textContent = formatPercentage(currentAverage);
            }
        
            completedCountEl.textContent = `${completedAssignments} of ${validAssignments.length}`;
            totalEarnedEl.textContent = currentEarned.toFixed(1);
            totalPossibleEl.textContent = currentPossible.toFixed(1);
            
            updatePredictionList();
            calculatePredictedGrade();
        } else {
            classDetail.classList.add('d-none');
            timeoutAlert.classList.remove('d-none', 'alert-danger');
            timeoutAlert.classList.add('m-0', 'p-0', 'text-muted');
            timeoutAlert.innerHTML = `<i>No assignments found for this class.</i>`;
        }
    }

    // Event listeners
    predictionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('predName').value;
        const weight = parseFloat(document.getElementById('predWeight').value);
        const total = parseFloat(document.getElementById('predTotal').value);
        const score = parseFloat(document.getElementById('predScore').value);
        
        predictedAssignments.push({ name, weight, total, score });
        updatePredictionList();
        calculatePredictedGrade();
        predictionForm.reset();
    });

    resetPredictionsBtn.addEventListener('click', function() {
        predictedAssignments = [];
        updatePredictionList();
        calculatePredictedGrade();
    });

    savePredictionsBtn.addEventListener('click', function() {
        const storageKey = `gradePredictions_${encodeURIComponent(classCode)}_${runParam || 'default'}`;
        localStorage.setItem(storageKey, JSON.stringify(predictedAssignments));
        
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show mt-3';
        alert.innerHTML = `Predictions saved successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.querySelector('#predictionModal .modal-body').appendChild(alert);
        
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 2000);
    });

    // Load saved predictions
    const storageKey = `gradePredictions_${encodeURIComponent(classCode)}_${runParam || 'default'}`;
    const savedPredictions = localStorage.getItem(storageKey);
    if (savedPredictions) {
        try {
            predictedAssignments = JSON.parse(savedPredictions);
        } catch (e) {
            console.error('Error loading saved predictions:', e);
            predictedAssignments = [];
        }
    }

    const params = new URLSearchParams({
        code: classCode
    });
    
    if (useCachedData) {
        params.append('data', encodedData);
    }
    if (runParam) {
        params.append('run', runParam);
    }

    fetch(`/backends/classes-detail-backend.php?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            loader.classList.add('d-none');
            classDetail.classList.remove('d-none');
            
            const keys = Object.keys(data || {});
            if (keys.length === 0) {
                classDetail.classList.add('d-none');
                timeoutAlert.classList.remove('d-none');
                timeoutAlert.innerHTML = `<i class="bi bi-x-octagon"></i> Could not retrieve class data. Please try again in a few minutes.`;
                return;
            }
            
            const className = keys[0];
            const classData = data[className];
            processClassData(classData);
        })
        .catch(err => {
            loader.innerHTML = '<div class="alert alert-danger">Failed to contact HAC. You might be disconnected from Wi-Fi.</div>';
            console.error(err);
        });
});
// rendered inside grades.php
if (window.self !== window.top) {
  // Show desktop version
  document.getElementById('desktopView').classList.remove('d-none');
} 
</script>
<!--- for performance chart --->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>