CREATE DATABASE IF NOT EXISTS test;
-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--
USE test;

--
-- Описание для таблицы users
--
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT(11) NOT NULL AUTO_INCREMENT,
  login VARCHAR(80) NOT NULL,
  password VARCHAR(100) NOT NULL,
  email VARCHAR(50) NOT NULL,
  name VARCHAR(50) DEFAULT '',
  phone VARCHAR(20) DEFAULT '',
  datetime DATETIME NOT NULL COMMENT 'Дата и время регистрации',
  PRIMARY KEY (id),
  INDEX IDX_users_email (email),
  INDEX IDX_users_login (login),
  INDEX IDX_users_phone (phone)
)
ENGINE = INNODB
AUTO_INCREMENT = 26
AVG_ROW_LENGTH = 963
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Юзеры';

--
-- Описание для таблицы pix
--
DROP TABLE IF EXISTS pix;
CREATE TABLE pix (
  id INT(11) NOT NULL AUTO_INCREMENT,
  filename VARCHAR(255) NOT NULL,
  user_id INT(11) NOT NULL,
  PRIMARY KEY (id),
  INDEX IDX_pix_user_id (user_id),
  INDEX IDX_user_id (user_id),
  CONSTRAINT FK_pix_users_id FOREIGN KEY (user_id)
    REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 23
AVG_ROW_LENGTH = 1024
CHARACTER SET utf8
COLLATE utf8_general_ci
COMMENT = 'Изображения юзеров';

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;