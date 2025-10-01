<?php
/**
 * Redirect to public directory for Laravel application
 * This file handles the case when DocumentRoot points to Laravel root instead of public/
 */

// Redirect to public directory
header('Location: /public/');
exit;
?>
