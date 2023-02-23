
-- database per il progetto web di Samuele Facenda

-- idea: applicazione in cui fare il login e salvare delle note
-- incentrata sul titolo(label) delle note
-- ci possono anche essere dei commenti sulle note
-- le note possono essere condivise con utenti specifici

-- creazione del database
CREATE DATABASE IF NOT EXISTS webFacenda;

-- selezione del database
USE webFacenda;

-- creazione della tabella users
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    edit_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (id)
);

-- creazione della tabella notes
DROP TABLE IF EXISTS notes;
CREATE TABLE notes (
    id INT NOT NULL AUTO_INCREMENT,
    label VARCHAR(50) NOT NULL,
    user_id INT NOT NULL,
    date DATE NOT NULL DEFAULT CURRENT_DATE,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- creazione della tabella comments
DROP TABLE IF EXISTS comments;
CREATE TABLE comments (
    note_id INT NOT NULL,
    text VARCHAR(500) NOT NULL,
    edit_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (note_id),
    FOREIGN KEY (note_id) REFERENCES notes(id)
);

-- creazione della tabella shared
DROP TABLE IF EXISTS shared;
CREATE TABLE shared (
    note_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (note_id, user_id),
    FOREIGN KEY (note_id) REFERENCES notes(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);