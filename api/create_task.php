<?php 

// Create a Task

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../core/init.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

$task = new Task($db);

$data = json_decode(file_get_contents("php://input"));

if( 
    !empty($data->description) && 
    isset($data->completed) && 
    !empty($data->user_id) 
) {

$task->description = $data->description;
$task->completed = $data->completed;
$task->user_id = $data->user_id;

if($task->create()) {
    http_response_code(201);
    echo json_encode(
        array('message' => 'Task created.')
    );
} else {
    http_response_code(500);
    echo json_encode(
        array('message' => 'Unable to create task.')
    );
}
} else {
    http_response_code(400);
    echo json_encode(
        array('message' => 'Unable to create task. Insufficient data.')
    );
}
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}
?>