<?php

use Gjoni\Router\Router;

Router::setMap("SpotifyAPI\\Http\\Controllers");

Router::get("/", function() {
    echo "home";
});

Router::group("/authorization", function () {
    Router::get("/url", "AuthenticationController@buildAuthorizationUrl");

    Router::post("/access-token", "AuthenticationController@requestAccessToken");
    Router::post("/refreshed-access-token", "AuthenticationController@refreshAccessToken");
});

Router::group("/user", function() {
    Router::get("/profile", "UserController@getProfile");
    Router::get("/playlists", "UserController@getPlaylists");
});

Router::group("/test", function() {
    Router::get("/cookie", "TestController@customHeader");
});

Router::run();
