<?php session_start(); include_once("_h.php"); ?>

<style>
/* Disable hover effects on touch devices */
@media (hover: none) and (pointer: coarse) {
    .btn:hover,
    .nav-link:hover,
    .card:hover,
    a:hover {
        color: inherit !important;
        background-color: inherit !important;
        transform: none !important;
        box-shadow: inherit !important;
    }
}

/* Mobile navbar title truncation */
#mobile-navbar-title {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
}

/* Ensure consistent navbar height */
.mobile-app-navbar {
    min-height: 52px;
}

.mobile-app-navbar .d-flex.align-items-center {
    min-height: 44px;
}
</style>

    <?php if ($isInApp): ?>
    <!-- Mobile App Top Navbar -->
    <div class="mobile-app-navbar sticky-top d-lg-none" style="top: 0; z-index: 1000; border-bottom: 1px solid var(--bs-border-color); margin-left: -12px; margin-right: -12px; margin-top: -12px; margin-bottom: 20px;">
        <div class="d-flex align-items-center px-3 py-2">
            <!-- Back button (hidden initially, shown in detailed view) -->
            <button id="mobile-back-btn" class="btn btn-link p-0 text-decoration-none d-none" style="font-size: 1.2rem; margin-right: 12px; color: var(--bs-body-color); min-width: 24px;">
                <i class="bi bi-chevron-left"></i>
            </button>
            
            <!-- Title -->
            <h5 id="mobile-navbar-title" class="mb-0 fw-semibold" style="flex: 1;">Calendars</h5>

            <!-- Right actions (shown in detailed view) - use visibility instead of d-none -->
            <div id="mobile-navbar-actions" style="visibility: hidden; min-width: 36px;">
                <button class="btn btn-link p-0 text-decoration-none" id="mobile-manage-collaborators" style="font-size: 1.2rem; color: var(--bs-body-color);">
                    <i class="bi bi-people"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Desktop header -->
    <h2 class="d-none d-lg-block">Calendars</h2>
    <?php else: ?>
    <!-- Desktop header when not in app -->
    <h2 class="">Calendars</h2>
    <?php endif; ?>

    <hr class="<?php echo $isInApp ? 'd-none d-lg-block' : ''; ?>">

    <!-- Selection View -->
    <section id="selection-view">

        <!-- Pending Invites Section -->
        <div id="pending-invites-section" class="card mb-4 d-none">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">
                    Pending  Invites
                </h6>
            </div>
            <div class="card-body p-3" id="pending-invites-list">
                <!-- Pending invites will be listed here -->
            </div>
        </div>
        
        <!-- Calendars Grid -->
        <div id="calendars-list" class="row g-3">
            <!-- Calendars will be loaded here -->
        </div>
        
        <!-- Empty State -->
        <div id="no-calendars" class="text-center py-5 d-none">
            <i class="bi bi-calendar-x display-1 text-muted mb-3"></i>
            <h4 class="text-muted mb-2">No calendars yet</h4>
            <p class="text-muted mb-4">Create a calendar to organize events or assignments. You can also invite collaborators to view and modify the same calendar.</p>
            <button class="btn btn-primary" id="create-first-calendar">
                <i class="bi bi-plus-circle me-2"></i>Create
            </button>
        </div>
        
        <!-- Max Calendars State -->
        <div id="max-calendars" class="text-center py-5 d-none">
            <i class="bi bi-calendar-check display-1 text-muted mb-3"></i>
            <h4 class="text-muted mb-2">Maximum Calendars Reached</h4>
            <p class="text-muted mb-4">You've reached the maximum limit of 15 calendars. Please delete existing calendars to create new ones.</p>
        </div>
    </section>

    <!-- Detailed View -->
    <section id="detailed-view" class="d-none">
        <!-- Navigation Header -->
        <div class="mb-4 <?php echo $isInApp ? 'd-none d-lg-block' : ''; ?>">
            <div class="d-flex gap-2 mb-3">
                <button class="btn btn-outline-secondary" id="back-to-selection">
                    <i class="bi bi-arrow-left me-2"></i>Back
                </button>
                
                <button class="btn btn-outline-primary" id="manage-collaborators">
                    <i class="bi bi-people me-2"></i>Collaborators
                </button>
            </div>

            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3 id="current-calendar-name" class="h4 fw-semibold mb-1">Calendar</h3>
                    <div class="d-flex gap-3 text-muted small">
                        <span id="calendar-owner-info"></span>
                        <span id="calendar-stats"></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="row g-4">
            <!-- Calendar View -->
            <div class="col-lg-9">
                <!-- Calendar Navigation -->
                <div class="card mb-3">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2 align-items-center">
                                <button class="btn btn-sm btn-outline-primary" id="prev-month">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary" id="next-month">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                                <span id="current-month" class="fw-semibold ms-2"></span>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary" id="today-btn">Today</button>
                                <div class="btn-group btn-group-sm d-none" role="group">
                                    <input type="radio" class="btn-check" name="view-type" id="month-view" autocomplete="off" checked>
                                    <label class="btn btn-outline-primary" for="month-view">Month</label>
                                    <input type="radio" class="btn-check" name="view-type" id="week-view" autocomplete="off">
                                    <label class="btn btn-outline-primary" for="week-view">Week</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- FullCalendar Container -->
                <div class="card">
                    <div class="card-body p-0">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-3">
                <!-- Today's Events -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold" id="today-events-title">
                            <i class="bi bi-calendar-event me-2"></i>Today's Events
                        </h6>
                    </div>
                    <div class="card-body p-3" id="today-events">
                        <!-- Today's events will be listed here -->
                    </div>
                </div>
                
                <!-- Calendar Actions -->
                <div class="card" style="">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold">
                            Actions
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-warning text-start" id="rename-calendar">
                                <i class="bi bi-pencil me-2"></i>Rename Calendar
                            </button>
                            <button class="btn btn-outline-danger text-start" id="delete-calendar">
                                <i class="bi bi-trash me-2"></i>Delete Calendar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<!-- Modals -->
<!-- Create Calendar Modal -->
<div class="modal fade" id="createCalendarModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Create New Calendar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="create-calendar-form">
                        <input type="text" class="form-control" id="calendar-name" placeholder="Enter calendar name" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-calendar">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Add New Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="add-event-form">
                    <input type="hidden" id="event-date">
                    <div class="mb-3">
                        <label for="event-subject" class="form-label">Subject</label>
                        <select class="form-select" id="event-subject">
                            <option value="" disabled>-- Select --</option>
                            <!-- Subjects will be populated from assignments -->
                        </select>
                    </div>
                    <div class="mb-3" id="custom-subject-container" style="display: none;">
                        <input type="text" class="form-control" id="custom-subject" placeholder="Enter custom subject">
                    </div>
                    <div class="mb-3">
                        <label for="event-type" class="form-label">Event Type</label>
                        <select class="form-select" id="event-type">
                            <option value="Homework">Homework</option>
                            <option value="Test">Test</option>
                            <option value="Quiz">Quiz</option>
                            <option value="Custom">Custom</option>
                        </select>
                    </div>
                    <div class="mb-3" id="custom-type-container" style="display: none;">
                        <input type="text" class="form-control" id="custom-type" placeholder="Enter custom event type">
                    </div>
                    <div class="mb-3">
                        <label for="event-description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="event-description" rows="3" placeholder="Add event details..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-event">Add Event</button>
            </div>
        </div>
    </div>
</div>

<!-- Day Events Modal -->
<div class="modal fade" id="dayEventsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="day-events-title">Events</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="day-events-list">
                    <!-- Day events will be listed here -->
                </div>
                <div id="no-day-events" class="text-center py-3 d-none">
                    <p class="text-muted mb-0">No events for this day</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="add-event-to-day">Add Event</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Edit Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="edit-event-form">
                    <input type="hidden" id="edit-event-id">
                    <input type="hidden" id="edit-event-date">
                    <div class="mb-3">
                        <label for="edit-event-subject" class="form-label">Subject</label>
                        <select class="form-select" id="edit-event-subject" disabled>
                            <option value="">-- Select --</option>
                            <!-- Subjects will be populated from assignments -->
                        </select>
                        <div class="form-text">Subject cannot be changed when editing events</div>
                    </div>
                    <div class="mb-3" id="edit-custom-subject-container" style="display: none;">
                        <label for="edit-custom-subject" class="form-label">Custom Subject</label>
                        <input type="text" class="form-control" id="edit-custom-subject" placeholder="Enter custom subject" readonly>
                        <div class="form-text">Custom subject cannot be changed</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-event-type" class="form-label">Event Type</label>
                        <select class="form-select" id="edit-event-type">
                            <option value="Homework">Homework</option>
                            <option value="Test">Test</option>
                            <option value="Quiz">Quiz</option>
                            <option value="Custom">Custom</option>
                        </select>
                    </div>
                    <div class="mb-3" id="edit-custom-type-container" style="display: none;">
                        <label for="edit-custom-type" class="form-label">Custom Type</label>
                        <input type="text" class="form-control" id="edit-custom-type" placeholder="Enter custom event type">
                    </div>
                    <div class="mb-3">
                        <label for="edit-event-description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="edit-event-description" rows="3" placeholder="Add event details..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="update-event">Update Event</button>
            </div>
        </div>
    </div>
</div>

<!-- Manage Collaborators Modal -->
<div class="modal fade" id="manageCollaboratorsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold">Manage Collaborators</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h6 class="fw-semibold">Add Collaborator</h6>
                    <div class="input-group">
                        <input type="text" class="form-control" id="user-search" placeholder="Search users by name or ID...">
                        <button class="btn btn-outline-primary" type="button" id="search-users">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                    <label class="form-label text-muted mb-0">
                        <small>Users must be in the same school as the owner.</small>
                    </label>
                    <div id="user-results" class="mt-3"></div>
                </div>
                
                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Current Collaborators</h6>
                        <div id="collaborators-list">
                            <!-- Collaborators will be listed here -->
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Pending Invites</h6>
                        <div id="pending-invites">
                            <!-- Pending invites will be listed here -->
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

<!-- Loader -->
<div id="loader" class="d-none align-items-center justify-content-center gap-3 my-5 py-5">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mb-0 text-muted">Loading calendars...</p>
</div>

<?php include_once("_f.php"); ?>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

<style>

.char-limit {
    font-size: 0.75rem;
    color: var(--bs-secondary);
}

.char-limit.warning {
    color: var(--bs-warning);
}

.char-limit.danger {
    color: var(--bs-danger);
}

.fc .fc-daygrid-day.fc-day-today {
    background-color: rgba(var(--bs-info-rgb), 0.2) !important;
    position: relative;
}

.fc .fc-daygrid-day.fc-day-today::before {
    content: '';
    position: absolute;
    top: 2px;
    right: 2px;
    width: 6px;
    height: 6px;
    background-color: var(--bs-info);
    border-radius: 50%;
}

.fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
    color: var(--bs-info) !important;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 2px;
}

.fc-theme-bootstrap5 .fc-scrollgrid {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
}

.fc-theme-bootstrap5 .fc-col-header-cell {
    background-color: rgba(var(--bs-secondary-rgb), 0.05);
    border-color: var(--bs-border-color);
    color: var(--bs-secondary);
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.5rem;
}

.fc-theme-bootstrap5 .fc-daygrid-day {
    border-color: var(--bs-border-color);
    transition: background-color 0.15s ease-in-out;
}

.fc-theme-bootstrap5 .fc-daygrid-day:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.fc-theme-bootstrap5 .fc-daygrid-day-number {
    color: var(--bs-body-color);
    font-weight: 500;
    padding: 4px;
    text-decoration: none;
}

.fc-theme-bootstrap5 .fc-event {
    border: none;
    border-radius: var(--bs-border-radius-sm);
    font-size: 0.75rem;
    font-weight: 500;
    padding: 2px 4px;
    margin: 1px 2px;
}

.fc-theme-standard .fc-scrollgrid {
    border: 1px solid var(--bs-border-color) !important;
}

.fc-theme-standard td, .fc-theme-standard th {
    border: 1px solid var(--bs-border-color) !important;
}

.fc-theme-standard th {
    background-color: var(--bs-body-bg) !important;
    color: var(--bs-body-color) !important;
}

.fc .fc-col-header-cell {
    background-color: var(--bs-body-bg) !important;
}

.fc .fc-col-header-cell-cushion {
    color: var(--bs-body-color) !important;
    text-decoration: none !important;
}

.fc .fc-toolbar {
    flex-wrap: wrap;
    gap: 0.5rem;
    padding: 1rem;
    margin-bottom: 0;
}

.fc .fc-toolbar-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--bs-body-color);
}

.fc .fc-button {
    background-color: var(--bs-body-bg);
    border-color: var(--bs-border-color);
    color: var(--bs-body-color);
}

.fc .fc-button:hover {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.fc .fc-button-primary:not(:disabled).fc-button-active {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.fc-daygrid-day-frame {
    position: relative;
    min-height: 100px;
    transition: background-color 0.2s ease;
    padding: 0.25rem;
}

.fc-daygrid-day-frame:hover {
    background-color: rgba(var(--bs-primary-rgb), 0.05);
    cursor: pointer;
}

.fc-day-today {
    background-color: rgba(var(--bs-info-rgb), 0.1) !important;
}

.fc-event {
    border-radius: 4px;
    border: none;
    padding: 2px 4px;
    font-size: 0.75rem;
    margin-bottom: 1px;
    cursor: pointer;
}

.event-item {
    padding: 0.75rem;
    border-radius: 6px;
    border-left: 4px solid var(--bs-primary);
    margin-bottom: 0.75rem;
    //background-color: var(--bs-body-bg);
    transition: transform 0.2s ease;
}

.event-item:hover {
    transform: translateY(-1px);
}

.event-item.test {
    border-left-color: var(--bs-danger);
}

.event-item.quiz {
    border-left-color: var(--bs-warning);
}

.event-item.due-date {
    border-left-color: var(--bs-info);
}

.event-item.custom {
    border-left-color: var(--bs-secondary);
}

.event-type2.test {
    border-left-color: var(--bs-danger);
}

.event-type2.quiz {
    border-left-color: var(--bs-warning);
}

.event-type2.due-date {
    border-left-color: var(--bs-info);
}

.event-type2.custom {
    border-left-color: var(--bs-secondary);
}

.calendar-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
    border: 1px solid var(--bs-border-color);
}

.calendar-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.invite-item {
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid var(--bs-border-color);
    margin-bottom: 1rem;
    //background-color: var(--bs-body-bg);
}

.fc {
    --fc-border-color: var(--bs-border-color);
    //--fc-page-bg-color: var(--bs-body-bg);
    //--fc-neutral-bg-color: var(--bs-body-bg);
    --fc-list-event-hover-bg-color: var(--bs-body-bg);
}

.fc .fc-daygrid-body, 
.fc .fc-scrollgrid-section-body table {
    //background-color: var(--bs-body-bg) !important;
}

.fc .fc-daygrid-day {
    //background-color: var(--bs-body-bg) !important;
}

@media (max-width: 768px) {
    .fc .fc-toolbar {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .fc .fc-toolbar .fc-toolbar-chunk {
        margin-bottom: 0.5rem;
    }
}

.border-dashed {
    border: 2px dashed var(--bs-border-color) !important;
}

.border-dashed:hover {
    border-color: var(--bs-primary) !important;
    background-color: rgba(var(--bs-primary-rgb), 0.05);
}

.border-dashed .text-muted {
    transition: color 0.2s ease;
}

.border-dashed:hover .text-muted {
    color: var(--bs-primary) !important;
}

.calendar-card .dropdown-menu {
    z-index: 1060 !important;
}

.calendar-card {
    position: relative;
    z-index: 1;
}

.calendar-card:hover {
    z-index: 2;
}
</style>

<!-- Include FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

<script>
class CalendarApp {
    constructor() {
        this.currentView = 'selection';
        this.currentCalendarId = null;
        this.calendar = null;
        this.calendarsData = {};
        this.currentSelectedDate = null;
        this.pollingInterval = null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadCalendars();
        this.initCalendar();
        this.startPolling();
    }

    // Polling Management
    startPolling() {
        if (this.pollingInterval) clearInterval(this.pollingInterval);
        this.pollingInterval = setInterval(() => {
            if (this.currentView === 'selection' || this.currentView === 'detailed') {
                this.refreshCalendarsData();
            }
        }, 500);
    }

    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    }

    async refreshCalendarsData() {
        if (document.getElementById('loader').classList.contains('d-flex')) return;
        
        try {
            const response = await fetch('/backends/calendar-backend.php?action=get_calendars');
            const data = await response.json();
            
            if (data.success) {
                const oldData = JSON.stringify(this.calendarsData);
                const newData = JSON.stringify(data.calendars || {});
                
                if (oldData !== newData) {
                    this.calendarsData = data.calendars || {};
                    this.refreshOpenModals();
                    
                    if (this.currentView === 'selection') {
                        this.renderCalendarsList();
                        this.loadPendingInvitesSection();
                    } else if (this.currentView === 'detailed' && this.currentCalendarId) {
                        if (this.calendarsData[this.currentCalendarId]) {
                            this.refreshDetailedView();
                        } else {
                            this.showSelectionView();
                            this.showNotification('You are unable to view this calendar.', 'warning');
                        }
                    }
                }
            }
        } catch (error) {
            console.error('Error polling:', error);
        }
    }

    refreshOpenModals() {
        // Refresh collaborators modal if open
        const collaboratorsModal = document.getElementById('manageCollaboratorsModal');
        if (collaboratorsModal && collaboratorsModal.classList.contains('show')) {
            this.refreshCollaboratorsModal();
        }
        
        // Refresh day events modal if open
        const dayEventsModal = document.getElementById('dayEventsModal');
        if (dayEventsModal && dayEventsModal.classList.contains('show') && this.currentSelectedDate) {
            this.showDayEventsModal(this.currentSelectedDate);
        }
        
        // Refresh pending invites section
        if (this.currentView === 'selection') {
            this.loadPendingInvitesSection();
        }
        
        // Refresh today's events
        if (this.currentView === 'detailed' && this.currentCalendarId) {
            this.loadTodayEvents();
        }
    }

    // Utility Functions
    showLoader() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.classList.remove('d-none');
            loader.classList.add('d-flex');
        }
    }

    hideLoader() {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.classList.remove('d-flex');
            loader.classList.add('d-none');
        }
    }

formatDate(dateStr) {
    const date = new Date(dateStr + 'T00:00:00');
    return date.toLocaleDateString('en-US', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
}

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 1060; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }

    getEventColor(type) {
        const colors = {
            'Homework': '#0d6efd',
            'Test': '#dc3545', 
            'Quiz': '#d6a204',
            'due-date': '#0dcaf0',
            'Custom': '#6c757d',
            'homework': '#0d6efd',
            'test': '#dc3545', 
            'quiz': '#d6a204',
            'custom': '#6c757d'

        };
        return colors[type] || '#6c757d';
    }

    getUserDisplayName(userId) {
        if (userId === '<?php echo $_SESSION["id"]; ?>') {
            return 'You';
        }
        
        if (this.currentCalendarId && this.calendarsData[this.currentCalendarId]) {
            const calendar = this.calendarsData[this.currentCalendarId];
            if (calendar.owner === userId) {
                return calendar.owner_name || 'Owner';
            }
            
            if (calendar.collaborators) {
                const collaborator = calendar.collaborators.find(collab => collab.id === userId);
                if (collaborator) {
                    return collaborator.name;
                }
            }
        }
        
        return 'User ' + userId;
    }

showEditEventModal(eventId, subject, type, description) {
    document.getElementById('edit-event-id').value = eventId;
    
    document.getElementById('edit-event-date').value = this.currentSelectedDate;
    
    const subjectSelect = document.getElementById('edit-event-subject');
    const typeSelect = document.getElementById('edit-event-type');
    
    this.loadSubjectsForEditModal().then(() => {
        let isCustomSubject = true;
        
        for (let option of subjectSelect.options) {
            if (option.value === subject && option.value !== 'Custom') {
                isCustomSubject = false;
                break;
            }
        }
        
        if (isCustomSubject) {
            subjectSelect.value = 'Custom';
            document.getElementById('edit-custom-subject').value = subject;
            document.getElementById('edit-custom-subject-container').style.display = 'block';
        } else {
            subjectSelect.value = subject;
            document.getElementById('edit-custom-subject-container').style.display = 'none';
        }
        
        subjectSelect.disabled = true;
        
        let isCustomType = true;
        for (let option of typeSelect.options) {
            if (option.value === type && option.value !== 'Custom') {
                isCustomType = false;
                break;
            }
        }
        
        if (isCustomType) {
            typeSelect.value = 'Custom';
            document.getElementById('edit-custom-type').value = type;
            document.getElementById('edit-custom-type-container').style.display = 'block';
        } else {
            typeSelect.value = type;
            document.getElementById('edit-custom-type-container').style.display = 'none';
        }
        
        document.getElementById('edit-event-description').value = description || '';
    });
    
    const modal = new bootstrap.Modal(document.getElementById('editEventModal'));
    modal.show();
}
async loadSubjectsForEditModal() {
    const subjectSelect = document.getElementById('edit-event-subject');
    if (!subjectSelect) return;
    
    // Clear existing options except the first one
    while (subjectSelect.options.length > 1) {
        subjectSelect.remove(1);
    }
    
    try {
        const response = await fetch('/backends/calendar-backend.php?action=get_subjects');
        const data = await response.json();
        
        if (data.success && data.subjects) {
            data.subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject;
                option.textContent = subject;
                subjectSelect.appendChild(option);
            });
        } else {
            // Add some default subjects if none are returned
            const defaultSubjects = ['Math', 'Science', 'English', 'History', 'Art', 'Music'];
            defaultSubjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject;
                option.textContent = subject;
                subjectSelect.appendChild(option);
            });
        }
        
        // Add custom option
        const customOption = document.createElement('option');
        customOption.value = 'Custom';
        customOption.textContent = 'Custom';
        subjectSelect.appendChild(customOption);
    } catch (error) {
        console.error('Error loading subjects for edit modal:', error);
    }
}

async updateEvent() {
    const eventId = document.getElementById('edit-event-id').value;
    const date = document.getElementById('edit-event-date').value;
    const subjectSelect = document.getElementById('edit-event-subject');
    const customSubject = document.getElementById('edit-custom-subject').value.trim();
    const subject = subjectSelect.value === 'Custom' ? customSubject : subjectSelect.value;
    const typeSelect = document.getElementById('edit-event-type');
    const customType = document.getElementById('edit-custom-type').value.trim();
    const type = typeSelect.value === 'Custom' ? customType : typeSelect.value;
    const description = document.getElementById('edit-event-description').value.trim();
    
    if (!subject) {
        this.showNotification('Please select or enter a subject.', 'warning');
        return;
    }
    
    if (!type) {
        this.showNotification('Please select or enter an event type.', 'warning');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'update_event');
    formData.append('calendar_id', this.currentCalendarId);
    formData.append('event_id', eventId);
    formData.append('date', date);
    formData.append('subject', subject);
    formData.append('type', type);
    formData.append('description', description);
    
    try {
        const response = await fetch('/backends/calendar-backend.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editEventModal'));
            if (modal) modal.hide();
            this.showNotification('Event updated successfully.', 'success');
            this.refreshDetailedView();
            
            // Refresh day events modal if open
            const dayModal = bootstrap.Modal.getInstance(document.getElementById('dayEventsModal'));
            if (dayModal && this.currentSelectedDate === date) {
                this.showDayEventsModal(date);
            }
        } else {
            this.showNotification('Error updating event: ' + data.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        this.showNotification('Failed to update event.', 'danger');
    }
}

    // View Management
    showSelectionView() {
        this.currentView = 'selection';
        this.currentCalendarId = null;
        
        document.getElementById('selection-view').classList.remove('d-none');
        document.getElementById('detailed-view').classList.add('d-none');
        
        // Update mobile navbar
        const mobileBackBtn = document.getElementById('mobile-back-btn');
        const mobileTitle = document.getElementById('mobile-navbar-title');
        const mobileActions = document.getElementById('mobile-navbar-actions');
        if (mobileBackBtn) mobileBackBtn.classList.add('d-none');
        if (mobileTitle) mobileTitle.textContent = 'Calendars';
        if (mobileActions) mobileActions.style.visibility = 'hidden';
        
        this.loadCalendars();
    }

    showDetailedView(calendarId) {
        this.currentView = 'detailed';
        this.currentCalendarId = calendarId;
        
        document.getElementById('selection-view').classList.add('d-none');
        document.getElementById('detailed-view').classList.remove('d-none');
        
        // Update mobile navbar
        const mobileBackBtn = document.getElementById('mobile-back-btn');
        const mobileTitle = document.getElementById('mobile-navbar-title');
        const mobileActions = document.getElementById('mobile-navbar-actions');
        const calendarData = this.calendarsData[calendarId];
        
        if (mobileBackBtn) mobileBackBtn.classList.remove('d-none');
        if (mobileTitle && calendarData) mobileTitle.textContent = calendarData.name;
        if (mobileActions) mobileActions.style.visibility = 'visible';
        
        this.refreshDetailedView();
    }

    refreshDetailedView() {
        if (this.currentView === 'detailed' && this.currentCalendarId) {
            const calendarData = this.calendarsData[this.currentCalendarId];
            if (calendarData) {
                document.getElementById('current-calendar-name').textContent = calendarData.name;
                document.getElementById('calendar-owner-info').textContent = `Owner: ${calendarData.owner_name || 'You'}`;
                
                const eventCount = Object.values(calendarData.events || {}).flat().length;
                const collaboratorCount = calendarData.collaborators ? calendarData.collaborators.length : 0;
                document.getElementById('calendar-stats').textContent = `${eventCount} events • ${collaboratorCount} collaborators`;
                
                // Show/hide management buttons based on permissions
                const isOwner = calendarData.owner === '<?php echo $_SESSION["id"]; ?>';
                const isCollaborator = calendarData.user_role === 'collaborator';
                
                document.getElementById('manage-collaborators').style.display = isOwner ? 'block' : 'none';
                document.getElementById('rename-calendar').style.display = (isOwner || isCollaborator) ? 'block' : 'none';
                document.getElementById('delete-calendar').style.display = isOwner ? 'block' : 'none';
            }
            this.refreshCalendar();
            this.loadTodayEvents();
            this.loadSubjects();
        }
    }

initCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;
    
    this.calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: false, 
        themeSystem: 'bootstrap5',
        height: 'auto',
        dayMaxEvents: 3,
        showNonCurrentDates: true,
        fixedWeekCount: false,
        firstDay: 0,
        
        dayCellDidMount: (info) => {
            const today = new Date();
            const cellDate = info.date;
            
            if (cellDate.getDate() === today.getDate() &&
                cellDate.getMonth() === today.getMonth() &&
                cellDate.getFullYear() === today.getFullYear()) {
                
                info.el.classList.remove('fc-day-today');
                
                info.el.style.backgroundColor = 'rgba(var(--bs-info-rgb), 0.15)';
                info.el.style.border = '2px solid var(--bs-info)';
                info.el.style.borderRadius = 'var(--bs-border-radius)';
                
                // Enhance day number styling
                const dayNumber = info.el.querySelector('.fc-daygrid-day-number');
                if (dayNumber) {
                    dayNumber.style.color = 'var(--bs-info)';
                    dayNumber.style.fontWeight = '700';
                    dayNumber.style.fontSize = '1.1em';
                }
            }
            
            // Clean styling for other days
            if (!info.el.classList.contains('fc-day-other')) {
                info.el.style.backgroundColor = '';
            }
        },
        
        events: (fetchInfo, successCallback) => {
            if (!this.currentCalendarId || !this.calendarsData[this.currentCalendarId]) {
                successCallback([]);
                return;
            }
            
            const calendarEvents = this.calendarsData[this.currentCalendarId].events || {};
            const events = [];
            
            Object.entries(calendarEvents).forEach(([date, dayEvents]) => {
                dayEvents.forEach(event => {
                    events.push({
                        id: event.id,
                        title: `${event.subject} - ${event.type}`,
                        start: date,
                        allDay: true,
                        backgroundColor: this.getEventColor(event.type),
                        borderColor: this.getEventColor(event.type),
                        textColor: '#fff',
                        classNames: ['shadow-sm'],
                        extendedProps: {
                            description: event.description,
                            subject: event.subject,
                            type: event.type,
                            date: date
                        }
                    });
                });
            });
            
            successCallback(events);
        },
        
        eventClick: (info) => {
            this.currentSelectedDate = info.event.startStr;
            this.showDayEventsModal(info.event.startStr);
        },
        
        dateClick: (info) => {
            this.currentSelectedDate = info.dateStr;
            this.showDayEventsModal(info.dateStr);
        },
        
        datesSet: () => {
            this.updateMonthDisplay();
        }
    });
    
    this.calendar.render();
    this.updateMonthDisplay();
}

    updateMonthDisplay() {
        if (!this.calendar) return;
        
        const currentDate = this.calendar.getDate();
        const monthYear = currentDate.toLocaleDateString('en-US', { 
            month: 'long', 
            year: 'numeric' 
        });
        document.getElementById('current-month').textContent = monthYear;
    }

    refreshCalendar() {
        if (this.calendar) {
            this.calendar.refetchEvents();
            this.updateMonthDisplay();
        }
    }

    // Event Listeners
    setupEventListeners() {
        // View switching
        document.getElementById('back-to-selection')?.addEventListener('click', () => this.showSelectionView());
        document.getElementById('mobile-back-btn')?.addEventListener('click', () => this.showSelectionView());
        
        // Calendar creation
        document.getElementById('create-first-calendar')?.addEventListener('click', () => this.showCreateCalendarModal());
        document.getElementById('save-calendar')?.addEventListener('click', () => this.createCalendar());
        
        // Calendar management
        document.getElementById('rename-calendar')?.addEventListener('click', () => this.showRenameCalendarPrompt());
        document.getElementById('delete-calendar')?.addEventListener('click', () => this.showDeleteCalendarPrompt());
        document.getElementById('manage-collaborators')?.addEventListener('click', () => this.showManageCollaboratorsModal());
        document.getElementById('mobile-manage-collaborators')?.addEventListener('click', () => this.showManageCollaboratorsModal());
        
        // Event management
        document.getElementById('save-event')?.addEventListener('click', () => this.addEvent());
        document.getElementById('add-event-to-day')?.addEventListener('click', () => {
            if (this.currentSelectedDate) {
                const modal = bootstrap.Modal.getInstance(document.getElementById('dayEventsModal'));
                if (modal) modal.hide();
                this.showAddEventModal(this.currentSelectedDate);
            }
        });
        
        document.addEventListener('click', (e) => {
            if (e.target.closest('.delete-day-event')) {
                const eventId = e.target.closest('button').dataset.eventId;
                const dateStr = window.calendarApp.currentSelectedDate;
                if (confirm('Are you sure you want to delete this event?')) {
                    window.calendarApp.deleteEvent(eventId, dateStr);
                }
            }
        });


        document.getElementById('user-search')?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                this.searchUsers();
            }
        });

        document.getElementById('calendar-name')?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('save-calendar').click();
            }
        });

        
        // Edit event form interactions
        document.getElementById('edit-event-subject')?.addEventListener('change', function() {
            document.getElementById('edit-custom-subject-container').style.display = 
                this.value === 'Custom' ? 'block' : 'none';
        });
        
        document.getElementById('edit-event-type')?.addEventListener('change', function() {
            document.getElementById('edit-custom-type-container').style.display = 
                this.value === 'Custom' ? 'block' : 'none';
        });
        
        // Update event button
        document.getElementById('update-event')?.addEventListener('click', () => this.updateEvent());
        
        // Calendar navigation
        document.getElementById('prev-month')?.addEventListener('click', () => this.calendar.prev());
        document.getElementById('next-month')?.addEventListener('click', () => this.calendar.next());
        document.getElementById('today-btn')?.addEventListener('click', () => {
            this.calendar.today();
            this.updateMonthDisplay();
        });
        
        // View type toggles
        document.getElementById('month-view')?.addEventListener('change', () => {
            this.calendar.changeView('dayGridMonth');
            this.updateMonthDisplay();
        });
        
        document.getElementById('week-view')?.addEventListener('change', () => {
            this.calendar.changeView('dayGridWeek');
            this.updateMonthDisplay();
        });
        
        // Form interactions
        document.getElementById('event-subject')?.addEventListener('change', function() {
            document.getElementById('custom-subject-container').style.display = 
                this.value === 'Custom' ? 'block' : 'none';
        });
        
        document.getElementById('event-type')?.addEventListener('change', function() {
            document.getElementById('custom-type-container').style.display = 
                this.value === 'Custom' ? 'block' : 'none';
        });
        
        // User search
        document.getElementById('search-users')?.addEventListener('click', () => this.searchUsers());
        
        document.getElementById('user-search')?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                this.searchUsers();
            }
        });


        this.setupModalCloseListeners();
    }

    setupModalCloseListeners() {
        const closeButtons = document.querySelectorAll('.btn-close, .btn-secondary[data-bs-dismiss="modal"]');
        
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal');
                if (modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) bsModal.hide();
                }
            });
        });
        
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
                document.body.style.overflow = 'auto';
                document.body.style.paddingRight = '0';
            });
        });
    }

    // Calendar Data Management
    async loadCalendars() {
        this.showLoader();
        
        try {
            const response = await fetch('/backends/calendar-backend.php?action=get_calendars');
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            if (data.success) {
                this.calendarsData = data.calendars || {};
                this.renderCalendarsList();
                this.loadPendingInvitesSection();
            } else {
                throw new Error(data.error || 'Unknown error');
            }
        } catch (error) {
            console.error('Error loading calendars:', error);
            this.showNotification('Failed to load calendars: ' + error.message, 'danger');
        } finally {
            this.hideLoader();
        }
    }

loadPendingInvitesSection() {
    const section = document.getElementById('pending-invites-section');
    const list = document.getElementById('pending-invites-list');
    
    if (!section || !list) return;

    const receivedInvites = [];

    // Iterate through all calendars to find pending invites
    Object.entries(this.calendarsData).forEach(([calendarId, calendarData]) => {
        // Check if user has pending role for this calendar
        if (calendarData.user_role === 'pending') {
            // Add calendar-level pending invite info
            receivedInvites.push({
                calendar_id: calendarId,
                calendar_name: calendarData.name || '(Unnamed Calendar)',
                role: 'pending',
                ...calendarData
            });
        }
    });

    if (receivedInvites.length === 0) {
        section.classList.add('d-none');
        return;
    }

    section.classList.remove('d-none');
    list.innerHTML = receivedInvites.map(invite => `
        <div class="invite-item">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>${invite.calendar_name}</strong>
                    <small class="text-muted d-block">Invited by ${invite.owner_name || 'Unknown'}</small>
                </div>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-success accept-invite" data-calendar-id="${invite.calendar_id}">
                        <i class="bi bi-check"></i> Accept
                    </button>
                    <button class="btn btn-sm btn-outline-danger decline-invite" data-calendar-id="${invite.calendar_id}">
                        <i class="bi bi-x"></i> Decline
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    document.querySelectorAll('.accept-invite').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const calendarId = e.target.closest('button').dataset.calendarId;
            this.acceptInvite(calendarId);
        });
    });

    document.querySelectorAll('.decline-invite').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const calendarId = e.target.closest('button').dataset.calendarId;
            this.declineInvite(calendarId);
        });
    });
}

renderCalendarsList() {
    const container = document.getElementById('calendars-list');
    const noCalendars = document.getElementById('no-calendars');
    const maxCalendars = document.getElementById('max-calendars');
    
    if (!container) return;
    
    container.innerHTML = '';
    
    // Count owned calendars
    let ownedCalendarsCount = 0;
    Object.values(this.calendarsData).forEach(calendarData => {
        if (calendarData.owner === '<?php echo $_SESSION["id"]; ?>') {
            ownedCalendarsCount++;
        }
    });
    
    const canAddCalendar = ownedCalendarsCount < 15;
    const hasCalendars = Object.keys(this.calendarsData).length > 0;
    
    // Show appropriate empty state
    if (noCalendars) noCalendars.classList.add('d-none');
    if (maxCalendars) maxCalendars.classList.add('d-none');
    
    if (!hasCalendars) {
        if (noCalendars && canAddCalendar) {
            noCalendars.classList.remove('d-none');
        } else if (maxCalendars && !canAddCalendar) {
            maxCalendars.classList.remove('d-none');
        }
        return;
    }
    
    if (!canAddCalendar && maxCalendars) {
        maxCalendars.classList.remove('d-none');
    }
    
    if (noCalendars) noCalendars.classList.add('d-none');
    
    Object.entries(this.calendarsData).forEach(([id, calendarData]) => {
        if (calendarData.user_role === 'pending') {
            return;
        }
        
        const col = document.createElement('div');
        col.className = 'col-md-6 col-lg-4';
        
        const isOwner = calendarData.owner === '<?php echo $_SESSION["id"]; ?>';
        const isCollaborator = calendarData.user_role === 'collaborator';
        const eventCount = Object.values(calendarData.events || {}).flat().length;
        const collaboratorCount = calendarData.collaborators ? calendarData.collaborators.length : 0;
        
        // Truncate calendar name if too long
        const calendarName = calendarData.name || 'Unnamed Calendar';
        const displayName = calendarName.length > 25 ? calendarName.substring(0, 25) + '...' : calendarName;
        
        col.innerHTML = `
            <div class="card calendar-card h-100" style="cursor: pointer;">
                <div class="card-body" data-calendar-id="${id}">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title" title="${calendarName}">${displayName}</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                    data-bs-toggle="dropdown" onclick="event.stopPropagation()">
                                <i class="bi bi-three-dots"></i>
                            </button>
                            <ul class="dropdown-menu">
                                ${isOwner ? `
                                    <li><a class="dropdown-item rename-calendar-btn" href="#" data-id="${id}">
                                        <i class="bi bi-pencil me-2"></i>Rename
                                    </a></li>
                                    <li><a class="dropdown-item delete-calendar-btn" href="#" data-id="${id}">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </a></li>
                                ` : ''}
                                ${isCollaborator ? `
                                    <li><a class="dropdown-item rename-calendar-btn" href="#" data-id="${id}">
                                        <i class="bi bi-pencil me-2"></i>Rename
                                    </a></li>
                                    <li><a class="dropdown-item leave-calendar-btn" href="#" data-id="${id}">
                                        <i class="bi bi-box-arrow-right me-2"></i>Leave
                                    </a></li>
                                ` : ''}
                            </ul>
                        </div>
                    </div>
                    <p class="card-text">
                        <small class="text-muted">
                            Owner: ${calendarData.owner_name || 'You'}
                        </small>
                    </p>
                    <span class="badge ${isOwner ? 'bg-warning text-dark' : 'bg-success'}">
                        ${isOwner ? 'Owner' : 'Collaborator'}
                    </span>
                </div>
            </div>
        `;
        container.appendChild(col);
        
        col.querySelector('.card-body').addEventListener('click', (e) => {
            if (!e.target.closest('.dropdown') && !e.target.closest('.dropdown-toggle')) {
                const calendarId = e.currentTarget.dataset.calendarId;
                this.showDetailedView(calendarId);
            }
        });
    });
    
    if (canAddCalendar) {
        const addCol = document.createElement('div');
        addCol.className = 'col-md-6 col-lg-4';
        addCol.innerHTML = `
            <div class="card h-100 border-dashed">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4"
                     style="cursor: pointer; min-height: 75px;" id="add-calendar-card">
                    <div class="text-muted">
                        <i class="bi bi-plus-circle display-4"></i>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(addCol);
        
        addCol.querySelector('#add-calendar-card').addEventListener('click', () => {
            this.showCreateCalendarModal();
        });
    }
    
    this.attachCalendarCardEventListeners();
}

attachCalendarCardEventListeners() {
    document.querySelectorAll('.rename-calendar-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const calendarId = e.target.closest('a').dataset.id;
            this.renameCalendarPrompt(calendarId);
        });
    });
    
    document.querySelectorAll('.delete-calendar-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const calendarId = e.target.closest('a').dataset.id;
            if (confirm('Are you sure you want to delete this calendar? This will remove all collaborators.')) {
                this.deleteCalendarRequest(calendarId);
            }
        });
    });
    
    document.querySelectorAll('.leave-calendar-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const calendarId = e.target.closest('a').dataset.id;
            if (confirm('Are you sure you want to leave this calendar?')) {
                this.leaveCalendar(calendarId);
            }
        });
    });
}
    // Calendar Creation and Management
showCreateCalendarModal() {
    const calendarNameInput = document.getElementById('calendar-name');
    if (calendarNameInput) {
        calendarNameInput.value = '';
        // Add maxlength attribute
        calendarNameInput.maxLength = 30;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('createCalendarModal'));
    modal.show();
    
    // Add input event listener to show character count
    if (calendarNameInput) {
        calendarNameInput.addEventListener('input', function() {
            const remaining = 30 - this.value.length;
            // You could add a character counter display here if needed
        });
    }
}
async createCalendar() {
    const calendarNameInput = document.getElementById('calendar-name');
    if (!calendarNameInput) return;
    
    const name = calendarNameInput.value.trim();
    
    if (!name) {
        this.showNotification('Please enter a calendar name.', 'warning');
        return;
    }
    
    // Enforce 30 character limit
    if (name.length > 30) {
        this.showNotification('Calendar name must be 30 characters or less.', 'warning');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'create_calendar');
    formData.append('name', name);
    
    // Get button reference and store original text BEFORE the try block
    const saveButton = document.getElementById('save-calendar');
    const originalText = saveButton.innerHTML;
    
    try {
        // Show loading state on button
        saveButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creating...';
        saveButton.disabled = true;
        
        const response = await fetch('/backends/calendar-backend.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('createCalendarModal'));
            if (modal) modal.hide();
            this.showNotification('Calendar created successfully.', 'success');
            await this.loadCalendars();
            
            // Reset the form
            calendarNameInput.value = '';
        } else {
            this.showNotification('Error creating calendar: ' + (data.error || 'Unknown error'), 'danger');
        }
    } catch (error) {
        console.error('Error creating calendar:', error);
        this.showNotification('Failed to create calendar: ' + error.message, 'danger');
    } finally {
        // Always restore button state, whether success or error
        if (saveButton) {
            saveButton.innerHTML = originalText;
            saveButton.disabled = false;
        }
    }
}
showRenameCalendarPrompt() {
    if (!this.currentCalendarId || !this.calendarsData[this.currentCalendarId]) return;
    
    const currentName = this.calendarsData[this.currentCalendarId].name;
    const newName = prompt(`Enter new calendar name (max 30 characters):`, currentName);
    if (newName && newName.trim()) {
        const trimmedName = newName.trim();
        if (trimmedName.length > 30) {
            this.showNotification('Calendar name must be 30 characters or less.', 'warning');
            return;
        }
        this.renameCalendarRequest(this.currentCalendarId, trimmedName);
    }
}

renameCalendarPrompt(calendarId) {
    if (!this.calendarsData[calendarId]) return;
    
    const currentName = this.calendarsData[calendarId].name;
    const newName = prompt(`Enter new calendar name (max 30 characters):`, currentName);
    if (newName && newName.trim()) {
        const trimmedName = newName.trim();
        if (trimmedName.length > 30) {
            // yes, this is protected in the backend.
            this.showNotification('Calendar name must be 30 characters or less.', 'warning');
            return;
        }
        this.renameCalendarRequest(calendarId, trimmedName);
    }
}

    async renameCalendarRequest(calendarId, newName) {
        const formData = new FormData();
        formData.append('action', 'rename_calendar');
        formData.append('calendar_id', calendarId);
        formData.append('new_name', newName);
        
        try {
            const response = await fetch('/backends/calendar-backend.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Calendar renamed successfully.', 'success');
                this.refreshDetailedView();
            } else {
                this.showNotification('Error renaming calendar: ' + data.error, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Failed to rename calendar', 'danger');
        }
    }

    showDeleteCalendarPrompt() {
        if (!this.currentCalendarId || !this.calendarsData[this.currentCalendarId]) return;
        
        if (confirm('Are you sure you want to delete this calendar? This will remove all collaborators and events.')) {
            this.deleteCalendarRequest(this.currentCalendarId);
        }
    }

    async deleteCalendarRequest(calendarId) {
        const formData = new FormData();
        formData.append('action', 'delete_calendar');
        formData.append('calendar_id', calendarId);
        
        try {
            const response = await fetch('/backends/calendar-backend.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Calendar deleted successfully.', 'success');
                if (this.currentCalendarId === calendarId) {
                    this.showSelectionView();
                }
                this.loadCalendars();
            } else {
                this.showNotification('Error deleting calendar: ' + data.error, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Failed to delete calendar.', 'danger');
        }
    }

    async leaveCalendar(calendarId) {
        if (confirm('Are you sure you want to leave this calendar? You will lose access to all events.')) {
            const formData = new FormData();
            formData.append('action', 'leave_calendar');
            formData.append('calendar_id', calendarId);
            
            try {
                const response = await fetch('/backends/calendar-backend.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification('Left calendar successfully.', 'success');
                    await this.loadCalendars();
                    
                    if (this.currentCalendarId === calendarId) {
                        this.showSelectionView();
                    }
                    
                    if (this.currentView === 'selection') {
                        this.renderCalendarsList();
                        this.loadPendingInvitesSection();
                    }
                } else {
                    this.showNotification('Error leaving calendar: ' + data.error, 'danger');
                }
            } catch (error) {
                console.error('Error:', error);
                this.showNotification('Failed to leave calendar.', 'danger');
            }
        }
    }

    // Event Management
showAddEventModal(date) {
    document.getElementById('event-date').value = date;
    document.getElementById('event-subject').value = '';
    document.getElementById('custom-subject').value = '';
    document.getElementById('event-type').value = 'Homework';
    document.getElementById('custom-type').value = '';
    document.getElementById('event-description').value = '';
    document.getElementById('custom-subject-container').style.display = 'none';
    document.getElementById('custom-type-container').style.display = 'none';
    
    // Reload subjects every time modal is opened
    this.loadSubjects();
    
    const modal = new bootstrap.Modal(document.getElementById('addEventModal'));
    modal.show();
}

async addEvent() {
    const date = document.getElementById('event-date').value;
    const subjectSelect = document.getElementById('event-subject');
    const customSubject = document.getElementById('custom-subject').value.trim();
    const subject = subjectSelect.value === 'Custom' ? customSubject : subjectSelect.value;
    const typeSelect = document.getElementById('event-type');
    const customType = document.getElementById('custom-type').value.trim();
    const type = typeSelect.value === 'Custom' ? customType : typeSelect.value;
    const description = document.getElementById('event-description').value.trim();
    
    if (!subject) {
        this.showNotification('Please select or enter a subject.', 'warning');
        return;
    }
    
    if (!type) {
        this.showNotification('Please select or enter an event type.', 'warning');
        return;
    }
    
    // ADD THIS DATE VALIDATION
    const eventDate = new Date(date + 'T00:00:00');
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const oneYearAgo = new Date(today);
    oneYearAgo.setFullYear(today.getFullYear() - 1);
    
    const fiveYearsFromNow = new Date(today);
    fiveYearsFromNow.setFullYear(today.getFullYear() + 5);
    
    if (eventDate < oneYearAgo) {
        this.showNotification('Events cannot be added more than 1 year in the past.', 'warning');
        return;
    }
    
    if (eventDate > fiveYearsFromNow) {
        this.showNotification('Events cannot be added more than 5 years in the future.', 'warning');
        return;
    }
    // END DATE VALIDATION
    
    const formData = new FormData();
    formData.append('action', 'add_event');
    formData.append('calendar_id', this.currentCalendarId);
    formData.append('date', date);
    formData.append('subject', subject);
    formData.append('type', type);
    formData.append('description', description);
    
    try {
        const response = await fetch('/backends/calendar-backend.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('addEventModal'));
            if (modal) modal.hide();
            this.showNotification('Event added successfully.', 'success');
            this.refreshDetailedView();
            
            // Refresh day events modal if open
            const dayModal = bootstrap.Modal.getInstance(document.getElementById('dayEventsModal'));
            if (dayModal && this.currentSelectedDate === date) {
                this.showDayEventsModal(date);
            }
        } else {
            this.showNotification('Error adding event: ' + data.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        this.showNotification('Failed to add event. You may not be connected to Wi-Fi.', 'danger');
    }
}

    // Day Events Modal
    showDayEventsModal(dateStr) {
        this.currentSelectedDate = dateStr;
        
        const modalTitle = document.getElementById('day-events-title');
        const eventsList = document.getElementById('day-events-list');
        const noEvents = document.getElementById('no-day-events');
        
        if (modalTitle) {
            modalTitle.innerHTML = `${this.formatDate(dateStr)}`;
        }
        
        if (!this.currentCalendarId || !this.calendarsData[this.currentCalendarId]) {
            eventsList.innerHTML = '';
            noEvents.classList.remove('d-none');
        } else {
            const dayEvents = this.calendarsData[this.currentCalendarId].events?.[dateStr] || [];
            
            if (dayEvents.length === 0) {
                eventsList.innerHTML = '';
                noEvents.classList.remove('d-none');
            } else {
                noEvents.classList.add('d-none');
                eventsList.innerHTML = dayEvents.map(event => `
                    <div class="event-item ${event.type} mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="fw-bold">${event.subject} <span class="small text-warning text-capitalize event-type2">${event.type}</span></div>
                                ${event.description ? `<div class="small mt-1">${event.description}</div>` : ''}
                                <div class="small text-muted">Added by ${this.getUserDisplayName(event.added_by)} ${event.added_at ? `on ${new Date(event.added_at * 1000).toLocaleDateString()}` : ''}</div>
                            </div>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-outline-primary edit-day-event" 
                                        data-event-id="${event.id}" 
                                        data-event-subject="${event.subject}" 
                                        data-event-type="${event.type}" 
                                        data-event-description="${event.description || ''}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-day-event" data-event-id="${event.id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
                
                // Add event listeners to edit buttons
                document.querySelectorAll('.edit-day-event').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const eventId = e.target.closest('button').dataset.eventId;
                        const subject = e.target.closest('button').dataset.eventSubject;
                        const type = e.target.closest('button').dataset.eventType;
                        const description = e.target.closest('button').dataset.eventDescription;
                        this.showEditEventModal(eventId, subject, type, description);
                    });
                });
            }
        }
        
        const modal = new bootstrap.Modal(document.getElementById('dayEventsModal'));
        modal.show();
    }

    async deleteEvent(eventId, dateStr = null) {
        const formData = new FormData();
        formData.append('action', 'delete_event');
        formData.append('calendar_id', this.currentCalendarId);
        formData.append('event_id', eventId);
        if (dateStr) {
            formData.append('date', dateStr);
        }
        
        try {
            const response = await fetch('/backends/calendar-backend.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Event deleted successfully.', 'success');
                await this.loadCalendars();
                this.refreshDetailedView();
                
                // Refresh day events modal if open
                const dayModal = bootstrap.Modal.getInstance(document.getElementById('dayEventsModal'));
                if (dayModal && this.currentSelectedDate) {
                    this.showDayEventsModal(this.currentSelectedDate);
                }
                
                // Refresh today's events preview
                this.loadTodayEvents();
            } else {
                this.showNotification('Error deleting event: ' + data.error, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Failed to delete event.', 'danger');
        }
    }

    loadTodayEvents() {
        if (!this.currentCalendarId || !this.calendarsData[this.currentCalendarId]) {
            const container = document.getElementById('today-events');
            if (container) {
                container.innerHTML = '<p class="text-muted mb-0">No calendar selected</p>';
            }
            return;
        }
        
        const today = new Date().toISOString().split('T')[0];
        const events = this.calendarsData[this.currentCalendarId].events?.[today] || [];
        const container = document.getElementById('today-events');
        const title = document.getElementById('today-events-title');
        
        if (!container) return;
        
        if (title) title.textContent = `Today's Events (${events.length})`;
        
        if (events.length === 0) {
            container.innerHTML = '<p class="text-muted mb-0">No events scheduled for today</p>';
            return;
        }
        
        container.innerHTML = events.map(event => `
            <div class="event-item ${event.type}">
                <div class="fw-bold">${event.subject}</div>
                <div class="small text-muted text-capitalize">${event.type}</div>
                ${event.description ? `<div class="small mt-1">${event.description}</div>` : ''}
                <div class="small text-muted">Added by ${this.getUserDisplayName(event.added_by)}</div>
            </div>
        `).join('');
    }

    // Subject Management
async loadSubjects() {
    const subjectSelect = document.getElementById('event-subject');
    if (!subjectSelect) return;
    
    // Clear existing options except the first one (placeholder)
    while (subjectSelect.options.length > 1) {
        subjectSelect.remove(1);
    }
    
    // Remove the flag check - always reload subjects when called
    // This ensures subjects are available after modals reopen
    
    try {
        const response = await fetch('/backends/calendar-backend.php?action=get_subjects');
        const data = await response.json();
        
        let subjects = [];
        
        if (data.success && data.subjects) {
            subjects = data.subjects;
        } else {
            // Add default subjects if none are returned
            subjects = ['Math', 'Science', 'English', 'History', 'Art', 'Music'];
        }
        
        const uniqueSubjects = [...new Set(subjects)];
        
        uniqueSubjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject;
            option.textContent = subject;
            subjectSelect.appendChild(option);
        });
        
        // Add custom option
        const customOption = document.createElement('option');
        customOption.value = 'Custom';
        customOption.textContent = 'Custom...';
        subjectSelect.appendChild(customOption);
        
    } catch (error) {
        console.error('Error loading subjects:', error);
        // Add fallback subjects on error
        const fallbackSubjects = ['Math', 'Science', 'English', 'History'];
        
        fallbackSubjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject;
            option.textContent = subject;
            subjectSelect.appendChild(option);
        });
        
        const customOption = document.createElement('option');
        customOption.value = 'Custom';
        customOption.textContent = 'Custom...';
        subjectSelect.appendChild(customOption);
    }
}
    // Collaborator Management
    showManageCollaboratorsModal() {
        document.getElementById('user-search').value = '';
        document.getElementById('user-results').innerHTML = '';
        this.loadCollaboratorsList();
        this.loadPendingInvites();
        
        const modal = new bootstrap.Modal(document.getElementById('manageCollaboratorsModal'));
        modal.show();
        
        const modalElement = document.getElementById('manageCollaboratorsModal');
        modalElement.addEventListener('hidden.bs.modal', function() {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.style.overflow = 'auto';
            document.body.style.paddingRight = '0';
        });
    }

    async searchUsers() {
        const query = document.getElementById('user-search').value.trim();
        
        if (!query) {
            this.showNotification('Please enter a search query.', 'warning');
            return;
        }
        
        if (query.length < 3) {
            this.showNotification('Your search query must be 3 characters or longer.', 'warning');
            return;
        }
        
        if (query.length > 25) {
            this.showNotification('Please enter a shorter search query.', 'warning');
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'search_users');
        formData.append('query', query);
        formData.append('calendar_id', this.currentCalendarId);
        
        try {
            const response = await fetch('/backends/calendar-backend.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            const resultsContainer = document.getElementById('user-results');
            if (!resultsContainer) return;
            
            if (data.success && data.users.length > 0) {
                resultsContainer.innerHTML = data.users.map(user => `
                    <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-1">
                        <div>
                            <strong>${user.name}</strong>
                            <small class="text-muted">(${user.id})</small>
                        </div>
                        <button class="btn btn-sm btn-primary invite-user" data-user-id="${user.id}">
                            Invite
                        </button>
                    </div>
                `).join('');
                
                document.querySelectorAll('.invite-user').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        this.inviteUser(e.target.closest('button').dataset.userId);
                    });
                });
            } else {
                resultsContainer.innerHTML = '<p class="text-muted">No users found</p>';
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('user-results').innerHTML = '<p class="text-danger">Error searching users</p>';
        }
    }

    loadCollaboratorsList() {
        const container = document.getElementById('collaborators-list');
        const calendar = this.calendarsData[this.currentCalendarId];
        
        if (!container) return;
        
        if (!calendar || !calendar.collaborators || calendar.collaborators.length === 0) {
            container.innerHTML = '<p class="text-muted">No collaborators yet</p>';
            return;
        }
        
        container.innerHTML = calendar.collaborators.map(collab => `
            <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-1">
                <div>
                    <strong>${collab.name}</strong>
                    <small class="text-muted">(${collab.id})</small>
                </div>
                ${calendar.owner === '<?php echo $_SESSION["id"]; ?>' ? `
                    <button class="btn btn-sm btn-outline-danger remove-collaborator" data-user-id="${collab.id}">
                        Remove
                    </button>
                ` : ''}
            </div>
        `).join('');
        
        document.querySelectorAll('.remove-collaborator').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.removeCollaborator(e.target.closest('button').dataset.userId);
            });
        });
    }

    loadPendingInvites() {
        const container = document.getElementById('pending-invites');
        const calendar = this.calendarsData[this.currentCalendarId];
        
        if (!container) return;
        
        let pendingInvites = [];
        
        if (calendar && calendar.pending_invites) {
            pendingInvites = calendar.pending_invites;
        } else {
            pendingInvites = [];
        }
        
        if (pendingInvites.length === 0) {
            container.innerHTML = '<p class="text-muted">No pending invites</p>';
            return;
        }
        
        container.innerHTML = pendingInvites.map(invite => `
            <div class="d-flex justify-content-between align-items-center p-2 border rounded mb-1">
                <div>
                    <strong>${invite.user_name || 'Unknown User'}</strong>
                    <small class="text-muted">(${invite.user_id || 'Unknown ID'})</small>
                    <small class="text-muted d-none">Invited: ${new Date(invite.invited_at * 1000).toLocaleDateString()}</small>
                </div>
                ${calendar.owner === '<?php echo $_SESSION["id"]; ?>' ? `
                    <button class="btn btn-sm btn-outline-warning cancel-invite" data-invite-id="${invite.id}" data-user-id="${invite.user_id}">
                        Cancel
                    </button>
                ` : ''}
            </div>
        `).join('');
        
        document.querySelectorAll('.cancel-invite').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const inviteId = e.target.closest('button').dataset.inviteId;
                const userId = e.target.closest('button').dataset.userId;
                this.cancelInvite(inviteId, userId);
            });
        });
    }

    async inviteUser(userId) {
        const formData = new FormData();
        formData.append('action', 'invite_user');
        formData.append('calendar_id', this.currentCalendarId);
        formData.append('user_id', userId);
        
        try {
            const response = await fetch('/backends/calendar-backend.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Invitation sent successfully.', 'success');
                await this.loadCalendars();
                this.refreshCollaboratorsModal();
                this.refreshDetailedView();
                this.refreshOpenModals();
            } else {
                this.showNotification('Error sending invitation: ' + data.error, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Failed to send invitation.', 'danger');
        }
    }

    async removeCollaborator(userId) {
        if (!confirm('Are you sure you want to remove this collaborator?')) {
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'remove_collaborator');
        formData.append('calendar_id', this.currentCalendarId);
        formData.append('user_id', userId);
        
        try {
            const response = await fetch('/backends/calendar-backend.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Collaborator removed successfully.', 'success');
                await this.loadCalendars();
                this.refreshCollaboratorsModal();
                this.refreshDetailedView();
                this.refreshOpenModals();
            } else {
                this.showNotification('Error removing collaborator: ' + data.error, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Failed to remove collaborator.', 'danger');
        }
    }

async acceptInvite(calendarId) {
    const formData = new FormData();
    formData.append('action', 'accept_invite');
    formData.append('calendar_id', calendarId);
    
    try {
        const response = await fetch('/backends/calendar-backend.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            this.showNotification('Invitation accepted successfully.', 'success');
            await this.loadCalendars();
            this.refreshOpenModals();
            if (this.currentView === 'selection') {
                this.renderCalendarsList();
                this.loadPendingInvitesSection();
            }
        } else {
            this.showNotification('Error accepting invitation: ' + data.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        this.showNotification('Failed to accept invitation.', 'danger');
    }
}

async declineInvite(calendarId) {
    const formData = new FormData();
    formData.append('action', 'decline_invite');
    formData.append('calendar_id', calendarId);
    
    try {
        const response = await fetch('/backends/calendar-backend.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            this.showNotification('Invitation declined.', 'info');
            await this.loadCalendars();
            this.refreshOpenModals();
            if (this.currentView === 'selection') {
                this.renderCalendarsList();
                this.loadPendingInvitesSection();
            }
        } else {
            this.showNotification('Error declining invitation: ' + data.error, 'danger');
        }
    } catch (error) {
        console.error('Error:', error);
        this.showNotification('Failed to decline invitation.', 'danger');
    }
}

    async cancelInvite(inviteId, userId = null) {
        const formData = new FormData();
        formData.append('action', 'cancel_invite');
        formData.append('calendar_id', this.currentCalendarId);
        formData.append('invite_id', inviteId);
        if (userId) {
            formData.append('user_id', userId);
        }
        
        try {
            const response = await fetch('/backends/calendar-backend.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Invitation canceled successfully.', 'success');
                await this.loadCalendars();
                this.refreshCollaboratorsModal();
                this.refreshDetailedView();
                this.refreshOpenModals();
            } else {
                this.showNotification('Error canceling invitation: ' + data.error, 'danger');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Failed to cancel invitation.', 'danger');
        }
    }

    refreshCollaboratorsModal() {
        document.getElementById('user-results').innerHTML = '';
        this.loadCollaboratorsList();
        this.loadPendingInvites();
    }
}

// Initialize the application when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.calendarApp = new CalendarApp();
});
</script>