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
 * Trait TodoistTasksTrait.
 */
trait TodoistTasksTrait
{
    /**
     * Get active tasks.
     *
     * @param array $optionalParameters Possibility to add non-required parameters, see
     *                                  https://developer.todoist.com/rest/v2#get-active-tasks
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool Returns an array containing all user active tasks, or false on failure.
     */
    public function getAllTasks(array $optionalParameters = [])
    {
        if (count($optionalParameters)) {
            $query = http_build_query($optionalParameters, null, '&', PHP_QUERY_RFC3986);

            /** @var object $result Result of the GET request. */
            $result = $this->get('tasks?' . $query);
        } else {
            /** @var object $result Result of the GET request. */
            $result = $this->get('tasks');
        }

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Create a new task.
     *
     * @param string $content            The content of the task.
     * @param array  $optionalParameters Possibility to add non-required parameters, see
     *                                   https://developer.todoist.com/rest/v2#create-a-new-task
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool An array containing the values of the new task, or false on failure.
     */
    public function createTask(string $content, array $optionalParameters = [])
    {
        if ( ! strlen($content)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters = [
            'description',
            'project_id',
            'section_id',
            'parent_id',
            'order',
            'labels',
            'priority',
            'due_string',
            'due_date',
            'due_datetime',
            'due_lang',
            'assignee_id',
        ];
        $filteredParameters = array_intersect_key($optionalParameters, array_flip($validParameters));

        $postData = $this->preparePostData(
            array_merge(['content' => $content], $filteredParameters)
        );

        /** @var object $result Result of the POST request. */
        $result = $this->post('tasks', $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Get a task.
     *
     * @param string $taskId The ID of the task.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the task data related to the given id, or false on failure.
     */
    public function getTask(string $taskId)
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
     * @param string      $taskId             The ID of the task.
     * @param array       $optionalParameters Possibility to add non-required parameters, see
     *                                        https://developer.todoist.com/rest/v2#update-a-task
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool True on success, false on failure.
     */
    public function updateTask(string $taskId, array $optionalParameters = [])
    {
        if ( ! $this->validateId($taskId)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters = [
            'content',
            'description',
            'labels',
            'priority',
            'due_string',
            'due_date',
            'due_datetime',
            'due_lang',
            'assignee_id',
        ];
        $filteredParameters = array_intersect_key($optionalParameters, array_flip($validParameters));

        $data = $this->preparePostData($filteredParameters);

        /** @var object $result Result of the POST request. */
        $result = $this->post('tasks/' . $taskId, $data);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Close a task.
     *
     * @param string $taskId The ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function closeTask(string $taskId): bool
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
     * @param string $taskId The ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function reopenTask(string $taskId): bool
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
     * @param string $taskId The ID of the task.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteTask(string $taskId): bool
    {
        if ( ! $this->validateId($taskId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('tasks/' . $taskId);

        return 204 === $result->getStatusCode();
    }
}
