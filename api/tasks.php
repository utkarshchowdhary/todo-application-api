<?php

// Read All Tasks Data

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json; charset=UTF-8");

include_once('../core/init.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $task = new Task($db);

    $task->id = isset($_GET['id']) ? $_GET['id'] : NULL;


    // Read All tasks Data  GET /todo/api/tasks.php
    if (is_null($task->id)) {

        $result = $task->read();

        $num = $result->rowCount();

        if ($num > 0) {
            $task_arr = array();
            $task_arr['data'] = array();

            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $task_item = array(
                    'id' => $id,
                    'description' => html_entity_decode($description),
                    'completed' => $completed == 1 ? true : false,
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

        // Read Single task Data  GET /todo/api/tasks.php?id=?
    } elseif (!is_null($task->id)) {
        $result = $task->read_single();

        $num = $result->rowCount();

        if ($num > 0) {
            $task_arr = array();
            $task_arr['data'] = array();

            $row = $result->fetch(PDO::FETCH_ASSOC);

            $task_item = array(
                'id' => $row['id'],
                'description' => html_entity_decode($row['description']),
                'completed' => $row['completed'] == 1 ? true : false,
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
    }

    // Create a new Task POST /todo/api/tasks.php
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = new Task($db);

    $data = json_decode(file_get_contents("php://input"));

    if (
        !empty($data->description) &&
        isset($data->completed) &&
        !empty($data->user_id)
    ) {

        $task->description = $data->description;
        $task->completed = $data->completed;
        $task->user_id = $data->user_id;

        if ($task->create()) {
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

    // Update an existing Task PUT /todo/api/tasks.php?id=?
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {

    $task = new Task($db);

    $data = json_decode(file_get_contents("php://input"));

    $task->id = isset($_GET['id']) ? $_GET['id'] : die();

    if (
        !empty($data->description) &&
        isset($data->completed)
    ) {

        $task->description = $data->description;
        $task->completed = $data->completed;

        if ($task->update()) {
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

    // Delete an existing Task DELETE /todo/api/tasks.php?id=?
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $task = new Task($db);

    $task->id = isset($_GET['id']) ? $_GET['id'] : die();

    if ($task->delete()) {
        http_response_code(200);
        echo json_encode(
            array('message' => 'Task deleted.')
        );
    } else {
        http_response_code(503);
        echo json_encode(
            array('message' => 'Unable to delete task.')
        );
    }
} else {
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed.'));
}
