<?php

require __DIR__ . '/db.inc.php';
require __DIR__ . '/function.php';


$messagesPerPage = 3;

// Search feature
$searchTerm = isset($_GET['search']) ? strip_tags(trim($_GET['search'])) : '';

// Pagination feature
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;


if ($currentPage < 1) $currentPage = 1;
$offset = ceil($currentPage - 1) * $messagesPerPage;



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = strip_tags(trim($_POST['name'] ?? ''));
    $message = strip_tags(trim($_POST['message'] ?? ''));

    $stmt = $pdo->prepare("INSERT INTO `guestentry` (name, message) VALUES (:name, :message)");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':message', $message);
    $stmt->execute();
    header("Location: index.php");
    exit;
}

// Searching values from the db
if (!empty($searchTerm)) {
    $stmt = $pdo->prepare("
        SELECT * FROM guestentry 
        WHERE name LIKE :search OR message LIKE :search 
        ORDER BY created_at DESC 
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare("
        SELECT * FROM guestentry 
        ORDER BY created_at DESC 
        LIMIT :limit OFFSET :offset
    ");
}

$stmt->bindValue(':limit', $messagesPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count Total Messages for Pagination
if (!empty($searchTerm)) {
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) FROM guestentry 
        WHERE name LIKE :search OR message LIKE :search
    ");
    $countStmt->bindValue(':search', "%$searchTerm%", PDO::PARAM_STR);
    $countStmt->execute();
    $totalMessages = $countStmt->fetchColumn();
} else {
    $countStmt = $pdo->query("SELECT COUNT(*) FROM guestentry");
    $totalMessages = $countStmt->fetchColumn();
}

$totalPages = ceil($totalMessages / $messagesPerPage);



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook</title>
    <link rel="stylesheet" href="./assets/styles.css">
</head>

<body>
    <div class="container">
        <!-- Display search input field -->
        <form class="searchForm" action="index.php" method="GET">
            <input class="searchFormInput" type="text" name="search" placeholder="Search message..." value="<?= e($_GET['search'] ?? ''); ?>">
            <button class="cta" type="submit">Search</button>
        </form>

        <?php
        if (!empty($searchTerm)) {
            foreach ($messages as $msg) {
                echo "<div>";
                echo "<strong>" . e($msg['name']) . "</strong><br>";
                echo "<p>" . nl2br(e($msg['message'])) . "</p>";
                echo "<small>" . $msg['created_at'] . "</small>";
                echo "</div><hr>";
            }
        }

        ?>

        <h1>Guestbook</h1>

        <h3>Leave a Message</h3>
        <form action="index.php" method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <textarea name="message" placeholder="Message" required></textarea>
            <button type="submit">Post Message</button>
        </form>

        <div id="messages">
            <!-- Messages will be dynamically inserted here with PHP -->
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <strong><?php echo e($message['name']) ?></strong> <span class="date"><?php $createdAt = new DateTime($message['created_at']);
                                                                                            echo $createdAt->format('F j, Y \a\t g:i A'); ?></span>
                    <p><?php echo e($message['message']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        if ($totalPages > 1) {
            echo "<div class='pagination'>";
            for ($i = 1; $i <= $totalPages; $i++) {
                $link = "?page=$i";
                if (!empty($searchTerm)) {
                    $link .= "&search=" . urlencode($searchTerm);
                }

                if ($i == $currentPage) {
                    echo "<strong>$i</strong> ";
                } else {
                    echo "<a href='$link'>$i</a> ";
                }
            }
            echo "</div>";
        }

        ?>
    </div>


</body>

</html>