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
 *
 * @package FabianBeiner\Todoist
 */
trait TodoistTasksTrait
{
    /**
     * Get active tasks.
     *
     * @param array $options Possibility to add non-required parameters, see
     *                       https://developer.todoist.com/rest/v1/#get-active-tasks.
     *
     * @return array|bool Returns an array containing all user active tasks, or false on failure.
     */
    public function getAllTasks(array $options = [])
    {
        $path = 'tasks';
        if (count($options)) {
            $query = http_build_query($options, null, '&', PHP_QUERY_RFC3986);
            $path  = $path . $query;
        }
        /** @var object $result Result of the GET request. */
        $result = $this->get($path);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Create a new task.
     *
     * @param string $content The content of the task.
     * @param array  $options Possibility to add non-required parameters, see
     *                        https://developer.todoist.com/rest/v1/#create-a-new-task.
     *
     * @return array|bool An array containing the values of the new task, or false on failure.
     */
    public function createTask(string $content, array $options = [])
    {
        $content = filter_var($content, FILTER_SANITIZE_STRING);
        if ( ! strlen($content)) {
            return false;
        }

        unset($options['content']);
        $data = $this->preparePostData(array_merge(['content' => $content], $options));
        /** @var object $result Result of the POST request. */
        $result = $this->post('tasks', $data);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Get a task.
     *
     * @param int $taskId The ID of the task.
     *
     * @return array|bool An array containing the task data related to the given id, or false on failure.
     */
    public function getTask(int $taskId)
    {
        if ( ! $this->validateId($taskId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('tasks/' . $taskId);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Update a task.
     *
     * @param int         $taskId  The ID of the task.
     * @param string|null $content The content of the task or null.
     * @param array       $options Possibility to add non-required parameters, see
     *                             https://developer.todoist.com/rest/v1/#update-a-task.
     *
     * @return bool True on success, false on failure.
     */
    public function updateTask(int $taskId, string $content = null, array $options = []): bool
    {
        $content = filter_var($content, FILTER_SANITIZE_STRING);
        if ( ! $this->validateId($taskId)) {
            return false;
        }

        $data = $this->preparePostData(array_merge($options, ['content' => $content]));
        /** @var object $result Result of the POST request. */
        $result = $this->post('tasks/' . $taskId, $data);

        return 204 === $result->getStatusCode();
    }

    /**
     * Close a task.
     *
     * @param int $taskId The ID of the task.
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
     * @param int $taskId The ID of the task.
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
     * @param int $taskId The ID of the task.
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
