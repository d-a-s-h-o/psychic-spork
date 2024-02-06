<h2>About</h2>
<div class="tldr">
    <h3>TL;DR</h3>
    <p>This is a free.</p>
    <ul>
        <li>Maximum file size: <b>10GB</b></li>
        <li>Maximum file expiration: <b>2 years</b></li>
        <li><span style="color: #2ECC40;">File password protection: <b>Yes (and this encrypts the file at rest)</b></span></li>
        <li>Logging: <b>None</b></li>
        <li><span style="color: #FF4136;">Javascript: <b>No</b></span></li>
    </ul>
    <p>I only delete CSAM.</p>
    <p>I am rarely online to delete things.</p>

</div>
<p>This is a free and easy to use file upload site. It is designed to be simple and fast for users to share files with others.</p>
<p>Files are automatically deleted after two years, and can be password protected if desired.</p>
<p>There is also an API available for developers to integrate with their own applications. I mean, it's fairly simple, but it's there.</p>
<ul>
    <li>Maximum file size: 10GB (Try splitting your files into smaller chunks if they're larger than that, 7-zip is a good tool for that)</li>
    <li>Maximum file expiration: 2 years (I mean, that's should be enough, right?)</li>
    <li>Inline file preview for images and text files</li>
    <li>API for developers</li>
    <li>File password protection. (If you password protect a file, the file will be encrypted using the password you provide. This means that the file will be safe from prying eyes when at rest, but if you forget the password, there is no way to recover the file.)</li>
    <li>File deletion after expiration</li>
    <li>Zero javascript... why? Because Tor users are cool too.</li>
</ul>
<h2>Privacy</h2>
<p>Are you having a laugh? This is the internet. There is no privacy.</p>
<p>But you can always encrypt your files before uploading them. That's a thing. Have a look at <a href="https://www.gnupg.org/">GnuPG</a> and <a href="https://www.7-zip.org/">7-Zip</a>. They're both free and open source.</p>
<p>If you password protect a file, the file will be encrypted using the password you provide. However, this is only for the file at rest. The file will be decrypted on the server and sent to the user in plain text (but over HTTPS if that makes you feel any better). There is no logging of the password or the decrypted file. I have turned off all logging on the server because I don't want liability for anything. I don't even know what's in the files. I don't want to know. I don't care. I'm just providing a service. I'm not your mom.</p>
<h2>Abuse</h2>
<p>If you see any abuse of this service, please report it to the site administrator at <a href="mailto:abuse@dasho.dev">abuse@dasho.dev</a>. I will take action to remove any abusive content as soon as possible. Please note that I cannot remove encrypted files, as I do not know what is in them. If you have a problem with an encrypted file, you will need to provide the password to prove that the file is abusive.</p>
<p>Let's be real here, though. Even if I do remove a file, it'll probably just get uploaded again. I'm just a guy with a server. Don't bug me unless it's really bad like CSAM (Child Sexual Abuse Material) or something. I don't want that on my server.</p>
<h4>Are you law enforcement?</h4>
<p>Well if you are, then I'm happy to cooperate with you in keeping people safe. I don't need a warrant shoved in my face to do the right thing. You can request the removal of any file by emailing me at <a href="mailto:abuse@dasho.dev">abuse@dasho.dev</a>. Unlike most of the trolls who report things that just don't respect their pronouns, I will actually take your request seriously.</p>
<h2>Source Code</h2>
<p>The source code for this application might become available on <a href="github.com">GitHub</a> shortly, once I stop being lazy.</p>