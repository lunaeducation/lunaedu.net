<?php
session_start();
require_once(__DIR__ . '/../_backend-libs.php');

if (!empty($_SESSION['id']) && isset($_POST['leaderboard_action'])) {
    $prefs = loadPrefs($_SESSION['id']);
    
    if (!isset($prefs['leaderboard'])) {
        $prefs['leaderboard'] = [
            'enabled' => false,
            'participate' => false,
            'alias' => '',
            'show_nav' => true
        ];
    }
    
    $action = $_POST['leaderboard_action'];
    
    switch ($action) {
        case 'toggle_leaderboard':
            $prefs['leaderboard']['enabled'] = isset($_POST['enabled']);
            
            if (!$prefs['leaderboard']['enabled']) {
                $prefs['leaderboard']['participate'] = false;
                $prefs['leaderboard']['show_nav'] = false;
            } else {
                $prefs['leaderboard']['show_nav'] = true;
            }
            break;
            
        case 'toggle_participation':
            if ($prefs['leaderboard']['enabled']) {
                $prefs['leaderboard']['participate'] = isset($_POST['participate']);
            }
            break;
            
        case 'set_alias':
            if ($prefs['leaderboard']['enabled']) {
                $alias = trim($_POST['alias'] ?? '');
                if (empty($alias)) {
                    $prefs['leaderboard']['alias'] = 'Anonymous';
                } else {
                    $prefs['leaderboard']['alias'] = htmlspecialchars($alias);
                }
            }
            break;
            
        case 'toggle_nav':
            if ($prefs['leaderboard']['enabled']) {
                $prefs['leaderboard']['show_nav'] = isset($_POST['show_nav']);
            }
            break;
    }
    
    savePrefs($_SESSION['id'], $prefs);
    
    // Update session variables
    $_SESSION['leaderboard_enabled'] = $prefs['leaderboard']['enabled'];
    $_SESSION['leaderboard_participate'] = $prefs['leaderboard']['participate'];
    $_SESSION['leaderboard_alias'] = $prefs['leaderboard']['alias'];
    $_SESSION['leaderboard_show_nav'] = $prefs['leaderboard']['show_nav'];
}

header("Location: /user");
exit;