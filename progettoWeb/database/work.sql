
-- select all labels for user, given user's email
SELECT label
FROM notes
RIGHT JOIN users
ON notes.user_id = users.id
WHERE users.email = ?;

-- get all labels and comments for user, given user's email
SELECT label, text, notes.id as note_id, comments.note_id as comment_id, date
FROM notes
LEFT JOIN comments
ON notes.id = comments.note_id
LEFT JOIN users
ON notes.user_id = users.id
WHERE users.email = ?
LIMIT 0, 100;

-- insert a new note for user, given user's email
INSERT INTO notes (label, user_id)
SELECT ?, id
FROM users
WHERE email = ?;

-- insert a note with a comment for user, given user's email
INSERT INTO notes (label, user_id)
SELECT ?, id
FROM users
WHERE email = ?;
INSERT INTO comments (note_id, text)
VALUES (LAST_INSERT_ID(), ?);

-- edit a comment for user, given comment id
UPDATE comments
SET text = ?
WHERE id = ?;

-- delete a comment for user, given comment id
DELETE FROM comments
WHERE id = ?;

-- delete a note for user, given note id. Also delete all comments for that note
DELETE FROM notes
WHERE id = ?;
DELETE FROM comments
WHERE note_id = ?;

-- edit a note for user, given note id
UPDATE notes
SET label = ?
WHERE id = ?;