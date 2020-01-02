<?php
/**
 * PHP Client for Todoist
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

/**
 * Trait TodoistLabelsTrait.
 *
 * @package FabianBeiner\Todoist
 */
trait TodoistLabelsTrait
{
    /**
     * Get all the labels.
     *
     * @return array|bool An array containing all user labels, or false on failure.
     */
    public function getAllLabels()
    {
        /** @var object $result Result of the GET request. */
        $result = $this->get('labels');

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Create a new label.
     *
     * @param string $labelName          The name of the label.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v1/#create-a-new-label.
     *
     * @return array|bool An array containing the values of the new label, or false on failure.
     */
    public function createLabel(string $labelName, array $optionalParameters = [])
    {
        $labelName = filter_var($labelName, FILTER_SANITIZE_STRING);
        if ( ! strlen($labelName)) {
            return false;
        }

        $postData = $this->preparePostData(array_merge(['name' => $labelName], $optionalParameters));
        /** @var object $result Result of the POST request. */
        $result = $this->post('labels', $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Get a label.
     *
     * @param int $labelId The ID of the label.
     *
     * @return array|bool An array containing the label data related to the given id, or false on failure.
     */
    public function getLabel(int $labelId)
    {
        if ( ! $this->validateId($labelId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('labels/' . $labelId);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Update a label.
     *
     * @param int    $labelId      The ID of the label.
     * @param string $newLabelName The new name of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function updateLabel(int $labelId, string $newLabelName): bool
    {
        $newLabelName = filter_var($newLabelName, FILTER_SANITIZE_STRING);
        if ( ! strlen($newLabelName) || ! $this->validateId($labelId)) {
            return false;
        }

        $postData = $this->preparePostData(['name' => $newLabelName]);
        /** @var object $result Result of the POST request. */
        $result = $this->post('labels/' . $labelId, $postData);

        return 204 === $result->getStatusCode();
    }

    /**
     * Delete a label.
     *
     * @param int $labelId The ID of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteLabel(int $labelId): bool
    {
        if ( ! $this->validateId($labelId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('labels/' . $labelId);

        return 204 === $result->getStatusCode();
    }
}
