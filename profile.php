<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

        <a href="new_post.php"><button>Create New Post</button></a>
        <a href="view_posts.php"><button>View All Posts</button></a>
        <a href="my_post.php"><button>My Posts</button></a>
        <a href="private_post.php"><button>Private Posts</button></a>

        <?php if ($user_role === 'admin'): ?>
            <a href="admin.php"><button>Admin Panel</button></a> 
        <?php endif; ?>

        <a href="logout.php"><button>Logout</button></a>
    </div>
</body>
</html>
