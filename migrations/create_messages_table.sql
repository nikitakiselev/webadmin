create table messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    object_id INT,
    content TEXT,
    created DATETIME DEFAULT NULL,
    modified DATETIME DEFAULT NULL
);