<?php
/**
 * @author  Fabian Beiner (fb@fabianbeiner.de)
 * @link    https://fabianbeiner.de
 * @license MIT License
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\RequestOptions;

trait TodoistLabels
{
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
     * Get all labels.
     *
     * @return array|bool Array with all labels (can be empty), or false on failure.
     */
    public function getAllLabels()
    {
        $result = $this->client->get('labels?' . $this->tokenQuery);
        $status = $result->getStatusCode();

        if ($status === 204) {
            return [];
        }
        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Create a new label.
     *
     * @param string $name Name of the label.
     *
     * @return array|bool Array with values of the new label, or false on failure.
     */
    public function createLabel($name)
    {
        if ( ! mb_strlen($name, 'utf8')) {
            return false;
        }

        $result = $this->client->post('labels?' . $this->tokenQuery, [
            RequestOptions::JSON => ['name' => trim($name)],
            'X-Request-Id'       => $this->guidv4()
        ]);
        $status = $result->getStatusCode();

        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Get a label.
     *
     * @param int $labelId ID of the label.
     *
     * @return array|bool Array with values of the label, or false on failure.
     */
    public function getLabel($labelId)
    {
        if ( ! ctype_digit($labelId)) {
            return false;
        }

        $result = $this->client->get('labels/' . $labelId . '?' . $this->tokenQuery);
        $status = $result->getStatusCode();

        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Alias for updateLabel.
     *
     * @param int    $labelId ID of the label.
     * @param string $name    New name of the label.
     */
    public function renameLabel($labelId, $name)
    {
        $this->updateLabel($labelId, $name);
    }

    /**
     * Update (actually rename…) a label.
     *
     * @param int    $labelId ID of the label.
     * @param string $name    New name of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function updateLabel($labelId, $name)
    {
        if ( ! ctype_digit($labelId) || ! mb_strlen($name, 'utf8')) {
            return false;
        }

        $result = $this->client->post('labels/' . $labelId . '?' . $this->tokenQuery, [
            RequestOptions::JSON => ['name' => trim($name)],
            'X-Request-Id'       => $this->guidv4()
        ]);
        $status = $result->getStatusCode();

        if ($status === 200 || $status === 204) {
            return true;
        }

        return false;
    }

    /**
     * Delete a label.
     *
     * @param int $labelId ID of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteLabel($labelId)
    {
        if ( ! ctype_digit($labelId)) {
            return false;
        }

        $result = $this->client->delete('labels/' . $labelId . '?' . $this->tokenQuery);
        $status = $result->getStatusCode();

        if ($status === 200 || $status === 204) {
            return true;
        }

        return false;
    }
}
