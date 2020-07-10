<?php 

// Read All Tasks Data

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include_once('../core/init.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {

$task = new Task($db);

$result = $task->read();

$num = $result->rowCount();

if($num > 0) {
    $task_arr = array();
    $task_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $task_item = array(
            'id' => $id,
            'description' => html_entity_decode($description),
            'completed' => $completed == 1 ? true: false,
            'initiated_at' => $initiated_at,
            'user_id' => $user_id,
            'name' => $name,
        );
        
        array_push($task_arr['data'], $task_item); 
}
    http_response_code(200);
    echo json_encode($task_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'No tasks found.'));
    
}
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}

?>