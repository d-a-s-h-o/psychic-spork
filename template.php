<?php
if($page === 'raw') {
    include 'raw.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bin 404</title>
    <style>
        body {
            font-family: Lucida Grande, Tahoma, Verdana, Arial, sans-serif;
            background-color: #12161A;
            color: #fff;
        }
        a {
            color: #25a7ff;
            text-decoration: underline dotted;
        }
        .container {
            width: 50%;
            margin: 0 auto;
        }
        .container h1 {
            text-align: center;
        }
        .container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container form input {
            margin-bottom: 10px;
            width: 200px;
        }
        .container form input[type="submit"] {
            width: 100px;
            margin: 0 auto;
        }

        nav {
            display: flex;
            justify-content: space-between;
            background-color: #1A1E23;
            border-radius: 10px;
        }

        nav .left,
        nav .right {
            display: flex;
            align-items: center;
        }
        nav .left div,
        nav .right div {
            margin: 0;
        }

        nav .left div,
        nav .right div {
            color: #fff;
            background-color: transparent;
            border: none;
            border-radius: 0;
            padding: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        nav .left div {
            border-right: 1px solid #2A2E33;
            /* Set left button to have 5px border-radius */
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        /* Make right buttons have appropriate separating borders and correct border radiuses */
        nav .right div {
            border-left: 1px solid #2A2E33;
        }

        nav .right a:first-child div {
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
            border-left: none;
        }

        nav .right a:last-child div {
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        nav .right a div[data-active="true"] {
            background-color: #4CAF50;
        }

        nav .left a div:hover,
        nav .right a div:hover {
            background-color: #2A2E33;
        }

        nav div a {
            text-decoration: none;
        }

        code {
            background-color: #111416; 
            padding: 5px;
            border-radius: 5px;
        }

        input[type="file"]::file-selector-button {
            display: none;
        }

        .file-upload {
            border: 1px solid #ccc;
            background-color : #2A2E33;
            border-radius: 5px;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
            margin: 10px auto;
            width: 200px;
            box-sizing: border-box;
            text-align: center;
        }

        .file-upload:hover {
            background-color: #22262B;
        }

        #secondary_header {
            margin-top: 20px;
            text-align: center;
        }

        #secondary_input_one,
        #secondary_input_two,
        #secondary_submit,
        #file_download_password,
        #file_download_submit,
        #file_report_reason,
        #file_report_submit,
        #home_file_password,
        #home_file_expire,
        #home_file_submit {
            max-width: 200px;
            width: 200px;
            margin-bottom: 10px;
            padding: 5px;
            background-color: #2A2E33;
            border: 1px solid #ccc;
            border-radius: 5px;
            color: #fff;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #1A1E23;
        }

        th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #2A2E33;
            color: white;
        }

        .table {
            margin: 0 auto;
        }

        .table a {
            color: #25a7ff;
            text-decoration: underline dotted;
        }

        .tldr {
            background-color: #2A2E33;
            padding: 10px;
            border-radius: 5px;
            margin: -10px 0;
        }

        .pages {
            /* Make a masonary grid */
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .page_card {
            /* Make the cards scale to fit the width of the page */
            flex: 1 1 300px;
            margin: 10px;
            padding: 10px;
            background-color: #2A2E33;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .page_card:hover {
            background-color: #1A1E23;
        }

        .page_card_title {
            color: #fff;
            /* Make the title of the card bold and scale with the size of the card */
            font-weight: bold;
            font-size: 1.5em;
        }

        .pages a {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <nav>
        <div class="left">
            <a href="/">
                <div id="home" data-active="<?php echo $page === 'home' ? 'true' : 'false'; ?>" >
                    Home
                </div>
            </a>
        </div>
        <div class="right">
            <?php if (isAdminLoggedIn()) { ?>
            <a href="/admin">
                <div id="admin" data-active="<?php echo $page === 'admin' ? 'true' : 'false'; ?>">
                    Admin
                </div>
            </a>
            <?php } ?>
            <a href="/about">
                <div id="about" data-active="<?php echo $page === 'about' ? 'true' : 'false'; ?>">
                    About
                </div>
            </a>
            <a href="/api">
                <div id="api" data-active="<?php echo $page === 'api' ? 'true' : 'false'; ?>">
                    API
                </div>
            </a>
            <?php if (isAdminLoggedIn()) { ?>
            <a href="/logout">
                <div id="logout" style="background-color: #FF4136;">
                    Logout
                </div>
            </a>
            <?php } ?>
        </div>
    </nav>
    <div class="container">
        <h1>BIN-404</h1>
        <?php
            if (isset($page)) {
                if($page === 'raw') {
                    if(isset($raw_page_content)) {
                        echo $raw_page_content;
                    }
                }else{
                    include $page . '.php';
                }
            }
        ?>
    </div>