<?php


function getFixture(string $name): string {
    $fileContents = "";
    $path = getcwd() . "/tests/Unit/fixtures/$name.json";

    if (file_exists($path)) {
        $fileContents = file_get_contents($path);
    }

    return $fileContents;
}
