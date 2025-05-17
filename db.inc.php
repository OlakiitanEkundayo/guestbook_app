<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=guestbook', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Connection Failed: " . $e->getMessage());
}
