<?php
/**
 * PHP Client for Todoist
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @version 0.8.0 <2019-07-19>
 *
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
use function strlen;

/**
 * Class TodoistClient.
 */
class TodoistClient extends GuzzleClient
{
    /*
     * Use Traits.
     */
    use TodoistCommentsTrait, TodoistLabelsTrait, TodoistProjectsTrait, TodoistTasksTrait;

    /**
     * @var string The URL of the Todoist REST API.
     */
    protected $restApiUrl = 'https://api.todoist.com/rest/v1/';

    /**
     * Todoist constructor.
     *
     * @param string $apiToken The API token to access the Todoist API.
     * @param array  $config   Configuration to be passed to Guzzle client.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function __construct(string $apiToken, array $config = [])
    {
        $apiToken = trim($apiToken);
        if (40 !== strlen($apiToken)) {
            throw new TodoistException('The provided API token is invalid.');
        }

        $defaults = [
            'headers'     => [
                'Accept-Encoding' => 'gzip'
            ],
            'http_errors' => false,
            'timeout'     => 5
        ];

        $config                             = array_replace_recursive($defaults, $config);
        $config['base_uri']                 = $this->restApiUrl;
        $config['headers']['Authorization'] = sprintf('Bearer %s', $apiToken);

        parent::__construct($config);
    }

    /**
     * Wrapper on Guzzle's requestAsync method.
     *
     * @param string $method
     * @param string $uri
     * @param array  $options
     *
     * @return PromiseInterface
     * @throws \Exception
     */
    public function requestAsync($method, $uri = '', array $options = []): PromiseInterface
    {
        // Ensure the “X-Request-Id” header gets regenerated for every call.
        $options['headers']['X-Request-Id'] = bin2hex(random_bytes(16));

        return parent::requestAsync($method, $uri, $options);
    }

    /**
     * Prepare Guzzle request data.
     *
     * @param array $data
     *
     * @return array
     */
    protected function prepareRequestData(array $data = []): array
    {
        array_walk_recursive($data, 'trim');

        return [RequestOptions::JSON => $data];
    }

    /**
     * Validates an ID to be a positive integer.
     *
     * @param mixed $id
     *
     * @return bool
     */
    protected function validateId($id): bool
    {
        $filterOptions = ['options' => ['min_range' => 0]];

        return (bool) filter_var($id, FILTER_VALIDATE_INT, $filterOptions);
    }
}
