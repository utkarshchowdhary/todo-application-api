<?php 

// Read Single User Data

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include_once('../core/init.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {

$user = new User($db);

$user->id = isset($_GET['id']) ? $_GET['id'] : die();

$result = $user->read_single();

$num = $result->rowCount();

if($num > 0) {
    $user_arr = array();
    $user_arr['data'] = array();
    $count = 0;

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $count++;
        
        if($count == 1 ) {
        $user_item = array(
            'id' => $id,
            'name' => $name,
            'age' => $age,
            'residence' => $residence,
            'created_at' => $created_at,
            'tasks' => array()
        );

        array_push($user_arr['data'], $user_item); 
    }

        if(!is_null($task_id)) {
        array_push($user_arr['data'][0]['tasks'] , array(
            'task_id' => $task_id,
            'description' => html_entity_decode($description),
            'completed' => $completed == 1 ? true: false,
            'initiated_at' => $initiated_at,
        ));
    }

}
    http_response_code(200);
    echo json_encode($user_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'No user found.'));
    
}
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}

?>