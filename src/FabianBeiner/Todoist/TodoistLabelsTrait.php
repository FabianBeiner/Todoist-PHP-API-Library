<?php
/**
 * Todoist PHP API Library
 * An unofficial PHP client library for accessing the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license MIT
 * @link    https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\RequestOptions;

/**
 * Trait TodoistLabelsTrait.
 *
 * @package FabianBeiner\Todoist
 */
trait TodoistLabelsTrait
{
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
            RequestOptions::JSON => ['name' => trim($name)]
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
        if ( ! filter_var($labelId, FILTER_VALIDATE_INT) || $labelId <= 0) {
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
     * Alias for updateLabel().
     *
     * @param int    $labelId ID of the label.
     * @param string $name    New name of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function renameLabel($labelId, $name)
    {
        return $this->updateLabel($labelId, $name);
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
        if ( ! filter_var($labelId, FILTER_VALIDATE_INT) || $labelId <= 0 || ! mb_strlen($name, 'utf8')) {
            return false;
        }

        $result = $this->client->post('labels/' . $labelId . '?' . $this->tokenQuery, [
            RequestOptions::JSON => ['name' => trim($name)]
        ]);

        $status = $result->getStatusCode();
        if ($status === 204) {
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
        if ( ! filter_var($labelId, FILTER_VALIDATE_INT) || $labelId <= 0) {
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
