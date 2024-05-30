


CREATE DATABASE music;
USE music;

CREATE TABLE artists (
    artist_id INT AUTO_INCREMENT PRIMARY KEY,
    pseudonym VARCHAR(255) NOT NULL,
    real_name VARCHAR(255) NOT NULL,
    age INT,
    country VARCHAR(255),
    famous_track VARCHAR(255),
    cover_link VARCHAR(255),
    song_link VARCHAR(255)
)
ALTER TABLE artists
ADD COLUMN about TEXT;



select * from artists;

ALTER TABLE artists ADD COLUMN status VARCHAR(10) DEFAULT 'active' AFTER about;
ALTER TABLE artists DROP COLUMN status;


CREATE TABLE deleted_artists (
    artist_id INT AUTO_INCREMENT PRIMARY KEY,
    pseudonym VARCHAR(255) NOT NULL,
    real_name VARCHAR(255) NOT NULL,
    age INT,
    country VARCHAR(255),
    famous_track VARCHAR(255),
    cover_link VARCHAR(255),
    song_link VARCHAR(255),
    deleted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE deleted_artists
SELECT * FROM deleted_artists;
CLEAR deleted_artists;
DELETE * from deleted_artists WHERE id =1;


DELETE FROM artists WHERE artist_id = '9';

SELECT * FROM artists WHERE pseudonym = 'Britney Manson'





CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
);

INSERT INTO users (username, password_hash, role) VALUES ('admin', '$2y$10$C6t4OC6a.IRtWlzPQLPvEun845WL5XSneh4MptpI7ooXm/rZpFGjy', 'admin');
