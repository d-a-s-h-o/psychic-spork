<?php

function render_logo() {
    $logo = file_get_contents('../logo.png');
    // Convert the image to base64
    $base64 = base64_encode($logo);
    // Output the image
    echo '<img src="data:image/png;base64,' . $base64 . '" alt="404" style="width: 100%; height: auto;" id="logo">';
    exit();
}

function clean($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function getDBSize() {
    global $db;
    $stmt = $db->prepare('SELECT page_count * page_size as size FROM pragma_page_count(), pragma_page_size()');
    $stmt->execute();
    return $stmt->fetch();
}

function getDBVersion() {
    global $db;
    $stmt = $db->prepare('SELECT value FROM config WHERE name = "db_version"');
    $stmt->execute();
    return $stmt->fetch();
}

function getDBInfo() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM pragma_page_count(), pragma_page_size()');
    $stmt->execute();
    return $stmt->fetch();
}

function getDBTables() {
    global $db;
    $stmt = $db->prepare('SELECT name FROM sqlite_master WHERE type = "table"');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDBTableInfo($table) {
    global $db;
    $stmt = $db->prepare('PRAGMA table_info(' . $table . ')');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDBIndexes() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM sqlite_master WHERE type = "index"');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDBIndexInfo($index) {
    global $db;
    $stmt = $db->prepare('PRAGMA index_info(' . $index . ')');
    $stmt->execute();
    return $stmt->fetchAll();
}

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}

// Admin stuff
function isAdminLoggedIn() {
    global $db;
    if (isset($_SESSION['token'])) {
        $stmt = $db->prepare('SELECT * FROM sessions WHERE token = :token');
        $stmt->bindParam(':token', $_SESSION['token']);
        $stmt->execute();
        $session = $stmt->fetch();
        if ($session) {
            return true;
        }
    }
    return false;
}

function admin_login($username, $password) {
    global $db;
    if ($username === 'admin' && $password === 'supersecret') {
        $_SESSION['admin_logged_in'] = true;
        // Add session token to database
        createSession($_SESSION['token']);
        return true;
    }
    return false;
}

function admin_logout() {
    global $db;
    $_SESSION['admin_logged_in'] = false;
    deleteSession($_SESSION['token']);
    unset($_SESSION['token']);
}

function createSession($token, $expires_at = null) {
    global $db;
    // Skip if the token already exists
    $stmt = $db->prepare('SELECT * FROM sessions WHERE token = :token');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $session = $stmt->fetch();
    if ($session) {
        return;
    }
    $created_at = date('Y-m-d H:i:s');
    $expires_at = $expires_at ? $expires_at : date('Y-m-d H:i:s', strtotime('+1 hour'));
    $stmt = $db->prepare('INSERT INTO sessions (token, created_at, expires_at) VALUES (:token, :created_at, :expires_at)');
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->bindParam(':expires_at', $expires_at);
    $stmt->execute();
}

function deleteSession($token) {
    global $db;
    $stmt = $db->prepare('DELETE FROM sessions WHERE token = :token');
    $stmt->bindParam(':token', $token);
    $stmt->execute();
}

function cleanSessions() {
    global $db;
    $now = date('Y-m-d H:i:s');
    $stmt = $db->prepare('DELETE FROM sessions WHERE expires_at < :now');
    $stmt->bindParam(':now', $now);
    $stmt->execute();
}

function getSessions() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM sessions');
    $stmt->execute();
    return $stmt->fetchAll();
}

// File upload

function isSlugInUse($slug) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM files WHERE slug = :slug');
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result ? true : false;
}

function generateSlug($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $slug = '';
    for ($i = 0; $i < $length; $i++) {
        $slug .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $slug;
}
function fileUpload($file, $password = null, $expires_at = null) {
    global $db;
    // Dissalow files larger than 10GB
    if ($file['size'] > 10000000000) {
        return false;
    }
    // Keep extensions
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    // Option password protection and expiration date
    if (!$expires_at) {
        // Expire in 2 years
        $expires_at = date('Y-m-d H:i:s', strtotime('+2 years'));
    } else {
        switch ($expires_at) {
            case '0.01':
                $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                break;
            case '0.02':
                $expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                break;
            case '0.04':
                $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));
                break;
            case '0.12':
                $expires_at = date('Y-m-d H:i:s', strtotime('+3 hours'));
                break;
            case '0.25':
                $expires_at = date('Y-m-d H:i:s', strtotime('+6 hours'));
                break;
            case '0.5':
                $expires_at = date('Y-m-d H:i:s', strtotime('+12 hours'));
                break;
            case '1':
                $expires_at = date('Y-m-d H:i:s', strtotime('+1 day'));
                break;
            case '3':
                $expires_at = date('Y-m-d H:i:s', strtotime('+3 days'));
                break;
            case '7':
                $expires_at = date('Y-m-d H:i:s', strtotime('+1 week'));
                break;
            case '14':
                $expires_at = date('Y-m-d H:i:s', strtotime('+2 weeks'));
                break;
            case '30':
                $expires_at = date('Y-m-d H:i:s', strtotime('+1 month'));
                break;
            case '90':
                $expires_at = date('Y-m-d H:i:s', strtotime('+3 months'));
                break;
            case '365':
                $expires_at = date('Y-m-d H:i:s', strtotime('+1 year'));
                break;
            case '730':
                $expires_at = date('Y-m-d H:i:s', strtotime('+2 years'));
                break;
            default:
                $expires_at = date('Y-m-d H:i:s', strtotime('+2 years'));
                break;
        }
    }
    if (!$password) {
        $password = null;
    }
    // Generate a unique slug
    $slug = generateSlug();
    while (isSlugInUse($slug)) {
        $slug = generateSlug();
    }
    // Move the file to the uploads directory
    move_uploaded_file($file['tmp_name'], '../uploads/' . $slug . '.' . $file_extension);
    // Insert the file into the database
    $stmt = $db->prepare('INSERT INTO files (id, original_name, slug, created_at, MIME, extension, size, downloads, expires_at, password, enabled) VALUES (:id, :original_name, :slug, :created_at, :MIME, :extension, :size, :downloads, :expires_at, :password, :enabled)');
    $tmp_id = bin2hex(random_bytes(16));
    $tmp_date = date('Y-m-d H:i:s');
    $tmp_downloads = 0;
    $tmp_enabled = 1;
    $stmt->bindParam(':id', $tmp_id);
    $stmt->bindParam(':original_name', $file['name']);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':created_at', $tmp_date);
    $stmt->bindParam(':MIME', $file['type']);
    $stmt->bindParam(':extension', $file_extension);
    $stmt->bindParam(':size', $file['size']);
    $stmt->bindParam(':downloads', $tmp_downloads);
    $stmt->bindParam(':expires_at', $expires_at);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':enabled', $tmp_enabled);
    $stmt->execute();
    return $slug;
}

function fileDownload($slug) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM files WHERE slug = :slug');
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    $file = $stmt->fetch();
    if ($file) {
        if (file_exists('../uploads/' . $slug . '.' . $file['extension'])) {
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
            readfile('../uploads/' . $slug . '.' . $file['extension']);
            $file['downloads']++;
            $stmt = $db->prepare('UPDATE files SET downloads = :downloads WHERE slug = :slug');
            $stmt->bindParam(':downloads', $file['downloads']);
            $stmt->bindParam(':slug', $slug);
            $stmt->execute();
            return true;
        }
    }
    return false;
}

function fileDelete($slug) {
    global $db;
    $file = getFile($slug);
    $file_extension = $file['extension'];
    $stmt = $db->prepare('DELETE FROM files WHERE slug = :slug');
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    unlink('../uploads/' . $slug . '.' . $file_extension);
}

function getFile($slug) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM files WHERE slug = :slug');
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    $file = $stmt->fetch();
    if($file) {
        return $file;
    }
    return false;
}

function getFiles() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM files WHERE enabled = 1');
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDisabledFiles() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM files WHERE enabled = 0');
    $stmt->execute();
    return $stmt->fetchAll();
}

function disableFile($slug) {
    global $db;
    $stmt = $db->prepare('UPDATE files SET enabled = 0 WHERE slug = :slug');
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
}

function enableFile($slug) {
    global $db;
    $stmt = $db->prepare('UPDATE files SET enabled = 1 WHERE slug = :slug');
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
}

function directDownload($slug) {
    if (file_exists('uploads/' . $slug)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $slug . '"');
        readfile('uploads/' . $slug);
        exit();
    }
}

function cleanFiles() {
    global $db;
    $now = date('Y-m-d H:i:s');
    $stmt = $db->prepare('SELECT * FROM files WHERE expires_at < :now');
    $stmt->bindParam(':now', $now);
    $stmt->execute();
    $files = $stmt->fetchAll();
    foreach ($files as $file) {
        fileDelete($file['slug']);
    }
}

function reportFile($slug, $reason) {
    global $db;
    $stmt = $db->prepare('INSERT INTO reports (slug, created_at, reason) VALUES (:slug, :created_at, :reason)');
    $created_at = date('Y-m-d H:i:s');
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->bindParam(':reason', $reason);
    $stmt->execute();
}

function getReports() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM reports');
    $stmt->execute();
    return $stmt->fetchAll();
}



function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $then = new DateTime($datetime);
    $interval = $then->diff($now);
    $suffix = ' ago';
    if ($interval->y > 1) {
        return $interval->format('%y years' . $suffix);
    }
    if ($interval->y > 0 && $interval->m > 0) {
        return $interval->format('%y years, %m months' . $suffix);
    }
    if ($interval->y > 0) {
        return $interval->format('%y year' . $suffix);
    }
    if ($interval->m > 1) {
        return $interval->format('%m months' . $suffix);
    }
    if ($interval->m > 0) {
        return $interval->format('%m month' . $suffix);
    }
    if ($interval->d > 1) {
        return $interval->format('%d days' . $suffix);
    }
    if ($interval->d > 0) {
        return $interval->format('%d day' . $suffix);
    }
    if ($interval->h > 1) {
        return $interval->format('%h hours' . $suffix);
    }
    if ($interval->h > 0) {
        return $interval->format('%h hour' . $suffix);
    }
    if ($interval->i > 1) {
        return $interval->format('%i minutes' . $suffix);
    }
    if ($interval->i > 0) {
        return $interval->format('%i minute' . $suffix);
    }
    if ($interval->s > 1) {
        return $interval->format('%s seconds' . $suffix);
    }
    return 'just now';
}

function time_yet_to_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $then = new DateTime($datetime);
    $diff = $now->diff($then);
    $suffix = ' left';
    if ($diff->y > 1) {
        return $diff->format('%y years' . $suffix);
    }
    if ($diff->y > 0 && $diff->m > 0) {
        return $diff->format('%y years, %m months' . $suffix);
    }
    if ($diff->y > 0) {
        return $diff->format('%y year' . $suffix);
    }
    if ($diff->m > 1) {
        return $diff->format('%m months' . $suffix);
    }
    if ($diff->m > 0) {
        return $diff->format('%m month' . $suffix);
    }
    if ($diff->d > 1) {
        return $diff->format('%d days' . $suffix);
    }
    if ($diff->d > 0) {
        return $diff->format('%d day' . $suffix);
    }
    if ($diff->h > 1) {
        return $diff->format('%h hours' . $suffix);
    }
    if ($diff->h > 0) {
        return $diff->format('%h hour' . $suffix);
    }
    if ($diff->i > 1) {
        return $diff->format('%i minutes' . $suffix);
    }
    if ($diff->i > 0) {
        return $diff->format('%i minute' . $suffix);
    }
    if ($diff->s > 1) {
        return $diff->format('%s seconds' . $suffix);
    }
    return 'just now';
}

?>