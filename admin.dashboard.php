<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./assets/styles.css">
</head>

<body>
    <div class="container">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        <h2>Messages</h2>
        <div id="admin-messages">
            <div class="admin-message">
                <strong>John Doe</strong>
                <span class="date">April 24, 2024 at 3:12 pm</span>
                <p>This is a guestbook message!</p>
                <form action="delete.php" method="POST">
                    <input type="hidden" name="id" value="1">
                    <button type="submit" class="delete">Delete</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>