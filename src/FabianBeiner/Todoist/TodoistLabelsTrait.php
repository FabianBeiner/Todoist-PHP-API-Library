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
        if ('' === $name) {
            return false;
        }

        $result = $this->client->post('labels', [
            RequestOptions::JSON => ['name' => trim($name)],
        ]);

        if ($result->getStatusCode() === 200) {
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
        if ($labelId <= 0 || !filter_var($labelId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->get('labels/' . $labelId);

        $status = $result->getStatusCode();
        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Alias for updateLabel().
     *
     * @param int $labelId ID of the label.
     * @param string $name New name of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function renameLabel($labelId, $name): bool
    {
        return $this->updateLabel($labelId, $name);
    }

    /**
     * Update (actually rename…) a label.
     *
     * @param int $labelId ID of the label.
     * @param string $name New name of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function updateLabel($labelId, $name): bool
    {
        if ($labelId <= 0 || '' === $name || !filter_var($labelId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->post('labels/' . $labelId, [
            RequestOptions::JSON => ['name' => trim($name)],
        ]);

        return ($result->getStatusCode() === 204);
    }

    /**
     * Delete a label.
     *
     * @param int $labelId ID of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteLabel($labelId): bool
    {
        if ($labelId <= 0 || !filter_var($labelId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->delete('labels/' . $labelId . '?' . $this->tokenQuery);
        $status = $result->getStatusCode();

        return ($status === 200 || $status === 204);
    }
}
