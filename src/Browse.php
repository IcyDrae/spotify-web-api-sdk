<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Browse API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-browse
 */
class Browse
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Browse constructor.
     *
     * Initializes the client object, response, headers and client parameters.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->client = new Client($sdk, [
            "base_uri" => SdkConstants::API_URL,
            "timeout" => 1,
            "allow_redirects" => ["track_redirects" => true]
        ]);
    }

    /**
     * Get a list of new album releases featured in Spotify (shown, for example, on a Spotify player’s “Browse” tab).
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter if you want the list of returned items to be relevant to a particular country. If omitted, the returned items will be relevant to all countries.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object). Use with limit to get the next set of items.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a message and an albums object. The albums object contains an array of simplified album objects (wrapped in a paging object) in JSON format. On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the list, you can use Get an Album’s Tracks to drill down further.
     * The results are returned in an order reflected within the Spotify clients, and therefore may not be ordered by date.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getNewReleases(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/new-releases", $options);
    }

    /**
     * Get a list of Spotify featured playlists (shown, for example, on a Spotify player’s ‘Browse’ tab).
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter if you want the list of returned items to be relevant to a particular country. If omitted, the returned items will be relevant to all countries.
     *      - locale(string): The desired language, consisting of a lowercase ISO 639-1 language code and an uppercase ISO 3166-1 alpha-2 country code, joined by an underscore. For example: es_MX, meaning “Spanish (Mexico)”. Provide this parameter if you want the results returned in a particular language (where available). Note that, if locale is not supplied, or if the specified language is not available, all strings will be returned in the Spotify default language (American English). The locale parameter, combined with the country parameter, may give odd results if not carefully matched. For example country=SE&locale=de_DE will return a list of categories relevant to Sweden but as German language strings.
     *      - timestamp(string): A timestamp in ISO 8601 format: yyyy-MM-ddTHH:mm:ss. Use this parameter to specify the user’s local time to get results tailored for that specific date and time in the day. If not provided, the response defaults to the current UTC time. Example: “2014-10-23T09:00:00” for a user whose local time is 9AM. If there were no featured playlists (or there is no data) at the specified time, the response will revert to the current UTC time.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object). Use with limit to get the next set of items.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a message and a playlists object. The playlists object contains an array of simplified playlist objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the list of playlist objects, you can use Get a Playlist and Get a Playlist’s Tracks to drill down further.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getFeaturedPlaylists(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/featured-playlists", $options);
    }

    /**
     * Get a list of categories used to tag items in Spotify (on, for example, the Spotify player’s “Browse” tab).
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter if you want the list of returned items to be relevant to a particular country. If omitted, the returned items will be relevant to all countries.
     *      - locale(string): The desired language, consisting of a lowercase ISO 639-1 language code and an uppercase ISO 3166-1 alpha-2 country code, joined by an underscore. For example: es_MX, meaning “Spanish (Mexico)”. Provide this parameter if you want the results returned in a particular language (where available). Note that, if locale is not supplied, or if the specified language is not available, all strings will be returned in the Spotify default language (American English). The locale parameter, combined with the country parameter, may give odd results if not carefully matched. For example country=SE&locale=de_DE will return a list of categories relevant to Sweden but as German language strings.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object). Use with limit to get the next set of items.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object with a categories field, with an array of category objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the list, you can use Get a Category to drill down further.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getCategories(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/categories", $options);
    }

    /**
     * Get a single category used to tag items in Spotify (on, for example, the Spotify player’s “Browse” tab).
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     *  - required
     *      - {category_id}(string): The Spotify category ID for the category.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter if you want the list of returned items to be relevant to a particular country. If omitted, the returned items will be relevant to all countries.
     *      - locale(string): The desired language, consisting of a lowercase ISO 639-1 language code and an uppercase ISO 3166-1 alpha-2 country code, joined by an underscore. For example: es_MX, meaning “Spanish (Mexico)”. Provide this parameter if you want the results returned in a particular language (where available). Note that, if locale is not supplied, or if the specified language is not available, all strings will be returned in the Spotify default language (American English). The locale parameter, combined with the country parameter, may give odd results if not carefully matched. For example country=SE&locale=de_DE will return a list of categories relevant to Sweden but as German language strings.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a category object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the category, you can use Get a Category’s Playlists to drill down further.
     *
     * @param string $id The category id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getCategory(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/categories/$id", $options);
    }

    /**
     * Get a list of Spotify playlists tagged with a particular category.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     *  - required
     *      - {category_id}(string): The Spotify category ID for the category.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter to ensure that the category exists for a particular country.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object). Use with limit to get the next set of items.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of simplified playlist objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the list, you can use Get a Playlist and Get a Playlist’s Tracks to drill down further.
     *
     * @param string $id The category id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getCategoryPlaylists(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/categories/$id/playlists", $options);
    }

    /**
     * Recommendations are generated based on the available information for a given seed entity and matched against similar artists and tracks.
     * If there is sufficient information about the provided seeds, a list of tracks will be returned together with pool size details.
     * For artists and tracks that are very new or obscure there might not be enough data to generate a list of tracks.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - required
     *      - seed_artists(string): A comma separated list of Spotify IDs for seed artists. Up to 5 seed values may be provided in any combination of seed_artists, seed_tracks and seed_genres.
     *      - seed_genres(string): A comma separated list of any genres in the set of available genre seeds. Up to 5 seed values may be provided in any combination of seed_artists, seed_tracks and seed_genres.
     *      - seed_tracks(string): A comma separated list of Spotify IDs for a seed track. Up to 5 seed values may be provided in any combination of seed_artists, seed_tracks and seed_genres.
     *
     * - optional
     *      - limit(integer): The target size of the list of recommended tracks. For seeds with unusually small pools or when highly restrictive filtering is applied, it may be impossible to generate the requested number of recommended tracks. Debugging information for such cases is available in the response. Default: 20. Minimum: 1. Maximum: 100.
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking. Because min_*, max_* and target_* are applied to pools before relinking, the generated results may not precisely match the filters applied. Original, non-relinked tracks are available via the linked_from attribute of the relinked track response.
     *      - min_acousticness(number): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_acousticness(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_acousticness(number): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_danceability(number): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_danceability(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_danceability(number): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_duration_ms(integer): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_duration_ms(integer): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_duration_ms(integer): Target duration of the track (ms)
     *      - min_energy(number): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_energy(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_energy(number): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_instrumentalness(number): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_instrumentalness(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_instrumentalness(number): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_key(integer): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_key(integer): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_key(integer): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_liveness(number): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_liveness(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_liveness(number): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_loudness(number): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_loudness(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_loudness(number): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_mode(integer): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_mode(integer): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_mode(integer): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_popularity(integer): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_popularity(integer): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_popularity(integer): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_speechiness(number): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_speechiness(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_speechiness(number): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_tempo(number): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_tempo(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_tempo(number): Target tempo (BPM)
     *      - min_time_signature(integer): For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_time_signature(integer): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_time_signature(integer): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *      - min_valence(number):For each tunable track attribute, a hard floor on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, min_tempo=140 would restrict results to only those tracks with a tempo of greater than 140 beats per minute.
     *      - max_valence(number): For each tunable track attribute, a hard ceiling on the selected track attribute’s value can be provided. See tunable track attributes below for the list of available options. For example, max_instrumentalness=0.35 would filter out most tracks that are likely to be instrumental.
     *      - target_valence(number): For each of the tunable track attributes (below) a target value may be provided. Tracks with the attribute values nearest to the target values will be preferred. For example, you might request target_energy=0.6 and target_danceability=0.8. All target values will be weighed equally in ranking results.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a recommendations response object in JSON format.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getRecommendations(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::API_VERSION . "/recommendations", $options);
    }

    /**
     * Retrieve a list of available genres seed parameter values for recommendations.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a recommendations response object in JSON format.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getRecommendationGenres(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::API_VERSION . "/recommendations/available-genre-seeds", $options);
    }

}
