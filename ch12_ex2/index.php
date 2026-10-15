<?php

$lifetime = 60 * 60 * 24 * 365;
session_set_cookie_params($lifetime, '/');
session_start();

if (isset($_SESSION['tasklist'])) {
    $task_list = $_SESSION['tasklist'];
} else {
    $task_list = array();
}
$action = filter_input(INPUT_POST, 'action');
$errors = array();

switch($action) {
    case 'add':
        $new_task = filter_input(INPUT_POST, 'newtask');
        if (empty($new_task)) {
            $errors[] = 'The new task cannot be empty.';
        } else {
            $task_list[] = $new_task;
        }
        break;
    case 'delete':
        $task_id = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_id === NULL || $task_id === FALSE) {
            $errors[] = 'The task cannot be deleted.';
        } else {
            unset($task_list[$task_id]);
            $task_list = array_values($task_list);
        }
        break;
}

$_SESSION['tasklist'] = $task_list;

include('task_list.php');

?>

