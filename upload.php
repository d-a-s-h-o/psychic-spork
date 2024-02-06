<?php

if (isset($incomingAction)) {
    if ($incomingAction === 'upload') {
        $file = $incomingFile;
        $password = $incomingPassword;
        $expires = $incomingExpires;
        $token = $incomingToken;
        $slug = fileUpload($file, $password, $expires);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="refresh" content="5">
        <title>bin 404 - progress</title>
        <style>
            body {
                font-family: Lucida Grande, Tahoma, Verdana, Arial, sans-serif;
                background-color: #12161A;
                color: #fff;
            }
            .container {
                width: 50%;
                margin: 0 auto;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>bin 404 - progress</h1>
            <?php
            $key = ini_get("session.upload_progress.prefix") . "test";
            if (!empty($_SESSION[$key])) {
                $progress = $_SESSION[$key];
                $pct = 0;
                $size = 0;
                if ($progress["bytes_processed"] > 0 && $progress["content_length"] > 0) {
                    $pct = $progress["bytes_processed"] / $progress["content_length"] * 100;
                    $size = $progress["content_length"];
                }?>
                <div class="container">
                    <h1>bin 404 - progress</h1>
                    <p>Progress: <?php echo $pct; ?>%</p>
                    <p>Bytes processed: <?php echo $progress["bytes_processed"]; ?></p>
                    <p>Content length: <?php echo $progress["content_length"]; ?></p>
                    <?php
                    if ($pct == 100) {
                        ?>
                        <p>File upload complete!</p>
                        <p>Size: <?php echo $size; ?></p>
                        <p>Redirecting...</p>
                        <?php
                        header('Location: /file/' . $slug);
                    } else {
                        ?>
                        <p>Your file is being uploaded. Please wait...</p>
                        <p>Once the upload is complete, you will be redirected to the file page -> <a href="/file/<?php echo $slug; ?>">https://bin.4-0-4.io/file/<?php echo $slug; ?></a></p>
                        <?php
                    }
                    ?>
                </div>
                <?php
            } else {
                header('Location: /file/' . $slug);
            }
            ?>
        </div>
    </body>
</html>