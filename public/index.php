<?php
// Start the session for admin authentication
session_start();
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = false;
}

include '../db.php';
include '../handlers.php';
include '../routes.php';

cleanSessions();
cleanFiles();

if(isAdminLoggedIn()) {
    $_SESSION['admin_logged_in'] = true;
}

// Render the page

include '../template.php';

?>