<?php
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
        $content = nl2br(htmlspecialchars($row['content']));
    } else {
        // If no post ID is provided, display an error message
        $title = 'Post not found';
        $content = '<p>The post you are looking for does not exist.</p>';
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
} else {
    // If no post ID is provided, display an error message
    $title = 'Post not found';
    $content = '<p>The post you are looking for does not exist.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
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
                    <li><a href="<?php echo 'view_post.php'; ?>">View Post</a></li>
                </ul>
            </nav>
        </div>
        <div class="banner-text">
            <h1><?php echo $title; ?></h1>
        </div>
    </header>

    <main>
        <div class="container">
            <?php if ($title !== 'Post not found'): ?>
                <h2><?php echo htmlentities($row['title']); ?></h2>
                <?php echo nl2br($content); ?>
            <?php else: ?>
                <p>The post you are looking for does not exist.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>© 2024 My Blog. All rights reserved.</p>
    </footer>
</body>
</html>