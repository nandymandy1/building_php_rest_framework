<?php

header('Content-Type: application/json');
include '../classes/DB.php';
// get the HTTP method, path and body of the request
$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];
$host = $_SERVER['HTTP_HOST'];
$tables = ['posts'];

$url = rtrim($request, '/');
$url = filter_var($request, FILTER_SANITIZE_URL);
$url = explode('/', $url);
$tableName = (string) $url[3];

if ($url[4] != null) {
    $id = (int) $url[4];
} else {
    $id = null;
}

if (in_array($tableName, $tables)) {
    if ($method == 'GET') {
        if ($id) {
            $data = DB::query_assoc("SELECT * FROM $tableName WHERE id=:id", array(':id' => $id));
            echo json_encode($data);
        } else {
            $data = DB::query_assoc("SELECT * FROM $tableName");
            echo json_encode($data);
        }
    } elseif ($method == 'POST') {
        if ($_POST != null && !$id) {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $author = $_POST['author'];
            DB::query("INSERT INTO $tableName VALUES(null, :title, :content, :author, null, null)", array(':title' => $title, ':content' => $content, ':author' => $author));
            echo json_encode(['message' => 'Post added to the database successfully.', 'status' => 201]);
        } else {
            echo json_encode(['message' => 'Please fill in all the credentials.', 'status' => 403]);
        }
    } elseif ($method == 'PUT' && $id) {
        $_PUT = json_decode(file_get_contents('php://input'), true);
        $title = $_PUT['title'];
        $content = $_PUT['content'];
        $author = $_PUT['author'];
        DB::query("UPDATE $tableName SET title=:title, content=:content, author=:author WHERE id=:id", array(':title' => $title, ':content' => $content, ':author' => $author, ':id' => $id));
        $data = DB::query_assoc("SELECT * FROM $tableName WHERE id=:id", array(':id' => $id));
        echo json_encode(['post' => $data[0], 'message' => 'Post Updated to the database successfully.', 'status' => 201]);
    } elseif ($method == 'DELETE' && $id) {
        DB::query("DELETE FROM $tableName WHERE id=:id", array(':id' => $id));
        echo json_encode(['message' => 'Posts Deleted Successfully.', 'status' => 200]);
    }
} else {
    echo json_encode(['message' => 'Bad method call.', 'status' => 404]);
}
