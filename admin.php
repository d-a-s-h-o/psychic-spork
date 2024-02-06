<h3>Sessionny Technical Information</h3>
<pre>Your logged in status is: <?php echo isAdminLoggedIn() ? 'Logged in' : 'Not logged in'; ?></pre>
<pre>The current server time is: <?php echo date('Y-m-d H:i:s'); ?></pre>
<pre>Your token is: <span style="color: #FF4136;"><?php echo $_SESSION['token']; ?></span></pre>
<?php if(isAdminLoggedIn()) { ?>
<pre>Other tokens:</pre>
<pre>
<?php
$sessions = getSessions();
foreach ($sessions as $session) {
    if ($session['token'] === $_SESSION['token']) {
        $style = 'color: #FF4136; display: inline;';
    }
    else {
        $style = 'display: inline;';
    }
    echo '<form method="post" action="/admin" style="display: block;"><input type="hidden" name="action" value="delete_session"><input type="hidden" name="token" value="' . $session['token'] . '"><label style="' . $style . '">' . $session['token'] . ', Expires: ' . $session['expires_at'] . '</label> <input type="submit" value="Delete" style="background-color: #FF4136; display: inline;"></form><br />';
}
?>
</pre>

<h2>Admin</h2>
<div>
    <?php
    // If there are any reports, show them
    $reports = getReports();
    if (count($reports) > 0) {
        echo '<h3>Reports</h3>';
        echo '<table class="table">';
        echo '<tr>';
        echo '<th>File</th>';
        echo '<th>Reported At</th>';
        echo '<th>Reason</th>';
        echo '<th>Delete</th>';
        echo '</tr>';
        foreach ($reports as $report) {
            echo '<tr>';
            echo '<td><a href="/file/' . $report['slug'] . '">' . $report['slug'] . '</a></td>';
            echo '<td>' . $report['created_at'] . '</td>';
            echo '<td>' . $report['reason'] . '</td>';
            echo '<td><form method="post" action="/admin"><input type="hidden" name="action" value="delete_report"><input type="hidden" name="slug" value="' . $report['slug'] . '"><input type="submit" value="Delete" style="background-color: #FF4136;"></form></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    ?>

    <!-- List of all uploaded files from newest to oldest and paginated in groups of fifty depending on $_SESSION['admin_page'] variable -->
    <h3>Uploaded Files</h3>
    <table class="table">
        <tr>
            <th>File</th>
            <th>Slug</th>
            <th>Uploaded At</th>
            <th>Expires At</th>
            <th>Password</th>
            <th>Delete</th>
        </tr>
        <?php
        $files = getFiles();
        foreach ($files as $file) {
            echo '<tr>';
            echo '<td><a href="/file/' . $file['slug'] . '">' . $file['original_name'] . '</a></td>';
            echo '<td>' . $file['slug'] . '</td>';
            // Set dates to short form
            $file_ct = date('Y-m-d', strtotime($file['created_at']));
            $file_et = date('Y-m-d', strtotime($file['expires_at']));
            // Express relative time to (4 minutes ago, 3 days ago, etc.)
            $file_ct_ = date('Y-m-d H:i:s', strtotime($file['created_at']));
            $file_et_ = date('Y-m-d H:i:s', strtotime($file['expires_at']));
            $file_ct = time_elapsed_string($file_ct_);
            $file_et = time_yet_to_elapsed_string($file_et_);
            echo '<td>' . $file_ct . '</td>';
            echo '<td>' . $file_et . '</td>';
            echo '<td>' . ($file['password'] ? $file['password'] : 'No') . '</td>';
            echo '<td><form method="post" action="/admin"><input type="hidden" name="action" value="delete_file"><input type="hidden" name="slug" value="' . $file['slug'] . '"><input type="submit" value="Delete" style="background-color: #FF4136;"></form></td>';
            echo '</tr>';
        }
        ?>
    </table>
</div>
<h2>Settings</h2>

<style>
    .db_preview {
        margin-top: 20px;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
        width: fit-content;
        margin-left: auto;
        margin-right: auto;
    }

    .db_preview table {
        border-collapse: collapse;
        /* scale down the table to fit the container */
        max-width: 100%;
        --_scale_factor: 0.9;
        transform: scale(var(--_scale_factor));
    }

    .space {
        /* Add 1/4 of the page to the top */
        margin-top: 25%;
    }
</style>
<div class="Database">
    <h3>Database</h3>
    <p>Database version: <?php echo $db_version; ?></p>
    <p>Database size: <?php echo formatBytes(filesize('../files.db')); ?></p>
    <p>Database last modified: <?php echo date('Y-m-d H:i:s', filemtime('../files.db')); ?></p>
    <!-- Include inline preview of entire database -->
    <div class="space"></div>
    <details class="db_preview">
        <summary>Database Preview</summary>
        <h4>Files</h4>
            <?php
            $db = new PDO('sqlite:../files.db');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $result = $db->query('SELECT * FROM files');
            $files = $result->fetchAll();
            ?>
            <table>
                <tr>
                    <th>Original Name (TEXT)</th>
                    <th>Slug (TEXT)</th>
                    <th>Created At (TEXT)</th>
                    <th>Expires At (TEXT)</th>
                    <th>Password (TEXT)</th>
                    <th>MIME (TEXT)</th>
                    <th>Size (INTEGER)</th>
                    <th>Extension (TEXT)</th>
                    <th>Downloads (INTEGER)</th>
                    <th>Enabled (INTEGER)</th>
                </tr>
                <?php
                foreach ($files as $file) {
                    echo '<tr>';
                    echo '<td>' . $file['original_name'] . '</td>';
                    echo '<td>' . $file['slug'] . '</td>';
                    echo '<td>' . $file['created_at'] . '</td>';
                    echo '<td>' . $file['expires_at'] . '</td>';
                    echo '<td>' . $file['password'] . '</td>';
                    echo '<td>' . $file['MIME'] . '</td>';
                    echo '<td>' . $file['size'] . '</td>';
                    echo '<td>' . $file['extension'] . '</td>';
                    echo '<td>' . $file['downloads'] . '</td>';
                    echo '<td>' . $file['enabled'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        <h4>Sessions</h4>
            <?php
            $result = $db->query('SELECT * FROM sessions');
            $sessions = $result->fetchAll();
            ?>
            <table>
                <tr>
                    <th>Token (TEXT)</th>
                    <th>Created At (TEXT)</th>
                    <th>Expires At (TEXT)</th>
                </tr>
                <?php
                foreach ($sessions as $session) {
                    echo '<tr>';
                    echo '<td>' . $session['token'] . '</td>';
                    echo '<td>' . $session['created_at'] . '</td>';
                    echo '<td>' . $session['expires_at'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        <h4>Reports</h4>
            <?php
            $result = $db->query('SELECT * FROM reports');
            $reports = $result->fetchAll();
            ?>
            <table>
                <tr>
                    <th>Slug (TEXT)</th>
                    <th>Created At (TEXT)</th>
                    <th>Reason (TEXT)</th>
                </tr>
                <?php
                foreach ($reports as $report) {
                    echo '<tr>';
                    echo '<td>' . $report['slug'] . '</td>';
                    echo '<td>' . $report['created_at'] . '</td>';
                    echo '<td>' . $report['reason'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        <h4>Config</h4>
            <?php
            $result = $db->query('SELECT * FROM config');
            $config = $result->fetchAll();
            ?>
            <table>
                <tr>
                    <th>Name (TEXT)</th>
                    <th>Value (TEXT)</th>
                </tr>
                <?php
                foreach ($config as $conf) {
                    echo '<tr>';
                    echo '<td>' . $conf['name'] . '</td>';
                    echo '<td>' . $conf['value'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        <h4>'files' Schema</h4>
            <?php
            $result = $db->query('PRAGMA table_info(files)');
            $files = $result->fetchAll();
            ?>
            <table>
                <tr>
                    <th>Column</th>
                    <th>Type</th>
                    <th>PK</th>
                </tr>
                <?php
                foreach ($files as $file) {
                    echo '<tr>';
                    echo '<td>' . $file['name'] . '</td>';
                    echo '<td>' . $file['type'] . '</td>';
                    echo '<td>' . ($file['pk'] ? 'Yes' : 'No') . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        <h4>'sessions' Schema</h4>
            <?php
            $result = $db->query('PRAGMA table_info(sessions)');
            $sessions = $result->fetchAll();
            ?>
            <table>
                <tr>
                    <th>Column</th>
                    <th>Type</th>
                    <th>PK</th>
                </tr>
                <?php
                foreach ($sessions as $session) {
                    echo '<tr>';
                    echo '<td>' . $session['name'] . '</td>';
                    echo '<td>' . $session['type'] . '</td>';
                    echo '<td>' . ($session['pk'] ? 'Yes' : 'No') . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        <h4>'reports' Schema</h4>
            <?php
            $result = $db->query('PRAGMA table_info(reports)');
            $reports = $result->fetchAll();
            ?>
            <table>
                <tr>
                    <th>Column</th>
                    <th>Type</th>
                    <th>PK</th>
                </tr>
                <?php
                foreach ($reports as $report) {
                    echo '<tr>';
                    echo '<td>' . $report['name'] . '</td>';
                    echo '<td>' . $report['type'] . '</td>';
                    echo '<td>' . ($report['pk'] ? 'Yes' : 'No') . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        <h4>'config' Schema</h4>
            <?php
            $result = $db->query('PRAGMA table_info(config)');
            $config = $result->fetchAll();
            ?>
            <table>
                <tr>
                    <th>Column</th>
                    <th>Type</th>
                    <th>PK</th>
                </tr>
                <?php
                foreach ($config as $conf) {
                    echo '<tr>';
                    echo '<td>' . $conf['name'] . '</td>';
                    echo '<td>' . $conf['type'] . '</td>';
                    echo '<td>' . ($conf['pk'] ? 'Yes' : 'No') . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
    </details>










<?php } else { ?>

<h2 id="secondary_header">Admin Login</h2>
<form method="post" action="/admin">
    <input type="text" name="username" placeholder="Username" id="secondary_input_one">
    <input type="password" name="password" placeholder="Password" id="secondary_input_two">
    <input type="hidden" name="action" value="admin_login">
    <input type="submit" value="Login" id="secondary_submit">
</form>

<?php
if (isset($_SESSION['admin_login_error_message'])) {
        ?>
        <p style="color: #FF4136;">Sorry, the username or password is incorrect.</p>
        <?php
        unset($_SESSION['admin_login_error_message']);
    }
}
?>