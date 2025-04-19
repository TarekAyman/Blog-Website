<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT title, content, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC";
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
                    <small>Posted on <?= $row["created_at"] ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
