<?php
require_once 'helpers.php';

function startApiSession() {
    if (session_status() === PHP_SESSION_NONE) {
        $sessionName = 'LUNA_API_' . md5($_SERVER['REMOTE_ADDR'] . ($_GET['user'] ?? '') . ($_GET['link'] ?? ''));
        session_name($sessionName);
        session_start();
    }
}

function getName() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    $providedCookies = $_GET['cookies'] ?? null;
    
    $cookieFile = sys_get_temp_dir() . '/hac_cookies_' . md5($username . $link . session_id()) . '.txt';
    
    $ch = login($username, $password, $link, $providedCookies);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Classes/Classwork';
    $html = makeAuthenticatedRequest($url, $ch);
    
    $freshCookies = getCookiesFromHandle($ch, $cookieFile);
    
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $nameElement = $xpath->query("//div[contains(@class, 'sg-banner-menu-container')]//span");
    if ($nameElement->length > 0) {
        $name = trim($nameElement->item(0)->nodeValue);
        $result = ['name' => $name];
        
        if ($freshCookies && $providedCookies !== $freshCookies) {
            $result['_fresh_cookies'] = $freshCookies;
        }
        
        echo json_encode($result);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to extract name']);
    }
}
function getAttendance() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    $requestedMonth = $_GET['month'] ?? null;
    
    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Attendance/MonthlyView.aspx';
    
    $html = makeAuthenticatedRequest($url, $ch);
    
    $navigateToMonth = function($html, $ch, $url, $direction) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        if ($direction === 'prev') {
            $navLink = $xpath->query('//a[@title="Go to the previous month"]');
            
            if ($navLink->length === 0) {
                $navLink = $xpath->query('//a[contains(@title, "previous") or contains(@title, "Go to the previous month")]');
            }
        } else {
            $navLink = $xpath->query('//a[@title="Go to the next month"]');
            
            if ($navLink->length === 0) {
                $navLink = $xpath->query('//a[contains(@title, "next") or contains(@title, "Go to the next month")]');
            }
        }
        
        if ($navLink->length === 0) {
            $navLink = $xpath->query('//table[contains(@class, "sg-asp-calendar-header")]//a');
            
            if ($navLink->length >= 2) {
                if ($direction === 'prev') {
                    $navLink = $xpath->query('(//table[contains(@class, "sg-asp-calendar-header")]//a)[1]');
                } else {
                    $navLink = $xpath->query('(//table[contains(@class, "sg-asp-calendar-header")]//a)[last()]');
                }
            }
        }
        
        if ($navLink->length === 0) {
            $allLinks = $xpath->query('//a[contains(@href, "__doPostBack")]');
            if ($allLinks->length >= 2) {
                if ($direction === 'prev') {
                    $navLink = $xpath->query('(//a[contains(@href, "__doPostBack")])[1]');
                } else {
                    $navLink = $xpath->query('(//a[contains(@href, "__doPostBack")])[last()]');
                }
            }
        }
        
        if ($navLink->length === 0) {
            return null;
        }
        
        $href = $navLink->item(0)->getAttribute('href');
        
        if (preg_match("/__doPostBack\('([^']+)','([^']+)'\)/", $href, $matches)) {
            $eventTarget = $matches[1];
            $eventArgument = $matches[2];
            
            // Get form data
            $viewState = $xpath->query('//input[@id="__VIEWSTATE"]')->item(0);
            $viewStateGenerator = $xpath->query('//input[@id="__VIEWSTATEGENERATOR"]')->item(0);
            $eventValidation = $xpath->query('//input[@id="__EVENTVALIDATION"]')->item(0);
            
            if ($viewState && $viewStateGenerator && $eventValidation) {
                $postData = [
                    '__EVENTTARGET' => $eventTarget,
                    '__EVENTARGUMENT' => $eventArgument,
                    '__VIEWSTATE' => $viewState->getAttribute('value'),
                    '__VIEWSTATEGENERATOR' => $viewStateGenerator->getAttribute('value'),
                    '__EVENTVALIDATION' => $eventValidation->getAttribute('value'),
                ];
                
                // Make POST request
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_COOKIEFILE, '');
                curl_setopt($ch, CURLOPT_COOKIEJAR, '');
                
                $response = curl_exec($ch);
                if (curl_errno($ch)) {
                    return null;
                }
                return $response;
            }
        }
        
        return null;
    };
    
    $getMonthFromHTML = function($html) {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        $month = 'Unknown';
        $monthElement = $xpath->query('//table[@id="plnMain_cldAttendance"]//tr[1]//td[2]');
        if ($monthElement->length > 0) {
            $monthText = trim($monthElement->item(0)->textContent);
            $month = preg_replace('/[^A-Za-z0-9\s]/', '', $monthText);
            $month = trim($month);
        }
        return $month;
    };
    
    $initialMonth = $getMonthFromHTML($html);
    
    $debugInfo = ['initial_month' => $initialMonth];
    
    if ($requestedMonth === 'next') {
        $html = $navigateToMonth($html, $ch, $url, 'next');
        $debugInfo['action'] = 'navigated next';
    } elseif ($requestedMonth === 'prev') {
        $html = $navigateToMonth($html, $ch, $url, 'prev');
        $debugInfo['action'] = 'navigated prev';
    } elseif ($requestedMonth && $requestedMonth !== 'current') {
        $targetDate = DateTime::createFromFormat('Y-m-d', $requestedMonth . '-01');
        if (!$targetDate) {
            curl_close($ch);
            http_response_code(400);
            echo json_encode(['error' => 'Invalid month format. Use YYYY-MM (e.g., 2025-11)']);
            return;
        }
        
        $currentDate = DateTime::createFromFormat('F Y', $initialMonth);
        if (!$currentDate) {
            curl_close($ch);
            http_response_code(500);
            echo json_encode(['error' => 'Could not parse current month from page: ' . $initialMonth]);
            return;
        }
        
        $currentYear = (int)$currentDate->format('Y');
        $currentMonthNum = (int)$currentDate->format('n'); // n = 1-12
        $targetYear = (int)$targetDate->format('Y');
        $targetMonthNum = (int)$targetDate->format('n'); // n = 1-12
        
        $debugInfo['current_date'] = $currentDate->format('Y-m');
        $debugInfo['target_date'] = $targetDate->format('Y-m');
        $debugInfo['current_year'] = $currentYear;
        $debugInfo['current_month_num'] = $currentMonthNum;
        $debugInfo['target_year'] = $targetYear;
        $debugInfo['target_month_num'] = $targetMonthNum;
        
        $currentTotalMonths = ($currentYear * 12) + $currentMonthNum;
        $targetTotalMonths = ($targetYear * 12) + $targetMonthNum;
        $monthsDiff = $targetTotalMonths - $currentTotalMonths;
        
        $debugInfo['months_diff'] = $monthsDiff;
        
        if ($monthsDiff > 0) {
            $debugInfo['direction'] = 'forward';
            $debugInfo['steps'] = $monthsDiff;
            
            for ($i = 0; $i < $monthsDiff; $i++) {
                $newHtml = $navigateToMonth($html, $ch, $url, 'next');
                if ($newHtml) {
                    $html = $newHtml;
                    $debugInfo['step_' . ($i + 1)] = 'success';
                } else {
                    $debugInfo['step_' . ($i + 1)] = 'failed';
                    break;
                }
            }
        } elseif ($monthsDiff < 0) {
            $debugInfo['direction'] = 'backward';
            $debugInfo['steps'] = abs($monthsDiff);
            
            for ($i = 0; $i < abs($monthsDiff); $i++) {
                $newHtml = $navigateToMonth($html, $ch, $url, 'prev');
                if ($newHtml) {
                    $html = $newHtml;
                    $debugInfo['step_' . ($i + 1)] = 'success';
                } else {
                    $debugInfo['step_' . ($i + 1)] = 'failed';
                    break;
                }
            }
        } else {
            $debugInfo['direction'] = 'none (already on target month)';
        }
    }
    
    curl_close($ch);
    
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $currentMonth = 'Unknown';
    $monthElement = $xpath->query('//table[@id="plnMain_cldAttendance"]//tr[1]//td[2]');
    if ($monthElement->length > 0) {
        $monthText = trim($monthElement->item(0)->textContent);
        $currentMonth = preg_replace('/[^A-Za-z0-9\s]/', '', $monthText);
        $currentMonth = trim($currentMonth);
    }
    
    $debugInfo['final_month'] = $currentMonth;
    
    $calendarTable = $xpath->query('//table[@id="plnMain_cldAttendance"]');
    
    if ($calendarTable->length === 0) {
        http_response_code(500);
        echo json_encode(['error' => 'Attendance calendar not found']);
        return;
    }
    
    $dayHeaders = [];
    $headerRow = $xpath->query('//table[@id="plnMain_cldAttendance"]//tr[2]//th');
    foreach ($headerRow as $th) {
        $dayHeaders[] = trim($th->textContent);
    }
    
    $days = [];
    $weekRows = $xpath->query('//table[@id="plnMain_cldAttendance"]//tr[position() > 2]');
    
    foreach ($weekRows as $rowIndex => $row) {
        $dayCells = $xpath->query('.//td', $row);
        
        foreach ($dayCells as $cellIndex => $cell) {
            $dayText = trim($cell->textContent);
            
            if (empty($dayText) || !is_numeric($dayText)) {
                continue;
            }
            
            $isWeekend = false;
            if (isset($dayHeaders[$cellIndex])) {
                $weekday = $dayHeaders[$cellIndex];
                $isWeekend = in_array($weekday, ['Sat', 'Sun']);
            }
            
            $style = $cell->getAttribute('style');
            $class = $cell->getAttribute('class');
            
            $backgroundColor = '';
            if (preg_match('/background-color:\s*([^;]+)/i', $style, $matches)) {
                $backgroundColor = trim($matches[1]);
            }
            
            if (empty($backgroundColor) && preg_match('/sg-asp-calendar-today/', $class)) {
                // Today cell might have special styling
            }
            
            $isToday = strpos($class, 'sg-asp-calendar-today') !== false;
            
            $title = $cell->getAttribute('title');
            
            $status = 'present'; // Default
            $isSpecialDay = false;
            $specialEvent = null;
            
            if ($isWeekend) {
                $status = 'weekend';
                if ($title || $backgroundColor === '#CCCCCC' || $backgroundColor === '#cccccc') {
                    $isSpecialDay = true;
                    $specialEvent = $title ?: 'Weekend Event';
                    if ($title && strpos($title, 'School Closed') !== false) {
                        $status = 'no_school';
                    }
                }
            } 

            elseif ($backgroundColor === '#CCCCCC' || $backgroundColor === '#cccccc') {
                $status = 'no_school';
                $isSpecialDay = true;
                $specialEvent = $title ?: 'School Closed';
            }
            elseif ($backgroundColor) {
                $isSpecialDay = true;
                $specialEvent = $title ?: 'Special Attendance';
                
                // todo: fetch from key, not hard coded
                $colorStatusMap = [
                    '#006600' => 'excused',       // Green - College Visit/Military Visit
                    '#00cc00' => 'excused',       // Light Green - Various excused
                    '#0000cc' => 'excused',       // Blue - Doctor Note
                    '#00cccc' => 'excused_tardy', // Cyan - Excused Late/Early
                    '#660000' => 'unexcused_absence', // Dark Red - Unexcused
                    '#ff0000' => 'truancy',       // Red - Truancy
                    '#ffff00' => 'unexcused_tardy', // Yellow - Unexcused Late
                    '#ff6600' => 'unexcused_absence', // Orange - SYA Unexcused
                    '#666666' => 'suspended',     // Dark Gray - Suspension
                    '#FFCC99' => 'multiple_codes', // Peach - Multiple Codes
                    '#ffcc99' => 'multiple_codes', // Peach (lowercase)
                ];
                
                $status = $colorStatusMap[strtolower($backgroundColor)] ?? 'special';
            }

            elseif ($title) {
                $isSpecialDay = true;
                $specialEvent = $title;
                $status = 'special';
            }
            
            $days[] = [
                'day' => (int)$dayText,
                'weekday' => $dayHeaders[$cellIndex] ?? 'Unknown',
                'status' => $status,
                'is_today' => $isToday,
                'is_weekend' => $isWeekend,
                'is_special_day' => $isSpecialDay,
                'special_event' => $specialEvent,
                'background_color' => $backgroundColor,
                'css_class' => $class
            ];
        }
    }
    
    $legend = [];
    $legendDivs = $xpath->query('//div[contains(@class, "sg-width-38em")]');
    
    foreach ($legendDivs as $div) {
        $spans = $xpath->query('.//span', $div);
        if ($spans->length >= 2) {
            $colorSpan = $spans->item(0);
            $textSpan = $spans->item(1);
            
            $style = $colorSpan->getAttribute('style');
            if (preg_match('/background-color:\s*([^;]+)/', $style, $matches)) {
                $color = trim($matches[1]);
                $description = trim($textSpan->textContent);
                
                $legend[$color] = $description;
            }
        }
    }
    
    $totalDays = count($days);
    $presentDays = 0;
    $absentDays = 0;
    $excusedDays = 0;
    $tardyDays = 0;
    $specialEventDays = 0;
    
    foreach ($days as $day) {
        switch ($day['status']) {
            case 'present':
                $presentDays++;
                break;
            case 'unexcused_absence':
            case 'truancy':
            case 'suspended':
                $absentDays++;
                break;
            case 'excused':
            case 'excused_tardy':
                $excusedDays++;
                break;
            case 'unexcused_tardy':
                $tardyDays++;
                break;
        }
        
        if ($day['is_special_day']) {
            $specialEventDays++;
        }
    }
    
    $specialEvents = [];
    foreach ($days as $day) {
        if ($day['is_special_day'] && $day['special_event']) {
            $event = $day['special_event'];
            if (!isset($specialEvents[$event])) {
                $specialEvents[$event] = [];
            }
            $specialEvents[$event][] = $day['day'];
        }
    }
    
    $formattedEvents = [];
    foreach ($specialEvents as $event => $eventDays) {
        $formattedEvents[] = [
            'event' => $event,
            'days' => $eventDays,
            'count' => count($eventDays)
        ];
    }
    
    $canNavigatePrev = false;
    $canNavigateNext = false;
    
    $prevLinks = $xpath->query('//a[@title="Go to the previous month"]');
    if ($prevLinks->length === 0) {
        $prevLinks = $xpath->query('//a[contains(@title, "previous") or contains(@title, "Go to the previous month")]');
    }
    
    $nextLinks = $xpath->query('//a[@title="Go to the next month"]');
    if ($nextLinks->length === 0) {
        $nextLinks = $xpath->query('//a[contains(@title, "next") or contains(@title, "Go to the next month")]');
    }
    
    $canNavigatePrev = $prevLinks->length > 0;
    $canNavigateNext = $nextLinks->length > 0;
    
    $requestedMonthMatched = false;
    if ($requestedMonth && $requestedMonth !== 'current' && $requestedMonth !== 'next' && $requestedMonth !== 'prev') {
        $targetDate = DateTime::createFromFormat('Y-m', $requestedMonth);
        $currentDate = DateTime::createFromFormat('F Y', $currentMonth);
        
        if ($targetDate && $currentDate) {
            $requestedMonthMatched = 
                $targetDate->format('Y-m') === $currentDate->format('Y-m');
        }
    }
    
    $result = [
        'month' => $currentMonth,
        'requested_month' => $requestedMonth ?: 'current',
        'month_matched' => $requestedMonthMatched,
        'current_date' => date('Y-m-d'),
        'days' => $days,
        'statistics' => [
            'total_days' => $totalDays,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'excused_days' => $excusedDays,
            'tardy_days' => $tardyDays,
            'special_event_days' => $specialEventDays,
            'attendance_rate' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0
        ],
        'special_events' => $formattedEvents,
        'legend' => $legend,
        'navigation' => [
            'current' => $currentMonth,
            'can_navigate' => $canNavigatePrev || $canNavigateNext,
            'can_navigate_prev' => $canNavigatePrev,
            'can_navigate_next' => $canNavigateNext,
            'parameters' => [
                'month' => 'next|prev|YYYY-MM (e.g., 2025-11)'
            ]
        ],
        'debug' => $debugInfo
    ];
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
function navigateToMonth($html, $ch, $url, $direction) {
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    if ($direction === 'prev') {
        $navLink = $xpath->query('//a[@title="Go to the previous month"]');
        
        if ($navLink->length === 0) {
            $navLink = $xpath->query('//a[contains(@title, "previous") or contains(@title, "Go to the previous month")]');
        }
    } else {
        $navLink = $xpath->query('//a[@title="Go to the next month"]');
        
        if ($navLink->length === 0) {
            $navLink = $xpath->query('//a[contains(@title, "next") or contains(@title, "Go to the next month")]');
        }
    }
    
    if ($navLink->length === 0) {
        $navLink = $xpath->query('//table[contains(@class, "sg-asp-calendar-header")]//a');
        
        if ($navLink->length >= 2) {
            if ($direction === 'prev') {
                $navLink = $xpath->query('(//table[contains(@class, "sg-asp-calendar-header")]//a)[1]');
            } else {
                $navLink = $xpath->query('(//table[contains(@class, "sg-asp-calendar-header")]//a)[last()]');
            }
        }
    }
    
    if ($navLink->length === 0) {
        $allLinks = $xpath->query('//a[contains(@href, "__doPostBack")]');
        if ($allLinks->length >= 2) {
            if ($direction === 'prev') {
                $navLink = $xpath->query('(//a[contains(@href, "__doPostBack")])[1]');
            } else {
                $navLink = $xpath->query('(//a[contains(@href, "__doPostBack")])[last()]');
            }
        }
    }
    
    if ($navLink->length === 0) {
        return null;
    }
    
    $href = $navLink->item(0)->getAttribute('href');
    
    if (preg_match("/__doPostBack\('([^']+)','([^']+)'\)/", $href, $matches)) {
        $eventTarget = $matches[1];
        $eventArgument = $matches[2];
        
        // Get form data
        $viewState = $xpath->query('//input[@id="__VIEWSTATE"]')->item(0);
        $viewStateGenerator = $xpath->query('//input[@id="__VIEWSTATEGENERATOR"]')->item(0);
        $eventValidation = $xpath->query('//input[@id="__EVENTVALIDATION"]')->item(0);
        
        if ($viewState && $viewStateGenerator && $eventValidation) {
            $postData = [
                '__EVENTTARGET' => $eventTarget,
                '__EVENTARGUMENT' => $eventArgument,
                '__VIEWSTATE' => $viewState->getAttribute('value'),
                '__VIEWSTATEGENERATOR' => $viewStateGenerator->getAttribute('value'),
                '__EVENTVALIDATION' => $eventValidation->getAttribute('value'),
            ];
            
            // Make POST request
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_COOKIEFILE, '');
            curl_setopt($ch, CURLOPT_COOKIEJAR, '');
            
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                return null;
            }
            return $response;
        }
    }
    
    return null;
}
function getAssignments() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    $requestedRun = $_GET['run'] ?? null;
    $providedCookies = $_GET['cookies'] ?? null;
    
    $cookieFile = sys_get_temp_dir() . '/hac_cookies_' . md5($username . $link . session_id()) . '.txt';
    
    $ch = login($username, $password, $link, $providedCookies);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/Assignments.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    
    $freshCookies = getCookiesFromHandle($ch, $cookieFile);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $runOptions = $xpath->query('//select[@id="plnMain_ddlReportCardRuns"]/option');
    $availableRuns = [];
    $defaultRun = '';
    
    foreach ($runOptions as $option) {
        $value = $option->getAttribute('value');
        $text = trim($option->textContent);
        $isSelected = $option->hasAttribute('selected');
        
        $availableRuns[$value] = $text;
        
        if ($isSelected) {
            $defaultRun = $value;
        }
    }
    
    $selectedRun = $defaultRun;
    if ($requestedRun) {
        if (array_key_exists($requestedRun, $availableRuns)) {
            $selectedRun = $requestedRun;
        } else {
            $foundRun = array_search($requestedRun, $availableRuns);
            if ($foundRun !== false) {
                $selectedRun = $foundRun;
            }
        }
    }
    
    if ($selectedRun !== $defaultRun) {
        $ch = login($username, $password, $link);
        if (!$ch) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid username or password']);
            return;
        }
        
        $viewState = $xpath->query('//input[@id="__VIEWSTATE"]')->item(0)->getAttribute('value');
        $viewStateGenerator = $xpath->query('//input[@id="__VIEWSTATEGENERATOR"]')->item(0)->getAttribute('value');
        $eventValidation = $xpath->query('//input[@id="__EVENTVALIDATION"]')->item(0)->getAttribute('value');
        
        $postData = [
            '__EVENTTARGET' => 'ctl00$plnMain$btnRefreshView',
            '__EVENTARGUMENT' => '',
            '__VIEWSTATE' => $viewState,
            '__VIEWSTATEGENERATOR' => $viewStateGenerator,
            '__EVENTVALIDATION' => $eventValidation,
            'ctl00$plnMain$ddlReportCardRuns' => $selectedRun,
            'ctl00$plnMain$ddlClasses' => 'ALL',
            'ctl00$plnMain$ddlOrderBy' => 'Class',
        ];
        
        $html = makeAuthenticatedRequest($url, $ch, $postData);
        curl_close($ch);
        
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
    }
    
    $classDivs = $xpath->query('//div[contains(@class, "AssignmentClass")]');
    
    $result = [
        'report_card_run' => [
            'selected' => $selectedRun,
            'selected_text' => $availableRuns[$selectedRun] ?? 'Unknown',
            'available' => $availableRuns
        ],
        'classes' => []
    ];
    
    foreach ($classDivs as $div) {
        $classElement = $xpath->query('.//a[contains(@class, "sg-header-heading")]', $div);
        if ($classElement->length === 0) {
            continue;
        }

        $fullClassName = trim($classElement->item(0)->textContent);

        $classCode = '';
        $className = '';
        
        // Pattern: "CODE - SECTION NAME" -> separate CODE-SECTION from NAME
        if (preg_match('/^([^-]+-\s*\d+)\s+(.+)$/', $fullClassName, $matches)) {
            $classCode = trim($matches[1]);
            $className = trim($matches[2]);
        } else {
            $classCode = '';
            $className = $fullClassName;
        }
        
        $average = 'N/A';
        
        $avgElement = $xpath->query('.//span[contains(@id, "lblHdrAverage")]', $div);
        if ($avgElement->length > 0) {
            $avgText = trim($avgElement->item(0)->textContent);
            if (preg_match('/(\d+)/', $avgText, $matches)) {
                $average = $matches[1];
            }
        }
        
        if ($average === 'N/A') {
            $avgElements = $xpath->query('.//span[contains(@class, "sg-header-heading")]', $div);
            foreach ($avgElements as $avgEl) {
                $avgText = trim($avgEl->textContent);
                if (strpos($avgText, 'Average') !== false && preg_match('/(\d+)/', $avgText, $matches)) {
                    $average = $matches[1];
                    break;
                }
            }
        }
        
        $droppedElement = $xpath->query('.//span[contains(@class, "DroppedCourse")]', $div)->item(0);
        $isDropped = false;
        $droppedDate = null;
        
        if ($droppedElement) {
            $isDropped = true;
            $droppedText = trim($droppedElement->textContent);
            if (preg_match('/dropped as of\s+(\d{2}\/\d{2}\/\d{4})/', $droppedText, $matches)) {
                $droppedDate = $matches[1];
            }
        }
        
        $classData = [
            'class_code' => $classCode,
            'class_name' => $className,
            'average' => $average,
            'dropped' => $isDropped,
            'dropped_date' => $droppedDate,
            'assignments' => [],
            'categories' => []
        ];
        
        $assignmentTables = $xpath->query('.//table[contains(@id, "CourseAssignments")]', $div);
        if ($assignmentTables->length > 0) {
            $classData['assignments'] = parseTable($dom, $assignmentTables->item(0), true);
        }
        
        $categoryTables = $xpath->query('.//table[contains(@id, "CourseCategories")]', $div);
        if ($categoryTables->length > 0) {
            $classData['categories'] = parseCategoryTable($dom, $categoryTables->item(0));
        }
        
        $result['classes'][$className] = $classData;
    }
    
    if ($freshCookies && $providedCookies !== $freshCookies) {
        $result['_fresh_cookies'] = $freshCookies;
    }
    
    header('Content-Type: application/json');
    echo json_encode($result);
}

function parseCategoryTable($dom, $table) {
    $xpath = new DOMXPath($dom);
    $rows = $xpath->query('.//tr', $table);
    $data = [];
    
    foreach ($rows as $rowIndex => $row) {
        $rowData = [];
        $cells = $xpath->query('.//td', $row);
        
        foreach ($cells as $cellIndex => $cell) {
            $text = $cell->textContent;
            
            if ($rowIndex === 0) {
                $cleanHeaders = [
                    "Category",
                    "Student's Points", 
                    "Maximum Points",
                    "Percent",
                    "Category Weight", 
                    "Category Points"
                ];
                
                if ($cellIndex < count($cleanHeaders)) {
                    $text = $cleanHeaders[$cellIndex];
                }
            } else {
                // some bs idk
                $text = str_replace("\u00a0", ' ', $text);
                $text = preg_replace('/\s+/', ' ', $text);
                $text = trim($text);
                
                if ($text === '' || $text === ' ' || $text === ' ') {
                    $text = '';
                }
            }
            
            $rowData[] = $text;
        }
        
        if (!empty($rowData)) {
            $data[] = $rowData;
        }
    }
    
    return $data;
}

function parseTable($dom, $table, $isAssignmentTable = true) {
    $xpath = new DOMXPath($dom);
    $rows = $xpath->query('.//tr', $table);
    $data = [];
    
    foreach ($rows as $row) {
        $rowData = [];
        $cells = $xpath->query('.//td', $row);
        
        foreach ($cells as $cell) {
            $text = $cell->textContent;
            
            $text = preg_replace('/[^\S ]+/u', ' ', $text);
            
            if ($isAssignmentTable) {
                $text = str_replace('*', '', $text);
            }
            
            $text = preg_replace('/\s+/', ' ', trim($text));
            
            $rowData[] = $text;
        }
        
        if (!empty($rowData)) {
            $data[] = $rowData;
        }
    }
    
    return $data;
}

function getSchedule() {
    startApiSession();
    ini_set('log_errors', 1);
    ini_set('error_log', 'sch.log');

    $required = ['user', 'pass'];
    foreach ($required as $param) {
        if (!isset($_GET[$param])) {
            //http_response_code(400);
            echo json_encode(['error' => "Missing parameter: $param"]);
            return;
        }
    }
    
    $username = $_GET['user'];
    $password = $_GET['pass'];
    $link = $_GET['link'] ?? '';
        
    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }
    
    $url = $link . '/HomeAccess/Content/Student/Classes.aspx';
    $response = makeAuthenticatedRequest($url, $ch);
    curl_close($ch);
    
    if (!$response) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch data']);
        return;
    }
    
        
    $dom = new DOMDocument();
    @$dom->loadHTML($response);
    $xpath = new DOMXPath($dom);
    
    $table = $xpath->query('//table[@id="plnMain_dgSchedule"]');
    $schedule = [];
    
    if ($table->length > 0) {
        $rows = $xpath->query('.//tr[contains(@class, "sg-asp-table-data-row")]', $table->item(0));
        
        foreach ($rows as $row) {
            $cells = $xpath->query('.//td', $row);
            
            if ($cells->length >= 9) {
                $courseCode = trim($cells->item(0)->textContent);
                $description = trim($xpath->query('.//a', $cells->item(1))->item(0)->textContent);
                $period = trim($cells->item(2)->textContent);
                
                $teacherElement = $xpath->query('.//a', $cells->item(3));
                $teacher = $teacherElement->length > 0 ? 
                    trim(preg_replace('/\s+/', ' ', $teacherElement->item(0)->textContent)) : 
                    trim($cells->item(3)->textContent);
                
                $room = trim($cells->item(4)->textContent);
                $days = trim($cells->item(5)->textContent);
                $markingPeriods = trim($cells->item(6)->textContent);
                $building = trim($cells->item(7)->textContent);
                $status = trim($cells->item(8)->textContent);
                
                $courseCode = preg_replace('/\s+/', ' ', $courseCode);
                
                $schedule[] = [
                    'course_code' => $courseCode,
                    'description' => $description,
                    'period' => $period,
                    'teacher' => $teacher,
                    'room' => $room,
                    'days' => $days,
                    'marking_periods' => $markingPeriods,
                    'building' => $building,
                    'status' => $status
                ];
            }
        }
    }
    
    $groupedSchedule = [];
    foreach ($schedule as $entry) {
        $period = $entry['period'];
        if (!isset($groupedSchedule[$period])) {
            $groupedSchedule[$period] = [];
        }
        $groupedSchedule[$period][] = $entry;
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'schedule' => $schedule,
        'grouped_by_period' => $groupedSchedule,
        'term' => $xpath->query('//div[@id="plnMain_pageTitle"]')->item(0)->textContent ?? 'Current Term'
    ]);
}

function getTeacherEmail() {
    startApiSession();
    ini_set('log_errors', 1);
    ini_set('error_log', 'sch.log');

    $required = ['user', 'pass'];
    foreach ($required as $param) {
        if (!isset($_GET[$param])) {
            echo json_encode(['error' => "Missing parameter: $param"]);
            return;
        }
    }
    
    $username = $_GET['user'];
    $password = $_GET['pass'];
    $link = $_GET['link'] ?? '';
        
    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }
    
    $url = $link . '/HomeAccess/Content/Student/Classes.aspx';
    $response = makeAuthenticatedRequest($url, $ch);
    curl_close($ch);
    
    if (!$response) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch data']);
        return;
    }
    
    $dom = new DOMDocument();
    @$dom->loadHTML($response);
    $xpath = new DOMXPath($dom);
    
    $table = $xpath->query('//table[@id="plnMain_dgSchedule"]');
    $teachers = [];
    
    if ($table->length > 0) {
        $rows = $xpath->query('.//tr[contains(@class, "sg-asp-table-data-row")]', $table->item(0));
        
        foreach ($rows as $row) {
            $cells = $xpath->query('.//td', $row);
            
            if ($cells->length >= 9) {
                $descriptionLink = $xpath->query('.//a', $cells->item(1))->item(0);
                $subject = $descriptionLink ? trim($descriptionLink->textContent) : '';

                $teacherElement = $xpath->query('.//a', $cells->item(3));
                $teacherName = '';
                $teacherEmail = '';

                if ($teacherElement->length > 0) {
                    $teacherName = trim(preg_replace('/\s+/', ' ', $teacherElement->item(0)->textContent));
                    $teacherEmail = str_replace('mailto:', '', $teacherElement->item(0)->getAttribute('href'));
                    $teacherEmail = trim(explode(' ', $teacherEmail)[0]);
                } else {
                    $teacherName = trim($cells->item(3)->textContent);
                }

                $teachers[] = [
                    'teacher' => $teacherName,
                    'email' => $teacherEmail,
                    'subject' => $subject
                ];
            }
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode([
        'teachers' => $teachers,
        'term' => $xpath->query('//div[@id="plnMain_pageTitle"]')->item(0)->textContent ?? 'Current Term'
    ]);
}

function getInfo() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    $providedCookies = $_GET['cookies'] ?? null;
    
    $cookieFile = sys_get_temp_dir() . '/hac_cookies_' . md5($username . $link . session_id()) . '.txt';
    
    $ch = login($username, $password, $link, $providedCookies);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/Registration.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    
    $freshCookies = getCookiesFromHandle($ch, $cookieFile);
    
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $info = [];
    
    $nameElement = $xpath->query("//span[@id='plnMain_lblRegStudentName']")->item(0);
    if ($nameElement) $info['name'] = trim($nameElement->nodeValue);
    
    $gradeElement = $xpath->query("//span[@id='plnMain_lblGrade']")->item(0);
    if ($gradeElement) $info['grade'] = trim($gradeElement->nodeValue);
    
    $schoolElement = $xpath->query("//span[@id='plnMain_lblBuildingName']")->item(0);
    if ($schoolElement) $info['school'] = trim($schoolElement->nodeValue);
    
    $dobElement = $xpath->query("//span[@id='plnMain_lblBirthDate']")->item(0);
    if ($dobElement) $info['dob'] = trim($dobElement->nodeValue);
    
    $counselorElement = $xpath->query("//span[@id='plnMain_lblCounselor']")->item(0);
    if ($counselorElement) $info['counselor'] = trim($counselorElement->nodeValue);
    
    $languageElement = $xpath->query("//span[@id='plnMain_lblLanguage']")->item(0);
    if ($languageElement) $info['language'] = trim($languageElement->nodeValue);
    
    $cohortElement = $xpath->query("//span[@id='plnMain_lblCohortYear']")->item(0);
    if ($cohortElement) $info['cohort-year'] = trim($cohortElement->nodeValue);
    
    if ($freshCookies && $providedCookies !== $freshCookies) {
        $info['_fresh_cookies'] = $freshCookies;
    }
    
    echo json_encode($info);
}

function getAverages() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    $requestedRun = $_GET['run'] ?? null;
    $providedCookies = $_GET['cookies'] ?? null;
    
    $cookieFile = sys_get_temp_dir() . '/hac_cookies_' . md5($username . $link . session_id()) . '.txt';
    
    $ch = login($username, $password, $link, $providedCookies);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/Assignments.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    
    $freshCookies = getCookiesFromHandle($ch, $cookieFile);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $runOptions = $xpath->query('//select[@id="plnMain_ddlReportCardRuns"]/option');
    $availableRuns = [];
    $defaultRun = '';
    
    foreach ($runOptions as $option) {
        $value = $option->getAttribute('value');
        $text = trim($option->textContent);
        $isSelected = $option->hasAttribute('selected');
        
        $availableRuns[$value] = $text;
        
        if ($isSelected) {
            $defaultRun = $value;
        }
    }
    
    $selectedRun = $defaultRun;
    if ($requestedRun) {
        if (array_key_exists($requestedRun, $availableRuns)) {
            $selectedRun = $requestedRun;
        } else {
            $foundRun = array_search($requestedRun, $availableRuns);
            if ($foundRun !== false) {
                $selectedRun = $foundRun;
            }
        }
    }
    
    if ($selectedRun !== $defaultRun) {
        $ch = login($username, $password, $link);
        if (!$ch) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid username or password']);
            return;
        }
        
        $viewState = $xpath->query('//input[@id="__VIEWSTATE"]')->item(0)->getAttribute('value');
        $viewStateGenerator = $xpath->query('//input[@id="__VIEWSTATEGENERATOR"]')->item(0)->getAttribute('value');
        $eventValidation = $xpath->query('//input[@id="__EVENTVALIDATION"]')->item(0)->getAttribute('value');
        
        $postData = [
            '__EVENTTARGET' => 'ctl00$plnMain$btnRefreshView',
            '__EVENTARGUMENT' => '',
            '__VIEWSTATE' => $viewState,
            '__VIEWSTATEGENERATOR' => $viewStateGenerator,
            '__EVENTVALIDATION' => $eventValidation,
            'ctl00$plnMain$ddlReportCardRuns' => $selectedRun,
            'ctl00$plnMain$ddlClasses' => 'ALL',
            'ctl00$plnMain$ddlOrderBy' => 'Class',
        ];
        
        $html = makeAuthenticatedRequest($url, $ch, $postData);
        curl_close($ch);
        
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
    }
    
    $result = [
        'report_card_run' => [
            'selected' => $selectedRun,
            'selected_text' => $availableRuns[$selectedRun] ?? 'Unknown',
            'available' => $availableRuns
        ],
        'classes' => []
    ];
    
    $classDivs = $xpath->query('//div[contains(@class, "AssignmentClass")]');
    
    foreach ($classDivs as $div) {
        $classElement = $xpath->query('.//a[contains(@class, "sg-header-heading")]', $div);
        if ($classElement->length === 0) {
            continue;
        }
        
        $fullClassName = trim($classElement->item(0)->textContent);
        
        $classCode = '';
        $className = '';
        
        if (preg_match('/^([^-]+-\s*\d+)\s+(.+)$/', $fullClassName, $matches)) {
            $classCode = trim($matches[1]);
            $className = trim($matches[2]);
        } else {
            $classCode = '';
            $className = $fullClassName;
        }
        
        $avgElement = $xpath->query('.//span[contains(@class, "sg-header-heading") and contains(@id, "lblHdrAverage")]', $div);
        $average = 'N/A';
        
        if ($avgElement->length > 0) {
            $avgText = trim($avgElement->item(0)->textContent);
            if (preg_match('/\d+\.?\d*/', $avgText, $matches)) {
                $average = $matches[0];
            }
        }
        
        $droppedElement = $xpath->query('.//span[contains(@class, "DroppedCourse")]', $div)->item(0);
        $isDropped = false;
        $droppedDate = null;
        
        if ($droppedElement) {
            $isDropped = true;
            $droppedText = trim($droppedElement->textContent);
            if (preg_match('/dropped as of\s+(\d{2}\/\d{2}\/\d{4})/', $droppedText, $matches)) {
                $droppedDate = $matches[1];
            }
        }
        
        $result['classes'][] = [
            'class_code' => $classCode,
            'class' => $className,
            'average' => $average,
            'dropped' => $isDropped,
            'dropped_date' => $droppedDate
        ];
    }
    
    if ($freshCookies && $providedCookies !== $freshCookies) {
        $result['_fresh_cookies'] = $freshCookies;
    }
    
    header('Content-Type: application/json');
    echo json_encode($result);
}
function getClasses() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    
    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/Assignments.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $classes = [];
    
    // Extract classes
    $classElements = $xpath->query("//div[contains(@class, 'AssignmentClass')]");
    foreach ($classElements as $classElement) {
        $header = $xpath->query(".//div[contains(@class, 'sg-header')]", $classElement)->item(0);
        if ($header) {
            $classText = preg_replace('/\s+/', ' ', trim($header->nodeValue));
            $classParts = explode(' ', $classText);
            if (count($classParts) > 6) {
                $className = implode(' ', array_slice($classParts, 3, count($classParts) - 6));
                $classes[] = $className;
            }
        }
    }
    
    echo json_encode($classes);
}

/*function getReport() {
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    
    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/ReportCards.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $headers = ["Course", "Description", "Period", "Teacher", "Room", "1st", "2nd", "3rd", "Exam1", "Sem1", "4th", "5th", "6th", "Exam2", "Sem2", "CND1", "CND2", "CND3", "CND4", "CND5", "CND6"];
    $data = [];
    
    // Extract report card data
    $cells = $xpath->query("//td");
    $row = [];
    $counter = 0;
    
    foreach ($cells as $cell) {
        $counter++;
        if ($counter > 32) {
            $row[] = trim($cell->nodeValue);
        }
        if (count($row) % 32 == 0 && $counter > 32 && count($row) > 0) {
            // Process the row (remove specific columns)
            if (count($row) >= 32) {
                $processedRow = array_merge(
                    array_slice($row, 0, 5),
                    array_slice($row, 7, 16),
                    array_slice($row, 23, 9)
                );
                $data[] = $processedRow;
            }
            $row = [];
        }
    }
    
    $response = [
        'headers' => $headers,
        'data' => $data
    ];
    
    echo json_encode($response);
}*/

function getReport() {
    startApiSession();
    header('Content-Type: application/json');

    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = rtrim($_GET['link'] ?? '', '/');

    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/ReportCards.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    curl_close($ch);

    if (stripos($html, 'DailySchedule.aspx') !== false) {
        echo json_encode(['headers' => '', 'data' => '']);
        return;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $data = [];

    $rows = $xpath->query("//tr");
    foreach ($rows as $row) {
        $rowData = [];
        $cells = $xpath->query(".//td", $row);
        foreach ($cells as $cell) {
            $rowData[] = trim($cell->nodeValue);
        }
        if (!empty($rowData)) {
            $data[] = $rowData;
        }
    }

    if (empty($data)) {
        echo json_encode(null);
        return;
    }

    $headers = $data[0];
    $data = array_slice($data, 1);

    $response = [
        'headers' => $headers,
        'data' => $data
    ];

    echo json_encode($response);
}

function getProgressReport() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    $requestedDate = $_GET['date'] ?? null;
    
    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/InterimProgress.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $dateDropdown = $xpath->query('//select[@id="plnMain_ddlIPRDates"]');
    $hasMultipleDates = $dateDropdown->length > 0;
    
    $availableDates = [];
    $selectedDate = '';
    $selectedDateText = '';
    
    if ($hasMultipleDates) {
        $dateOptions = $xpath->query('//select[@id="plnMain_ddlIPRDates"]/option');
        
        foreach ($dateOptions as $option) {
            $value = $option->getAttribute('value');
            $text = trim($option->textContent);
            $isSelected = $option->hasAttribute('selected');
            
            $availableDates[$value] = $text;
            
            if ($isSelected) {
                $selectedDate = $value;
                $selectedDateText = $text;
            }
        }
        
        if (empty($selectedDate) && count($availableDates) > 0) {
            $selectedDate = array_keys($availableDates)[0];
            $selectedDateText = $availableDates[$selectedDate];
        }
        
        $useDate = $selectedDate;
        if ($requestedDate) {
            if (is_numeric($requestedDate)) {
                $dateKeys = array_keys($availableDates);
                $dateIndex = intval($requestedDate) - 1;
                if ($dateIndex >= 0 && $dateIndex < count($dateKeys)) {
                    $useDate = $dateKeys[$dateIndex];
                    $selectedDateText = $availableDates[$useDate];
                }
            } else {
                if (array_key_exists($requestedDate, $availableDates)) {
                    $useDate = $requestedDate;
                    $selectedDateText = $availableDates[$requestedDate];
                } else {
                    $foundDate = array_search($requestedDate, $availableDates);
                    if ($foundDate !== false) {
                        $useDate = $foundDate;
                        $selectedDateText = $availableDates[$foundDate];
                    }
                }
            }
        }
        
        if ($useDate !== $selectedDate) {
            $ch = login($username, $password, $link);
            if (!$ch) {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid username or password']);
                return;
            }
            
            $viewState = $xpath->query('//input[@id="__VIEWSTATE"]')->item(0)->getAttribute('value');
            $viewStateGenerator = $xpath->query('//input[@id="__VIEWSTATEGENERATOR"]')->item(0)->getAttribute('value');
            $eventValidation = $xpath->query('//input[@id="__EVENTVALIDATION"]')->item(0)->getAttribute('value');
            
            $postData = [
                '__EVENTTARGET' => 'ctl00$plnMain$ddlIPRDates',
                '__EVENTARGUMENT' => '',
                '__LASTFOCUS' => '',
                '__VIEWSTATE' => $viewState,
                '__VIEWSTATEGENERATOR' => $viewStateGenerator,
                '__EVENTVALIDATION' => $eventValidation,
                'ctl00$plnMain$ddlIPRDates' => $useDate,
            ];
            
            $html = makeAuthenticatedRequest($url, $ch, $postData);
            curl_close($ch);
            
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);
            
            $selectedDate = $useDate;
            $selectedDateText = $availableDates[$useDate];
        }
    } else {
        $titleElement = $xpath->query("//label[@id='plnMain_lblTitle']");
        if ($titleElement->length > 0) {
            $titleText = trim($titleElement->item(0)->textContent);
            if (preg_match('/Interim Progress Report For (.+)$/', $titleText, $matches)) {
                $selectedDateText = trim($matches[1]);
                $selectedDate = $selectedDateText;
                $availableDates[$selectedDate] = $selectedDateText;
            }
        }
    }
    
    $data = [];
    $headers = [];
    
    $iprTable = $xpath->query('//table[@id="plnMain_dgIPR"]');
    
    if ($iprTable->length > 0) {
        $rows = $xpath->query('.//tr', $iprTable->item(0));
        
        foreach ($rows as $rowIndex => $row) {
            $rowData = [];
            $cells = $xpath->query('.//td', $row);
            
            foreach ($cells as $cell) {
                $text = trim($cell->textContent);
                $text = preg_replace('/\s+/', ' ', $text);
                $text = trim($text);
                $rowData[] = $text;
            }
            
            if (!empty($rowData)) {
                if ($rowIndex === 0) {
                    $headers = $rowData;
                } else {
                    $data[] = $rowData;
                }
            }
        }
    }
    
    $commentLegend = [];
    $commentTable = $xpath->query('//table[@id="plnMain_dgCommentLegend"]');
    
    if ($commentTable->length > 0) {
        $commentRows = $xpath->query('.//tr[position() > 1]', $commentTable->item(0));
        
        foreach ($commentRows as $row) {
            $commentCells = $xpath->query('.//td', $row);
            if ($commentCells->length >= 2) {
                $commentCode = trim($commentCells->item(0)->textContent);
                $commentDescription = trim($commentCells->item(1)->textContent);
                
                $commentCode = preg_replace('/\s+/', ' ', $commentCode);
                $commentDescription = preg_replace('/\s+/', ' ', $commentDescription);
                
                if (!empty($commentCode)) {
                    $commentLegend[$commentCode] = $commentDescription;
                }
            }
        }
    }
    
    $numberedDates = [];
    $index = 1;
    foreach ($availableDates as $value => $text) {
        $numberedDates[] = [
            'number' => $index,
            'value' => $value,
            'text' => $text
        ];
        $index++;
    }
    
    $selectedNumber = 0;
    foreach ($numberedDates as $date) {
        if ($date['value'] === $selectedDate) {
            $selectedNumber = $date['number'];
            break;
        }
    }
    
    $response = [
        'multiple_dates_available' => $hasMultipleDates,
        'selected_date' => [
            'number' => $selectedNumber,
            'value' => $selectedDate,
            'text' => $selectedDateText
        ],
        'available_dates' => $numberedDates,
        'headers' => $headers,
        'data' => $data,
        'comment_legend' => $commentLegend
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
}

function getTranscript() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    
    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/Transcript.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $transcript = [];
    
    $groups = $xpath->query("//td[contains(@class, 'sg-transcript-group')]");
    foreach ($groups as $group) {
        $semester = [];
        
        $yearElement = $xpath->query(".//span[contains(@id, 'YearValue')]", $group)->item(0);
        if ($yearElement) $semester['year'] = trim($yearElement->nodeValue);
        
        $semesterElement = $xpath->query(".//span[contains(@id, 'GroupValue')]", $group)->item(0);
        if ($semesterElement) $semester['semester'] = trim($semesterElement->nodeValue);
        
        $gradeElement = $xpath->query(".//span[contains(@id, 'GradeValue')]", $group)->item(0);
        if ($gradeElement) $semester['grade'] = trim($gradeElement->nodeValue);
        
        $schoolElement = $xpath->query(".//span[contains(@id, 'BuildingValue')]", $group)->item(0);
        if ($schoolElement) $semester['school'] = trim($schoolElement->nodeValue);
        
        $courseData = [];
        $rows = $xpath->query(".//table[2]//tr[contains(@class, 'sg-asp-table-header-row') or contains(@class, 'sg-asp-table-data-row')]", $group);
        foreach ($rows as $row) {
            $rowData = [];
            $cells = $xpath->query(".//td", $row);
            foreach ($cells as $cell) {
                $rowData[] = trim($cell->nodeValue);
            }
            if (!empty($rowData)) {
                $courseData[] = $rowData;
            }
        }
        $semester['data'] = $courseData;
        
        $creditsElement = $xpath->query(".//label[contains(@id, 'CreditValue')]", $group)->item(0);
        if ($creditsElement) $semester['credits'] = trim($creditsElement->nodeValue);
        
        if (isset($semester['year']) && isset($semester['semester'])) {
            $key = $semester['year'] . " - Semester " . $semester['semester'];
            $transcript[$key] = $semester;
        }
    }
    
    $gpaRows = $xpath->query("//table[@id='plnMain_rpTranscriptGroup_tblCumGPAInfo']//tr[contains(@class, 'sg-asp-table-data-row')]");
    foreach ($gpaRows as $row) {
        $labelElement = $xpath->query(".//span[contains(@id, 'GPADescr')]", $row)->item(0);
        $valueElement = $xpath->query(".//span[contains(@id, 'GPACum')]", $row)->item(0);
        
        if ($labelElement && $valueElement) {
            $label = trim($labelElement->nodeValue);
            $value = trim($valueElement->nodeValue);
            $transcript[$label] = $value;
        }
        
        $rankElement = $xpath->query(".//span[contains(@id, 'GPARank')]", $row)->item(0);
        if ($rankElement) $transcript['rank'] = trim($rankElement->nodeValue);
        
        $quartileElement = $xpath->query(".//span[contains(@id, 'GPAQuartile')]", $row)->item(0);
        if ($quartileElement) $transcript['quartile'] = trim($quartileElement->nodeValue);
    }
    
    echo json_encode($transcript);
}

function getRank() {
    startApiSession();
    $username = $_GET['user'] ?? '';
    $password = $_GET['pass'] ?? '';
    $link = $_GET['link'] ?? '';
    
    $ch = login($username, $password, $link);
    if (!$ch) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid username or password']);
        return;
    }

    $url = $link . '/HomeAccess/Content/Student/Transcript.aspx';
    $html = makeAuthenticatedRequest($url, $ch);
    curl_close($ch);

    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);
    
    $rankInfo = [];
    
    $gpaRows = $xpath->query("//table[@id='plnMain_rpTranscriptGroup_tblCumGPAInfo']//tr[contains(@class, 'sg-asp-table-data-row')]");
    foreach ($gpaRows as $row) {
        $labelElement = $xpath->query(".//span[contains(@id, 'GPADescr')]", $row)->item(0);
        $valueElement = $xpath->query(".//span[contains(@id, 'GPACum')]", $row)->item(0);
        
        if ($labelElement && $valueElement) {
            $label = trim($labelElement->nodeValue);
            $value = trim($valueElement->nodeValue);
            $rankInfo[$label] = $value;
        }
        
        $rankElement = $xpath->query(".//span[contains(@id, 'GPARank')]", $row)->item(0);
        if ($rankElement) $rankInfo['rank'] = trim($rankElement->nodeValue);
        
        $quartileElement = $xpath->query(".//span[contains(@id, 'GPAQuartile')]", $row)->item(0);
        if ($quartileElement) $rankInfo['quartile'] = trim($quartileElement->nodeValue);
    }
    
    echo json_encode($rankInfo);
}
?>