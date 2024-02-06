<?php
$raw_page_content = null;
if ($file['password']) {
    if (isset($incomingPassword)) {
        if (password_verify($_POST['password'], password_hash($file['password'], PASSWORD_DEFAULT))) {
            // Password is correct
            fileDownload($file['slug']);
        } else {
            // Password is incorrect
            $raw_page_content =  '
            <p>Password is incorrect.</p>
            <p>Try again.</p>
            <form action="/raw/' . $file['slug'] . '" method="POST">
            <input type="password" name="password" placeholder="Password" required id="file_download_password">
            <input type="submit" value="Submit" id="file_download_submit">
            </form>';
        }
    } else {
        // Ask for password
        $raw_page_content = '
        <p>Oops! This file is password protected. Please enter the password to download it.</p>
        <form action="/raw/' . $file['slug'] . '" method="POST">
        <input type="password" name="password" placeholder="Password" required id="file_download_password">
        <input type="submit" value="Submit" id="file_download_submit">
        </form>';
    }
} else {
    fileDownload($file['slug']);
}
// For Curl requests

?>