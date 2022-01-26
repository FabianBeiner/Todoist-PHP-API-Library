<?php
/**
 * Todoist-PHP-API-Library
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\RequestOptions;

/**
 * Trait TodoistHelpers.
 *
 * @package FabianBeiner\Todoist
 */
trait TodoistHelpers
{
    /**
     * Handles the response of a call.
     *
     * @param int    $statusCode The HTTP status code.
     * @param string $content    The content of the call.
     *
     * @throws TodoistException Exception.
     * @return array|bool       An array with or without data, false on failure.
     */
    final protected function handleResponse(int $statusCode, string $content)
    {
        switch ($statusCode) {
            case 200:
                return json_decode($content, true);
            case 204:
                return [];
            case 401:
            case 403:
                throw new TodoistException('Unable to access the API. Is the API token valid?');
            case 402:
                throw new TodoistException('A non-premium user used a premium-only feature.');
            default:
                return false;
        }
    }

    /**
     * Prepare POST data for Guzzle.
     *
     * @param array $data The POST data as an array.
     *
     * @throws \Exception Exception.
     * @return array      Trimmed array with JSON data.
     */
    final protected function preparePostData(array $data = []): array
    {
        array_walk_recursive($data, 'trim');

        return array_merge(
            ['headers' => [
                'X-Request-Id' => bin2hex(random_bytes(16)),
            ]],
            [RequestOptions::JSON => $data]
        );
    }

    /**
     * Validates an ID to be a positive integer.
     *
     * @param int $validateId The ID to be validated.
     *
     * @return bool True on success, false on failure.
     */
    final protected function validateId(int $validateId): bool
    {
        return (bool)filter_var($validateId, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);
    }
}
