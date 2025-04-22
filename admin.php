<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

$sql = "SELECT posts.id, posts.title, posts.content, posts.created_at, posts.is_private, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px #ccc;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .post {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .post h3 {
            margin: 0;
            color: #444;
        }

        .post p {
            margin: 10px 0;
            color: #555;
        }

        .post small {
            color: #999;
        }

        .delete-btn {
            display: inline-block;
            margin-top: 8px;
            background-color: #ff3b30;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .delete-btn:hover {
            background-color: #e62e1f;
        }

        .private-indicator {
            display: inline-block;
            margin-top: 8px;
            padding: 6px 12px;
            background-color: #ffc107;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel - Manage Posts</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <h3><?= htmlspecialchars($row["title"]) ?></h3>
                    <p><?= nl2br(htmlspecialchars($row["content"])) ?></p>
                    <small>Posted by <?= htmlspecialchars($row["username"]) ?> on <?= $row["created_at"] ?></small><br>

                    
                    <?php if ($row["is_private"] == 1): ?>
                        <span class="private-indicator">Private</span>
                    <?php endif; ?>

                    <a href="delete_post.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts to display.</p>
        <?php endif; ?>
    </div>
</body>
</html>
