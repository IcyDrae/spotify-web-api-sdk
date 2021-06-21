
![GitHub](https://img.shields.io/github/license/ReardGjoni/spotify-web-api-sdk?style=flat-square)
![GitHub](https://img.shields.io/github/v/release/ReardGjoni/spotify-web-api-sdk?style=flat-square)
![packagist](https://img.shields.io/packagist/v/rgjoni/spotify-web-api-sdk?style=flat-square)
![GitHub](https://img.shields.io/github/last-commit/ReardGjoni/spotify-web-api-sdk/main?style=flat-square)

# Spotify Web API SDK

An SDK for the Spotify Web API, covering all available endpoints and offering different
authorization flows.

<a href="https://developer.spotify.com/documentation/web-api/reference/" target="_blank">
    Reference link
</a> (external)

### Table of contents

1. [Supported API's](#supported-apis)
1. [Supported authorization flows](#supported-authorization-flows)
2. [Dependencies](#dependencies)
    1. [Development dependencies](#development-dependencies)
4. [Installation](#installation)
5. [Namespace](#namespace)
6. [Usage](#usage)<br>
    1. [First Steps](#first-steps)
    2. [Authorization](#authorization)
        1. [Authorization Code Flow](#authorization-code-flow)
        2. [Authorization Code Flow with Proof Key for Code Exchange(PKCE)](#authorization-code-flow-with-proof-key-for-code-exchangepkce)
        3. [Client Credentials Flow](#client-credentials-flow)
        4. [Persisting tokens](#persisting-tokens)
    3. [Web API Endpoints](#web-api-endpoints)
7. [Errors & Exceptions](#errors-&-exceptions)
8. [Contributing](#contributing)
9. [License](#license)

## Supported API's

- Search API
- Browse API
- Follow API
- Playlists API
- Library API
- Artists API
- Player API
- Markets API
- Personalization API
- User Profile API
- Albums API
- Tracks API
- Episodes API
- Shows API

## Supported authorization flows
- Authorization Code
- Authorization Code with Proof Key for Code Exchange(PKCE)
- Client Credentials

## Dependencies

- PHP 8.0 or higher
- Guzzle

### Development dependencies
- PHPUnit

## Installation

Using composer:

```
composer require rgjoni/spotify-web-api-sdk
```

## Namespace

```
Gjoni\SpotifyWebApiSdk 
```

## Usage

### First steps

Firstly, create a main Sdk object with the client credentials(client id, client secret), scopes 
and the redirect uri. For the credentials, you have to create an app on the
<a href="https://developer.spotify.com/dashboard/" target="_blank">developer dashboard</a>.

```php

require '/path/to/vendor/autoload.php';

use Gjoni\SpotifyWebApiSdk;

# The scopes your app would need.
$scopes = [
    "user-read-private",
    "user-read-email",
    "playlist-read-private",
    "playlist-modify-public",
    "playlist-modify-private",
    "ugc-image-upload",
];
$redirectUri = "https://my.frontend.io/redirect";

$sdk = new SpotifyWebApiSdk\Sdk("clientId", "clientSecret", $scopes, $redirectUri);

```

### Authorization

After having created the main sdk object, these are the different ways to authorize your App to act on behalf of a user.

#### Authorization Code Flow

```php

require '/path/to/vendor/autoload.php';

use Gjoni\SpotifyWebApiSdk\Authorization;

$authorization = new Authorization\AuthorizationCode($sdk);

# Build the URL and have your frontend make a GET request to it.
$url = $authorization->buildUrl();

# After this, you should have an authorization code; use it in another request
# to seek access to user resources.
$accessToken = $authorization->requestAccessToken("auth_code");

# When the access token expires, just refresh it
$newAccessToken = $authorization->refreshAccessToken("refresh_token");

# That's it!

```

#### Authorization Code Flow with Proof Key for Code Exchange(PKCE)

1. Generate code verifier & challenge, persist the verifier and build the url.

```php

require '/path/to/vendor/autoload.php';

use Gjoni\SpotifyWebApiSdk\Authorization;

$authorization = new Authorization\AuthorizationCodePKCE($sdk);

# Generate a code verifier and a code challenge based on it
$verifier = $authorization->generateCodeVerifier();
$challenge = $authorization->generateCodeChallenge($verifier);

# Set the code challenge, it will be needed in the next step
$authorization->setCodeChallenge($challenge);

# Have some way to persist the code verifier, it will be needed in the second request.
$cookie = new Cookie($this->cookieConfig);

if (empty($cookie->code_verifier)) {
    $cookie->code_verifier = $verifier;
}

# Build the URL and have your frontend make a GET request to it.
$url = $authorization->buildUrl();

```

2. After getting an authorization code on behalf of the end-user, seek access.

```php

use Gjoni\SpotifyWebApiSdk\Authorization;

$cookie = new Cookie($this->cookieConfig);
$authorization = new Authorization\AuthorizationCodePKCE($this->sdk);

# Get the code verifier from your persistence method, in this case, a cookie
if (!empty($_COOKIE["code_verifier"])) {
    $authorization->setCodeVerifier($_COOKIE["code_verifier"]);
}

# Get your authorization code
$authCode = "my-auth-code";

# Request access
$accessToken = $authorization->requestAccessToken($authCode);

# When needed, refresh access
$newAccessToken = $authorization->refreshAccessToken("refresh-token");

# That's it!

```

#### Client Credentials Flow

```php

require '/path/to/vendor/autoload.php';

use Gjoni\SpotifyWebApiSdk\Authorization;

$authorization = new Authorization\ClientCredentials($sdk);

# Directly seek access
$accessToken = $authorization->requestAccessToken();

# That's it!

```

#### Persisting tokens

This SDK does not offer tokens persistence(access & refresh token), so that responsibility
falls on the consuming project. In the examples in this README, cookie storage is used; but it's not the only possibility, read
<a href="https://stackoverflow.com/questions/44324080/how-to-store-access-token-oauth-2-auth-code-flow" target="_blank">this</a>
& <a href="https://security.stackexchange.com/questions/113296/where-should-i-store-oauth2-access-tokens" target="_blank">this<a/>(external links) for more information.

### Web API Endpoints

Now that you have successfully authorized your App and persisted your tokens, now's the time to have some fun and request some user data!

#### Current user's profile

```php

require '/path/to/vendor/autoload.php';

use Gjoni\SpotifyWebApiSdk\UsersProfile;

$usersProfile = new UsersProfile($sdk);

$usersProfile->me();

```

#### A public user's profile

```php

require '/path/to/vendor/autoload.php';

use Gjoni\SpotifyWebApiSdk\UsersProfile;

$usersProfile = new UsersProfile($sdk);

# For a user with the id of 'wizzler'
$usersProfile->getUserProfile("wizzler");

```

The latter outputs the following JSON response:

```json

{
  "data": {
    "display_name": "Ronald Pompa",
    "external_urls": {
      "spotify": "https://open.spotify.com/user/wizzler"
    },
    "followers": {
      "href": null,
      "total": 4032
    },
    "href": "https://api.spotify.com/v1/users/wizzler",
    "id": "wizzler",
    "images": [
      {
        "height": null,
        "url": "https://i.scdn.co/image/ab6775700000ee85b5d374d281b9e510eda15fdf",
        "width": null
      }
    ],
    "type": "user",
    "uri": "spotify:user:wizzler"
  }
}

```

#### Get a single artist

```php

require '/path/to/vendor/autoload.php';

use Gjoni\SpotifyWebApiSdk\Artists;

$artists = new Artists($sdk);

# For an artist with the id of '0OdUWJ0sBjDrqHygGUXeCF'(Band of Horses)
$artists->getSingle("0OdUWJ0sBjDrqHygGUXeCF");

```

This outputs the following JSON response:

```json

{
  "data": {
    "external_urls": {
      "spotify": "https://open.spotify.com/artist/0OdUWJ0sBjDrqHygGUXeCF"
    },
    "followers": {
      "href": null,
      "total": 873614
    },
    "genres": [
      "indie folk",
      "indie pop",
      "indie rock",
      "modern rock",
      "stomp and holler"
    ],
    "href": "https://api.spotify.com/v1/artists/0OdUWJ0sBjDrqHygGUXeCF",
    "id": "0OdUWJ0sBjDrqHygGUXeCF",
    "images": [
      {
        "height": 640,
        "url": "https://i.scdn.co/image/0f9a5013134de288af7d49a962417f4200539b47",
        "width": 640
      },
      {
        "height": 320,
        "url": "https://i.scdn.co/image/8ae35be1043f330173de198c35a49161337e829c",
        "width": 320
      },
      {
        "height": 160,
        "url": "https://i.scdn.co/image/602dd7b3a2ee3f3fd86c6c4f50ab9b5a82e23c59",
        "width": 160
      }
    ],
    "name": "Band of Horses",
    "popularity": 65,
    "type": "artist",
    "uri": "spotify:artist:0OdUWJ0sBjDrqHygGUXeCF"
  }
}

```

## Errors & Exceptions

If you make an API request when the access token has expired, the following exception will be thrown:

    Gjoni\SpotifyWebApiSdk\Exception\AccessTokenExpiredException

with the message:

    The access token has expired, please refresh the token.
and the error code ```401```.

Other, usage errors, such as not providing the right parameter or the wrong amount of them, will cause
the ``` GuzzleHttp\Exception\RequestException ``` to bubble up to the top, necessitating to be handled
by the project.

## Contributing

Pull requests and issues are welcome, please refer to [CONTRIBUTORS.md](docs/CONTRIBUTORS.md)

## License

[GPLv3](LICENSE.md)
