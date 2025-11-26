<?php
// delete_post.php
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

// Delete the post using prepared statement
$query = "DELETE FROM posts WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        // Redirect to homepage with success message
        header('Location: index.php?deleted=1');
        exit;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        // Redirect with error
        header('Location: index.php?error=delete_failed');
        exit;
    }
} else {
    mysqli_close($conn);
    header('Location: index.php?error=delete_failed');
    exit;
}
?>