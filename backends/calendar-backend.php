<?php
session_start();
header('Content-Type: application/json');

require_once(__DIR__ . '/../_backend-libs.php');

if (empty($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated.']);
    exit;
}

function getCalendarFilePath($calendarId) {
    $url = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    return __DIR__ . "/../userdata/calendars/$url/$calendarId.json";
}

function loadCalendar($calendarId) {
    $file = getCalendarFilePath($calendarId);
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true) ?: null;
    }
    return null;
}

function saveCalendar($calendarId, $calendarData) {
    $district = $_SESSION['url'] ?? '';
    if (strpos($district, 'demodist') !== false) {
        return true; // lie to them
    }
    
    $path = getCalendarFilePath($calendarId);
    $folder = dirname($path);

    if (!is_dir($folder)) {
        if (!mkdir($folder, 0777, true)) {
            error_log("Failed to create calendar directory: $folder");
            return false;
        }
    }

    $result = file_put_contents($path, json_encode($calendarData, JSON_PRETTY_PRINT));
    if ($result === false) {
        error_log("Failed to write calendar file: $path");
        return false;
    }
    
    return true;
}
function deleteCalendarFile($calendarId) {
    $district = $_SESSION['url'] ?? '';
    if (strpos($district, 'demodist') !== false) {
        return true; 
    }
    
    $file = getCalendarFilePath($calendarId);
    if (file_exists($file)) {
        return unlink($file);
    }
    return true;
}

// preferences
function getUserCalendarsFilePath($userId) {
    $url = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    $safe_id = preg_replace('/[^A-Za-z0-9_\-]/', '_', $userId);
    return __DIR__ . "/../userdata/users/$url/$safe_id/calendars.json";
}

function loadUserCalendars($userId) {
    $file = getUserCalendarsFilePath($userId);
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true) ?: [];
    }
    return [];
}

function saveUserCalendars($userId, $userCalendars) {
    $district = $_SESSION['url'] ?? '';
    if (strpos($district, 'demodist') !== false) {
        return true;
    }
    
    $path = getUserCalendarsFilePath($userId);
    $folder = dirname($path);

    if (!is_dir($folder)) {
        if (!mkdir($folder, 0777, true)) {
            error_log("Failed to create user calendars directory: $folder");
            return false;
        }
    }

    $result = file_put_contents($path, json_encode($userCalendars, JSON_PRETTY_PRINT));
    if ($result === false) {
        error_log("Failed to write user calendars file: $path");
        return false;
    }
    
    return true;
}

// utility
function normalizeUserName($fullName) {
    if (empty($fullName)) {
        return 'Unknown User';
    }
    
    $fullName = (string)$fullName;
    
    $names = explode(' ', trim($fullName));
    
    $names = array_filter($names);
    
    if (count($names) >= 2) {
        return $names[0] . ' ' . end($names);
    }
    
    return $fullName;
}

function getUserInfo($userId) {
    $url = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    $safe_id = preg_replace('/[^A-Za-z0-9_\-]/', '_', $userId);
    $prefsFile = __DIR__ . "/../userdata/users/$url/$safe_id/prefs.json";
    
    if (file_exists($prefsFile)) {
        $prefs = json_decode(file_get_contents($prefsFile), true);
        if (isset($prefs["name"]) && !empty($prefs["name"])) {
            return [
                'id' => $userId,
                'name' => normalizeUserName($prefs['name'])
            ];
        }
    }
    
    return [
        'id' => $userId,
        'name' => 'User ' . $userId
    ];
}

function getAvailableUsers($district, $school) {
    $users = [];
    $districtFolder = "../userdata/users/$district/";

    if (!is_dir($districtFolder)) {
        return $users;
    }

    $userFolders = scandir($districtFolder);
    foreach ($userFolders as $userId) {
        if ($userId === '.' || $userId === '..') continue;

        $prefsFile = $districtFolder . $userId . '/prefs.json';
        if (!file_exists($prefsFile)) continue;

        $prefs = json_decode(file_get_contents($prefsFile), true);
        if (!$prefs) continue;

        $userSchool = $prefs['school'] ?? '';
        $displayName = $prefs['name'] ?? null;
        if (!$displayName || !$userSchool) continue;

        if ($userSchool === $school) {
            $users[] = [
                'id' => $userId,
                'name' => normalizeUserName($displayName)
            ];
        }
    }

    return $users;
}


// action
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'get_calendars': getCalendarsAction(); break;
    case 'create_calendar': createCalendarAction(); break;
    case 'rename_calendar': renameCalendarAction(); break;
    case 'delete_calendar': deleteCalendarAction(); break;
    case 'leave_calendar': leaveCalendarAction(); break;
    case 'add_event': addEventAction(); break;
    case 'get_subjects': getSubjectsAction(); break;
    case 'search_users': searchUsersAction(); break;
    case 'invite_user': inviteUserAction(); break;
    case 'remove_collaborator': removeCollaboratorAction(); break;
    case 'cancel_invite': cancelInviteAction(); break;
    case 'delete_event': deleteEventAction(); break;
    case 'accept_invite': acceptInviteAction(); break;
    case 'decline_invite': declineInviteAction(); break;
    case 'update_event': updateEventAction(); break;
    default: echo json_encode(['success' => false, 'error' => 'Invalid action']); exit;
}

function getCalendarsAction() {
    $userCalendarsRefs = loadUserCalendars($_SESSION['id']);
    $calendars = [];
    
    foreach ($userCalendarsRefs as $calendarId => $userCalendarRef) {
        $calendarData = loadCalendar($calendarId);
        if ($calendarData) {
            if ($calendarData['owner'] === $_SESSION['id']) {
                cleanupStaleCollaborators($calendarId);
                $calendarData = loadCalendar($calendarId);
            }
            
            $calendarData['user_role'] = $userCalendarRef['role'];
            
            if ($userCalendarRef['role'] === 'pending') {
                $calendarData['user_invites'] = $userCalendarRef['invites'] ?? [];
            } else {
                $calendarData['user_invites'] = [];
            }
            
            if ($calendarData['owner'] === $_SESSION['id']) {
                $calendarData['pending_invites'] = getAllPendingInvitesForCalendar($calendarId);
            } else {
                $calendarData['pending_invites'] = [];
            }
            
            $calendars[$calendarId] = $calendarData;
        }
    }
    
    echo json_encode(['success' => true, 'calendars' => $calendars]);
}

function getAllPendingInvitesForCalendar($calendarId) {
    $pendingInvites = [];
    $district = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    $userFolders = glob(__DIR__ . "/../userdata/users/$district/*", GLOB_ONLYDIR);
    
    foreach ($userFolders as $userFolder) {
        $userId = basename($userFolder);
        $userCalendars = loadUserCalendars($userId);
        
        if (isset($userCalendars[$calendarId]) && $userCalendars[$calendarId]['role'] === 'pending') {
            $userInfo = getUserInfo($userId);
            foreach ($userCalendars[$calendarId]['invites'] as $invite) {
                $pendingInvites[] = [
                    'id' => $invite['id'],
                    'user_id' => $userId,
                    'user_name' => $userInfo['name'],
                    'invited_by' => $invite['invited_by'],
                    'invited_by_name' => $invite['invited_by_name'] ?? 'Unknown',
                    'invited_at' => $invite['invited_at']
                ];
            }
        }
    }
    
    return $pendingInvites;
}


function createCalendarAction() {
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
    
    $name = $_POST['name'] ?? '';
    if (empty($name)) {
        echo json_encode(['success' => false, 'error' => 'Calendar name is required.']);
        exit;
    }

    if (mb_strlen($name) > 30) {
        echo json_encode(['success' => false, 'error' => 'Calendar name must be 30 characters or less.']);
        exit;
    }


    $userCalendars = loadUserCalendars($_SESSION['id']);
    $ownedCalendarsCount = 0;
    foreach ($userCalendars as $calendarRef) {
        if ($calendarRef['role'] === 'owner') {
            $ownedCalendarsCount++;
        }
    }
    
    if ($ownedCalendarsCount >= 15) {
        echo json_encode(['success' => false, 'error' => 'Maximum limit of 15 calendars reached.']);
        exit;
    }
    
    $calendarId = uniqid();
    $userInfo = getUserInfo($_SESSION['id']);
    
    $calendarData = [
        'id' => $calendarId,
        'name' => $name,
        'owner' => $_SESSION['id'],
        'owner_name' => $userInfo['name'],
        'created_at' => time(),
        'events' => [],
        'collaborators' => []
    ];
    
    if (!saveCalendar($calendarId, $calendarData)) {
        echo json_encode(['success' => false, 'error' => 'Failed to create calendar file.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    $userCalendars[$calendarId] = [
        'role' => 'owner',
        'added_at' => time()
    ];
    
    if (saveUserCalendars($_SESSION['id'], $userCalendars)) {
        echo json_encode(['success' => true, 'calendar_id' => $calendarId]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save user calendar reference.']);
    }
    
    error_reporting(E_ALL);
}

function renameCalendarAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    $newName = $_POST['new_name'] ?? '';
    
    if (empty($calendarId) || empty($newName)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID and new name are required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    $userRole = $userCalendars[$calendarId]['role'] ?? '';
    
    if ($userRole !== 'owner' && $userRole !== 'collaborator') {
        echo json_encode(['success' => false, 'error' => 'No permission to rename this calendar.']);
        exit;
    }
    
    $calendarData['name'] = $newName;
    
    if (saveCalendar($calendarId, $calendarData)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to rename calendar.']);
    }
}


function deleteCalendarAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    
    if (empty($calendarId)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID is required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    if ($calendarData['owner'] !== $_SESSION['id']) {
        echo json_encode(['success' => false, 'error' => 'Only the owner can delete the calendar.']);
        exit;
    }
    
    $district = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    $userFolders = glob(__DIR__ . "/../userdata/users/$district/*", GLOB_ONLYDIR);
    
    foreach ($userFolders as $userFolder) {
        $userId = basename($userFolder);
        $userCalendars = loadUserCalendars($userId);
        if (isset($userCalendars[$calendarId])) {
            unset($userCalendars[$calendarId]);
            saveUserCalendars($userId, $userCalendars);
        }
    }
    
    if (deleteCalendarFile($calendarId)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete calendar.']);
    }
}

function leaveCalendarAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    
    if (empty($calendarId)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID is required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    
    if (!isset($userCalendars[$calendarId])) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found in your list.']);
        exit;
    }
    
    if ($userCalendars[$calendarId]['role'] === 'owner') {
        echo json_encode(['success' => false, 'error' => 'Owner cannot leave their own calendar.']);
        exit;
    }
    
    if (isset($calendarData['collaborators'])) {
        $calendarData['collaborators'] = array_filter(
            $calendarData['collaborators'],
            function($collab) {
                return $collab['id'] !== $_SESSION['id'];
            }
        );
        
        if (!saveCalendar($calendarId, $calendarData)) {
            echo json_encode(['success' => false, 'error' => 'Failed to update calendar data.']);
            exit;
        }
    }
    
    unset($userCalendars[$calendarId]);
    
    if (saveUserCalendars($_SESSION['id'], $userCalendars)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to leave calendar.']);
    }
}

function cleanupStaleCollaborators($calendarId) {
    $district = $_SESSION['url'] ?? '';
    if (strpos($district, 'demodist') !== false) {
        return;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData || !isset($calendarData['collaborators'])) {
        return;
    }
    
    $validCollaborators = [];
    
    foreach ($calendarData['collaborators'] as $collaborator) {
        $userCalendars = loadUserCalendars($collaborator['id']);
        
        if (isset($userCalendars[$calendarId]) && $userCalendars[$calendarId]['role'] === 'collaborator') {
            $validCollaborators[] = $collaborator;
        }
    }
    
    if (count($validCollaborators) !== count($calendarData['collaborators'])) {
        $calendarData['collaborators'] = $validCollaborators;
        saveCalendar($calendarId, $calendarData);
    }
}

function addEventAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (empty($calendarId) || empty($date) || empty($subject) || empty($type)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    $userRole = $userCalendars[$calendarId]['role'] ?? '';
    
    if ($userRole !== 'owner' && $userRole !== 'collaborator') {
        echo json_encode(['success' => false, 'error' => 'No permission to add events to this calendar.']);
        exit;
    }
    
    if (!isset($calendarData['events'][$date])) {
        $calendarData['events'][$date] = [];
    }
    
    $eventId = uniqid();
    $calendarData['events'][$date][] = [
        'id' => $eventId,
        'subject' => $subject,
        'type' => $type,
        'description' => $description,
        'added_by' => $_SESSION['id'],
        'added_at' => time()
    ];
    
    if (saveCalendar($calendarId, $calendarData)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to add event.']);
    }
}

function getSubjectsAction() {
    $prefs = loadPrefs($_SESSION['id']);
    $subjects = [];
    
    if (isset($prefs['api_cache']['assignments'])) {
        $cache = $prefs['api_cache']['assignments'];
        $responseData = $cache['response'];
        
        if (isset($cache['encrypted']) && $cache['encrypted'] === true) {
            $password = $_SESSION['pass'] ?? '';
            $responseData = decryptData($cache['response'], $password);
            
            if ($responseData === false) {
                echo json_encode(['success' => true, 'subjects' => []]);
                return;
            }
        }
        
        $assignmentsData = json_decode($responseData, true);
        
        if ($assignmentsData && isset($assignmentsData['classes'])) {
            foreach ($assignmentsData['classes'] as $className => $classData) {
                $subjects[] = $className;
            }
        }
    }
    
    echo json_encode(['success' => true, 'subjects' => array_unique($subjects)]);
}
function searchUsersAction() {
    $query = $_POST['query'] ?? '';
    $calendarId = $_POST['calendar_id'] ?? '';
    
    if (empty($query)) {
        echo json_encode(['success' => false, 'error' => 'Search query is required.']);
        exit;
    }
    
    if (mb_strlen($query) < 3) {
        echo json_encode(['success' => false, 'error' => 'You must enter more than 3 characters for a search query.']);
        exit;
    }
    
    if (mb_strlen($query) > 25) {
        echo json_encode(['success' => false, 'error' => 'Please enter a shorter search query.']);
        exit;
    }
    
    $prefs = loadPrefs($_SESSION['id']);
    $school = $prefs['school'] ?? '';
    $district = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    
    $allUsers = getAvailableUsers($district, $school);
    
    $calendarData = loadCalendar($calendarId);
    $existingUserIds = [];
    
    if ($calendarData && isset($calendarData['owner'])) {
        $existingUserIds[] = $calendarData['owner'];
    }
    
    if ($calendarData && isset($calendarData['collaborators'])) {
        foreach ($calendarData['collaborators'] as $collab) {
            $existingUserIds[] = $collab['id'];
        }
    }
    
    $district = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    $userFolders = glob(__DIR__ . "/../userdata/users/$district/*", GLOB_ONLYDIR);
    
    foreach ($userFolders as $userFolder) {
        $userId = basename($userFolder);
        $userCalendars = loadUserCalendars($userId);
        
        if (isset($userCalendars[$calendarId]) && $userCalendars[$calendarId]['role'] === 'pending') {
            $existingUserIds[] = $userId;
        }
    }
    
    $filteredUsers = array_filter($allUsers, function($user) use ($query, $existingUserIds) {
        $matchesSearch = stripos($user['name'], $query) !== false || stripos($user['id'], $query) !== false;
        $notExisting = !in_array($user['id'], $existingUserIds);
        return $matchesSearch && $notExisting;
    });
    
    $users = array_slice($filteredUsers, 0, 10);
    echo json_encode(['success' => true, 'users' => array_values($users)]);
}
function inviteUserAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    $userId = $_POST['user_id'] ?? '';
    
    if (empty($calendarId) || empty($userId)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID and User ID are required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    $userRole = $userCalendars[$calendarId]['role'] ?? '';
    
    if ($userRole !== 'owner') {
        echo json_encode(['success' => false, 'error' => 'Only the owner can invite users.']);
        exit;
    }
    
    foreach ($calendarData['collaborators'] as $collab) {
        if ($collab['id'] === $userId) {
            echo json_encode(['success' => false, 'error' => 'User is already a collaborator.']);
            exit;
        }
    }
    
    $userCalendars = loadUserCalendars($userId);
    if (isset($userCalendars[$calendarId])) {
        echo json_encode(['success' => false, 'error' => 'User already has a pending invitation.']);
        exit;
    }
    
    $userInfo = getUserInfo($userId);
    $senderInfo = getUserInfo($_SESSION['id']);
    
    $inviteId = uniqid();
    $userCalendars[$calendarId] = [
        'role' => 'pending',
        'invites' => [
            [
                'id' => $inviteId,
                'invited_by' => $_SESSION['id'],
                'invited_by_name' => $senderInfo['name'],
                'invited_at' => time()
            ]
        ],
        'added_at' => time()
    ];
    
    if (saveUserCalendars($userId, $userCalendars)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to send invitation.']);
    }
}


function removeCollaboratorAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    $userId = $_POST['user_id'] ?? '';
    
    if (empty($calendarId) || empty($userId)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID and User ID are required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    $userRole = $userCalendars[$calendarId]['role'] ?? '';
    
    if ($userRole !== 'owner') {
        echo json_encode(['success' => false, 'error' => 'Only the owner can remove collaborators.']);
        exit;
    }
    
    $isCollaborator = false;
    foreach ($calendarData['collaborators'] as $collab) {
        if ($collab['id'] === $userId) {
            $isCollaborator = true;
            break;
        }
    }
    
    if (!$isCollaborator) {
        echo json_encode(['success' => false, 'error' => 'User is not a collaborator on this calendar.']);
        exit;
    }
    
    $originalCollaboratorCount = count($calendarData['collaborators']);
    $calendarData['collaborators'] = array_values(array_filter(
        $calendarData['collaborators'],
        function($collab) use ($userId) {
            return $collab['id'] !== $userId;
        }
    ));
    
    $newCollaboratorCount = count($calendarData['collaborators']);
    
    if ($originalCollaboratorCount === $newCollaboratorCount) {
        echo json_encode(['success' => false, 'error' => 'Failed to remove collaborator from calendar data.']);
        exit;
    }
    
    $targetUserCalendars = loadUserCalendars($userId);
    $hadCalendarReference = isset($targetUserCalendars[$calendarId]);
    
    if ($hadCalendarReference) {
        unset($targetUserCalendars[$calendarId]);
        $userCalendarsSaved = saveUserCalendars($userId, $targetUserCalendars);
        
        if (!$userCalendarsSaved) {
            echo json_encode(['success' => false, 'error' => 'Failed to update user calendar references.']);
            exit;
        }
    }
    
    if (saveCalendar($calendarId, $calendarData)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to save calendar data.']);
    }
}

function cancelInviteAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    $inviteId = $_POST['invite_id'] ?? '';
    
    if (empty($calendarId) || empty($inviteId)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID and Invite ID are required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    $userRole = $userCalendars[$calendarId]['role'] ?? '';
    
    if ($userRole !== 'owner') {
        echo json_encode(['success' => false, 'error' => 'Only the owner can cancel invitations.']);
        exit;
    }
    
    $district = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    $userFolders = glob(__DIR__ . "/../userdata/users/$district/*", GLOB_ONLYDIR);
    
    $inviteCancelled = false;
    $errors = [];
    
    foreach ($userFolders as $userFolder) {
        $userId = basename($userFolder);
        $userCalendars = loadUserCalendars($userId);
        
        if (isset($userCalendars[$calendarId]) && $userCalendars[$calendarId]['role'] === 'pending') {
            $originalInviteCount = count($userCalendars[$calendarId]['invites']);
            
            $userCalendars[$calendarId]['invites'] = array_values(array_filter(
                $userCalendars[$calendarId]['invites'],
                function($invite) use ($inviteId, &$inviteCancelled) {
                    if ($invite['id'] === $inviteId) {
                        $inviteCancelled = true;
                        return false;
                    }
                    return true;
                }
            ));
            
            $newInviteCount = count($userCalendars[$calendarId]['invites']);
            
            if ($originalInviteCount === $newInviteCount) {
                continue;
            }
            
            if (empty($userCalendars[$calendarId]['invites'])) {
                unset($userCalendars[$calendarId]);
            }
            
            if (!saveUserCalendars($userId, $userCalendars)) {
                $errors[] = "Failed to update user $userId";
            } else {
                $inviteCancelled = true;
                break;
            }
        }
    }
    
    if ($inviteCancelled) {
        if (!empty($errors)) {
            echo json_encode(['success' => true, 'warning' => 'Invite cancelled but some updates failed: ' . implode(', ', $errors)]);
        } else {
            echo json_encode(['success' => true]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invite not found or already cancelled.']);
    }
}

function verifyCalendarConsistency($calendarId) {
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) return false;
    
    $district = isset($_SESSION["url"]) ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) : 'unknown';
    $userFolders = glob(__DIR__ . "/../userdata/users/$district/*", GLOB_ONLYDIR);
    
    $issues = [];
    
    foreach ($calendarData['collaborators'] as $collaborator) {
        $userCalendars = loadUserCalendars($collaborator['id']);
        if (!isset($userCalendars[$calendarId]) || $userCalendars[$calendarId]['role'] !== 'collaborator') {
            $issues[] = "Collaborator {$collaborator['id']} missing user calendar reference";
        }
    }
    
    foreach ($userFolders as $userFolder) {
        $userId = basename($userFolder);
        $userCalendars = loadUserCalendars($userId);
        
        if (isset($userCalendars[$calendarId]) && $userCalendars[$calendarId]['role'] === 'collaborator') {
            $found = false;
            foreach ($calendarData['collaborators'] as $collaborator) {
                if ($collaborator['id'] === $userId) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $issues[] = "User $userId has collaborator role but not in calendar collaborators";
            }
        }
    }
    
    return empty($issues) ? true : $issues;
}

function deleteEventAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    $eventId = $_POST['event_id'] ?? '';
    $date = $_POST['date'] ?? '';
    
    if (empty($calendarId) || empty($eventId)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID and Event ID are required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    $userRole = $userCalendars[$calendarId]['role'] ?? '';
    
    if ($userRole !== 'owner' && $userRole !== 'collaborator') {
        echo json_encode(['success' => false, 'error' => 'No permission to delete events from this calendar.']);
        exit;
    }
    
    $eventFound = false;
    
    if (!empty($date) && isset($calendarData['events'][$date])) {
        $calendarData['events'][$date] = array_values(array_filter(
            $calendarData['events'][$date],
            function($event) use ($eventId, &$eventFound) {
                if ($event['id'] === $eventId) {
                    $eventFound = true;
                    return false;
                }
                return true;
            }
        ));
        
        if (empty($calendarData['events'][$date])) {
            unset($calendarData['events'][$date]);
        }
    } else {
        foreach ($calendarData['events'] as $eventDate => $events) {
            $calendarData['events'][$eventDate] = array_filter(
                $events,
                function($event) use ($eventId, &$eventFound) {
                    if ($event['id'] === $eventId) {
                        $eventFound = true;
                        return false;
                    }
                    return true;
                }
            );
            
            if (empty($calendarData['events'][$eventDate])) {
                unset($calendarData['events'][$eventDate]);
            }
        }
    }
    
    if (!$eventFound) {
        echo json_encode(['success' => false, 'error' => 'Event not found.']);
        exit;
    }
    
    if (saveCalendar($calendarId, $calendarData)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete event.']);
    }
}

function acceptInviteAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    
    if (empty($calendarId)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID is required.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    
    if (!isset($userCalendars[$calendarId]) || $userCalendars[$calendarId]['role'] !== 'pending') {
        echo json_encode(['success' => false, 'error' => 'Invite not found or already processed.']);
        exit;
    }
    
    $userCalendars[$calendarId]['role'] = 'collaborator';
    if (isset($userCalendars[$calendarId]['invites'])) {
        unset($userCalendars[$calendarId]['invites']);
    }
    
    $calendarData = loadCalendar($calendarId);
    if ($calendarData) {
        $userInfo = getUserInfo($_SESSION['id']);
        
        if (!isset($calendarData['collaborators'])) {
            $calendarData['collaborators'] = [];
        }
        
        $isAlreadyCollaborator = false;
        foreach ($calendarData['collaborators'] as $collab) {
            if ($collab['id'] === $_SESSION['id']) {
                $isAlreadyCollaborator = true;
                break;
            }
        }
        
        if (!$isAlreadyCollaborator) {
            $calendarData['collaborators'][] = [
                'id' => $_SESSION['id'],
                'name' => $userInfo['name']
            ];
            
            if (!saveCalendar($calendarId, $calendarData)) {
                echo json_encode(['success' => false, 'error' => 'Failed to update calendar data.']);
                exit;
            }
        }
    }
    
    if (saveUserCalendars($_SESSION['id'], $userCalendars)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to accept invitation.']);
    }
}

function declineInviteAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    
    if (empty($calendarId)) {
        echo json_encode(['success' => false, 'error' => 'Calendar ID is required.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    
    if (isset($userCalendars[$calendarId]) && $userCalendars[$calendarId]['role'] === 'pending') {
        unset($userCalendars[$calendarId]);
        
        if (saveUserCalendars($_SESSION['id'], $userCalendars)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to decline invitation.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invite not found.']);
    }
}

function updateEventAction() {
    $calendarId = $_POST['calendar_id'] ?? '';
    $eventId = $_POST['event_id'] ?? '';
    $date = $_POST['date'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if (empty($calendarId) || empty($eventId) || empty($date) || empty($subject) || empty($type)) {
        echo json_encode(['success' => false, 'error' => 'All fields are required.']);
        exit;
    }
    
    $calendarData = loadCalendar($calendarId);
    if (!$calendarData) {
        echo json_encode(['success' => false, 'error' => 'Calendar not found.']);
        exit;
    }
    
    $userCalendars = loadUserCalendars($_SESSION['id']);
    $userRole = $userCalendars[$calendarId]['role'] ?? '';
    
    if ($userRole !== 'owner' && $userRole !== 'collaborator') {
        echo json_encode(['success' => false, 'error' => 'No permission to edit events in this calendar.']);
        exit;
    }
    
    $eventFound = false;
    
    foreach ($calendarData['events'] as $eventDate => &$events) {
        foreach ($events as &$event) {
            if ($event['id'] === $eventId) {
                $event['subject'] = $subject;
                $event['type'] = $type;
                $event['description'] = $description;
                $event['updated_by'] = $_SESSION['id'];
                $event['updated_at'] = time();
                $eventFound = true;
                
                if ($eventDate !== $date) {
                    $calendarData['events'][$eventDate] = array_filter(
                        $calendarData['events'][$eventDate],
                        function($e) use ($eventId) {
                            return $e['id'] !== $eventId;
                        }
                    );
                    
                    if (!isset($calendarData['events'][$date])) {
                        $calendarData['events'][$date] = [];
                    }
                    $calendarData['events'][$date][] = $event;
                    
                    if (empty($calendarData['events'][$eventDate])) {
                        unset($calendarData['events'][$eventDate]);
                    }
                }
                
                break 2;
            }
        }
    }
    
    if (!$eventFound) {
        echo json_encode(['success' => false, 'error' => 'Event not found.']);
        exit;
    }
    
    if (saveCalendar($calendarId, $calendarData)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update event.']);
    }
}