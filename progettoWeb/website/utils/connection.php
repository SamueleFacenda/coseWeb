<?php

const search_users_query = "SELECT id, email, username FROM users WHERE MATCH(email, username) AGAINST(? WITH QUERY EXPANSION);";
const insert_user_query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?);";
const get_notes_query =
    "SELECT label, text, notes.id as note_id, comments.note_id as comment_id, date FROM notes".
    " LEFT JOIN comments ON notes.id = comments.note_id".
    " LEFT JOIN users ON notes.user_id = users.id WHERE users.email = ?".
    " ORDER BY date DESC".
    " LIMIT ?, ?;";
const get_user_id_query = "SELECT * FROM users WHERE email = ?;";
const insert_note_query = "INSERT INTO notes (label, user_id) SELECT ?, id FROM users WHERE email = ?;";
const insert_comment_query = "INSERT INTO comments (text, note_id) SELECT ?, id FROM notes ORDER BY date DESC LIMIT 1;";
const insert_comment_id_query = "INSERT INTO comments (text, note_id) VALUES (?, ?);";
const search_notes_query =
    "SELECT label, text, notes.id as note_id, comments.note_id as comment_id, date FROM notes".
    " LEFT JOIN comments ON notes.id = comments.note_id".
    " LEFT JOIN users ON notes.user_id = users.id WHERE users.email = ?".
    " AND (MATCH(label) AGAINST(?)".
    " OR MATCH(text) AGAINST(?))".
    " ORDER BY (MATCH(text) AGAINST(?) + MATCH(label) AGAINST(?)) DESC, date DESC".
    " LIMIT ?, ?;";
const check_note_owner_query =
    "SELECT 1 FROM notes WHERE id = ? AND user_id = (SELECT id from users WHERE email = ?) ".
    "UNION SELECT 1 FROM shared WHERE note_id = ? AND user_id = (SELECT id from users WHERE email = ?);";
const update_note_query = "UPDATE notes SET label = ? WHERE id = ?;";
const update_comment_query = "UPDATE comments SET text = ? WHERE note_id = ?;";
const delete_note_query = "DELETE FROM notes WHERE id = ?;";
const set_email_verified_query = "UPDATE users SET is_email_verified = 1 WHERE email = ?;";
const insert_shared_note_query = "INSERT INTO shared (note_id, user_id) SELECT ?, id FROM users WHERE email = ?;";
const comment_exists_query = "SELECT * FROM comments WHERE note_id = ?;";
const delete_comment_query = "DELETE FROM comments WHERE note_id = ?;";
const get_shared_notes_query =
    "SELECT id AS note_id, label, text ".
    "FROM notes ".
    "LEFT JOIN comments ".
    "ON comments.note_id = notes.id ".
    "RIGHT JOIN shared ".
    "ON shared.note_id = notes.id ".
    "WHERE shared.user_id = ".
    "(SELECT id FROM users WHERE email = ?) ".
    "OR notes.user_id = ".
    "(SELECT id FROM users WHERE email = ?) ".
    "GROUP BY note_id ".
    "ORDER BY date DESC ".
    "LIMIT ?, ?;";
const search_shared_notes_query =
    "SELECT id AS note_id, label, text, date ".
    "FROM notes ".
    "LEFT JOIN comments ".
    "ON comments.note_id = notes.id ".
    "RIGHT JOIN shared ".
    "ON shared.note_id = notes.id ".
    "WHERE shared.user_id = ".
    "(SELECT id FROM users WHERE email = ?) ".
    "OR notes.user_id = ".
    "(SELECT id FROM users WHERE email = ?) ".
    "AND (MATCH(label) AGAINST(?) ".
    "OR MATCH(text) AGAINST(?)) ".
    "GROUP BY note_id ".
    "ORDER BY (MATCH(text) AGAINST(?) + MATCH(label) AGAINST(?)) DESC, date DESC ".
    "LIMIT ?, ?;";

function connect(): void
{
    $servername = "127.0.0.1";
    $username = "facenda5inc2022";
    $password = "";
    $dbname = "my_facenda5inc2022";
    global $conn;
    //mysqli_report(MYSQLI_REPORT_ALL);

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}

function execute($query, $types, ...$params): mysqli_result | bool
{
    global $conn;
    $stmt = $conn->prepare($query);
    /*
    if($stmt === false){
        die("Error: " . $conn->error);
    }
    */
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
    return execute(search_notes_query, "sssssii", $email, $query, $query, $query, $query, $offset, $limit)->fetch_all(MYSQLI_ASSOC);
}


function edit_note($email, $note_id, $label, $comment=NULL): void
{
    if(execute(check_note_owner_query, "isis", $note_id, $email, $note_id, $email)->num_rows > 0){
        execute(update_note_query, "si", $label, $note_id);
        if(empty($comment)){
            execute(delete_comment_query, "i", $note_id);
        }else{
            // update the comment or create a new one
            if(execute(comment_exists_query, "i", $note_id)->num_rows > 0){
                execute(update_comment_query, "si", $comment, $note_id);
            }else{
                execute(insert_comment_id_query, "si", $comment, $note_id);
            }

        }
    }
}

function delete_note($email, $note_id): void
{
    if(execute(check_note_owner_query, "isis", $note_id, $email, $note_id, $email)->num_rows > 0){
        // c'e' il delete cascade per il commento
        execute(delete_note_query, "i", $note_id);
    }
}

function set_email_verified($email): void
{
    execute(set_email_verified_query, "s", $email);
}

function search_users($query): array
{
    return execute(search_users_query, "s", $query)->fetch_all(MYSQLI_ASSOC);
}

function share_note($owner, $note_id, $email): void
{
    if(execute(check_note_owner_query,  "isis", $note_id, $owner, $note_id, $owner)->num_rows > 0){
        execute(insert_shared_note_query, "is", $note_id, $email);
    }
}

function get_shared_notes_containing($email, $query, $limit=100, $offset=0): ?array
{
    return execute(search_shared_notes_query, "ssssssii", $email, $email, $query, $query, $query, $query, $offset, $limit)->fetch_all(MYSQLI_ASSOC);
}

function get_shared_notes($email, $limit=100, $offset=0): ?array
{
    return execute(get_shared_notes_query, "ssii", $email, $email, $offset, $limit)->fetch_all(MYSQLI_ASSOC);
}