<?php

error_reporting(E_ALL);

require_once dirname(__DIR__) . "/bootstrap.php";

use SpotifyAPI\Http\Response;

$output = new Response();
$output->headers();

require_once "router.php";

?>

