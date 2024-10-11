CREATE DATABASE pipper;
USE pipper;

CREATE TABLE tweets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(128) NOT NULL,
    tweet_content VARCHAR(280) NOT NULL,
    created_at TIMESTAMP DEFAULT NOW(),
    avatar VARCHAR(64)
);
