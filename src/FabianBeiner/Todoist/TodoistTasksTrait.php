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
 * Trait TodoistTasksTrait.
 */
trait TodoistTasksTrait
{
    /**
     * Get all tasks.
     *
     * @return array|bool Array with all tasks (can be empty), or false on failure.
     */
    public function getAllTasks()
    {
        /** @var object $result Result of the GET request. */
        $result = $this->get('tasks');

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
     * Create a new task.
     *
     * @param string $content Content of the task.
     * @param array  $options Possibility to add non-required parameters.
     *
     * @return object|bool Object with values of the new task, or false on failure.
     */
    public function createTask(string $content, array $options = [])
    {
        if ('' === $content) {
            return false;
        }

        unset($options['content']);
        $data = $this->prepareRequestData(array_merge(['content' => $content], $options));
        /** @var object $result Result of the POST request. */
        $result = $this->post('tasks', $data);

        $status = $result->getStatusCode();
        if (200 === $status) {
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
     * Get a task.
     *
     * @param int $taskId ID of the task.
     *
     * @return array|bool Array with values of the task, or false on failure.
     */
    public function getTask(int $taskId)
    {
        if ( ! $this->validateId($taskId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('tasks/' . $taskId);

        $status = $result->getStatusCode();
        if (200 === $status) {
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
     * Update a task.
     *
     * @param int    $taskId  ID of the task.
     * @param string $content Content of the task.
     * @param array  $options Possibility to add non-required parameters.
     *
     * @return bool
     */
    public function updateTask(int $taskId, string $content, array $options = []): bool
    {
        if ('' === $content) {
            return false;
        }

        unset($options['content']);
        $data = $this->prepareRequestData(array_merge(['content' => $content], $options));
        /** @var object $result Result of the POST request. */
        $result = $this->post('tasks/' . $taskId, $data);

        return 204 === $result->getStatusCode();
    }

    /**
     * Close a task.
     *
     * @param int $taskId ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function closeTask(int $taskId): bool
    {
        if ( ! $this->validateId($taskId)) {
            return false;
        }

        /** @var object $result Result of the POST request. */
        $result = $this->post('tasks/' . $taskId . '/close');

        return 204 === $result->getStatusCode();
    }

    /**
     * Reopen a task.
     *
     * @param int $taskId ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function reopenTask(int $taskId): bool
    {
        if ( ! $this->validateId($taskId)) {
            return false;
        }

        /** @var object $result Result of the POST request. */
        $result = $this->post('tasks/' . $taskId . '/reopen');

        return 204 === $result->getStatusCode();
    }

    /**
     * Delete a task.
     *
     * @param int $taskId ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteTask(int $taskId): bool
    {
        if ( ! $this->validateId($taskId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('tasks/' . $taskId);

        return 204 === $result->getStatusCode();
    }
}
