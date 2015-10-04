CREATE TABLE `users` (
    `id`    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `email` TEXT NOT NULL UNIQUE,
    `password`  TEXT NOT NULL
);
CREATE TABLE `numbers` (
    `id`    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `number`    NUMERIC NOT NULL UNIQUE,
    `user`  INTEGER NOT NULL,
    `dnd`   INTEGER NOT NULL DEFAULT 0
);
CREATE TABLE `actions` (
    `id`    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `number`    INTEGER NOT NULL,
    `extnumber` NUMERIC NOT NULL,
    `direction` TEXT NOT NULL,
    `action`    TEXT NOT NULL,
    `param1`    TEXT,
    `param2`    TEXT
);