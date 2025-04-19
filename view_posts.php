<?php
include "db.php";  // ربط الملف بقاعدة البيانات

// استعلام لجلب المنشورات من قاعدة البيانات
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Posts</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        
        .form-container {
            width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        
        h2 {
            text-align: center;
            color: #333;
        }

        
        .post {
            margin: 20px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .post h3 {
            margin: 0;
            color: #333;
        }

        .post p {
            margin: 10px 0;
            color: #555;
        }

        .post small {
            display: block;
            margin-top: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>All Posts</h2>
        
        <?php if ($result->num_rows > 0): ?>
            <?php while($post = $result->fetch_assoc()): ?>
                <div class="post">
                    <h3><?= $post['title'] ?></h3>
                    <p><?= $post['content'] ?></p>
                    <small>Posted on <?= $post['created_at'] ?></small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
