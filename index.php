<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');

include './classes/DB.php';
// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

$tables = ['posts'];

$url = rtrim($request, '/');
$url = filter_var($request, FILTER_SANITIZE_URL);
$url = explode('/', $url);
// print_r($url);
$tableName = (string) $url[3];

if ($url[4] != null) {
    $id = (int) $url[4];
} else {
    $id = null;
}
if (in_array($tableName, $tables)) {
    include "./api/$tableName.php";
} else {
    echo json_encode(['message' => 'Bad method call.', 'status' => 404]);
}
