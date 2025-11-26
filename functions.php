<?php
// functions.php
// Helper functions for the blog

/**
 * Format content with Markdown-style formatting
 * Supports: **bold**, *italic*, headers (###), horizontal rules (---), and line breaks
 */
function format_content($content) {
    // Escape HTML special characters first for security
    $content = htmlspecialchars($content);
    
    // Convert headers (must be done before line breaks)
    // ### Header 3
    $content = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $content);
    // ## Header 2
    $content = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $content);
    // # Header 1
    $content = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $content);
    
    // Convert horizontal rules (--- or ***)
    $content = preg_replace('/^---+$/m', '<hr>', $content);
    $content = preg_replace('/^\*\*\*+$/m', '<hr>', $content);
    
    // Convert **text** to <strong>text</strong> (bold)
    $content = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $content);
    
    // Convert *text* to <em>text</em> (italic)
    $content = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $content);
    
    // Convert line breaks to <br> tags
    $content = nl2br($content);
    
    return $content;
}

/**
 * Create excerpt from content (plain text, no formatting)
 */
function create_excerpt($content, $length = 200) {
    // Remove any HTML tags
    $plain_text = strip_tags($content);
    
    // Trim to specified length
    if (strlen($plain_text) > $length) {
        $excerpt = substr($plain_text, 0, $length);
        // Try to cut at last complete word
        $last_space = strrpos($excerpt, ' ');
        if ($last_space !== false) {
            $excerpt = substr($excerpt, 0, $last_space);
        }
        $excerpt .= '...';
        return $excerpt;
    }
    
    return $plain_text;
}
?>