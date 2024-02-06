<form method="post" enctype="multipart/form-data" action="/upload" id="home_file_form">
<input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="123" />
<label for="home_file_upload" class="file-upload">
    ☁️ Choose File
</label>
<input name="file" id="home_file_upload" type="file"/>
<input type="text" id="home_file_password" name="password" placeholder="Password (optional)">
<!-- Expiry dropdown menu -->
<select name="expires" id="home_file_expire">
    <option value="0.01">15 minutes</option>
    <option value="0.02">30 minutes</option>
    <option value="0.04">1 hour</option>
    <option value="0.12">3 hours</option>
    <option value="0.25">6 hours</option>
    <option value="0.5">12 hours</option>
    <option value="1">1 day</option>
    <option value="3">3 days</option>
    <option value="7">1 week</option>
    <option value="14">2 weeks</option>
    <option value="30">1 month</option>
    <option value="90">3 months</option>
    <option value="365">1 year</option>
    <option value="730" selected>2 years</option>
</select>
<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
<input type="hidden" name="action" value="upload">
<input type="submit" value="Upload" id="home_file_submit">
</form>
<br />
<h2>Home</h2>
<p>Welcome to BIN-404! A practical file sharing website.

<style>
    #logo {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 50%;
        filter: drop-shadow(0px 0px 5px #000) grayscale(100%);
        transition: filter 0.5s;
    }
    #logo:hover {
        filter: drop-shadow(0px 0px 30px #000);
    }
</style>
<?php render_logo(); ?>