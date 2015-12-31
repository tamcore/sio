CREATE TABLE `users` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `email` TEXT NOT NULL UNIQUE,
    `password` TEXT NOT NULL
    );

CREATE TABLE `numbers` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `number` NUMERIC NOT NULL UNIQUE,
    `user` INTEGER NOT NULL,
    `dnd` INTEGER NOT NULL DEFAULT 0,
    'dnd_action' TEXT NOT NULL DEFAULT 'hangup'
    );

CREATE TABLE 'actions' (
    `id`INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `number`INTEGER NOT NULL,
    `extnumber`TEXT NOT NULL,
    `direction`TEXT NOT NULL,
    `action`TEXT NOT NULL,
    `param1`TEXT,
    `param2`TEXT,
    'comment' TEXT,
    'type' INTEGER DEFAULT 0,
    'active' INTEGER NOT NULL DEFAULT 0
    );
