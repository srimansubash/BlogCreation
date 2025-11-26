<?php
// edit_post.php
// Database connection
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'blog';

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die('Could not connect: ' . mysqli_connect_error());
}

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Handle form submission
if (isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = 'Please fill in all fields';
    } else {
        // Use a prepared statement to prevent SQL injection
        $query = "UPDATE posts SET title = ?, content = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssi', $title, $content, $id);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                header('Location: view_post.php?id=' . $id);
                exit;
            } else {
                $error = 'Error updating the post';
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Error in preparing the SQL statement';
        }
    }
}

// Get the post data from database
$query = "SELECT * FROM posts WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Post not found
    mysqli_close($conn);
    header('Location: index.php');
    exit;
}

$post = mysqli_fetch_assoc($result);
$title = $post['title'];
$content = $post['content'];

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
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
                </ul>
            </nav>
        </div>
        <div class="banner-text">
            <h1>Edit Post</h1>
            <p>Update your post</p>
            <p style="font-size: 0.9em; margin-top: 5px;">Use **bold**, *italic*, ### headers, and --- for lines</p>
        </div>
    </header>

    <main>
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?id=' . $id; ?>" method="post">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
                </div>
                <div class="form-group">
                    <label for="content">Content:</label>
                    <p style="font-size: 0.9em; color: #666; margin-bottom: 5px;">
                        <strong>Formatting tips:</strong><br>
                        • **text** for <strong>bold</strong><br>
                        • *text* for <em>italic</em><br>
                        • # Header 1, ## Header 2, ### Header 3<br>
                        • --- for horizontal line
                    </p>
                    <textarea id="content" name="content" class="form-control" required><?php echo htmlspecialchars($content); ?></textarea>
                </div>
                <?php if (isset($error)) { ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php } ?>
                <div class="form-group">
                    <input type="submit" value="Update Post" name="submit" class="btn">
                    <a href="view_post.php?id=<?php echo $id; ?>" class="btn" style="background-color: #6c757d; margin-left: 10px;">Cancel</a>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 My Blog. All rights reserved.</p>
    </footer>
</body>
</html>