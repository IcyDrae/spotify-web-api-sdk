<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Search API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-search
 */
class Search extends Client
{
    /**
     * Search constructor.
     *
     * Initializes the client object, response, headers and client parameters.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        parent::__construct($sdk);
    }

    /**
     * Get Spotify Catalog information about albums, artists, playlists, tracks, shows or episodes that match a keyword string.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details.
     *
     * Query parameters:
     * - required
     *      - q(string): Search query keywords and optional field filters and operators. For example: q=roadhouse%20blues.
     *      - type(string): A comma-separated list of item types to search across. Valid types are: album , artist, playlist, track, show and episode. Search results include hits from all the specified item types. For example: q=name:abacab&type=album,track returns both albums and tracks with “abacab” included in their name.
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. If a country code is specified, only content that is playable in that market is returned. Note: - Playlist results are not affected by the market parameter. - If market is set to from_token, and a valid access token is specified in the request header, only content playable in the country associated with the user account, is returned. - Users can view the country that is associated with their account in the account settings. A user must grant access to the user-read-private scope prior to when the access token is issued.
     *      - limit(integer): Maximum number of results to return. Default: 20 Minimum: 1 Maximum: 50 Note: The limit is applied within each type, not on the total response. For example, if the limit value is 3 and the type is artist,album, the response contains 3 artists and 3 albums.
     *      - offset(integer): The index of the first result to return. Default: 0 (the first result). Maximum offset (including limit): 1,000. Use with limit to get the next page of search results.
     *      - include_external(string): Possible values: audio If include_external=audio is specified the response will include any relevant audio content that is hosted externally. By default external content is filtered out from responses.
     *
     * Response:
     *
     * On success:
     * In the response header the HTTP status code is 200 OK.
     * For each type provided in the type parameter, the response body contains an array of artist objects / simplified album objects / track objects / simplified show objects / simplified episode objects wrapped in a paging object in JSON.
     *
     * On error:
     * The header status code is an error code.
     * The response body contains an error object.
     *
     * Notes
     *
     * Writing a Query - Guidelines
     *
     * Encode spaces with the hex code %20 or +.
     *
     * Keyword matching:
     * Matching of search keywords is not case-sensitive. Operators, however, should be specified in uppercase. Unless surrounded by double quotation marks, keywords are matched in any order. For example: q=roadhouse&20blues matches both “Blues Roadhouse” and “Roadhouse of the Blues”. q="roadhouse&20blues" matches “My Roadhouse Blues” but not “Roadhouse of the Blues”.
     * Searching for playlists returns results where the query keyword(s) match any part of the playlist’s name or description. Only popular public playlists are returned.
     *
     * Operator: The operator NOT can be used to exclude results.
     *
     * For example: q=roadhouse%20NOT%20blues returns items that match “roadhouse” but excludes those that also contain the keyword “blues”.
     * Note: The operator must be specified in uppercase. Otherwise, they are handled as normal keywords to be matched.
     *
     * Field filters: By default, results are returned when a match is found in any field of the target object type. Searches can be made more specific by specifying an album, artist or track field filter.
     * For example: The query q=album:gold%20artist:abba&type=album returns only albums with the text “gold” in the album name and the text “abba” in the artist name.
     * To limit the results to a particular year, use the field filter year with album, artist, and track searches.
     * For example: q=bob%20year:2014
     * Or with a date range. For example: q=bob%20year:1980-2020
     * To retrieve only albums released in the last two weeks, use the field filter tag:new in album searches.
     * To retrieve only albums with the lowest 10% popularity, use the field filter tag:hipster in album searches. Note: This field filter only works with album searches.
     * Depending on object types being searched for, other field filters, include genre (applicable to tracks and artists), upc, and isrc. For example: q=lil%20genre:%22southern%20hip%20hop%22&type=artist. Use double quotation marks around the genre keyword string if it contains spaces.
     *
     * Notes
     * Currently, you cannot fetch sorted results.
     * You cannot search for playlists that contain a certain track.
     * You can search only one genre at a time.
     * You cannot search for playlists within a user’s library.
     * In an effort to keep the response small, but include as much information as possible, Spotify has expanded the response and intends to continue and improve the Search endpoint.
     * To query based on a release date query at a year level using the year scope. For example:
     * The query
     * https://api.spotify.com/v1/search?q=bob%20year:2014&type=album
     * Returns albums released in 2014 with their names or artist names containing “bob”. You can also use the tag:new field filter to get just these albums, as well as compilations and singles, released in the last 2 weeks.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function get(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::SEARCH, $options);
    }

}
