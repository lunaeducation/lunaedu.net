<?php

// remnant

session_start();
include_once("_h.php");
require_once("_backend-libs.php");

if ($_SESSION['leaderboard_show_nav'] === false) {
  echo '
  <div class="alert alert-warning">You disabled the leaderboard, so you cannot view it. Go to Settings if you wish to enable it.</div>
  ';
  exit;
}

if ($_SESSION['id'] !== 'K1629501' && $_SESSION['id'] !== 'K1340931') {
  echo '
  <h3 class="text-warning text-center fw-bold">Under Construction...</h3>
  ';
  exit;
}

// Get current user's classes for filter options
$averagesData = api_call('averages');
$classes = [];
if ($averagesData && isset($averagesData['classes'])) {
    foreach ($averagesData['classes'] as $class) {
        if (!$class['dropped']) {
            $classes[] = $class;
        }
    }
}

?>

<div class="container-fluid mt-4">
    <div class="row">
        <!-- Mobile dropdown filter -->
        <div class="col-12 d-md-none mb-3">
            <div class="card">
                <div class="card-body p-2">
                    <label class="form-label">View Scores For:</label>
                    <select class="form-select" id="mobile-filter">
                        <option value="overall">Overall Score</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= htmlspecialchars($class['class']) ?>">
                                <?= htmlspecialchars($class['class']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Sidebar navigation for class filter (desktop) -->
        <div class="col-md-3 col-lg-2 mb-4 d-none d-md-block">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action active" data-filter="overall">
                            <i class="bi bi-bar-chart me-2"></i>Overall Score
                        </a>
                        <?php foreach ($classes as $class): ?>
                            <a href="#" class="list-group-item list-group-item-action" data-filter="<?= htmlspecialchars($class['class']) ?>">
                                <i class="bi bi-journal me-2"></i><?= htmlspecialchars($class['class']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content area -->
        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Leaderboard</h2>
                <div class="text-muted small">Celebrating Academic Achievement</div>
            </div>
            
            <!-- Current user rank card -->
            <div id="user-rank-card" class="card shadow-sm mb-4 border-0">
                <div class="card-body">
                    <div id="user-rank-loading" class="text-center py-3">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading your rank...</span>
                        </div>
                        <p class="mt-2">Calculating your position...</p>
                    </div>
                    <div id="user-rank-content" style="display: none;"></div>
                </div>
            </div>

            <!-- Leaderboard table and spinner -->
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Students</h5>
                </div>
                <div class="card-body p-0">
                    <div id="leaderboard-loading" class="p-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading leaderboard...</span>
                            </div>
                            <p class="mb-0">Loading leaderboard...</p>
                        </div>
                    </div>
                    <div id="leaderboard-table"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.addEventListener('DOMContentLoaded', () => {
    const initialFilter = 'overall';
    loadLeaderboard(initialFilter);
    loadUserRank(initialFilter);
    
    // Add filter click handlers for desktop
    document.querySelectorAll('.list-group-item[data-filter]').forEach(item => {
        item.addEventListener('click', handleFilterChange);
    });
    
    // Add change handler for mobile dropdown
    document.getElementById('mobile-filter').addEventListener('change', function() {
        const filter = this.value;
        
        // Update desktop active state
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('data-filter') === filter) {
                item.classList.add('active');
            }
        });
        
        // Load new data
        loadLeaderboard(filter);
        loadUserRank(filter);
    });

    function handleFilterChange(e) {
        e.preventDefault();
        const filter = this.getAttribute('data-filter');
        
        // Update active state for desktop
        document.querySelectorAll('.list-group-item[data-filter]').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        
        // Update mobile dropdown to match
        document.getElementById('mobile-filter').value = filter;
        
        // Load new data
        loadLeaderboard(filter);
        loadUserRank(filter);
    }
});

function loadLeaderboard(filter = 'overall') {
    document.getElementById('leaderboard-loading').style.display = 'block';
    document.getElementById('leaderboard-table').innerHTML = '';
    
    fetch('backends/lb-backend.php?filter=' + encodeURIComponent(filter))
        .then(res => res.json())
        .then(data => {
            document.getElementById('leaderboard-loading').style.display = 'none';
            
            if (data.error) {
                document.getElementById('leaderboard-table').innerHTML = 
                    '<div class="alert alert-light m-4">' + data.error + '</div>';
                return;
            }
            
            // Build leaderboard table with proper ranking (handling ties)
            let html = '<div class="table-responsive"><table class="table table-hover mb-0">';
            html += '<thead class="table-light"><tr><th class="text-center" width="80">Rank</th><th>Student</th><th class="text-end" width="120">Score</th></tr></thead><tbody>';
            
            if (data.topUsers.length === 0) {
                html += '<tr><td colspan="4" class="text-center py-4 text-muted">No students found for this filter.</td></tr>';
            } else {
                let currentRank = 1;
                let previousScore = null;
                
                for (let i = 0; i < data.topUsers.length; i++) {
                    const user = data.topUsers[i];
                    
                    // Handle tied scores
                    if (previousScore !== null && user.score === previousScore) {
                        // Same rank as previous user
                    } else {
                        currentRank = i + 1;
                    }
                    
                    previousScore = user.score;
                    
                    // Add icons and styling for top 3 ranks
                    let rowClass = '';
                    let rankDisplay = `${currentRank}`;
                    if (currentRank === 1) { rankDisplay = '<i class="bi bi-trophy-fill text-warning"></i>'; rowClass = 'table-light fw-bold'; }
                    else if (currentRank === 2) { rankDisplay = '<i class="bi bi-trophy-fill text-secondary"></i>'; rowClass = 'table-light'; }
                    else if (currentRank === 3) { rankDisplay = '<i class="bi bi-trophy-fill" style="color: #cd7f32;"></i>'; rowClass = 'table-light'; }


                    html += `<tr class="${rowClass}">
                        <td class="fw-semibold text-center">${rankDisplay}</td>
                        <td>${user.name}</td> 
                        <td class="fw-semibold text-end">${Math.round(user.score)}</td>
                    </tr>`;
                }
            }
            
            html += '</tbody></table></div>';
            document.getElementById('leaderboard-table').innerHTML = html;
        })
        .catch(error => {
            document.getElementById('leaderboard-loading').innerHTML = 
                '<div class="alert alert-light m-4">Failed to load leaderboard.</div>';
            console.error('Error:', error);
        });
}

function loadUserRank(filter = 'overall') {
    document.getElementById('user-rank-loading').style.display = 'block';
    document.getElementById('user-rank-content').style.display = 'none';
    
    fetch('backends/lb-backend.php?action=user_rank&filter=' + encodeURIComponent(filter))
        .then(res => res.json())
        .then(data => {
            document.getElementById('user-rank-loading').style.display = 'none';
            document.getElementById('user-rank-content').style.display = 'block';
            
            if (data.error) {
                document.getElementById('user-rank-content').innerHTML = 
                    '<div class="alert alert-light">' + data.error + '</div>';
                return;
            }
            
            let content = '';
            if (data.rank && data.score) {
                let encouragement = 'Keep up the good work!';
                
                if (data.rank === 1) {
                    encouragement = 'Outstanding achievement!';
                } else if (data.rank <= 3) {
                    encouragement = 'Excellent performance!';
                } else if (data.rank <= 10) {
                    encouragement = 'Great job!';
                }
                
                content = `<div class="text-center p-3">
                    <div class="text-muted mb-1">Your Rank</div>
                    <h3 class="mb-2 fw-bold">#${data.rank}</h3>
                    <div class="display-6 fw-semibold text-primary mb-2">${Math.round(data.score)}</div>
                    <p class="text-muted mb-2">${filter === 'overall' ? 'Overall Score' : 'Class Score'}</p>

                    <div class="mt-3 p-2 rounded border">
                        <small>${encouragement}</small>
                    </div>
                    
                    <p class="text-muted mt-2 small">Out of ${data.totalUsers} students</p>
                </div>`;
            } else {
                content = '<div class="text-muted text-center p-3"><i class="bi bi-bar-chart-line fs-3 mb-2 d-block"></i>You are not ranked yet. Your score will appear here once calculated.</div>';
            }
            
            document.getElementById('user-rank-content').innerHTML = content;
        })
        .catch(error => {
            document.getElementById('user-rank-content').innerHTML = 
                '<div class="alert alert-light">Failed to load your rank.</div>';
            console.error('Error:', error);
        });
}
</script>

<?php include_once("_f.php"); ?>