
DROP DATABASE IF EXISTS yeticave_db;

CREATE DATABASE yeticave_db 
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave_db;

CREATE TABLE yeticave_db.categories (
	id INT AUTO_INCREMENT PRIMARY KEY,
	character_code VARCHAR(128) UNIQUE,
	category_name VARCHAR(128)
);

CREATE TABLE yeticave_db.users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	date_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email VARCHAR(128) NOT NULL UNIQUE,
	user_name VARCHAR(128),
	user_password VARCHAR(256),
	contacts TEXT
);

CREATE TABLE yeticave_db.lots (
	id INT AUTO_INCREMENT PRIMARY KEY,
	date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
	title VARCHAR(256),
	description TEXT,
	image VARCHAR(256),
	start_price INT,
	date_finish DATETIME,
	step INT,
	user_id INT,
	winner_id INT,
	category_id INT,
	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (winner_id) REFERENCES users(id),
	FOREIGN KEY (category_id) REFERENCES categories(id)
);

ALTER TABLE lots ADD FULLTEXT (title, description);

CREATE TABLE yeticave_db.bets (
	id INT AUTO_INCREMENT PRIMARY KEY,
	date_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	price INT NOT NULL,
	user_id INT,
	lot_id INT,
	FOREIGN KEY (user_id) REFERENCES users(id),
	FOREIGN KEY (lot_id) REFERENCES lots(id)
);


CREATE FULLTEXT INDEX lots_search ON lots(title, description);