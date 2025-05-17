<?php

require __DIR__ . '/db.inc.php';
require __DIR__ . '/function.php';

$messagePerPage = 5;
$currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
}

$offset = ($currentPage - 1) * $messagePerPage;
var_dump($currentPage);
var_dump($offset);


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


$stmt = $pdo->prepare("SELECT * FROM `guestentry` ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $messagePerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalStmt = $pdo->prepare("SELECT COUNT(*) FROM diaryquery");
$totalMessages = $totalStmt->fetchColumn();

$totalPages = ceil($totalMessages / $messagePerPage);

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
                if ($i == $currentPage) {
                    echo "<strong>$i</strong>";
                } else {
                    echo "<a href='?page=$i'>$i</a>";
                }
            }
            echo "</div>";
        }

        ?>
    </div>


</body>

</html>