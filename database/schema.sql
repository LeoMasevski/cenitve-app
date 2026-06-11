CREATE DATABASE IF NOT EXISTS cenitve_app
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE cenitve_app;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS valuations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,

    client_name VARCHAR(255) NOT NULL,
    client_address VARCHAR(255) NOT NULL,

    valuation_purpose ENUM(
        'zavarovano posojanje',
        'sodni postopek',
        'stečajni postopek',
        'računovodsko poročanje',
        'davčni postopek',
        'poslovna odločitev naročnika'
    ) NOT NULL,

    value_basis ENUM(
        'tržna vrednost',
        'likvidacijska vrednost',
        'tržna najemnina',
        'pravična vrednost'
    ) NOT NULL,

    value_premise ENUM(
        'sedanja ali obstoječa uporaba',
        'najgospodarnejša uporaba',
        'redna likvidacija'
    ) NOT NULL,

    first_inspection_at DATETIME NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_valuations_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);