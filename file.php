<h2><?php echo $file['original_name']; ?></h2>
<p>File code: <?php echo $file['slug']; ?></p>
<p>Created at: <?php echo $file['created_at']; ?></p>
<p>File type: <?php echo $file['extension']; ?></p>
<p>File size: <?php echo $file['size']; ?> bytes</p>
<p>Downloads so far: <?php echo $file['downloads']; ?></p>
<p>Expires at: <?php echo $file['expires_at']; ?></p>
<p>Password protected: <?php echo $file['password'] ? 'Yes' : 'No'; ?></p>

<!-- Report Form -->
<style>
    h3 {
        text-align: center;
    }
    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    input {
        margin-bottom: 10px;
        width: 200px;
    }
    input[type="submit"] {
        width: 100px;
        margin: 0 auto;
    }

    #preview_container {
        display: relative;
        width: 80%;
    }

    #preview {
        white-space: pre-wrap;
        word-wrap: break-word;
        display: block;
        margin: 0 auto;
        max-width: 100%;
        overflow-x: wrap;
        background-color: #1A1E23;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #2A2E33;
        color: #8A8D91;
    }

    #preview_filename {
        color: #fff;
        margin-bottom: 3px;
    }

    #preview_card {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        width: 80%;
        margin: 0 auto;
        background-color: #1A1E23;
        padding: 10px;
        border-radius: 10px;
        border: 1px solid #2A2E33;
        color: #8A8D91;
    }

    #preview_card .thumbnail {
        margin-right: 10px;
        max-height: 100px;
    }

    a.download_preview {
        text-decoration: none;
    }
</style>
<?php
    $possible_phrases = [
        "You're download is ready!",
        "Download your file below.",
        "Your file is ready to be downloaded.",
        "Download your file now.",
        "Would you download it already?",
        "What are you waiting for? Download it!",
        "You're just a click away from downloading your file.",
        "This is the moment you've been waiting for. Download it!",
        "So close! Just download it already.",
        "This is how you choose to waste your time? Right, well... good luck with that.",
        "You're getting fat.",
        "You're not getting any younger.",
        "You're not getting any better looking.",
        "My cat is more interesting than you.",
        "There are children in Africa who are more productive than you. #LoveAfrica #You'reKindaShitty",
        "I'm not mad, I'm just disappointed.",
        "If you were a vegetable, you'd be a turnip.",
        "You're not the person Mr. Rogers thought you could be.",
        "I'm not saying you're a failure, but you're not a success.",
        "I'm not disappointed, I'm just mad.",
        "If spaying and neutering applied to humans, I'd have you in a cone right now.",
        "Why are you like this?",
        "My expectations were low, but holy cow.",
        "My extra chromosome is more useful than you.",
        "My extended testicle is more valuable to society than you.",
        "If you were a fruit, you'd be a cucumber. And not the good kind.",
        "You're not the sharpest tool in the shed. You're not even in the shed. You're just the weird brush that I used to scratch my balls with.",
        "At least you're not a TikTok user.",
        "You're not the worst person I know, but you're in the top 10.",
        "It's better to be late than to arrive ugly.",
        "It's better to cum in the sink than to sink in the cum.",
        "Do you ever wonder what life would be like if you'd gotten enough oxygen at birth?",
        "It's better to be pissed off than pissed on. Unless you're into that sort of thing.",
        "Do you smell that? Smells like failure.",
        "I'm... horny?",
        "I'm horny!",
        "I'm so horny!",
        "I'm so horny right now!",
        "I'm no longer horny.",
        "I'm horny again!",
        "Put that thing back where it came from or so help me!",
        "Scooby-dooby-dooooooooooooooooo!",
        "I'm not a doctor, so unfortunately I can't help you with your erectile dysfunction.",
        "I'm not a doctor, but I can help you with your erectile dysfunction.",
        "Roses are red, violets are blue, I'm schizophrenic, and so am I.",
        "I smell wet paint.",
        "I smell wet paint. Do you smell wet paint?",
        "I smell wet paint. Do you smell wet paint? I smell wet paint.",
        "DO YOU SMELL WET PAINT?",
        "This is a test of the emergency broadcast system. This is only a test.",
        "This is a test of the emergency broadcast system. This is only a test. If this had been an actual emergency, you would have been instructed where to tune in your area for news and official information.",
        "This is getting kinda stupid, isn't it?",
        "I shouldn't have run over that squirrel.",
        "Could you imagine if I was a real person?, I'd be a real asshole."
    ];
?>
<h3><?php echo $possible_phrases[array_rand($possible_phrases)]; ?></h3>

<?php $preview_text_mime_types = [
    'text',
    'text/plain',
    'application/json',
    'application/xml',
    'application/csv',
    'text/html',
    'text/css',
    'text/javascript',
    'application',
    'application/x-sh',
    'application/octet-stream',
    'application/javascript',
    'application/x-javascript',
    'application/sql',
    'application/shell',
    'text/yaml',
    'text/x-toml',
    'text/x-ini',
    'text/x-config',
    'text/x-log',
    'text/markdown',
    'text/x-markdown',
    'text/x-rst',
    'text/x-textile',
    'text/x-asciidoc',
    'text/x-creole',
    'text/x-mediawiki',
    'text/x-wiki',
    'text/x-rdoc',
    'text/x-org',
    'text/x-pod',
    'text/x-tex',
    'text/x-latex',
    'text/x-lout',
    'text/x-groff',
    'text/x-troff'
];

$preview_image_mime_types = [
    'image',
    'image/png',
    'image/jpeg',
    'image/gif',
    'image/webp',
    'image/svg+xml',
    'image/tiff',
    'image/bmp',
    'image/vnd.microsoft.icon',
    'image/x-icon',
    'image/vnd.wap.wbmp',
    'image/ico',
    'image/icon',
    'image/x-icon',
    'image/x-ico',
    'image/x-win-bitmap'
];

$preview_video_mime_types = [
    'video',
    'video/mp4',
    'video/webm',
    'video/ogg',
    'video/quicktime',
    'video/x-msvideo',
    'video/x-ms-wmv',
    'video/x-flv',
    'video/x-matroska',
    'video/3gpp',
    'video/3gpp2',
    'video/avi'
];

$preview_audio_mime_types = [
    'audio',
    'audio/mpeg',
    'audio/ogg',
    'audio/wav',
    'audio/webm',
    'audio/aac',
    'audio/flac',
    'audio/midi',
    'audio/x-midi',
    'audio/x-wav',
    'audio/x-aiff',
    'audio/x-aifc',
    'audio/x-aif',
    'audio/x-gsm',
    'audio/x-ms-wma'
];
?>

<a href="/raw/<?php echo $file['slug']; ?>" class="download_preview">
    <div id="preview_card">
        <?php
        // If it has a password, don't show the preview
        if ($file['password']) {
            ?>
            <div class = "thumbnail">
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/shield.png')); ?>" alt="File" class="thumbnail">
            </div>
            <?php
        }else{
        ?>
        <div class="thumbnail">
            <?php if ($file['extension'] === 'pdf') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/pdf.png')); ?>" alt="PDF" class="thumbnail">
            <?php elseif ($file['extension'] === 'png') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/png.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'jpg' || $file['extension'] === 'jpeg') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/jpg.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'gif') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/gif.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'mp4') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/mp4.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'mov') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/mov.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'avi') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/video.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'mp3') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/mp3.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'wav') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/music.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'ogg') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/music.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'txt') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/txt.png')); ?>" alt="File" class="thumbnail">
            <?php elseif ($file['extension'] === 'zip' || $file['extension'] === 'rar' || $file['extension'] === '7z') : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/zip.png')); ?>" alt="File" class="thumbnail">
            <?php elseif (in_array(explode('/', $file['MIME'])[0], $preview_text_mime_types)) : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/txt.png')); ?>" alt="File" class="thumbnail">
            <?php else : ?>
                <img src="data:image/png;base64,<?php echo base64_encode(file_get_contents('../icons/file.png')); ?>" alt="File" class="thumbnail">
            <?php endif; ?>
        </div>
        <?php } ?>
        <div class="details">
            <p>Name : <?php echo $file['original_name']; ?></p>
            <p>Type : <?php echo $file['MIME']; ?></p>
            <p>Size : <?php echo $file['size']; ?> bytes</p>
        </div>
    </div>
</a>

<?php if (in_array($file['MIME'], $preview_image_mime_types) && !$file['password']) : ?>
    <img src="/raw/<?php echo $file['slug']; ?>" alt="Preview" id="preview">
<?php endif; ?>

<h3>Report</h3>
<form action="/report/<?php echo $file['slug']; ?>" method="POST">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" id="file_report_token">
    <input type="text" name="reason" placeholder="Reason" required id="file_report_reason">
    <input type="submit" value="Report" id="file_report_submit">
</form>
<?php if (isset($_SESSION['extra_message'])) : ?>
    <p><?php echo $_SESSION['extra_message']; ?></p>
    <?php $_SESSION['extra_message'] = null; ?>
<?php endif; ?>