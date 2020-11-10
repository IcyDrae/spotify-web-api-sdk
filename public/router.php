<?php


use Gjoni\Router\Router;

Router::setMap("SpotifyAPI\\Controllers");

Router::get("/", function() {
    echo "home";
});

Router::get("/build-request-url", "AuthenticationController@requestAuthCode");

Router::post("/request-access-token/{code}", "AuthenticationController@getAccessToken");

Router::get("/user-profile", "UserController@getProfile");

Router::get("/test-custom-header", "TestController@customHeader");

Router::run();
