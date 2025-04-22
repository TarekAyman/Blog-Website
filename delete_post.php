<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM posts WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    if (!$post) {
        echo "Post not found or you don't have permission.";
        exit();
    }

    $delete_sql = "DELETE FROM posts WHERE id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $post_id, $user_id);
    if ($delete_stmt->execute()) {
        header("Location: my_post.php");  
        exit();
    } else {
        echo "Error deleting post.";
    }
} else {
    echo "No post ID specified.";
    exit();
}
