<?php
/**
 * PHP Client for Todoist
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
     * Get all labels.
     *
     * @return array|bool Array with all labels (can be empty), or false on failure.
     */
    public function getAllLabels()
    {
        /** @var object $result Result of the GET request. */
        $result = $this->get('labels');

        $status = $result->getStatusCode();
        if (204 === $status) {
            return [];
        }
        if (200 === $status) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Create a new label.
     *
     * @param string $name Name of the label.
     *
     * @return object|bool Object with values of the new label, or false on failure.
     */
    public function createLabel(string $name)
    {
        if ('' === $name) {
            return false;
        }

        $data = $this->prepareRequestData(['name' => $name]);
        /** @var object $result Result of the POST request. */
        $result = $this->post('labels', $data);

        if (200 === $result->getStatusCode()) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Prepare Guzzle request data.
     *
     * @param array $data
     *
     * @return array
     */
    abstract protected function prepareRequestData(array $data = []): array;

    /**
     * Get a label.
     *
     * @param int $labelId ID of the label.
     *
     * @return object|bool Object with values of the label, or false on failure.
     */
    public function getLabel(int $labelId)
    {
        if ( ! $this->validateId($labelId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('labels/' . $labelId);

        if (200 === $result->getStatusCode()) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Validates an ID to be a positive integer.
     *
     * @param mixed $id
     *
     * @return bool
     */
    abstract protected function validateId($id): bool;

    /**
     * Alias for updateLabel().
     *
     * @param int    $labelId ID of the label.
     * @param string $name    New name of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function renameLabel(int $labelId, string $name): bool
    {
        return $this->updateLabel($labelId, $name);
    }

    /**
     * Update (rename) a label.
     *
     * @param int    $labelId ID of the label.
     * @param string $name    New name of the label.
     *
     * @return bool True on success, false on failure.
     */
    public function updateLabel(int $labelId, string $name): bool
    {
        if ('' === $name || ! $this->validateId($labelId)) {
            return false;
        }

        $data = $this->prepareRequestData(['name' => $name]);
        /** @var object $result Result of the POST request. */
        $result = $this->post('labels/' . $labelId, $data);

        return 204 === $result->getStatusCode();
    }

    /**
     * Delete a label.
     *
     * @param int $labelId ID of the label.
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
