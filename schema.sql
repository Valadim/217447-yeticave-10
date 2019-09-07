DROP DATABASE `yeticave`;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255) NOT NULL UNIQUE,
  class VARCHAR(255)
);

CREATE TABLE users (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL UNIQUE,
  username  VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  contacts TEXT,
  create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE lot (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  img_path VARCHAR(255) NOT NULL,
  start_price INT NOT NULL,
  finish_date DATETIME NOT NULL,
  bid_step INT NOT NULL,
  user_id INT NOT NULL,
  winner_id INT,
  category_id INT NOT NULL,
  is_active TINYINT NOT NULL,

  FOREIGN KEY (user_id)  REFERENCES users (id) ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (category_id)  REFERENCES category (id) ON UPDATE CASCADE ON DELETE RESTRICT
);

CREATE TABLE bid (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price INT NOT NULL,
  user_id INT NOT NULL,
  lot_id INT NOT NULL,

  FOREIGN KEY (user_id)  REFERENCES users (id) ON UPDATE CASCADE ON DELETE RESTRICT,
  FOREIGN KEY (lot_id)  REFERENCES lot (id) ON UPDATE CASCADE ON DELETE RESTRICT
);
