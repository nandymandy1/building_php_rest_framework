<?php

header('Content-Type: application/json');
include './classes/DB.php';
// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];
$host = $_SERVER['HTTP_HOST'];
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

include './api/posts.php';
