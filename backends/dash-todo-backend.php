<?php

// I need to consolidate this into one file

session_start();
header('Content-Type: application/json');
date_default_timezone_set('US/Central');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (empty($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$userId = $_SESSION['id'];
$action = $_POST['action'] ?? '';

function getTodoFilePath($userId) {
    $dataDir = __DIR__ . '/../userdata/users/' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $_SESSION["url"]) . '/' . $userId;
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    return $dataDir . '/todos.json';
}

function getTodos($userId) {
    $filePath = getTodoFilePath($userId);
    if (!file_exists($filePath)) {
        return [];
    }
    
    $data = file_get_contents($filePath);
    if ($data === false) {
        return [];
    }
    
    $todos = json_decode($data, true);
    return is_array($todos) ? $todos : [];
}

function saveAllTodos($userId, $todos) {
    $filePath = getTodoFilePath($userId);
    $result = file_put_contents($filePath, json_encode($todos, JSON_PRETTY_PRINT));
    return $result !== false;
}

function saveTodo($userId, $todo) {
    $todos = getTodos($userId);
    $todoId = uniqid('todo_', true);
    $todos[$todoId] = $todo;
    return saveAllTodos($userId, $todos);
}

function deleteTodo($userId, $todoId) {
    $todos = getTodos($userId);
    if (isset($todos[$todoId])) {
        unset($todos[$todoId]);
        return saveAllTodos($userId, $todos);
    }
    return false;
}

try {
    switch ($action) {
        case 'get_todos':
            $todos = getTodos($userId);
            echo json_encode(['success' => true, 'todos' => $todos]);
            break;
            
        case 'add_todo':
            $title = trim($_POST['title'] ?? '');
            
            if (empty($title)) {
                echo json_encode(['success' => false, 'error' => 'Title is required']);
                exit;
            }
            
            $todo = [
                'title' => $title,
                'due_date' => null,
                'important' => isset($_POST['important']) && $_POST['important'] === 'true',
                'reminder' => null,
                'completed' => false,
                'created_at' => time()
            ];
            
            if (!empty($_POST['due_date'])) {
                $timestamp = strtotime($_POST['due_date']);
                if ($timestamp !== false) {
                    $todo['due_date'] = $timestamp;
                }
            }
            
            if (saveTodo($userId, $todo)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to save todo']);
            }
            break;
            
        case 'update_todo':
            $todoId = $_POST['id'] ?? '';
            $todos = getTodos($userId);
            
            if (!isset($todos[$todoId])) {
                echo json_encode(['success' => false, 'error' => 'Todo not found']);
                exit;
            }
            
            $todo = $todos[$todoId];
            
            if (isset($_POST['title'])) {
                $todo['title'] = trim($_POST['title']);
            }
            
            if (isset($_POST['due_date'])) {
                $todo['due_date'] = !empty($_POST['due_date']) ? strtotime($_POST['due_date']) : null;
            }
            
            if (isset($_POST['important'])) {
                $todo['important'] = $_POST['important'] === 'true';
            }
            
            if (isset($_POST['completed'])) {
                $todo['completed'] = $_POST['completed'] === 'true';
            }
            
            $todos[$todoId] = $todo;
            
            if (saveAllTodos($userId, $todos)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to update todo']);
            }
            break;
            
        case 'delete_todo':
            $todoId = $_POST['id'] ?? '';
            
            if (deleteTodo($userId, $todoId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete todo']);
            }
            break;
            
        case 'update_todo_order':
            $orderJson = $_POST['order'] ?? '[]';
            $order = json_decode($orderJson, true);
            
            if (!is_array($order)) {
                echo json_encode(['success' => false, 'error' => 'Invalid order data']);
                exit;
            }
            
            $todos = getTodos($userId);
            $reorderedTodos = [];
            
            foreach ($order as $todoId) {
                if (isset($todos[$todoId])) {
                    $reorderedTodos[$todoId] = $todos[$todoId];
                }
            }
            
            // Add any remaining todos
            foreach ($todos as $todoId => $todo) {
                if (!isset($reorderedTodos[$todoId])) {
                    $reorderedTodos[$todoId] = $todo;
                }
            }
            
            if (saveAllTodos($userId, $reorderedTodos)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to save order']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Unknown action: ' . $action]);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
}
?>