
-- database per il progetto web di Samuele Facenda

-- idea: applicazione in cui fare il login e salvare delle note
-- incentrata sul titolo(label) delle note
-- ci possono anche essere dei commenti sulle note
-- le note possono essere condivise con utenti specifici

-- creazione della tabella users
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password BINARY(60) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    edit_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    is_email_verified BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (id)
);

-- creazione della tabella notes
DROP TABLE IF EXISTS notes;
CREATE TABLE notes (
    id INT NOT NULL AUTO_INCREMENT,
    label VARCHAR(50) NOT NULL,
    user_id INT NOT NULL,
    date DATE NOT NULL DEFAULT (CURRENT_DATE),
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- creazione della tabella comments
DROP TABLE IF EXISTS comments;
CREATE TABLE comments (
    note_id INT NOT NULL,
    text VARCHAR(500) NOT NULL CHECK ( LENGTH(TRIM(text)) > 0 ),
    edit_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (note_id),
    FOREIGN KEY (note_id)
        REFERENCES notes(id)
        ON DELETE CASCADE
);

-- creazione della tabella shared
DROP TABLE IF EXISTS shared;
CREATE TABLE shared (
    note_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (note_id, user_id),
    FOREIGN KEY (note_id)
        REFERENCES notes(id)
        ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- triggers:

-- trigger dopo l'update di un comment, se non esiste un commento per quella nota, lo crea
DROP TRIGGER IF EXISTS update_comment;
DELIMITER $$
CREATE TRIGGER update_comment
AFTER UPDATE ON comments
FOR EACH ROW
BEGIN
    IF NEW.note_id NOT IN (SELECT note_id FROM comments) THEN
        INSERT INTO comments (note_id, text)
        VALUES (NEW.note_id, '');
    END IF;
END$$
DELIMITER ;

-- stored procedures:

-- inserimento di un nuovo utente
DROP PROCEDURE IF EXISTS insert_user;
DELIMITER $$
CREATE PROCEDURE insert_user(
    IN in_username VARCHAR(50),
    IN in_password BINARY(60),
    IN in_email VARCHAR(50)
)
BEGIN
    INSERT INTO users (username, password, email)
    VALUES (in_username, in_password, in_email);
END$$
DELIMITER ;

-- inserimento di una nuova nota, data l'email dell'utente
DROP PROCEDURE IF EXISTS insert_note;
DELIMITER $$
CREATE PROCEDURE insert_note(
    IN in_email VARCHAR(50),
    IN label VARCHAR(50)
)
BEGIN
    INSERT INTO notes (label, user_id)
    VALUES (label, (SELECT id FROM users WHERE in_email = email));
END$$
DELIMITER ;

-- inserimento di una nuova nota con commento, se esiste, data l'email dell'utente
DROP PROCEDURE IF EXISTS insert_note_with_comment;
DELIMITER $$
CREATE PROCEDURE insert_note_with_comment(
    IN in_email VARCHAR(50),
    IN in_label VARCHAR(50),
    IN comment VARCHAR(500)
)
BEGIN
    INSERT INTO notes (label, user_id)
    VALUES (in_label, (SELECT id FROM users WHERE in_email = email));
    IF comment IS NOT NULL AND comment != '' THEN
        INSERT INTO comments (note_id, text)
        VALUES (LAST_INSERT_ID(), comment);
    END IF;
END$$
DELIMITER ;

-- aggiornamento di una nota, data l'email dell'utente, il nuovo label e l'id della nota
DROP PROCEDURE IF EXISTS update_note_label;
DELIMITER $$
CREATE PROCEDURE update_note_label(
    IN in_email VARCHAR(50),
    IN in_label VARCHAR(50),
    IN note_id INT
)
BEGIN
    UPDATE notes
    SET label = in_label
    WHERE id = note_id AND user_id = (SELECT id FROM users WHERE in_email = email);
END$$
DELIMITER ;

-- aggiornamento di una nota, data l'email dell'utente, il nuovo commento e l'id della nota
DROP PROCEDURE IF EXISTS update_note_comment;
DELIMITER $$
CREATE PROCEDURE update_note_comment(
    IN in_email VARCHAR(50),
    IN comment VARCHAR(500),
    IN in_note_id INT
)
BEGIN
    UPDATE comments
    SET text = comment
    WHERE in_note_id = note_id AND
          note_id IN (SELECT id FROM notes WHERE user_id = (SELECT id FROM users WHERE in_email = email));
    IF ROW_COUNT() = 0 THEN
        INSERT INTO comments (note_id, text)
        VALUES (in_note_id, comment);
    END IF;
END$$
DELIMITER ;

-- eliminazione di una nota, data l'email dell'utente e l'id della nota
DROP PROCEDURE IF EXISTS delete_note;
DELIMITER $$
CREATE PROCEDURE delete_note(
    IN in_email VARCHAR(50),
    IN note_id INT
)
BEGIN
    DELETE FROM notes
    WHERE id = note_id AND user_id = (SELECT id FROM users WHERE in_email = email);
END$$
DELIMITER ;

-- ricerca di un utente, data una query, controlla se esiste un utente con username o email che contengano la query
DROP PROCEDURE IF EXISTS search_user;
DELIMITER $$
CREATE PROCEDURE search_user(
    IN query VARCHAR(50),
    IN lim INT,
    IN offs INT
)
BEGIN
    SELECT * FROM users
    WHERE LOWER(username) LIKE LOWER(CONCAT('%', query, '%')) OR LOWER(email) LIKE LOWER(CONCAT('%', query, '%'))
    LIMIT offs, lim;
END$$
DELIMITER ;

-- ricerca di una nota, data una query, controlla se esiste una nota con label che contengano la query, data anche l'email dell'utente
DROP PROCEDURE IF EXISTS search_note;
DELIMITER $$
CREATE PROCEDURE search_note(
    IN in_email VARCHAR(50),
    IN query VARCHAR(50),
    IN lim INT,
    IN offs INT
)
BEGIN
    SELECT * FROM notes
    LEFT JOIN comments c on notes.id = c.note_id
    WHERE (LOWER(label) LIKE LOWER(CONCAT('%', query, '%')) OR
              LOWER(text) LIKE LOWER(CONCAT('%', query, '%')))
      AND user_id = (SELECT id FROM users WHERE in_email = email)
    LIMIT offs, lim;
END$$
DELIMITER ;

-- inserisce una nuova condivisione, data l'email dell'utente, l'id della nota e l'email dell'utente con cui condividere la nota
DROP PROCEDURE IF EXISTS insert_shared;
DELIMITER $$
CREATE PROCEDURE insert_shared(
    IN in_email VARCHAR(50),
    IN note_id INT,
    IN shared_email VARCHAR(50)
)
BEGIN
    -- controlla se è il proprietario della nota
    IF (SELECT id FROM users WHERE in_email = email) != (SELECT user_id FROM notes WHERE id = note_id) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Non sei il proprietario della nota';
    END IF;
    INSERT INTO shared (note_id, user_id)
    VALUES (note_id, (SELECT id FROM users WHERE in_email = shared_email));
END$$
DELIMITER ;

-- prende tutte le note condivise con l'utente, data l'email dell'utente, e le note che lui ha condiviso
DROP PROCEDURE IF EXISTS get_shared;
DELIMITER $$
CREATE PROCEDURE get_shared(
    IN in_email VARCHAR(50)
)
BEGIN
    SELECT * FROM notes
    LEFT JOIN comments AS c on notes.id = c.note_id
    RIGHT JOIN shared AS s on notes.id = s.note_id
    WHERE s.user_id = (SELECT id FROM users WHERE in_email = email)
    UNION
    SELECT * FROM notes
    LEFT JOIN comments AS c on notes.id = c.note_id
    RIGHT JOIN shared AS s on notes.id = s.note_id
    WHERE notes.user_id = (SELECT id FROM users WHERE in_email = email);
END$$
DELIMITER ;
