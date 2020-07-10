<?php 

// Delete a User

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../core/init.php');

if($_SERVER['REQUEST_METHOD'] === 'DELETE') {

$user = new User($db);

$user->id = isset($_GET['id']) ? $_GET['id'] : die();

if($user->delete()) {
    http_response_code(200);
    echo json_encode(
        array('message' => 'User deleted.')
    );
} else {
    http_response_code(503);
    echo json_encode(
        array('message' => 'Unable to delete user.')
    );
}
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}
?>