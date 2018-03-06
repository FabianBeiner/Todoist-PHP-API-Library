<?php
/**
 * Todoist PHP API Library
 * An unofficial PHP client library for accessing the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 * @link    https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\RequestOptions;

/**
 * Trait TodoistTasksTrait.
 *
 * @package FabianBeiner\Todoist
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
        $result = $this->client->get('tasks');

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
     * Create a new task.
     *
     * @param string $content Content of the task.
     * @param array  $options Possibility to add non-required parameters.
     *
     * @return array|bool Array with values of the new task, or false on failure.
     */
    public function createTask(string $content, array $options = [])
    {
        if ( ! mb_strlen($content, 'utf8')) {
            return false;
        }

        if ($options) {
            unset($options['content']);
        }
        $result = $this->client->post('tasks',
                                      [
                                          RequestOptions::JSON => array_merge(['content' => trim($content)],
                                                                              $options),
                                      ]);

        $status = $result->getStatusCode();
        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Get a task.
     *
     * @param int $taskId ID of the task.
     *
     * @return array|bool Array with values of the task, or false on failure.
     */
    public function getTask(int $taskId)
    {
        if ( ! $taskId || ! filter_var($taskId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->get('tasks/' . $taskId);

        $status = $result->getStatusCode();
        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Update a task.
     *
     * @param int    $taskId  ID of the task.
     * @param string $content Content of the task.
     * @param array  $options Possibility to add non-required parameters.
     *
     * @return array|bool Array with values of the new task, or false on failure.
     */
    public function updateTask(int $taskId, string $content, array $options = [])
    {
        if ( ! mb_strlen($content, 'utf8')) {
            return false;
        }

        if ($options) {
            unset($options['content']);
        }
        $result = $this->client->post('tasks/' . $taskId,
                                      [
                                          RequestOptions::JSON => array_merge(['content' => trim($content)],
                                                                              $options),
                                      ]);

        $status = $result->getStatusCode();
        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Close a task.
     *
     * @param int $taskId ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function closeTask(int $taskId)
    {
        if ( ! $taskId || ! filter_var($taskId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->get('tasks/' . $taskId . '/close');
        $status = $result->getStatusCode();

        return ($status === 200 || $status === 204);
    }

    /**
     * Reopen a task.
     *
     * @param int $taskId ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function reopenTask(int $taskId)
    {
        if ( ! $taskId || ! filter_var($taskId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->get('tasks/' . $taskId . '/reopen');
        $status = $result->getStatusCode();

        return ($status === 200 || $status === 204);
    }

    /**
     * Delete a task.
     *
     * @param int $taskId ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteTask(int $taskId)
    {
        if ( ! $taskId || ! filter_var($taskId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->delete('tasks/' . $taskId);
        $status = $result->getStatusCode();

        return ($status === 200 || $status === 204);
    }
}
