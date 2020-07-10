<?php 

// Create a User

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once('../core/init.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if( 
    !empty($data->name) && 
    !empty($data->age) && 
    !empty($data->residence) 
) {

$user->name = $data->name;
$user->age = $data->age;
$user->residence = $data->residence;

if($user->create()) {
    http_response_code(201);
    echo json_encode(
        array('message' => 'User created.')
    );
} else {
    http_response_code(500);
    echo json_encode(
        array('message' => 'Unable to create user.')
    );
}
} else {
    http_response_code(400);
    echo json_encode(
        array('message' => 'Unable to create user. Insufficient data.')
    );
}
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}
?>