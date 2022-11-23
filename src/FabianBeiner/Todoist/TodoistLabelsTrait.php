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
     *                                   https://developer.todoist.com/rest/v2#create-a-new-personal-label
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool An array containing the values of the new label, or false on failure.
     */
    public function createLabel(string $labelName, array $optionalParameters = [])
    {
        if ( ! strlen($labelName)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters = [
            'order',
            'color',
            'is_favorite',
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
     * @param string $labelId The ID of the label.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the label data related to the given id, or false on failure.
     */
    public function getLabel(string $labelId)
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
     * @param string $labelId            The ID of the label.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v2#update-a-personal-label
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool True on success, false on failure.
     */
    public function updateLabel(string $labelId, array $optionalParameters = [])
    {
        if ( ! $this->validateId($labelId)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters = [
            'name',
            'order',
            'color',
            'is_favorite',
        ];
        $filteredParameters = array_intersect_key($optionalParameters, array_flip($validParameters));

        $postData = $this->preparePostData($filteredParameters);

        /** @var object $result Result of the POST request. */
        $result = $this->post('labels/' . $labelId, $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Delete a label.
     *
     * @param string $labelId The ID of the label.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool True on success, false on failure.
     */
    public function deleteLabel(string $labelId): bool
    {
        if ( ! $this->validateId($labelId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('labels/' . $labelId);

        return 204 === $result->getStatusCode();
    }

    /**
     * Returns an array containing all shared labels.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all shared labels, or false on failure.
     */
    public function getAllSharedLabels()
    {
        /** @var object $result Result of the GET request. */
        $result = $this->get('labels/shared');

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Renames all instances of a shared label.
     *
     * @param string $sharedLabelName     The name of the existing label to rename.
     * @param string $newSharedLabelName  The new name for the label.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return bool True on success, false on failure.
     */
    public function renameSharedLabels(string $sharedLabelName, string $newSharedLabelName): bool
    {
        if ( ! strlen($sharedLabelName) || ! strlen($newSharedLabelName)) {
            return false;
        }

        $postData = $this->preparePostData([
            'name'     => $sharedLabelName,
            'new_name' => $newSharedLabelName
        ]);

        /** @var object $result Result of the POST request. */
        $result = $this->post('labels/shared/rename', $postData);

        return 204 === $result->getStatusCode();
    }

    /**
     * Removes all instances of a shared label.
     *
     * @param string $sharedLabelName The name of the label to remove.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return bool True on success, false on failure.
     */
    public function removeSharedLabels(string $sharedLabelName): bool
    {
        if ( ! strlen($sharedLabelName)) {
            return false;
        }

        $postData = $this->preparePostData(['name' => $sharedLabelName]);

        /** @var object $result Result of the POST request. */
        $result = $this->post('labels/shared/remove', $postData);

        return 204 === $result->getStatusCode();
    }
}
