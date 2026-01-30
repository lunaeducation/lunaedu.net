<?php
session_start();
include_once("_h.php");
require_once("_backend-libs.php");
?>

<style>
.attendance-page {
    margin: 0 auto;
}

.page-header {
    margin-bottom: 1.5rem;
}

.page-title {
    font-size: 1.75rem;
    font-weight: 600;
    margin-bottom: 0;
}

/* Month Navigation */
.month-nav-bar {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 8px;
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.current-month {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--bs-body-color);
}

.nav-buttons {
    display: flex;
    gap: 0.5rem;
}

.nav-btn {
    width: 36px;
    height: 36px;
    border-radius: 6px;
    border: 1px solid var(--bs-border-color);
    background: var(--bs-body-bg);
    color: var(--bs-body-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
}

.nav-btn:hover:not(:disabled) {
    background: var(--bs-secondary-bg);
    border-color: var(--bs-border-color);
}

.nav-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

/* Stats Cards */
.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 8px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-card:hover {
    border-color: var(--bs-border-color);
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.stat-icon.success {
    background: rgba(16, 185, 129, 0.1);
    color: #10b981;
}

.stat-icon.danger {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
}

.stat-icon.warning {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}

.stat-icon.info {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--bs-secondary);
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--bs-body-color);
}

/* Main Grid Layout */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.5rem;
}

/* Calendar */
.calendar-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 8px;
    overflow: hidden;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.calendar-header {
    display: contents;
}

.day-name {
    padding: 0.875rem;
    text-align: center;
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary);
    background: var(--bs-secondary-bg);
    border-bottom: 1px solid var(--bs-border-color);
}

.calendar-body {
    display: contents;
}

.day-cell {
    aspect-ratio: 1;
    padding: 0.75rem;
    border-right: 1px solid var(--bs-border-color);
    border-bottom: 1px solid var(--bs-border-color);
    cursor: pointer;
    transition: background 0.15s;
    display: flex;
    flex-direction: column;
    position: relative;
}

.day-cell:nth-child(7n) {
    border-right: none;
}

.day-cell.empty {
    background: var(--bs-tertiary-bg);
    cursor: default;
    opacity: 0.3;
}

.day-cell.weekend {
    background: var(--bs-secondary-bg);
}

.day-cell:hover:not(.empty):not(.weekend) {
    background: var(--bs-secondary-bg);
}

.day-cell.today {
    background: rgba(var(--bs-primary-rgb), 0.08);
    border: 2px solid var(--bs-primary);
}

.day-number {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.day-cell.today .day-number {
    color: var(--bs-primary);
}

.day-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    margin-top: auto;
}

.status-badge {
    padding: 0.2rem 0.45rem;
    border-radius: 4px;
    font-size: 0.65rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.absent,
.status-badge.unexcused-absence,
.status-badge.truancy {
    background: rgba(239, 68, 68, 0.15);
    color: #dc2626;
}

.status-badge.excused,
.status-badge.excused-tardy {
    background: rgba(245, 158, 11, 0.15);
    color: #d97706;
}

.status-badge.tardy,
.status-badge.unexcused-tardy {
    background: rgba(59, 130, 246, 0.15);
    color: #2563eb;
}

.status-badge.no-school {
    background: rgba(107, 114, 128, 0.15);
    color: #6b7280;
}

.event-text {
    font-size: 0.7rem;
    color: var(--bs-secondary);
    margin-top: 0.25rem;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

/* Sidebar */
.sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Events List */
.events-card {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 8px;
    overflow: hidden;
}

.card-header {
    padding: 0.875rem 1rem;
    border-bottom: 1px solid var(--bs-border-color);
    background: var(--bs-secondary-bg);
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-body {
    padding: 0.875rem;
}

.event-list {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    max-height: 400px;
    overflow-y: auto;
}

.event-item {
    padding: 0.75rem;
    background: var(--bs-secondary-bg);
    border-radius: 6px;
    border-left: 3px solid var(--bs-primary);
}

.event-date {
    font-weight: 600;
    color: var(--bs-body-color);
    font-size: 0.8rem;
    margin-bottom: 0.25rem;
}

.event-name {
    font-size: 0.8rem;
    color: var(--bs-body-color);
}

.event-type {
    font-size: 0.7rem;
    color: var(--bs-secondary);
    margin-top: 0.25rem;
}

.no-events {
    text-align: center;
    padding: 1.5rem 1rem;
    color: var(--bs-secondary);
}

/* Legend */
.legend-grid {
    display: grid;
    gap: 0.625rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    padding: 0.625rem;
    background: var(--bs-secondary-bg);
    border-radius: 6px;
}

.legend-color {
    width: 18px;
    height: 18px;
    border-radius: 4px;
    flex-shrink: 0;
}

.legend-label {
    font-size: 0.8rem;
    color: var(--bs-body-color);
}

/* Modal */
.modal-header {
    background: var(--bs-secondary-bg);
    border-bottom: 1px solid var(--bs-border-color);
}

.modal-body {
    padding: 1.5rem;
}

.detail-section {
    margin-bottom: 1.5rem;
}

.detail-section:last-child {
    margin-bottom: 0;
}

.detail-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--bs-secondary);
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1rem;
    color: var(--bs-body-color);
}

.detail-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .stats-section {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .day-cell {
        padding: 0.5rem;
    }
    
    .day-number {
        font-size: 0.85rem;
    }
    
    .event-text {
        display: none;
    }
}

@media (max-width: 576px) {
    .month-nav-bar {
        padding: 0.875rem 1rem;
    }
    
    .current-month {
        font-size: 1.125rem;
    }
    
    .stats-section {
        grid-template-columns: 1fr;
    }
    
    .day-cell {
        padding: 0.375rem;
    }
    
    .day-name {
        padding: 0.625rem 0.375rem;
        font-size: 0.7rem;
    }
}
</style>

<div class="attendance-page">
    <!-- Loading -->
    <div id="loading" class="text-center py-5">
        <div class="spinner-border" style="width: 3rem; height: 3rem;"></div>
    </div>

    <!-- Error -->
    <div id="error" class="d-none">
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <span id="error-msg"></span>
        </div>
    </div>

    <!-- Main Content -->
    <div id="content" class="d-none">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">Attendance</h1>
        </div>

        <!-- Month Navigation -->
        <div class="month-nav-bar">
            <div class="current-month" id="month-name">Loading...</div>
            <div class="nav-buttons">
                <button class="nav-btn" id="prev-btn">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="nav-btn" id="next-btn">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-section d-none">
            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Present Days</div>
                    <div class="stat-value" id="stat-present">0</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon danger">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Absent Days</div>
                    <div class="stat-value" id="stat-absent">0</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Tardy Days</div>
                    <div class="stat-value" id="stat-tardy">0</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="bi bi-graph-up"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-label">Attendance Rate</div>
                    <div class="stat-value" id="stat-rate">0%</div>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="content-grid">
            <!-- Calendar -->
            <div class="calendar-card">
                <div class="calendar-grid">
                    <div class="calendar-header">
                        <div class="day-name">SUN</div>
                        <div class="day-name">MON</div>
                        <div class="day-name">TUE</div>
                        <div class="day-name">WED</div>
                        <div class="day-name">THU</div>
                        <div class="day-name">FRI</div>
                        <div class="day-name">SAT</div>
                    </div>
                    <div class="calendar-body" id="calendar-days"></div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Events -->
                <div class="events-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-calendar-event"></i>
                            Special Events
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="event-list" id="event-list">
                            <div class="no-events">
                                <i class="bi bi-calendar-x" style="font-size: 2rem; opacity: 0.3;"></i>
                                <p class="mt-2 mb-0">No special events this month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="events-card d-none">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-info-circle"></i>
                            Status Legend
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="legend-grid">
                            <div class="legend-item">
                                <div class="legend-color" style="background: rgba(239, 68, 68, 0.15); border: 2px solid #dc2626;"></div>
                                <div class="legend-label">Absent</div>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: rgba(245, 158, 11, 0.15); border: 2px solid #d97706;"></div>
                                <div class="legend-label">Excused</div>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: rgba(59, 130, 246, 0.15); border: 2px solid #2563eb;"></div>
                                <div class="legend-label">Tardy</div>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color" style="background: rgba(107, 114, 128, 0.15); border: 2px solid #6b7280;"></div>
                                <div class="legend-label">No School</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Day Detail Modal -->
<div class="modal fade" id="dayModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modal-body"></div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    let currentData = null;
    let currentMonth = null;

    init();

    function init() {
        setupEventListeners();
        loadAttendance();
    }

    function setupEventListeners() {
        document.getElementById('prev-btn').addEventListener('click', () => navigate('prev'));
        document.getElementById('next-btn').addEventListener('click', () => navigate('next'));
    }

    async function loadAttendance(month = null) {
        showLoading();
        hideError();

        try {
            const url = month 
                ? `/backends/attendance-backend.php?month=${encodeURIComponent(month)}`
                : '/backends/attendance-backend.php';

            const response = await fetch(url);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const data = await response.json();
            if (!data || !data.days) throw new Error('Invalid data');

            currentData = data;
            currentMonth = data.month;
            render();

        } catch (error) {
            console.error('Error:', error);
            showError('Failed to load attendance data');
        }
    }

    function navigate(direction) {
        if (!currentMonth) return;

        const [monthName, year] = currentMonth.split(' ');
        const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        
        let monthIndex = months.indexOf(monthName);
        let newYear = parseInt(year);

        if (direction === 'prev') {
            monthIndex--;
            if (monthIndex < 0) {
                monthIndex = 11;
                newYear--;
            }
        } else {
            monthIndex++;
            if (monthIndex > 11) {
                monthIndex = 0;
                newYear++;
            }
        }

        const newMonth = `${newYear}-${String(monthIndex + 1).padStart(2, '0')}`;
        loadAttendance(newMonth);
    }

    function render() {
        hideLoading();
        showContent();

        document.getElementById('month-name').textContent = currentMonth;

        const stats = currentData.statistics || {};
        document.getElementById('stat-present').textContent = stats.present_days || 0;
        document.getElementById('stat-absent').textContent = stats.absent_days || 0;
        document.getElementById('stat-tardy').textContent = stats.tardy_days || 0;
        document.getElementById('stat-rate').textContent = stats.attendance_rate ? `${stats.attendance_rate}%` : '0%';

        renderCalendar();
        renderEventsList();
        updateNavButtons();
    }

    function renderCalendar() {
        const container = document.getElementById('calendar-days');
        container.innerHTML = '';

        const days = currentData.days || [];
        if (!days.length) return;

        const weekdayMap = { 'Sun': 0, 'Mon': 1, 'Tue': 2, 'Wed': 3, 'Thu': 4, 'Fri': 5, 'Sat': 6 };
        const startPos = weekdayMap[days[0].weekday] || 0;

        for (let i = 0; i < startPos; i++) {
            const empty = document.createElement('div');
            empty.className = 'day-cell empty';
            container.appendChild(empty);
        }

        days.forEach(day => {
            container.appendChild(createDayElement(day));
        });
    }

    function createDayElement(day) {
        const el = document.createElement('div');
        el.className = 'day-cell';

        if (day.is_today) el.classList.add('today');
        if (day.is_weekend) el.classList.add('weekend');

        if (day.background_color && day.background_color !== '#CCCCCC') {
            el.style.backgroundColor = day.background_color;
            const textColor = getContrastColor(day.background_color);
            el.style.color = textColor;
        }

        const num = document.createElement('div');
        num.className = 'day-number';
        num.textContent = day.day;
        el.appendChild(num);

        if (day.special_event && !day.is_weekend) {
            const event = document.createElement('div');
            event.className = 'event-text';
            event.textContent = day.special_event;
            el.appendChild(event);
        }

        const badgeContainer = document.createElement('div');
        badgeContainer.className = 'day-badges';

        if (day.status && day.status !== 'present' && day.status !== 'weekend') {
            const badge = document.createElement('div');
            badge.className = `status-badge ${day.status.replace(/_/g, '-')}`;
            badge.textContent = formatStatusShort(day.status);
            badgeContainer.appendChild(badge);
        }

        el.appendChild(badgeContainer);
        el.addEventListener('click', () => showDayModal(day));

        return el;
    }

    function renderEventsList() {
        const container = document.getElementById('event-list');
        const days = currentData.days || [];
        
        // Collect all events with their dates (exclude "Weekend Event")
        const eventDays = [];
        days.forEach(day => {
            if (day.special_event && day.special_event !== 'Weekend Event') {
                eventDays.push({
                    day: day.day,
                    weekday: day.weekday,
                    event: day.special_event,
                    status: day.status,
                    isWeekend: day.is_weekend
                });
            }
        });

        if (eventDays.length === 0) {
            container.innerHTML = `
                <div class="no-events">
                    <i class="bi bi-calendar-x" style="font-size: 2rem; opacity: 0.3;"></i>
                    <p class="mt-2 mb-0">No special events this month</p>
                </div>
            `;
            return;
        }

        container.innerHTML = '';
        eventDays.forEach(item => {
            const eventItem = document.createElement('div');
            eventItem.className = 'event-item';
            
            const borderColor = item.status === 'no_school' ? '#6b7280' : 
                               item.isWeekend ? '#6b7280' : 'var(--bs-primary)';
            eventItem.style.borderLeftColor = borderColor;
            
            eventItem.innerHTML = `
                <div class="event-date">${item.weekday}, ${currentMonth.split(' ')[0]} ${item.day}</div>
                <div class="event-name">${item.event}</div>
                ${item.status !== 'present' ? `<div class="event-type">${formatStatus(item.status)}</div>` : ''}
            `;
            
            container.appendChild(eventItem);
        });
    }

    function showDayModal(day) {
        const modal = new bootstrap.Modal(document.getElementById('dayModal'));
        
        document.getElementById('modal-title').textContent = `${day.weekday}, ${currentMonth.split(' ')[0]} ${day.day}`;
        
        let html = '';

        html += `
            <div class="detail-section">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="detail-badge status-badge ${day.status}">${formatStatus(day.status)}</span>
                </div>
            </div>
        `;

        if (day.special_event) {
            html += `
                <div class="detail-section">
                    <div class="detail-label">Event</div>
                    <div class="detail-value">${day.special_event}</div>
                </div>
            `;
        }

        if (day.background_color && day.background_color !== '#CCCCCC') {
            html += `
                <div class="detail-section d-none">
                    <div class="detail-label">Color Code</div>
                    <div class="detail-value">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 24px; height: 24px; background: ${day.background_color}; border-radius: 4px; border: 1px solid var(--bs-border-color);"></div>
                            <span>${day.background_color}</span>
                        </div>
                    </div>
                </div>
            `;
        }

        document.getElementById('modal-body').innerHTML = html;
        modal.show();
    }

    function formatStatus(status) {
        const map = {
            'present': 'Present',
            'absent': 'Absent',
            'excused': 'Excused',
            'tardy': 'Tardy',
            'unexcused_absence': 'Unexcused',
            'truancy': 'Truancy',
            'excused_tardy': 'Excused Tardy',
            'unexcused_tardy': 'Unexcused Tardy',
            'suspended': 'Suspended',
            'multiple_codes': 'Multiple',
            'weekend': 'Weekend',
            'no_school': 'No School'
        };
        return map[status] || status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function formatStatusShort(status) {
        const map = {
            'absent': 'ABS',
            'excused': 'EXC',
            'tardy': 'TARD',
            'unexcused_absence': 'UNA',
            'truancy': 'TRU',
            'excused_tardy': 'ETRD',
            'unexcused_tardy': 'UTRD',
            'suspended': 'SUSP',
            'multiple_codes': 'MULT',
            'no_school': 'NS'
        };
        return map[status] || status.substring(0, 4).toUpperCase();
    }

    function getContrastColor(hex) {
        const r = parseInt(hex.slice(1, 3), 16);
        const g = parseInt(hex.slice(3, 5), 16);
        const b = parseInt(hex.slice(5, 7), 16);
        return (0.299 * r + 0.587 * g + 0.114 * b) / 255 > 0.5 ? '#000000' : '#FFFFFF';
    }

    function updateNavButtons() {
        const [monthName] = currentMonth.split(' ');
        const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        const month = months.indexOf(monthName) + 1;
        
        document.getElementById('prev-btn').disabled = month === 8;
        document.getElementById('next-btn').disabled = month === 5;
    }

    function showLoading() {
        document.getElementById('loading').classList.remove('d-none');
        document.getElementById('content').classList.add('d-none');
    }

    function hideLoading() {
        document.getElementById('loading').classList.add('d-none');
    }

    function showContent() {
        document.getElementById('content').classList.remove('d-none');
    }

    function showError(msg) {
        document.getElementById('error-msg').textContent = msg;
        document.getElementById('error').classList.remove('d-none');
        hideLoading();
    }

    function hideError() {
        document.getElementById('error').classList.add('d-none');
    }
})();
</script>

<?php include_once("_f.php"); ?>