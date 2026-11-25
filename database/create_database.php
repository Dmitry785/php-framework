<?php 

$pdo = new PDO('sqlite:database.sqlite'); 

$pdo->exec('CREATE TABLE IF NOT EXISTS users 
    (id INTEGER PRIMARY KEY AUTOINCREMENT, 
    name TEXT NOT NULL, 
    email TEXT UNIQUE);'); 
echo "Ok";
