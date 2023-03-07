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
    $stmt = $conn->prepare("INSERT INTO notes (label, user_id) SELECT ?, id FROM users WHERE email = ?;");
    $stmt->bind_param("ss", $label, $email);
    if($comment == NULL){
        $stmt->execute();
        $stmt->close();
    }else{
        $stmt2 = $conn->prepare("INSERT INTO comments (note_id, text) VALUES (LAST_INSERT_ID(), ?);");
        $stmt2->bind_param("s", $comment);
        $stmt->execute();
        $stmt2->execute();
        $stmt->close();
        $stmt2->close();
    }
}

function get_notes($email, $limit=100, $offset=0): ?array
{
    global $conn;
    $stmt = $conn->prepare("SELECT label, text, notes.id as note_id, comments.note_id as comment_id, date FROM notes".
                            " LEFT JOIN comments ON notes.id = comments.note_id".
                            " LEFT JOIN users ON notes.user_id = users.id WHERE users.email = ?".
                            " ORDER BY date DESC".
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

function get_notes_containing($email, $limit=100, $offset=0, $query): ?array
{
    global $conn;
    $query = "%".$query."%";
    $query = strtolower($query);
    $stmt = $conn->prepare("SELECT label, text, notes.id as note_id, comments.note_id as comment_id, date FROM notes".
        " LEFT JOIN comments ON notes.id = comments.note_id".
        " LEFT JOIN users ON notes.user_id = users.id WHERE users.email = ?".
        " AND (LOWER(label) LIKE ? OR LOWER(text) LIKE ?)".
        " ORDER BY date DESC".
        " LIMIT ?, ?;");
    $stmt->bind_param("sssii", $email, $query, $query, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }else{
        return null;
    }
}


function edit_note($email, $note_id, $label, $comment=NULL): void
{
    global $conn;
    $stmt = $conn->prepare("UPDATE notes SET label = ? WHERE id = ? ".
        "AND user_id = (SELECT id from users WHERE email = ?)");
    $stmt->bind_param("sis", $label, $note_id, $email);
    $stmt->execute();
    if(!empty($comment) && $stmt->affected_rows > 0){
        // here the user is the certificated note owner

        // check if note has a comment
        $stmt = $conn->prepare("SELECT * FROM comments WHERE note_id = ?;");
        $stmt->bind_param("i", $note_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            // update comment
            $stmt = $conn->prepare("UPDATE comments SET text = ? WHERE note_id = ?;");
        }else{
            // add comment
            $stmt = $conn->prepare("INSERT INTO comments (text, note_id) VALUES (?, ?);");
        }
        $stmt->bind_param("si", $comment, $note_id);
        $stmt->execute();
        $stmt->close();
    }

}

function delete_note($email, $note_id): void
{
    global $conn;
    // delete label
    $stmt = $conn->prepare("DELETE FROM notes WHERE id = ?".
                            " AND user_id = (SELECT id from users WHERE email = ?)");
    $stmt->bind_param("is", $note_id, $email);
    $stmt->execute();
    // delete comment
    if($stmt->affected_rows > 0){
        $stmt = $conn->prepare("DELETE FROM comments WHERE note_id = ?;");
        $stmt->bind_param("i", $note_id);
        $stmt->execute();
        $stmt->close();
    }

}

function delete_comment($comment_id): void
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?;");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $stmt->close();
}

function get_users(): array
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users;");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function set_email_verified($email): void
{
    global $conn;
    $stmt = $conn->prepare("UPDATE users SET is_email_verified = 1 WHERE email = ?;");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
}

function search_users($query){
    global $conn;
    $query = "%".$query."%";
    $stmt = $conn->prepare("SELECT id, email, username FROM users WHERE username LIKE ? OR email LIKE ?;");
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_all(MYSQLI_ASSOC);
}