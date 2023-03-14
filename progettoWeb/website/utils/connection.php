<?php

const search_users_query = "SELECT id, email, username FROM users WHERE LOWER(username) LIKE ? OR LOWER(email) LIKE ?;";
const insert_user_query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?);";
const get_notes_query =
    "SELECT label, text, notes.id as note_id, comments.note_id as comment_id, date FROM notes".
    " LEFT JOIN comments ON notes.id = comments.note_id".
    " LEFT JOIN users ON notes.user_id = users.id WHERE users.email = ?".
    " ORDER BY date DESC".
    " LIMIT ?, ?;";
const get_user_id_query = "SELECT id FROM users WHERE email = ?;";
const insert_note_query = "INSERT INTO notes (label, user_id) SELECT ?, id FROM users WHERE email = ?;";
const insert_comment_query = "INSERT INTO comments (text, note_id) VALUES (?, LAST_INSERT_ID);";
const search_notes_query = "SELECT label, text, notes.id as note_id, comments.note_id as comment_id, date FROM notes".
    " LEFT JOIN comments ON notes.id = comments.note_id".
    " LEFT JOIN users ON notes.user_id = users.id WHERE users.email = ?".
    " AND (LOWER(label) LIKE ? OR LOWER(text) LIKE ?)".
    " ORDER BY date DESC".
    " LIMIT ?, ?;";
const check_note_owner_query = "SELECT * FROM notes WHERE id = ? AND user_id = (SELECT id from users WHERE email = ?);";
const update_note_query = "UPDATE notes SET label = ? WHERE id = ? AND user_id = (SELECT id from users WHERE email = ?);";
const update_comment_query = "UPDATE comments SET text = ? WHERE note_id = ?;";

function connect(): void
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

function execute($query, $types, ...$params): mysqli_result
{
    global $conn;
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result();
}

function email_exists($email): bool
{
    return execute(get_user_id_query, "s", $email)->num_rows > 0;
}

function get_user($email): ?array
{
    return execute(get_user_id_query, "s", $email)->fetch_assoc();
}

function add_user($username, $password, $email): void
{
    execute(insert_user_query, "sss", $username, $password, $email);
}

function add_note($email, $label, $comment=NULL): void
{
    execute(insert_note_query, "ss", $label, $email);
    execute(insert_comment_query, "s", $comment);
}

function get_notes($email, $limit=100, $offset=0): ?array
{
    return execute(get_notes_query, "sii", $email, $offset, $limit)->fetch_all(MYSQLI_ASSOC);
}

function get_notes_containing($email, $query, $limit=100, $offset=0): ?array
{
    $query = "%".$query."%";
    $query = strtolower($query);
    return execute(search_notes_query, "sssii", $email, $query, $query, $offset, $limit)->fetch_all(MYSQLI_ASSOC);
}


function edit_note($email, $note_id, $label, $comment=NULL): void
{
    global $conn;
    $stmt = $conn->prepare();
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
    $query = strtolower($query);
    $stmt = $conn->prepare(search_users_query);
    $stmt->bind_param("ss", $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function share($note, $email){

}