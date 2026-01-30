
<?php session_start(); include_once("_h.php"); ?>
<div class="">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
<?php
if (isset($_SESSION['dashwelcome']) && $_SESSION['dashwelcome'] == 1) {

    echo "
    <script>
        var date = new Date();
        var hour = date.getHours();
        document.cookie = 'client_hour=' + hour + '; path=/';
    </script>
    ";

    $clientHour = isset($_COOKIE['client_hour']) ? (int)$_COOKIE['client_hour'] : date('H');

    $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    $firstName = explode(' ', trim($name))[0];

    // Greeting variations
    $morningGreetings = [
        "Good morning, $firstName",
        "Morning, $firstName",
        "A good morning to you, $firstName",
        "Morning greetings, $firstName"
    ];

    $afternoonGreetings = [
        "Good afternoon, $firstName",
        "Afternoon, $firstName",
        "A good afternoon to you, $firstName",
        "Afternoon greetings, $firstName"
    ];

    $eveningGreetings = [
        "Good evening, $firstName",
        "Evening, $firstName",
        "A good evening to you, $firstName",
        "Evening greetings, $firstName"
    ];

    $defaultGreetings = [
        "Welcome, $firstName",
        "Welcome back, $firstName",
        "Greetings, $firstName",
    ];

    if ($clientHour >= 5 && $clientHour <= 9) {
        $pool = array_merge($morningGreetings, $defaultGreetings);
    } elseif ($clientHour >= 13 && $clientHour <= 17) {
        $pool = array_merge($afternoonGreetings, $defaultGreetings);
    } elseif ($clientHour >= 19 && $clientHour <= 21) {
        $pool = array_merge($eveningGreetings, $defaultGreetings);
    } else {
        $pool = $defaultGreetings; 
    }
    
    echo $pool[array_rand($pool)];

} else {
    echo "Dashboard";
}
?>
</h2>

            </div>
            <hr class="my-3" hidden>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row mb-4" hidden>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Launchpad Links</h6>
                            <h4 class="mb-0" id="linksCount">--</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-link-45deg display-6 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Tasks Completed</h6>
                            <h4 class="mb-0" id="completedTasks">--</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-check-circle display-6 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Pending Tasks</h6>
                            <h4 class="mb-0" id="pendingTasks">--</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-list-task display-6 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="card-title mb-0">Upcoming Assignments</h6>
                            <h4 class="mb-0" id="assignmentsCount">--</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-journal-check display-6 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column (Assignments) -->
        <div class="col-lg-8">
            <!-- Launchpad Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-info text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-rocket-takeoff me-2"></i>
                        Launchpad
                    </h5>
                    <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addLinkModal">
                        <i class="bi bi-plus-circle me-1"></i> Add
                    </button>
                </div>
                <div class="card-body p-0">
                    <div id="linksList">
                        <div class="text-center text-muted py-5">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading launchpad...</span>
                            </div>
                            <span class="ms-2">Loading launchpad...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Assignments Section -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2 text-primary"></i>
                        Upcoming Assignments
                    </h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#classFilterCollapse">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <button class="btn btn-sm btn-primary d-none" id="viewMoreUpcoming" style="display: none;">
                            View More
                        </button>
                    </div>
                </div>
                
                <div class="collapse mb-3" id="classFilterCollapse">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row" id="classFilterCheckboxes">
                                <!-- Class checkboxes will be populated here -->
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-sm btn-primary me-2" id="selectAllClasses">Select All</button>
                                <button class="btn btn-sm btn-outline-secondary" id="deselectAllClasses">Deselect All</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="loader" class="d-flex align-items-center gap-3 p-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading assignments...</span>
                    </div>
                    <span class="ms-2">Loading assignments...</span>
                </div>
                
                <!-- Desktop card view -->
                <div id="classDetail" class="d-none">
                    <div class="row g-3" id="assignmentsCards">
                        <!-- Filled dynamically with cards -->
                    </div>
                </div>
                
                <!-- Mobile view -->
                <div class="d-lg-none d-none" id="mobileAssignmentsContainer">
                    <div class="row g-3" id="mobileAssignmentsList">
                        <!-- Filled dynamically -->
                    </div>
                </div>
            </div>

            <!-- Recently Graded Assignments Section -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle me-2 text-success"></i>
                        Recently Graded
                    </h5>
                    <button class="btn btn-sm btn-success d-none" id="viewMoreGraded" style="display: none;">
                        View More
                    </button>
                </div>

                <div id="gradedLoader" class="d-flex align-items-center gap-3 p-4">
                    <div class="spinner-border spinner-border-sm text-success" role="status">
                        <span class="visually-hidden">Loading graded assignments...</span>
                    </div>
                    <span class="ms-2">Loading graded assignments...</span>
                </div>
                
                <!-- Desktop card view -->
                <div id="gradedClassDetail" class="d-none">
                    <div class="row g-3" id="gradedAssignmentsCards">
                        <!-- Filled dynamically with cards -->
                    </div>
                </div>
                
                <!-- Mobile view -->
                <div class="d-lg-none d-none" id="mobileGradedAssignmentsContainer">
                    <div class="row g-3" id="mobileGradedAssignmentsList">
                        <!-- Filled dynamically -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (To-Do List) -->
        <div class="col-lg-4">
            <!-- To-Do List Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-warning py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 mt-0 text-dark">
                        <i class="bi bi-list-check me-2"></i>
                        To-Do List
                    </h5>
                    <button class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        <i class="bi bi-plus-circle me-1"></i> Add
                    </button>
                </div>
                <div class="card-body p-0" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div id="todoList">
                        <div class="text-center text-muted py-5">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading tasks...</span>
                            </div>
                            <span class="ms-2">Loading tasks...</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="showCompleted">
                        <label class="form-check-label small" for="showCompleted">Show Completed</label>
                    </div>
                    <small class="text-muted" id="todoCounter">0 tasks</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

/* Force text wrapping for todo items, modal titles, and other text elements */
.todo-title {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Also add for launchpad items to be consistent */
.link-title {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Modal titles */
.modal-title {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Card titles in general */
.card-title {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Assignment names in tables */
#assignmentsTable td {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Mobile assignment cards */
#mobileAssignmentsList .mb-1 {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Form labels */
.form-label {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Flex containers need min-width: 0 to enable text wrapping */
.flex-grow-1 {
    min-width: 0; /* This enables text truncation in flex containers */
}

/* Specific fix for modal header titles */
.modal-header .modal-title {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
    line-height: 1.3;
}

/* For really long text in detail modals */
#detailTitle {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
    line-height: 1.4;
}
.todo-title {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Also add for launchpad items to be consistent */
.link-title {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

.flex-grow-1 {
    min-width: 0; /* This enables text truncation in flex containers */
}

@media (max-width: 768px) {
    .link-url {
        display: none;
    }
}

.todo-item {
    cursor: grab;
    position: relative;
    transition: none; /* Remove smooth transitions */
}

.todo-item:active {
    cursor: grabbing;
}

.todo-item.dragging {
    opacity: 0.5;
    background-color: #f8f9fa !important;
}

.todo-item.placeholder {
    height: 60px;
    background-color: transparent;
    border: 2px dashed #dee2e6;
    opacity: 0.6;
}

/* Drop zone indicators */
.todo-item.drop-above::before {
    content: "";
    position: absolute;
    top: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background-color: #007bff;
    z-index: 10;
}

.todo-item.drop-below::after {
    content: "";
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background-color: #007bff;
    z-index: 10;
}

.todo-sort-handle {
    cursor: grab;
    color: #6c757d;
    margin-right: 10px;
    padding: 5px;
    border-radius: 3px;
    transition: background-color 0.1s ease;
}

.todo-sort-handle:hover {
    background-color: rgba(0, 0, 0, 0.1);
}

.todo-sort-handle:active {
    cursor: grabbing;
    background-color: rgba(0, 0, 0, 0.2);
}

/* Remove any smooth transitions from the list */
.todo-list-dragging .todo-item:not(.dragging) {
    transition: none;
}

/* Dark mode compatibility for assignment cards */
[data-bs-theme="dark"] .bg-light,
.dark-mode .bg-light {
    background-color: rgba(255, 255, 255, 0.05) !important;
}

[data-bs-theme="dark"] .bg-danger.bg-opacity-10,
.dark-mode .bg-danger.bg-opacity-10 {
    background-color: rgba(220, 53, 69, 0.2) !important;
}

[data-bs-theme="dark"] .bg-warning.bg-opacity-10,
.dark-mode .bg-warning.bg-opacity-10 {
    background-color: rgba(255, 193, 7, 0.2) !important;
}

[data-bs-theme="dark"] .bg-info.bg-opacity-10,
.dark-mode .bg-info.bg-opacity-10 {
    background-color: rgba(13, 202, 240, 0.2) !important;
}

[data-bs-theme="dark"] .bg-success.bg-opacity-10,
.dark-mode .bg-success.bg-opacity-10 {
    background-color: rgba(25, 135, 84, 0.2) !important;
}

[data-bs-theme="dark"] .bg-primary.bg-opacity-10,
.dark-mode .bg-primary.bg-opacity-10 {
    background-color: rgba(13, 110, 253, 0.2) !important;
}

</style>

<!-- Link Item Template -->
<template id="linkTemplate">
    <div class="list-group-item list-group-item-action link-item p-3 border-0 border-bottom" style="cursor:pointer;">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h6 class="card-title mb-0 text-primary"><i class="bi bi-link-45deg text-primary"></i> <span class="link-title"></span> &raquo; <small class="text-muted link-url d-md-inline text-truncate"></small></h6>
            </div>
            <div class="flex-shrink-0 dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation();" style="--bs-btn-padding-y: .2rem; --bs-btn-padding-x: .25rem; --bs-btn-font-size: .75rem;">
                                    <i class="bi bi-three-dots-vertical"></i>

                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li hidden><a class="dropdown-item link-open no-abort" href="#" target="_blank">Open</a></li>
                    <li><a class="dropdown-item link-edit no-abort" href="#">Edit</a></li>
                    <li><a class="dropdown-item link-delete no-abort" href="#">Delete</a></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<!-- Add Link Modal -->
<div class="modal fade" id="addLinkModal" tabindex="-1" aria-labelledby="addLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addLinkModalLabel">Add New Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="linkForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="linkTitle" class="form-label">Link Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="linkTitle" required placeholder="e.g., Google">
                    </div>
                    
                    <div class="mb-3">
                        <label for="linkUrl" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="linkUrl" required placeholder="https://example.com">
                    </div>
                    <p><span class="text-danger">*</span> Required</p>

                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Link Modal -->
<div class="modal fade" id="editLinkModal" tabindex="-1" aria-labelledby="editLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLinkModalLabel">Edit Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editLinkForm">
                <input type="hidden" id="editLinkId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editLinkTitle" class="form-label">Link Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editLinkTitle" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editLinkUrl" class="form-label">URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="editLinkUrl" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Todo Item Template -->
<template id="todoTemplate">
    <div class="list-group-item list-group-item-action todo-item p-3 border-0 border-bottom">
        <div class="d-flex align-items-center">
            <!-- Add drag handle -->
            <div class="todo-sort-handle me-2">
                <i class="bi bi-grip-vertical"></i>
            </div>
            <div class="form-check me-3">
                <input class="form-check-input todo-complete" type="checkbox">
            </div>
            <div class="flex-grow-1" style="cursor:pointer;min-width:0;">
                <h6 class="card-title mb-1 todo-title"></h6>
                <small class="text-muted todo-details"></small>
            </div>
            <div class="flex-shrink-0 dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation();">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item todo-view no-abort" href="#">View Details</a></li>
                    <li><a class="dropdown-item todo-edit no-abort" href="#">Edit</a></li>
                    <li><a class="dropdown-item todo-delete no-abort" href="#">Delete</a></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="todoForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="todoTitle" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="todoTitle" required placeholder="What needs to be done?">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="todoDueDate" class="form-label">Due Date (optional)</label>
                            <input type="date" class="form-control" id="todoDueDate">
                        </div>
                        <div class="col-md-6" hidden>
                            <label for="todoReminder" class="form-label">Reminder (optional)</label>
                            <input type="datetime-local" class="form-control" id="todoReminder">
                        </div>
                        
                    </div>
                    
                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="todoImportant">
                        <label class="form-check-label" for="todoImportant">Mark as Important</label>
                    </div>
                    <p><span class="text-danger">*</span> Required</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTodoForm">
                <input type="hidden" id="editTodoId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editTodoTitle" class="form-label">Task Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editTodoTitle" required>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="editTodoDueDate" class="form-label">Due Date (optional)</label>
                            <input type="date" class="form-control" id="editTodoDueDate">
                        </div>
                        <div class="col-md-6" hidden>
                            <label for="editTodoReminder" class="form-label">Reminder (optional)</label>
                            <input type="datetime-local" class="form-control" id="editTodoReminder">
                        </div>
                    </div>
                    
                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="editTodoImportant">
                        <label class="form-check-label" for="editTodoImportant">Mark as Important</label>
                    </div>
                    
                    <div class="mb-3 form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="editTodoCompleted">
                        <label class="form-check-label" for="editTodoCompleted">Mark as Completed</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailModalLabel">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 id="detailTitle" class="card-title mb-3"></h5>
                <div class="mb-3">
                    <span class="badge bg-secondary me-2" id="detailStatus"></span>
                    <span class="badge bg-warning text-dark" id="detailImportant"></span>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-12">
                        <h6 class="text-muted">Due Date</h6>
                        <p id="detailDueDate" class="mb-0">-</p>
                    </div>
                    <div class="col-sm-6" hidden>
                        <h6 class="text-muted">Reminder</h6>
                        <p id="detailReminder" class="mb-0">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnEditTask" class="btn btn-outline-primary me-2">
                    <i class="bi bi-pencil me-1"></i> Edit
                </button>
                <button id="btnDeleteTask" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<?php
$jsCode = <<<'JAVASCRIPT_CODE'

// ============================================================================
// GLOBAL STATE & UTILITIES
// ============================================================================

let linksController = null;
let todosController = null;
let assignmentsController = null;
let isNavigating = false;

let links = [];
let todos = [];
let allUngradedAssignments = [];
let classFilters = {};
let currentSelectedTodo = null;

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================

function showToast(message, type = 'info') {
    if (isNavigating) return;
    
    document.querySelectorAll('.toast').forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.appendChild(toast);
    new bootstrap.Toast(toast).show();
}

function showError(message) {
    showToast('Error: ' + message, 'danger');
}

function showAlert(containerId, message, type = 'danger', showRetry = false, retryCallback = null) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} d-flex justify-content-between align-items-center`;
    alertDiv.innerHTML = `
        <span>${message}</span>
        ${showRetry ? `<button class="btn btn-sm btn-outline-${type}" onclick="(${retryCallback.toString()})()">Retry</button>` : ''}
    `;
    
    container.innerHTML = '';
    container.appendChild(alertDiv);
}

function parseDate(dateStr) {
    if (!dateStr) return new Date(0);
    const parts = dateStr.split('/');
    if (parts.length === 3) return new Date(parts[2], parts[0] - 1, parts[1]);
    return new Date(dateStr);
}

function formatDate(date) {
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function isImportantCategory(category) {
    return category && (category.toUpperCase() === 'MAJOR' || category.toUpperCase() === 'MINOR');
}

function isUngraded(score) {
    if (!score || score.toString().trim() === '') return true;
    const scoreStr = score.toString().toUpperCase();
    return scoreStr === 'N/A' || scoreStr.includes('EXEMPT') || scoreStr === 'X';
}

function isInternalLink(href) {
    try {
        const url = new URL(href, window.location.origin);
        return url.origin === window.location.origin;
    } catch (e) {
        return false;
    }
}

// ============================================================================
// FETCH UTILITIES
// ============================================================================

function abortableFetch(url, options = {}) {
    const controller = new AbortController();
    return {
        promise: fetch(url, {
            ...options,
            signal: controller.signal,
            headers: {
                'Cache-Control': 'no-cache',
                'Pragma': 'no-cache',
                ...options.headers
            }
        }),
        controller,
        abort: () => controller.abort()
    };
}

function abortAllRequests() {
    console.log('Aborting all requests...');
    isNavigating = true;
    
    if (linksController) {
        linksController.abort();
        linksController = null;
    }
    if (todosController) {
        todosController.abort();
        todosController = null;
    }
    if (assignmentsController) {
        assignmentsController.abort();
        assignmentsController = null;
    }
    
    if (window.stop) window.stop();
}

// ============================================================================
// NAVIGATION HANDLING
// ============================================================================

function setupNavigationAbort() {
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href && !link.target && !link.hasAttribute('download') && 
            !link.classList.contains('no-abort') && isInternalLink(link.href)) {
            
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            isNavigating = true;
            abortAllRequests();
            
            const highestTimeoutId = setTimeout(() => {}, 0);
            for (let i = 0; i < highestTimeoutId; i++) {
                clearTimeout(i);
            }
            
            setTimeout(() => {
                window.location.href = link.href;
            }, 50);
            
            return false;
        }
    }, true);

    window.addEventListener('beforeunload', () => {
        isNavigating = true;
        abortAllRequests();
    });

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') {
            isNavigating = true;
            abortAllRequests();
        } else {
            isNavigating = false;
        }
    });
}

// ============================================================================
// LINK MANAGER
// ============================================================================

const LinkManager = {
    elements: {},
    
    init() {
        this.elements = {
            form: document.getElementById('linkForm'),
            editForm: document.getElementById('editLinkForm'),
            list: document.getElementById('linksList'),
            template: document.getElementById('linkTemplate'),
            addModal: new bootstrap.Modal(document.getElementById('addLinkModal')),
            editModal: new bootstrap.Modal(document.getElementById('editLinkModal'))
        };
        
        this.bindEvents();
        this.load();
    },
    
    bindEvents() {
        this.elements.form.addEventListener('submit', (e) => this.add(e));
        this.elements.editForm.addEventListener('submit', (e) => this.update(e));
    },
    
    async load() {
        if (isNavigating) return Promise.reject(new Error('Navigation in progress'));
        
        if (linksController) linksController.abort();
        
        const fetchObj = abortableFetch('/backends/dash-links-backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_links'
        });
        
        linksController = fetchObj.controller;
        
        try {
            const response = await fetchObj.promise;
            if (!response.ok) throw new Error(`Server error: ${response.status}`);
            
            const data = await response.json();
            if (isNavigating) throw new Error('Navigation in progress');
            
            if (data.success) {
                links = data.links || {};
                this.render();
                updateStats();
            } else {
                throw new Error(data.error || 'Failed to load links');
            }
        } catch (err) {
            if (err.name === 'AbortError' || isNavigating) throw err;
            console.error('Error loading links:', err);
            showError('Failed to load links.');
            throw err;
        } finally {
            linksController = null;
        }
    },
    
    render() {
        if (isNavigating) return;
        
        this.elements.list.innerHTML = '';
        
        if (!links || Object.keys(links).length === 0) {
            this.elements.list.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="bi bi-link-45deg display-4 d-block mb-2"></i>
                    <p>No links yet. Add one to get started!</p>
                </div>
            `;
            return;
        }
        
        Object.entries(links).forEach(([id, link]) => {
            const element = this.elements.template.content.cloneNode(true);
            const item = element.querySelector('.link-item');
            
            element.querySelector('.link-title').textContent = link.title || 'Untitled';
            element.querySelector('.link-url').textContent = link.url || '';
            item.setAttribute('data-id', id);
            
            element.querySelector('.link-edit').addEventListener('click', (e) => {
                e.preventDefault();
                this.edit(id, link);
            });
            
            element.querySelector('.link-delete').addEventListener('click', (e) => {
                e.preventDefault();
                this.delete(id);
            });
            
            item.addEventListener('click', (e) => {
                if (!e.target.matches('.dropdown-toggle, .dropdown-item') && link.url) {
                    window.open(link.url, '_blank');
                }
            });
            
            this.elements.list.appendChild(element);
        });
    },
    
    async add(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('action', 'add_link');
        formData.append('title', document.getElementById('linkTitle').value);
        formData.append('url', document.getElementById('linkUrl').value);
        
        try {
            const response = await fetch('/backends/dash-links-backend.php', { method: 'POST', body: formData });
            const data = await response.json();
            
            if (data.success) {
                this.elements.form.reset();
                this.elements.addModal.hide();
                showToast('Link added successfully.', 'success');
                this.load();
            } else {
                showError(data.error || 'Failed to add link');
            }
        } catch (err) {
            console.error('Error adding link:', err);
            showError('Failed to add link');
        }
    },
    
    edit(id, link) {
        document.getElementById('editLinkId').value = id;
        document.getElementById('editLinkTitle').value = link.title;
        document.getElementById('editLinkUrl').value = link.url;
        this.elements.editModal.show();
    },
    
    async update(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('action', 'update_link');
        formData.append('id', document.getElementById('editLinkId').value);
        formData.append('title', document.getElementById('editLinkTitle').value);
        formData.append('url', document.getElementById('editLinkUrl').value);
        
        try {
            const response = await fetch('/backends/dash-links-backend.php', { method: 'POST', body: formData });
            const data = await response.json();
            
            if (data.success) {
                this.elements.editModal.hide();
                showToast('Link updated successfully.', 'success');
                this.load();
            } else {
                showError(data.error || 'Failed to update link');
            }
        } catch (err) {
            console.error('Error updating link:', err);
            showError('Failed to update link');
        }
    },
    
    async delete(id) {
        if (!confirm('Are you sure you want to delete this link?')) return;
        
        const formData = new FormData();
        formData.append('action', 'delete_link');
        formData.append('id', id);
        
        try {
            const response = await fetch('/backends/dash-links-backend.php', { method: 'POST', body: formData });
            const data = await response.json();
            
            if (data.success) {
                showToast('Link deleted successfully.', 'success');
                this.load();
            } else {
                showError(data.error || 'Failed to delete link');
            }
        } catch (err) {
            console.error('Error deleting link:', err);
            showError('Failed to delete link');
        }
    }
};

// ============================================================================
// TODO MANAGER
// ============================================================================

const TodoManager = {
    elements: {},
    
    init() {
        this.elements = {
            form: document.getElementById('todoForm'),
            editForm: document.getElementById('editTodoForm'),
            list: document.getElementById('todoList'),
            template: document.getElementById('todoTemplate'),
            showCompleted: document.getElementById('showCompleted'),
            counter: document.getElementById('todoCounter'),
            addModal: new bootstrap.Modal(document.getElementById('addTaskModal')),
            editModal: new bootstrap.Modal(document.getElementById('editTaskModal')),
            detailModal: new bootstrap.Modal(document.getElementById('taskDetailModal'))
        };
        
        this.bindEvents();
        this.load();
        this.initDragAndDrop();
    },
    
    bindEvents() {
        this.elements.form.addEventListener('submit', (e) => this.add(e));
        this.elements.editForm.addEventListener('submit', (e) => this.updateFromForm(e));
        this.elements.showCompleted.addEventListener('change', () => this.render());
        
        document.getElementById('btnEditTask').onclick = () => {
            this.elements.detailModal.hide();
            setTimeout(() => {
                const todo = todos[currentSelectedTodo];
                if (todo) this.edit(currentSelectedTodo, todo);
            }, 300);
        };
        
        document.getElementById('btnDeleteTask').onclick = () => {
            this.elements.detailModal.hide();
            setTimeout(() => this.delete(currentSelectedTodo), 300);
        };
    },
    
    async load() {
        if (isNavigating) return Promise.reject(new Error('Navigation in progress'));
        
        if (todosController) todosController.abort();
        
        const fetchObj = abortableFetch('/backends/dash-todo-backend.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=get_todos'
        });
        
        todosController = fetchObj.controller;
        
        try {
            const response = await fetchObj.promise;
            const data = await response.json();
            
            if (isNavigating) throw new Error('Navigation in progress');
            
            if (data.success) {
                todos = data.todos;
                this.render();
                this.updateCounter();
                updateStats();
            } else {
                throw new Error('Failed to load todos');
            }
        } catch (err) {
            if (err.name === 'AbortError' || isNavigating) throw err;
            console.error('Error loading todos:', err);
            showError('Failed to load todos');
            throw err;
        } finally {
            todosController = null;
        }
    },
    
    updateCounter() {
        const total = Object.keys(todos).length;
        const completed = Object.values(todos).filter(todo => todo.completed).length;
        this.elements.counter.textContent = `${completed}/${total} tasks completed`;
    },
    
    render() {
        if (isNavigating) return;
        
        this.elements.list.innerHTML = '';
        
        if (Object.keys(todos).length === 0) {
            this.elements.list.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="bi bi-check2-square display-4 d-block mb-2"></i>
                    <p>No tasks yet. Add one to get started!</p>
                </div>
            `;
            return;
        }
        
        const showCompletedTasks = this.elements.showCompleted.checked;
        
        Object.entries(todos).forEach(([id, todo]) => {
            if (!showCompletedTasks && todo.completed) return;
            
            const element = this.elements.template.content.cloneNode(true);
            const item = element.querySelector('.todo-item');
            const title = element.querySelector('.todo-title');
            const details = element.querySelector('.todo-details');
            const checkbox = element.querySelector('.todo-complete');
            
            title.textContent = todo.title;
            checkbox.checked = todo.completed;
            
            item.setAttribute('draggable', 'true');
            item.setAttribute('data-id', id);
            
            if (todo.important) {
                item.classList.add('bg-warning', 'bg-opacity-10');
                title.classList.add('text-warning');
            }
            
            if (todo.completed) {
                title.classList.add('text-decoration-line-through', 'text-muted');
            }
            
            let detailsText = '';
            if (todo.due_date) {
                const dueDate = new Date(todo.due_date * 1000);
                detailsText += `Due: ${dueDate.toLocaleDateString()}`;
            }
            details.textContent = detailsText;
            
            checkbox.addEventListener('change', () => {
                this.updateTodo(id, { completed: checkbox.checked });
            });
            
            element.querySelector('.todo-view').addEventListener('click', (e) => {
                e.preventDefault();
                this.showDetails(id, todo);
            });
            
            element.querySelector('.todo-edit').addEventListener('click', (e) => {
                e.preventDefault();
                this.edit(id, todo);
            });
            
            element.querySelector('.todo-delete').addEventListener('click', (e) => {
                e.preventDefault();
                this.delete(id);
            });
            
            item.addEventListener('click', (e) => {
                if (!e.target.matches('.dropdown-toggle, .dropdown-item, .form-check-input, .todo-sort-handle, .bi-grip-vertical')) {
                    this.showDetails(id, todo);
                }
            });
            
            element.querySelector('.todo-sort-handle').addEventListener('click', (e) => {
                e.stopPropagation();
            });
            
            this.elements.list.appendChild(element);
        });
    },
    
    showDetails(id, todo) {
        currentSelectedTodo = id;
        
        document.querySelectorAll('.todo-item').forEach(item => item.classList.remove('active'));
        const selectedItem = document.querySelector(`.todo-item[data-id="${id}"]`);
        if (selectedItem) selectedItem.classList.add('active');

        document.getElementById('detailTitle').textContent = todo.title;
        
        const statusBadge = document.getElementById('detailStatus');
        statusBadge.textContent = todo.completed ? 'Completed' : 'Pending';
        statusBadge.className = `badge me-2 ${todo.completed ? 'bg-success' : 'bg-secondary'}`;
        
        const importantBadge = document.getElementById('detailImportant');
        importantBadge.classList.toggle('d-none', !todo.important);
        if (todo.important) importantBadge.textContent = 'Important';
        
        const dueDateEl = document.getElementById('detailDueDate');
        if (todo.due_date) {
            const dueDate = new Date(todo.due_date * 1000);
            dueDateEl.textContent = dueDate.toLocaleDateString();
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            dueDateEl.classList.toggle('text-danger', dueDate < today && !todo.completed);
            dueDateEl.classList.toggle('fw-bold', dueDate < today && !todo.completed);
        } else {
            dueDateEl.textContent = '-';
        }
        
        this.elements.detailModal.show();
    },
    
    async add(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('action', 'add_todo');
        formData.append('title', document.getElementById('todoTitle').value);
        
        const dueDate = document.getElementById('todoDueDate').value;
        if (dueDate) formData.append('due_date', dueDate);
        
        formData.append('important', document.getElementById('todoImportant').checked);
        
        try {
            const response = await fetch('/backends/dash-todo-backend.php', { method: 'POST', body: formData });
            const data = await response.json();
            
            if (data.success) {
                this.elements.form.reset();
                this.elements.addModal.hide();
                showToast('Task added successfully.', 'success');
                this.load();
            } else {
                showError(data.error || 'Failed to add todo');
            }
        } catch (err) {
            console.error('Error adding todo:', err);
            showError('Failed to add todo');
        }
    },
    
    edit(id, todo) {
        document.getElementById('editTodoId').value = id;
        document.getElementById('editTodoTitle').value = todo.title;
        
        if (todo.due_date) {
            const dueDate = new Date(todo.due_date * 1000);
            document.getElementById('editTodoDueDate').value = dueDate.toISOString().split('T')[0];
        } else {
            document.getElementById('editTodoDueDate').value = '';
        }
        
        document.getElementById('editTodoImportant').checked = todo.important;
        document.getElementById('editTodoCompleted').checked = todo.completed;
        this.elements.editModal.show();
    },
    
    async updateFromForm(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('action', 'update_todo');
        formData.append('id', document.getElementById('editTodoId').value);
        formData.append('title', document.getElementById('editTodoTitle').value);
        
        const dueDate = document.getElementById('editTodoDueDate').value;
        if (dueDate) formData.append('due_date', dueDate);
        
        formData.append('important', document.getElementById('editTodoImportant').checked);
        formData.append('completed', document.getElementById('editTodoCompleted').checked);
        
        try {
            const response = await fetch('/backends/dash-todo-backend.php', { method: 'POST', body: formData });
            const data = await response.json();
            
            if (data.success) {
                this.elements.editModal.hide();
                showToast('Task updated successfully.', 'success');
                this.load();
            } else {
                showError(data.error || 'Failed to update todo');
            }
        } catch (err) {
            console.error('Error updating todo:', err);
            showError('Failed to update todo');
        }
    },
    
    async updateTodo(id, updates) {
        const formData = new FormData();
        formData.append('action', 'update_todo');
        formData.append('id', id);
        
        for (const [key, value] of Object.entries(updates)) {
            formData.append(key, value);
        }
        
        try {
            const response = await fetch('/backends/dash-todo-backend.php', { method: 'POST', body: formData });
            const data = await response.json();
            
            if (data.success) {
                this.load();
            } else {
                showError(data.error || 'Failed to update todo');
            }
        } catch (err) {
            console.error('Error updating todo:', err);
            showError('Failed to update todo');
        }
    },
    
    async delete(id) {
        if (!confirm('Are you sure you want to delete this task?')) return;
        
        const formData = new FormData();
        formData.append('action', 'delete_todo');
        formData.append('id', id);
        
        try {
            const response = await fetch('/backends/dash-todo-backend.php', { method: 'POST', body: formData });
            const data = await response.json();
            
            if (data.success) {
                showToast('Task deleted successfully.', 'success');
                this.load();
            } else {
                showError(data.error || 'Failed to delete todo');
            }
        } catch (err) {
            console.error('Error deleting todo:', err);
            showError('Failed to delete todo');
        }
    },
    
    initDragAndDrop() {
        let draggedItem = null;
        let placeholder = null;
        
        const getDragAfterElement = (container, y) => {
            const draggableElements = [...container.querySelectorAll('.todo-item:not(.dragging):not(.placeholder)')];
            
            return draggableElements.reduce((closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                }
                return closest;
            }, { offset: Number.NEGATIVE_INFINITY }).element;
        };
        
        const updateTodoOrder = () => {
            const todoItems = [...this.elements.list.querySelectorAll('.todo-item')];
            const newOrder = todoItems.map(item => item.getAttribute('data-id'));
            
            const formData = new URLSearchParams();
            formData.append('action', 'update_todo_order');
            formData.append('order', JSON.stringify(newOrder));
            
            fetch('/backends/dash-todo-backend.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to update order:', data.error);
                    this.load();
                }
            })
            .catch(err => {
                console.error('Error updating todo order:', err);
                this.load();
            });
        };
        
        this.elements.list.addEventListener('dragstart', (e) => {
            if (e.target.closest('.todo-sort-handle') || e.target.closest('.todo-item')) {
                draggedItem = e.target.closest('.todo-item');
                draggedItem.classList.add('dragging');
                
                placeholder = document.createElement('div');
                placeholder.className = 'todo-item placeholder';
                placeholder.style.height = `${draggedItem.offsetHeight}px`;
                
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', draggedItem.getAttribute('data-id'));
            }
        });
        
        this.elements.list.addEventListener('dragend', () => {
            if (draggedItem) {
                draggedItem.classList.remove('dragging');
                if (placeholder && placeholder.parentNode) {
                    placeholder.parentNode.removeChild(placeholder);
                }
                document.querySelectorAll('.todo-item').forEach(item => {
                    item.classList.remove('drop-above', 'drop-below');
                });
                draggedItem = null;
                placeholder = null;
            }
        });
        
        this.elements.list.addEventListener('dragover', (e) => {
            e.preventDefault();
            if (!draggedItem) return;
            
            const afterElement = getDragAfterElement(this.elements.list, e.clientY);
            
            document.querySelectorAll('.todo-item').forEach(item => {
                item.classList.remove('drop-above', 'drop-below');
            });
            
            if (!afterElement) {
                this.elements.list.appendChild(placeholder);
                const items = this.elements.list.querySelectorAll('.todo-item:not(.dragging):not(.placeholder)');
                if (items.length > 0) items[items.length - 1].classList.add('drop-below');
            } else {
                this.elements.list.insertBefore(placeholder, afterElement);
                if (afterElement !== draggedItem) afterElement.classList.add('drop-above');
            }
        });
        
        this.elements.list.addEventListener('drop', (e) => {
            e.preventDefault();
            if (!draggedItem || !placeholder) return;
            
            if (placeholder.parentNode) {
                placeholder.parentNode.replaceChild(draggedItem, placeholder);
            }
            
            document.querySelectorAll('.todo-item').forEach(item => {
                item.classList.remove('drop-above', 'drop-below');
            });
            
            updateTodoOrder();
        });
    }
};

// ============================================================================
// ASSIGNMENTS MANAGER
// ============================================================================

const AssignmentsManager = {
    elements: {},
    maxVisible: 6,
    showingAll: false,
    
    init() {
        this.elements = {
            loader: document.getElementById('loader'),
            classDetail: document.getElementById('classDetail'),
            cardsContainer: document.getElementById('assignmentsCards'),
            mobileList: document.getElementById('mobileAssignmentsList'),
            filterContainer: document.getElementById('classFilterCheckboxes'),
            selectAll: document.getElementById('selectAllClasses'),
            deselectAll: document.getElementById('deselectAllClasses'),
            viewMore: document.getElementById('viewMoreUpcoming')
        };
        
        this.bindEvents();
        this.loadUpcoming();
    },
    
    bindEvents() {
        this.elements.selectAll.addEventListener('click', () => {
            document.querySelectorAll('.class-filter').forEach(checkbox => {
                checkbox.checked = true;
                classFilters[checkbox.value] = true;
            });
            this.saveFilters();
            this.filterAssignments();
        });

        this.elements.deselectAll.addEventListener('click', () => {
            document.querySelectorAll('.class-filter').forEach(checkbox => {
                checkbox.checked = false;
                classFilters[checkbox.value] = false;
            });
            this.saveFilters();
            this.filterAssignments();
        });
        
        this.elements.viewMore.addEventListener('click', () => {
            this.showingAll = !this.showingAll;
            this.elements.viewMore.textContent = this.showingAll ? 'View Less' : 'View More';
            this.filterAssignments();
        });
    },
    
    loadFilters() {
        const saved = sessionStorage.getItem('classFilters');
        if (saved) classFilters = JSON.parse(saved);
    },
    
    saveFilters() {
        sessionStorage.setItem('classFilters', JSON.stringify(classFilters));
    },
    
    async loadUpcoming() {
        if (isNavigating) return Promise.reject(new Error('Navigation in progress'));
        
        if (assignmentsController) assignmentsController.abort();
        
        this.elements.loader.classList.remove('d-none');
        this.elements.classDetail.classList.add('d-none');
        
        const fetchObj = abortableFetch('/backends/dash-grades-backend.php');
        assignmentsController = fetchObj.controller;
        
        const timeoutId = setTimeout(() => {
            if (assignmentsController === fetchObj.controller) {
                fetchObj.abort();
            }
        }, 5000);
        
        try {
            const response = await fetchObj.promise;
            clearTimeout(timeoutId);
            
            if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
            
            const data = await response.json();
            if (isNavigating || assignmentsController !== fetchObj.controller) {
                throw new Error('Navigation in progress');
            }
            
            this.processUpcoming(data);
            return data;
        } catch (err) {
            clearTimeout(timeoutId);
            if (err.name === 'AbortError' || isNavigating) throw err;
            
            console.error('Error loading assignments:', err);
            this.elements.loader.innerHTML = `
                <div class="alert alert-danger">
                    Failed to load assignments. 
                    <button class="btn btn-sm btn-outline-secondary ms-2" onclick="AssignmentsManager.loadUpcoming()">Retry</button>
                </div>
            `;
            throw err;
        } finally {
            assignmentsController = null;
        }
    },
    
    processUpcoming(data) {
        this.elements.loader.classList.add('d-none');
        
        const isMobile = window.innerWidth < 992;
        this.elements.classDetail.classList.toggle('d-none', isMobile);
        document.getElementById('mobileAssignmentsContainer').classList.toggle('d-none', !isMobile);
        
        this.loadFilters();
        this.elements.filterContainer.innerHTML = '';
        
        allUngradedAssignments = [];
        const classes = data.classes;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const nextWeek = new Date(today);
        nextWeek.setDate(today.getDate() + 7);

        Object.keys(classes).forEach((className, index) => {
            const classData = classes[className];
            const col = document.createElement('div');
            col.className = 'col-md-4 col-sm-6 mb-2';
            
            const isChecked = classFilters[className] !== undefined ? classFilters[className] : true;
            
            col.innerHTML = `
                <div class="form-check">
                    <input class="form-check-input class-filter" type="checkbox" 
                           id="filter-${index}" value="${className}" ${isChecked ? 'checked' : ''}>
                    <label class="form-check-label" for="filter-${index}">${className}</label>
                </div>
            `;
            this.elements.filterContainer.appendChild(col);
            classFilters[className] = isChecked;
            
            if (classData.assignments && classData.assignments.length > 0) {
                const assignments = classData.assignments[0][0] === 'Date Due' ? 
                                   classData.assignments.slice(1) : classData.assignments;
                
                assignments.forEach(assignment => {
                    let dueDateStr, assignmentName, category, score;
                    
                    if (Array.isArray(assignment)) {
                        dueDateStr = assignment[0];
                        assignmentName = assignment[2] || 'N/A';
                        category = assignment[3] || 'N/A';
                        score = assignment[4];
                    } else {
                        dueDateStr = assignment.due_date || assignment.date_due;
                        assignmentName = assignment.name || assignment.assignment || 'N/A';
                        category = assignment.category || 'N/A';
                        score = assignment.score;
                    }
                    
                    if (isUngraded(score)) {
                        const dueDate = parseDate(dueDateStr);
                        
                        if (dueDate >= today && dueDate <= nextWeek) {
                            allUngradedAssignments.push({
                                subject: className,
                                assignmentName: assignmentName,
                                category: category,
                                dueDate: dueDate,
                                rawDueDate: dueDateStr || 'N/A',
                                isImportant: isImportantCategory(category)
                            });
                        }
                    }
                });
            }
        });

        document.querySelectorAll('.class-filter').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                classFilters[e.target.value] = e.target.checked;
                this.saveFilters();
                this.filterAssignments();
            });
        });

        allUngradedAssignments.sort((a, b) => a.dueDate - b.dueDate);
        this.filterAssignments();
    },
    
    filterAssignments() {
        const filtered = allUngradedAssignments.filter(assignment => classFilters[assignment.subject]);
        
        document.getElementById('assignmentsCount').textContent = filtered.length;
        this.elements.cardsContainer.innerHTML = '';
        this.elements.mobileList.innerHTML = '';
        
        // Show/hide view more button
        this.elements.viewMore.style.display = filtered.length > this.maxVisible ? 'inline-block' : 'none';
        
        if (filtered.length === 0) {
            this.elements.classDetail.classList.remove('d-none');
            this.elements.cardsContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-calendar-x display-4 d-block text-muted mb-2"></i>
                    <p class="text-muted">No upcoming ungraded assignments found.</p>
                </div>
            `;
            return;
        }
        
        this.elements.classDetail.classList.remove('d-none');
        const displayCount = this.showingAll ? filtered.length : Math.min(filtered.length, this.maxVisible);
        
        filtered.slice(0, displayCount).forEach(assignment => {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const assignmentDueDate = new Date(assignment.dueDate);
            assignmentDueDate.setHours(0, 0, 0, 0);
            
            const isDueToday = assignmentDueDate.getTime() === today.getTime();
            const isOverdue = assignment.dueDate < today && !isDueToday;
            
            let cardClass = 'border-0';
            let badgeClass = 'bg-primary';
            let dateClass = 'text-muted';
            let cardBg = 'bg-light';
            
            if (isOverdue) {
                cardClass = 'border-0';
                badgeClass = 'bg-danger';
                dateClass = 'text-danger fw-bold';
                cardBg = 'bg-danger bg-opacity-10';
            } else if (isDueToday) {
                cardClass = 'border-0';
                badgeClass = 'bg-warning text-dark';
                dateClass = 'text-warning fw-bold';
                cardBg = 'bg-warning bg-opacity-10';
            } else if (assignment.isImportant) {
                cardClass = 'border-0';
                badgeClass = 'bg-info text-dark';
                cardBg = 'bg-info bg-opacity-10';
            }
            
            const card = document.createElement('div');
            card.className = 'col-lg-4 col-md-6';
            card.innerHTML = `
                <div class="card h-100 shadow-sm ${cardClass} ${cardBg}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge ${badgeClass} mb-2">${assignment.category}</span>
                        </div>
                        <h6 class="card-title mb-2 fw-bold">${assignment.assignmentName}</h6>
                        <p class="card-text mb-2">
                            <small class="text-muted fw-semibold">
                                <i class="bi bi-book me-1"></i>${assignment.subject}
                            </small>
                        </p>
                        <p class="card-text mb-0">
                            <small class="${dateClass}">
                                <i class="bi bi-calendar-event me-1"></i>
                                ${formatDate(assignment.dueDate)}
                                ${isDueToday ? ' (Today)' : isOverdue ? ' (Overdue)' : ''}
                            </small>
                        </p>
                    </div>
                </div>
            `;
            this.elements.cardsContainer.appendChild(card);
        });
    }
};

// ============================================================================
// GRADED ASSIGNMENTS MANAGER
// ============================================================================

const GradedManager = {
    elements: {},
    maxVisible: 6,
    showingAll: false,
    
    init() {
        this.elements = {
            loader: document.getElementById('gradedLoader'),
            classDetail: document.getElementById('gradedClassDetail'),
            cardsContainer: document.getElementById('gradedAssignmentsCards'),
            mobileList: document.getElementById('mobileGradedAssignmentsList'),
            viewMore: document.getElementById('viewMoreGraded')
        };
        
        this.load();
        this.bindEvents();
    },
    
    bindEvents() {
        this.elements.viewMore.addEventListener('click', () => {
            this.showingAll = !this.showingAll;
            this.elements.viewMore.textContent = this.showingAll ? 'View Less' : 'View More';
            this.render(this.gradedAssignments);
        });
    },
    
    async load() {
        if (isNavigating) return Promise.reject(new Error('Navigation in progress'));
        
        this.elements.loader.classList.remove('d-none');
        this.elements.classDetail.classList.add('d-none');
        
        try {
            const response = await fetch('/backends/dash-grades-backend.php');
            if (!response.ok) throw new Error(`HTTP error: ${response.status}`);
            
            const data = await response.json();
            if (isNavigating) throw new Error('Navigation in progress');
            
            this.process(data);
            return data;
        } catch (err) {
            if (isNavigating) throw err;
            
            console.error('Error loading graded assignments:', err);
            this.elements.loader.innerHTML = '<div class="alert alert-danger">Failed to load graded assignments.</div>';
            throw err;
        }
    },
    
    process(data) {
        const gradedAssignments = [];
        const classes = data.classes;
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const lastWeek = new Date(today);
        lastWeek.setDate(today.getDate() - 7);
        
        Object.entries(classes).forEach(([className, classData]) => {
            if (classData.assignments && classData.assignments.length > 0) {
                const assignments = classData.assignments[0][0] === 'Date Due' ? 
                                   classData.assignments.slice(1) : classData.assignments;
                
                assignments.forEach(assignment => {
                    let dueDateStr, assignmentName, score;
                    
                    if (Array.isArray(assignment)) {
                        dueDateStr = assignment[0];
                        assignmentName = assignment[2] || 'N/A';
                        score = assignment[4];
                    } else {
                        dueDateStr = assignment.due_date || assignment.date_due;
                        assignmentName = assignment.name || assignment.assignment || 'N/A';
                        score = assignment.score;
                    }
                    
                    if (score && score.toString().trim() !== '' && 
                        !score.toString().toUpperCase().includes('EXEMPT') && 
                        score.toString().toUpperCase() !== 'X' &&
                        score.toString().toUpperCase() !== 'N/A') {
                        
                        const dueDate = parseDate(dueDateStr);
                        
                        if (dueDate >= lastWeek && dueDate <= today) {
                            gradedAssignments.push({
                                subject: className,
                                assignmentName: assignmentName,
                                dueDate: dueDate,
                                score: score
                            });
                        }
                    }
                });
            }
        });
        
        gradedAssignments.sort((a, b) => b.dueDate - a.dueDate);
        this.render(gradedAssignments);
    },
    
    render(assignments) {
        this.gradedAssignments = assignments;
        this.elements.loader.classList.add('d-none');
        this.elements.classDetail.classList.remove('d-none');
        
        this.elements.cardsContainer.innerHTML = '';
        this.elements.mobileList.innerHTML = '';
        
        // Show/hide view more button
        this.elements.viewMore.style.display = assignments.length > this.maxVisible ? 'inline-block' : 'none';
        
        if (assignments.length === 0) {
            this.elements.cardsContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-check-circle display-4 d-block text-muted mb-2"></i>
                    <p class="text-muted">No graded assignments found.</p>
                </div>
            `;
            return;
        }
        
        const displayCount = this.showingAll ? assignments.length : Math.min(assignments.length, this.maxVisible);
        
        assignments.slice(0, displayCount).forEach(assignment => {
            // Determine score color and badge
            let scoreClass = 'text-muted';
            let badgeClass = 'bg-secondary';
            let cardBg = 'bg-light';
            const scoreNum = parseFloat(assignment.score);
            
            if (!isNaN(scoreNum)) {
                if (scoreNum >= 90) {
                    scoreClass = 'text-success';
                    badgeClass = 'bg-success';
                    cardBg = 'bg-success bg-opacity-10';
                } else if (scoreNum >= 80) {
                    scoreClass = 'text-primary';
                    badgeClass = 'bg-primary';
                    cardBg = 'bg-primary bg-opacity-10';
                } else if (scoreNum >= 70) {
                    scoreClass = 'text-warning';
                    badgeClass = 'bg-warning text-dark';
                    cardBg = 'bg-warning bg-opacity-10';
                } else {
                    scoreClass = 'text-danger';
                    badgeClass = 'bg-danger';
                    cardBg = 'bg-danger bg-opacity-10';
                }
            }
            
            const card = document.createElement('div');
            card.className = 'col-lg-4 col-md-6';
            card.innerHTML = `
                <div class="card h-100 shadow-sm border-0 ${cardBg}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge ${badgeClass}">${assignment.score}%</span>
                        </div>
                        <h6 class="card-title mb-2 fw-bold">${assignment.assignmentName}</h6>
                        <p class="card-text mb-2">
                            <small class="text-muted fw-semibold">
                                <i class="bi bi-book me-1"></i>${assignment.subject}
                            </small>
                        </p>
                        <p class="card-text mb-0">
                            <small class="text-muted">
                                <i class="bi bi-calendar-check me-1"></i>
                                ${formatDate(assignment.dueDate)}
                            </small>
                        </p>
                    </div>
                </div>
            `;
            this.elements.cardsContainer.appendChild(card);
        });
    }
};

// ============================================================================
// STATS UPDATE
// ============================================================================

function updateStats() {
    document.getElementById('linksCount').textContent = Object.keys(links || {}).length;
    const totalTodos = Object.keys(todos || {}).length;
    const completedTodos = Object.values(todos || {}).filter(todo => todo.completed).length;
    document.getElementById('completedTasks').textContent = completedTodos;
    document.getElementById('pendingTasks').textContent = totalTodos - completedTodos;
}

// ============================================================================
// INITIALIZATION
// ============================================================================

document.addEventListener('DOMContentLoaded', () => {
    console.log('Initializing dashboard...');
    
    setupNavigationAbort();
    
    // Initialize all managers in parallel
    LinkManager.init();
    TodoManager.init();
    AssignmentsManager.init();
    GradedManager.init();
    
    console.log('Dashboard initialized');
});

JAVASCRIPT_CODE;


$obfuscated = $jsCode;
//$obfuscated = Obfuscator::Obfuscate($jsCode);
echo "<script>" . $obfuscated . "</script>";

?>
<?php include_once("_f.php"); ?>