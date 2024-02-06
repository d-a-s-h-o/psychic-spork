<?php
// Set up the database connection
$db = new PDO('sqlite:../files.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialize database
$db->exec('CREATE TABLE IF NOT EXISTS files (
            id TEXT PRIMARY KEY,
            original_name TEXT,
            slug TEXT,
            created_at TEXT,
            MIME TEXT, size INTEGER,
            extension TEXT,
            downloads INTEGER,
            expires_at TEXT,
            password TEXT,
            enabled INTEGER)');
// Sessions table schema:
$db->exec('CREATE TABLE IF NOT EXISTS sessions (
            token TEXT PRIMARY KEY,
            created_at TEXT,
            expires_at TEXT)');
// Create an index on the slug column for faster lookups
$db->exec('CREATE INDEX IF NOT EXISTS slug_index ON files (slug)');
// Create an index on the token column for faster lookups
$db->exec('CREATE INDEX IF NOT EXISTS token_index ON sessions (token)');
// Create a reports table
$db->exec('CREATE TABLE IF NOT EXISTS reports (
            id INTEGER PRIMARY KEY,
            slug TEXT,
            created_at TEXT,
            reason TEXT)');
// Create config table
$db->exec('CREATE TABLE IF NOT EXISTS config (
            id INTEGER PRIMARY KEY,
            name TEXT,
            value TEXT)');

// Check the database version
$result = $db->query('SELECT value FROM config WHERE name = "db_version"');
if ($result) {
    $db_version = $result->fetchColumn();
} else {
    $db->exec('INSERT INTO config (name, value) VALUES ("db_version", 1)');
}

//  These are for updating the database schema
if ($db_version < 2) {
    // Add the extension column to the files table if it doesn't exist
    $exists = $db->query('PRAGMA table_info(files)')->fetchAll();
    $exists = array_map(function ($column) {
        return $column['name'];
    }, $exists);
    if (!in_array('extension', $exists)) {
        $db->exec('ALTER TABLE files ADD COLUMN extension TEXT');
    }
    // Update the database version
    $db->exec('UPDATE config SET value = 2 WHERE name = "db_version"');
}
?>