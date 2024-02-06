<?php

// Parse the request URI
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Get request parameters
if (isset($_GET['slug'])) {
    $incomingSlug = clean($_GET['slug']);
} elseif (isset($_POST['slug'])) {
    $incomingSlug = clean($_POST['slug']);
} else {
    $incomingSlug = null;
}
if (isset($_GET['password'])) {
    $incomingPassword = clean($_GET['password']);
} elseif (isset($_POST['password'])) {
    $incomingPassword = clean($_POST['password']);
} else {
    $incomingPassword = null;
}
if (isset($_GET['expires'])) {
    $incomingExpires = clean($_GET['expires']);
} elseif (isset($_POST['expires'])) {
    $incomingExpires = clean($_POST['expires']);
} else {
    $incomingExpires = null;
}
if (isset($_GET['token'])) {
    $incomingToken = clean($_GET['token']);
} elseif (isset($_POST['token'])) {
    $incomingToken = clean($_POST['token']);
} else {
    $incomingToken = null;
}
if (isset($_GET['action'])) {
    $incomingAction = clean($_GET['action']);
} elseif (isset($_POST['action'])) {
    $incomingAction = clean($_POST['action']);
} else {
    $incomingAction = null;
}
if (isset($_GET['username'])) {
    $incomingUsername = clean($_GET['username']);
} elseif (isset($_POST['username'])) {
    $incomingUsername = clean($_POST['username']);
} else {
    $incomingUsername = null;
}
if (isset($_FILES['file'])) {
    $incomingFile = $_FILES['file'];
} else {
    $incomingFile = null;
}
if (isset($_POST['reason'])) {
    $incomingReason = clean($_POST['reason']);
} else {
    $incomingReason = null;
}


// Route the request to the appropriate handler
switch ($path) {
    case '/':
        // Home page
        $page = 'home';
        if (isset($incomingAction)) {
            if ($incomingAction === 'upload') {
                $file = $incomingFile;
                $password = $incomingPassword;
                $expires = $incomingExpires;
                $token = $incomingToken;
                $slug = fileUpload($file, $password, $expires);
                if ($slug) {
                    header('Location: /file/' . $slug);
                } else {
                    header('Location: /');
                }
            }
        }
        break;
    case '/upload':
        include '../upload.php';
        $page = 'home';
        break;
    case '/admin':
        $page = 'admin';
        if (isset($_SESSION['admin_login_error'])) {
            $_SESSION['admin_login_error_message'] = 'Invalid username or password';
            unset($_SESSION['admin_login_error']);
        }
        if (isset($_POST)) {
            if ($incomingAction === 'admin_login') {
                $username = $incomingUsername;
                $password = $incomingPassword;
                if (admin_login($username, $password)) {
                    isset($_SESSION['token']) ? createSession($_SESSION['token']) : null;
                    header('Location: /admin');
                } else {
                    $_SESSION['admin_login_error'] = true;
                    header('Location: /admin');
                }
            }
            if ($incomingAction === 'delete_session') {
                $token = $incomingToken;
                deleteSession($token);
                header('Location: /admin');
            }
            if ($incomingAction === 'delete_file') {
                $slug = $incomingSlug;
                fileDelete($slug);
                header('Location: /admin');
            }
        }
        break;
    case '/about':
        // Handle /about route
        $page = 'about';
        break;
    case '/api':
        // Handle /api route
        $page = 'api';
        break;
    case '/logout':
        // Handle /logout route
        if (isAdminLoggedIn()) {
            admin_logout();
        }
        header('Location: /');
        break;
    default:
        if (strpos($path, '/file/') === 0) {
            $slug = substr($path, 6);
            $file = getFile(clean($slug));
            if ($file) {
                $page = 'file';
            } else {
                // 404 Not Found
                $page = '404';
            }
        } elseif (strpos($path, '/raw/') === 0) {
            $slug = substr($path, 5);
            $file = getFile(clean($slug));
            if ($file) {
                $page = 'raw';
            } else {
                // 404 Not Found
                $page = '404';
            }
        } elseif (strpos($path, '/report/') === 0) {
            $slug = substr($path, 8);
            $file = getFile(clean($slug));
            if ($file) {
                $page = 'file';
                if (isset($_POST)) {
                    $token = $incomingToken;
                    if (!isset($incomingReason)) {
                        $reason = null;
                        $_SESSION['extra_message'] = 'Please enter a reason';
                        header('Location: /file/' . $slug);
                    }
                } else {
                        $reason = $incomingReason;
                        reportFile($slug, $reason);
                        $_SESSION['extra_message'] = 'Report Sent 👍';
                        header('Location: /file/' . $slug);
                }
            } else {
                // 404 Not Found
                $page = '404';
            }
        }else{
            // 404 Not Found
            $page = '404';
        }
        break;
}

?>