<?php
/**
 * Todoist PHP API Library
 * An unofficial PHP client library for accessing the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license MIT
 * @link    https://github.com/FabianBeiner/Todoist-PHP-API-Library
 * @version 0.4.0 <2017-11-14>
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client;

/**
 * Class Todoist.
 *
 * @package FabianBeiner\Todoist
 */
class Todoist
{
    /**
     * Use Traits.
     */
    use TodoistCommentsTrait, TodoistLabelsTrait, TodoistProjectsTrait;

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
        // Check and set the API token.
        if (mb_strlen($apiToken, 'utf8') !== 40) {
            throw new \Exception('❌ The provided API token is invalid!');
        }
        $this->apiToken = trim($apiToken);

        // Create a default query for the token.
        $this->tokenQuery = http_build_query([
                                                 'token' => $this->apiToken
                                             ],
                                             null,
                                             '&',
                                             PHP_QUERY_RFC3986);

        // Create a Guzzle client.
        $this->client = new Client([
                                       'base_uri'    => $this->restApiUrl,
                                       'headers'     => ['X-Request-Id' => $this->generateV4GUID()],
                                       'http_errors' => false,
                                       'timeout'     => 5
                                   ]);
    }

    /**
     * Generate a v4 GUID string.
     *
     * @author Pavel Volyntsev <pavel.volyntsev@gmail.com>
     * @see    http://php.net/manual/en/function.com-create-guid.php#117893
     *
     * @return string A v4 GUID.
     */
    private function generateV4GUID()
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
