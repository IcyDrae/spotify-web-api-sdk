<?php


use Gjoni\Router\Router;

Router::setMap("SpotifyAPI\\Http\\Controllers");

Router::get("/", function() {
    echo "home";
});

Router::group("/authorize", function () {
    Router::get("/build-url", "AuthenticationController@buildAuthorizationUrl");
    Router::get("/request-access-token", "AuthenticationController@requestAccessToken");
});

Router::group("/user", function() {
    Router::get("/profile", "UserController@getProfile");
});

Router::get("/test-custom-header", "TestController@customHeader");

Router::run();
