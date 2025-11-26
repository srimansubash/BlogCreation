<?php
// view_post.php
// Include helper functions
require_once 'functions.php';

// Database connection
$conn = new mysqli('localhost', 'root', '', 'blog');

// Check connection
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get the post ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Get the post from the database
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query returned any rows
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = htmlentities($row['title']);
        $content = format_content($row['content']); // Use formatting function
        $postFound = true;
    } else {
        // If no post found with that ID
        $title = 'Post not found';
        $content = '<p>The post you are looking for does not exist.</p>';
        $postFound = false;
    }

    // Close the statement
    $stmt->close();
} else {
    // If no post ID is provided, redirect to homepage
    header('Location: index.php');
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/e61d547b58.js" crossorigin="anonymous"></script>
    <style>
        .post-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-edit {
            background-color: #28a745;
        }
        .btn-edit:hover {
            background-color: #218838;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .btn-back {
            background-color: #6c757d;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <header class="banner">
        <div class="navbar">
            <div class="logo">My Blog</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="add_post.php">Add Post</a></li>
                </ul>
            </nav>
        </div>
        <div class="banner-text">
            <h1><?php echo $title; ?></h1>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if ($postFound): ?>
                <div class="post">
                    <?php echo $content; ?>
                </div>
                <div class="post-actions">
                    <a href="index.php" class="btn btn-back">← Back to Home</a>
                    <a href="edit_post.php?id=<?php echo $id; ?>" class="btn btn-edit">✏️ Edit Post</a>
                    <a href="delete_post.php?id=<?php echo $id; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.');">🗑️ Delete Post</a>
                </div>
            <?php else: ?>
                <p>The post you are looking for does not exist.</p>
                <a href="index.php" class="btn btn-back" style="margin-top: 20px;">← Back to Home</a>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>© 2024 My Blog. All rights reserved.</p>
    </footer>
</body>
</html>