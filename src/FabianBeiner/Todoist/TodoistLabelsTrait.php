<?php
/**
 * Todoist-PHP-API-Library
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

/**
 * Trait TodoistLabelsTrait.
 */
trait TodoistLabelsTrait
{
    /**
     * Returns an array containing all user labels.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * Creates a new label and returns its object as array.
     *
     * @param string $labelName          The name of the label.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v1/#create-a-new-label.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool An array containing the values of the new label, or false on failure.
     */
    public function createLabel(string $labelName, array $optionalParameters = [])
    {
        $labelName = filter_var($labelName, FILTER_SANITIZE_STRING);
        if ( ! strlen($labelName)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters    = [
            'color',
            'favorite',
            'order',
        ];
        $filteredParameters = array_intersect_key($optionalParameters, array_flip($validParameters));

        $postData = $this->preparePostData(array_merge(['name' => $labelName], $filteredParameters));
        /** @var object $result Result of the POST request. */
        $result = $this->post('labels', $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Returns a label by ID.
     *
     * @param int $labelId The ID of the label.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * Updates a label.
     *
     * @param int    $labelId            The ID of the label.
     * @param string $newLabelName       The new name of the label.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v1/#update-a-label.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return bool True on success, false on failure.
     */
    public function updateLabel(int $labelId, string $newLabelName, array $optionalParameters = []): bool
    {
        $newLabelName = filter_var($newLabelName, FILTER_SANITIZE_STRING);
        if ( ! strlen($newLabelName) || ! $this->validateId($labelId)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters    = [
            'color',
            'favorite',
            'order',
        ];
        $filteredParameters = array_intersect_key($optionalParameters, array_flip($validParameters));

        $postData = $this->preparePostData(array_merge(['name' => $newLabelName], $filteredParameters));
        /** @var object $result Result of the POST request. */
        $result = $this->post('labels/' . $labelId, $postData);

        return 204 === $result->getStatusCode();
    }

    /**
     * Delete a label.
     *
     * @param int $labelId The ID of the label.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
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
