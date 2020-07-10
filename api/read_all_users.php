<?php 

// Read All Users Data

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include_once('../core/init.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {

$user = new User($db);

$result = $user->read();

$num = $result->rowCount();

if($num > 0) {
    $user_arr = array();
    $user_arr['data'] = array();

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $found = false;

        foreach ($user_arr['data'] as &$item) {

            foreach($item as $key => $value) {
                if($key == 'id' and $value == $id) {
                    $found = true;
                    array_push($item['tasks'] , array(
                        'task_id' => $task_id,
                        'description' => html_entity_decode($description),
                        'completed' => $completed == 1 ? true: false,
                        'initiated_at' => $initiated_at
                    ));
                    break 2;
                }
            }
        }


        if(!$found) {

        $user_item = array(
            'id' => $id,
            'name' => $name,
            'age' => $age,
            'residence' => $residence,
            'created_at' => $created_at,
            'tasks' => array()
        );
        if(!is_null($task_id)) {
        array_push($user_item['tasks'] , array(
            'task_id' => $task_id,
            'description' => html_entity_decode($description),
            'completed' => $completed == 1 ? true: false,
            'initiated_at' => $initiated_at,
        ));
    }

        array_push($user_arr['data'], $user_item); 
    }
}
    http_response_code(200);
    echo json_encode($user_arr);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'No users found.'));
    
}
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}

?>