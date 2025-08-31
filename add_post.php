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

if (isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = 'Please fill in all fields';
    } else {
        // Use a prepared statement to prevent SQL injection
        $query = "INSERT INTO posts (title, content) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $title, $content);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: index.php');
                exit;
            } else {
                $error = 'Error in executing the SQL statement';
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Error in preparing the SQL statement';
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Post</title>
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
            <h1>Add a New Post</h1>
            <p>Share your thoughts with the world</p>
        </div>
    </header>

    <main>
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($title ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" class="form-control"><?php echo htmlspecialchars($content ?? ''); ?></textarea>
                </div>
                <?php if (isset($error)) { ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php } ?>
                <div class="form-group">
                    <input type="submit" value="Add Post" name="submit" class="btn">
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 My Blog. All rights reserved.</p>
    </footer>
</body>
</html>
