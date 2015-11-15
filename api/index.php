<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();

// http://hostname/api/
$app->get('/', function() use ( $app ) {
    echo "Welcome to Task REST API";
});

// http://domain.address/api/tasks
// get all tasks
$app->get('/tasks', function() use ( $app ) {
    $tasks = getTasks();
    //Define what kind is this response
    $app->response()->header('Content-Type','application/json');
    echo json_encode($tasks);
});

// http://domain.address/api/tasks/1
// get a task by id
$app->get('/tasks/:id', function($id) use ( $app ) {
    $tasks = getTasks();
    $index = array_search($id, array_column($tasks, 'id'));
    
    if($index > -1) {
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode($tasks[$index]);
    }
    else {
        $app->response()->setStatus(204);
    }
});

$app->post('/tasks', function() use ( $app ) {
    $taskJson = $app->request()->getBody();
    $task = json_decode($taskJson);
    if($task) {
        echo "{$task->description} added";
    }
    else {
        $app->response()->setStatus(400);
        echo "Malformat JSON";
    }
});

//TODO move it to a DAO class
function getTasks() {
    $tasks = array (
        array('id'=>1,'description'=>'Learn REST','done'=>false),
        array('id'=>2,'description'=>'Learn JavaScript','done'=>false),
        array('id'=>3,'description'=>'Learn English','done'=>false)
    );
    return $tasks;
}

$app->run();
?>