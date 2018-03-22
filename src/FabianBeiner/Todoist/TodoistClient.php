<?php
/**
 * Todoist PHP API Library
 * An unofficial PHP client library for accessing the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @author  Balazs Csaba <balazscsaba2006@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @version 0.7.0 <2018-03-22>
 *
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;

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
     * @var string The current URL of the Todoist REST API.
     */
    protected $restApiUrl = 'https://beta.todoist.com/API/v8/';

    /**
     * Todoist constructor.
     *
     * @param string $apiToken The API token to access the Todoist API.
     * @param array  $config   Configuration to be passed to Guzzle client
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function __construct(string $apiToken, array $config = [])
    {
        $apiToken = trim($apiToken);
        if (40 !== \strlen($apiToken)) {
            throw new TodoistException('The provided API token is invalid!');
        }

        $defaults = [
            'headers'     => ['Accept-Encoding' => 'gzip'],
            'http_errors' => false,
            'timeout'     => 5,
        ];
        $config   = $this->mergeConfigurations($defaults, $config);

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
     */
    public function requestAsync($method, $uri = '', array $options = []): PromiseInterface
    {
        // ensure X-Request-Id header is regenerated for every call
        $options['headers']['X-Request-Id'] = $this->generateV4GUID();

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

        return (bool)filter_var($id, FILTER_VALIDATE_INT, $filterOptions);
    }

    /**
     * Merge configurations.
     *
     * @param array $first
     * @param array $second
     *
     * @return array
     */
    private function mergeConfigurations(array $first, array $second): array
    {
        $merged = $first;

        foreach ($second as $key => $value) {
            if ( ! array_key_exists($key, $first) && ! is_numeric($key)) {
                $merged[$key] = $second[$key];
                continue;
            }

            $merged[$key] = $value;
            if (\is_array($value) || \is_array($first[$key])) {
                $merged[$key] = $this->mergeConfigurations($first[$key], $value);
            }
        }

        return $merged;
    }

    /**
     * Generate a v4 GUID string.
     *
     * @author Pavel Volyntsev <pavel.volyntsev@gmail.com>
     *
     * @see    http://php.net/manual/en/function.com-create-guid.php#117893
     *
     * @return string A v4 GUID.
     */
    private function generateV4GUID(): string
    {
        if (true === \function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        }

        $data    = openssl_random_pseudo_bytes(16);
        $data[6] = \chr(\ord($data[6]) & 0x0f | 0x40);
        $data[8] = \chr(\ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
