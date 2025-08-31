<?php
// Database connection
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'blog';

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}

// Get all blog posts from database
$query = "SELECT * FROM posts";
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
</head>
<body>
    <header class="banner">
        <div class="navbar">
            <div class="logo">My Blog</div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="add_post.php">Add Post</a></li>
                    <li><a href="view_post.php">View Post</a></li>
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
            // Display blog posts
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="post">';
                echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                echo '<a class="read-more" href="view_post.php?id=' . urlencode($row['id']) . '">Read more</a>';
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
