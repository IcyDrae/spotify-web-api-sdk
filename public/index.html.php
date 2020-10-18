<?php

error_reporting(E_ALL);

require_once(__DIR__ . "./../vendor/autoload.php");
require_once(__DIR__ . "./../config/SecretsCollection.php");

use SpotifyAPI\Controllers\Authentication;
use SpotifyAPI\Secrets\Secrets;


$secrets = new Secrets();
$secrets->setId(SecretsCollection::$id);
$secrets->setSecret(SecretsCollection::$secret);
$secrets->setHost(SecretsCollection::$host);
$secrets->setBaseUri(SecretsCollection::$baseUri);
$secrets->setApiUri(SecretsCollection::$apiUri);

