<?php
/**
 * Todoist SDK for PHP — An unofficial Todoist API library
 *
 * An open source PHP SDK which allows you to access the Todoist API from your
 * PHP application.
 *
 * @author  Fabian Beiner (fb@fabianbeiner.de)
 * @link    https://fabianbeiner.de
 * @license MIT License
 * @version 0.3.0 (2017-11-11)
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\Uuid;

class Todoist
{
    // Traits.
    use TodoistProjects;
    use TodoistLabels;

    /**
     * @var string The current URL of the Todoist REST API.
     */
    protected $restApiUrl = 'https://beta.todoist.com/API/v8/';

    /**
     * @var string|null The API token to access the Todoist API, or null if unset.
     */
    private $apiToken = null;

    /**
     * @var \GuzzleHttp\Client|null Guzzle client, or null if unset.
     */
    private $client = null;

    /**
     * @var string|null Default URL query.
     */
    private $tokenQuery = null;

    /**
     * Todoist constructor.
     *
     * @param string $apiToken The API token to access the Todoist API.
     *
     * @throws \Exception
     */
    public function __construct($apiToken)
    {
        // Check the API token.
        if ( ! mb_strlen($apiToken, 'utf8')) {
            throw new \Exception('The provided API token is invalid.');
        }
        $this->apiToken = trim($apiToken);

        // Create a default query for the token.
        $this->tokenQuery = http_build_query([
                                                 'token' => $this->apiToken
                                             ], null, '&', PHP_QUERY_RFC3986);

        // Create the Guzzle client.
        $this->client = new Client([
                                       'base_uri' => $this->restApiUrl,
                                       'timeout'  => 5
                                   ]);
    }
}
