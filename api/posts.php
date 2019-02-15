<?php


if ($method == 'GET') {
    if ($id) {
        $data = DB::query_assoc("SELECT * FROM $tableName WHERE id=:id", array(':id' => $id));
        echo json_encode($data[0]);
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
        $data = DB::query_assoc("SELECT * FROM $tableName ORDER BY id DESC LIMIT 1");
        echo json_encode(['post' => $data[0], 'message' => 'Post added to the database successfully.', 'status' => 201]);
    } else {
        echo json_encode(['message' => 'Please fill in all the credentials.', 'status' => 403]);
    }
} elseif ($method == 'PUT' && $id) {
    $post = DB::query_assoc("SELECT * FROM $tableName WHERE id=:id", array(':id' => $id));
    if ($post != null) {
        $_PUT = json_decode(file_get_contents('php://input'), true);
        $title = $_PUT['title'];
        $content = $_PUT['content'];
        $author = $_PUT['author'];
        DB::query("UPDATE $tableName SET title=:title, content=:content, author=:author WHERE id=:id", array(':title' => $title, ':content' => $content, ':author' => $author, ':id' => $id));
        $data = DB::query_assoc("SELECT * FROM $tableName WHERE id=:id", array(':id' => $id));
        echo json_encode(['post' => $data[0], 'message' => 'Post Updated Successfully.', 'status' => 201]);
    } else {
        echo json_encode(['message' => 'Post Not Found', 'status' => 201]);
    }
} elseif ($method == 'DELETE' && $id) {
    $post = DB::query_assoc("SELECT * FROM $tableName WHERE id=:id", array(':id' => $id));
    if ($post != null) {
        DB::query("DELETE FROM $tableName WHERE id=:id", array(':id' => $id));
        echo json_encode(['message' => 'Posts Deleted Successfully.', 'status' => 200]);
    } else {
        echo json_encode(['message' => 'Post Not Found', 'status' => 201]);
    }
}
