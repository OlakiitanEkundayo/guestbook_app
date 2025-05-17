<?php

require __DIR__ . '/db.inc.php';
require __DIR__ . '/function.php';


if (!empty($name) && !empty($message)) {

    $stmt = $pdo->prepare("SELECT * FROM `dairyquery` WHERE name = :name and message = :message ORDER BY created_at DESC");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':message', $message);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    var_dump($results);
}
