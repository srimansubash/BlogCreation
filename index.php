<?php
// index.php
// Include helper functions
require_once 'functions.php';

// Database connection
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'blog';

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}

// Get all blog posts from database, ordered by newest first
$query = "SELECT * FROM posts ORDER BY created_at DESC";
$result = mysqli_query($conn, $query) or die('Query failed: ' . mysqli_error($conn));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/e61d547b58.js" crossorigin="anonymous"></script>
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .post-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .post-actions-small {
            display: flex;
            gap: 10px;
        }
        .btn-small {
            padding: 5px 10px;
            font-size: 14px;
            display: inline-block;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s;
        }
        .btn-edit-small {
            background-color: #28a745;
            color: white;
        }
        .btn-edit-small:hover {
            background-color: #218838;
        }
        .btn-delete-small {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete-small:hover {
            background-color: #c82333;
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
            <h1>Welcome to My Blog</h1>
            <p>Insights and stories from around the world</p>
        </div>
    </header>

    <main>
        <div class="container">
            <?php
            // Show success message if post was deleted
            if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
                echo '<div class="success-message">Post deleted successfully!</div>';
            }
            
            // Check if there are any posts
            if (mysqli_num_rows($result) > 0) {
                // Display blog posts
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="post">';
                    echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                    
                    // Show excerpt (first 200 characters, plain text)
                    $excerpt = create_excerpt($row['content'], 200);
                    echo '<p>' . htmlspecialchars($excerpt) . '</p>';
                    
                    echo '<div class="post-meta">';
                    echo '<a class="read-more" href="view_post.php?id=' . urlencode($row['id']) . '">Read more</a>';
                    echo '<div class="post-actions-small">';
                    echo '<a class="btn-small btn-edit-small" href="edit_post.php?id=' . urlencode($row['id']) . '">✏️ Edit</a>';
                    echo '<a class="btn-small btn-delete-small" href="delete_post.php?id=' . urlencode($row['id']) . '" onclick="return confirm(\'Are you sure you want to delete this post?\');">🗑️ Delete</a>';
                    echo '</div>';
                    echo '</div>';
                    
                    echo '</div>';
                }
            } else {
                echo '<div class="post">';
                echo '<h2>No posts yet</h2>';
                echo '<p>Be the first to create a post!</p>';
                echo '<a class="btn" href="add_post.php">Add Your First Post</a>';
                echo '</div>';
            }

            mysqli_close($conn);
            ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 My Blog. All rights reserved.</p>
    </footer>
</body>
</html>