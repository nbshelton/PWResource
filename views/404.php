<h1>HTTP 404: Not Found</h1>

<p>The resource you requested was not found on the server:</p>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443 ? "https" : "http"; ?>
<p><?=$protocol."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?></p>