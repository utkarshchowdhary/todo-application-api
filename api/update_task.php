<?php 

// Update a Task

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../core/init.php');

if($_SERVER['REQUEST_METHOD'] === 'PUT') {

$task = new Task($db);

$data = json_decode(file_get_contents("php://input"));

$task->id = isset($_GET['id']) ? $_GET['id'] : die();

if(
    !empty($data->description) && 
    isset($data->completed)
) {

$task->description = $data->description;
$task->completed = $data->completed;

if($task->update()) {
    http_response_code(200);
    echo json_encode(
        array('message' => 'task updated.')
    );
} else {
    http_response_code(503);
    echo json_encode(
        array('message' => 'Unable to update task.')
    );
}
} else {
    http_response_code(400);
    echo json_encode(
        array('message' => 'Unable to update task. Insufficient data.')
    );
}
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}
?>