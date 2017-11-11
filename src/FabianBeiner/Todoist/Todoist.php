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
 * @version 0.3.1 (2017-11-11)
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client;

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

    /**
     * Generate a GUID v4 string.
     *
     * @author Pavel Volyntsev <pavel.volyntsev@gmail.com>
     * @see    http://php.net/manual/en/function.com-create-guid.php#117893
     * @return string GUID v4 string.
     */
    private function guidv4()
    {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        $data    = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
