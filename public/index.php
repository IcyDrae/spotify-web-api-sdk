<?php

error_reporting(E_ALL);

date_default_timezone_set("Europe/Berlin");

require_once dirname(__DIR__) . "/bootstrap.php";

use SpotifyAPI\Http\Response;
use bandwidthThrottle\tokenBucket\storage\StorageException;
use bandwidthThrottle\tokenBucket\Rate;
use bandwidthThrottle\tokenBucket\TokenBucket;
use bandwidthThrottle\tokenBucket\storage\FileStorage;

$response = new Response();
$response->headers();

/**
 * Rate limiting- 10 request per second maximum
 */
try {
    $storage = new FileStorage(__DIR__ . "/api.bucket");
} catch (StorageException $e) {
    printf($e->getMessage());
}

$rate = new Rate(10, Rate::SECOND);
$bucket = new TokenBucket(10, $rate, $storage);
$bucket->bootstrap(10);

if (!$bucket->consume(1, $seconds)) {
    http_response_code(429);
    header(sprintf("Retry-After: %d", floor($seconds)));
    exit();
}

require_once "router.php";

?>
