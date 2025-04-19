<?php
include "db.php";  
session_start();   

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];  

    
    $sql = "INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $title, $content);  
    if ($stmt->execute()) {
        $message = "Post created successfully!";
    } else {
        $message = "Failed to create post.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        
        .form-container {
            width: 400px;
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

        
        label {
            font-size: 16px;
            color: #444;
        }

        
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        
        button:hover {
            background-color: #0056b3;
        }

        
        .message {
            text-align: center;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create New Post</h2>
        <?php if (isset($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <label>Title:</label>
            <input type="text" name="title" required><br>

            <label>Content:</label>
            <textarea name="content" required></textarea><br>

            <button type="submit">Create Post</button>
        </form>
    </div>
</body>
</html>
