<?php 

// Read Single Task Data

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include_once('../core/init.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {


$task = new Task($db);

$task->id = isset($_GET['id']) ? $_GET['id'] : die();

$result = $task->read_single();

$num = $result->rowCount();

if($num > 0) {
    $task_arr = array();
    $task_arr['data'] = array();

    $row = $result->fetch(PDO::FETCH_ASSOC);

    $task_item = array(
        'id' => $row['id'],
        'description' => html_entity_decode($row['description']),
        'completed' => $row['completed'] == 1 ? true: false,
        'initiated_at' => $row['created_at'],
        'user_id' => $row['user_id'],
        'name' => $row['name'],
    );
    
    array_push($task_arr['data'], $task_item); 

    http_response_code(200);
    echo json_encode($task_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'No task found.'));
    
}
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}

?>