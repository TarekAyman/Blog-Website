<?php
session_start();
include "db.php";

// التأكد من أن المستخدم مسجل دخوله
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

// تعديل الاستعلام ليشمل البوستات التي ليست خاصة
$sql = "SELECT id, title, content, created_at FROM posts WHERE user_id = ? AND is_private = 0 ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Posts</title>
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

        .button-group {
            margin-top: 10px;
        }

        .edit-btn,
        .delete-btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 8px;
            transition: background-color 0.3s ease;
        }

        .edit-btn:hover {
            background-color: #0056b3;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        .no-posts {
            text-align: center;
            color: #888;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Posts</h2>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <h3><?= htmlspecialchars($row["title"]) ?></h3>
                    <p><?= nl2br(htmlspecialchars($row["content"])) ?></p>
                    <small>Posted on <?= $row["created_at"] ?></small><br>

                    <div class="button-group">
                        <a href="edit_post.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
                        <a href="delete_post.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="no-posts">You haven't created any public posts yet. Start by creating one!</p>
        <?php endif; ?>
    </div>
</body>
</html>
