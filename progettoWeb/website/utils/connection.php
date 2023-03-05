<?php

function connect()
{
    $servername = "127.0.0.1";
    $username = "facenda5inc2022";
    $password = "";
    $dbname = "my_facenda5inc2022";
    global $conn;
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

function email_exists($email): bool
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

function username_exists($username){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

function get_user($email): ?array
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }else{
        return null;
    }
}

function add_user($username, $password, $email): void
{
    global $conn;
    $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
    $password = password_hash($password, PASSWORD_BCRYPT);
    $stmt->bind_param("sss", $username, $password, $email);
    $stmt->execute();
    $stmt->close();
}

function add_note($email, $label, $comment=NULL): void
{
    global $conn;
    if($comment == NULL){
        $stmt = $conn->prepare("INSERT INTO notes (label, user_id) SELECT ?, id FROM users WHERE email = ?;");
        $stmt->bind_param("ss", $label, $email);
    }else{
        $stmt = $conn->prepare("INSERT INTO notes (label, user_id) SELECT ?, id FROM users WHERE email = ?;".
                                "INSERT INTO comments (note_id, text) VALUES (LAST_INSERT_ID(), ?);");
        $stmt->bind_param("sss", $label, $email, $comment);
    }
    $stmt->execute();
    $stmt->close();
}

function get_notes($email, $limit=18446744073709551615, $offset=0): ?array
{
    global $conn;
    $stmt = $conn->prepare("SELECT label, text, notes.id as note_id, comments.note_id as comment_id, date FROM notes".
                            " LEFT JOIN comments ON notes.id = comments.note_id".
                            " LEFT JOIN users ON notes.user_id = users.id WHERE users.email = ?".
                            " LIMIT ?, ?;");
    $stmt->bind_param("sii", $email, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }else{
        return null;
    }
}

function edit_note($note_id, $label, $comment=NULL): void
{
    global $conn;
    if($comment == NULL){
        $stmt = $conn->prepare("UPDATE notes SET label = ? WHERE id = ?;");
        $stmt->bind_param("si", $label, $note_id);
    }else{
        $stmt = $conn->prepare("UPDATE notes SET label = ? WHERE id = ?;".
                                "UPDATE comments SET text = ? WHERE note_id = ?;");
        $stmt->bind_param("sisi", $label, $note_id, $comment, $note_id);
    }
    $stmt->execute();
    $stmt->close();
}

function delete_note($note_id): void
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ?;".
                            "DELETE FROM comments WHERE note_id = ?;");
    $stmt->bind_param("i", $note_id);
    $stmt->execute();
    $stmt->close();
}

function delete_comment($comment_id): void
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?;");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $stmt->close();
}