CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  cat_id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR NOT NULL,
  class CHAR
);

CREATE TABLE lot (
  lot_id INT AUTO_INCREMENT PRIMARY KEY,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name  CHAR NOT NULL,
  description TEXT,
  img_path CHAR,
  start_price INT NOT NULL,
  finish_date DATETIME NOT NULL,
  bid_step INT NOT NULL,
  user_id INT,
  cat_id INT
);

CREATE TABLE bid (
  bid_id INT AUTO_INCREMENT PRIMARY KEY,
  date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  bid INT,
  user_id INT,
  lot_id INT
);

CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  email CHAR NOT NULL,
  username  CHAR NOT NULL,
  password CHAR NOT NULL,
  contacts TEXT,
  create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  lot_id INT,
  bid_id INT
);
