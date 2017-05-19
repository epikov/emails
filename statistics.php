<?php

header('Content-Type: image/gif');
echo base64_decode('R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==');

if ($_GET['counter_id']) {
    file_put_contents('open_email.log', 'opened in ' . $_GET['counter_id'], FILE_APPEND);
}
