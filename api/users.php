<?php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include_once('../core/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $user = new User($db);

    $user->id = isset($_GET['id']) ? $_GET['id'] : NULL;

    // Read All Users Data  GET /todo/api/users.php
    if (is_null($user->id)) {

        $result = $user->read();

        $num = $result->rowCount();

        if ($num > 0) {
            $user_arr = array();
            $user_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $found = false;

                foreach ($user_arr['data'] as &$item) {

                    foreach ($item as $key => $value) {
                        if ($key == 'id' and $value == $id) {
                            $found = true;
                            array_push($item['tasks'], array(
                                'task_id' => $task_id,
                                'description' => html_entity_decode($description),
                                'completed' => $completed == 1 ? true : false,
                                'initiated_at' => $initiated_at
                            ));
                            break 2;
                        }
                    }
                }

                if (!$found) {

                    $user_item = array(
                        'id' => $id,
                        'name' => $name,
                        'age' => $age,
                        'residence' => $residence,
                        'created_at' => $created_at,
                        'tasks' => array()
                    );
                    if (!is_null($task_id)) {
                        array_push($user_item['tasks'], array(
                            'task_id' => $task_id,
                            'description' => html_entity_decode($description),
                            'completed' => $completed == 1 ? true : false,
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

        // Read Single User Data  GET /todo/api/users.php?id=?
    } elseif (!is_null($user->id)) {

        $result = $user->read_single();

        $num = $result->rowCount();

        if ($num > 0) {
            $user_arr = array();
            $user_arr['data'] = array();
            $count = 0;

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $count++;

                if ($count == 1) {
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

                if (!is_null($task_id)) {
                    array_push($user_arr['data'][0]['tasks'], array(
                        'task_id' => $task_id,
                        'description' => html_entity_decode($description),
                        'completed' => $completed == 1 ? true : false,
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
    }

    // Create a  new User POST /todo/api/users.php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->name) &&
        !empty($data->age) &&
        !empty($data->residence)
    ) {

        $user->name = $data->name;
        $user->age = $data->age;
        $user->residence = $data->residence;

        if ($user->create()) {
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

    // Update an existing User PUT /todo/api/users.php?id=?
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $user = new User($db);

    $data = json_decode(file_get_contents("php://input"));

    $user->id = isset($_GET['id']) ? $_GET['id'] : die();

    if (
        !empty($data->name) &&
        !empty($data->age) &&
        !empty($data->residence)
    ) {

        $user->name = $data->name;
        $user->age = $data->age;
        $user->residence = $data->residence;

        if ($user->update()) {
            http_response_code(200);
            echo json_encode(
                array('message' => 'User updated.')
            );
        } else {
            http_response_code(503);
            echo json_encode(
                array('message' => 'Unable to update user.')
            );
        }
    } else {
        http_response_code(400);
        echo json_encode(
            array('message' => 'Unable to update user. Insufficient data.')
        );
    }

    // Delete an existing User DELETE /todo/api/users.php?id=?
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $user = new User($db);

    $user->id = isset($_GET['id']) ? $_GET['id'] : die();

    if ($user->delete()) {
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
