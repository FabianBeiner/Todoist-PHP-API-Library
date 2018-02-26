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
 * Trait TodoistCommentsTrait.
 *
 * @package FabianBeiner\Todoist
 */
trait TodoistCommentsTrait
{
    /**
     * Alias for getAllComments('task', $taskId).
     *
     * @param int $taskId ID of the task.
     *
     * @return array|bool Array with all comments (can be empty), or false on failure.
     */
    public function getAllCommentsByTask($taskId)
    {
        return $this->getAllComments('task', $taskId);
    }

    /**
     * Get all comments.
     *
     * @param string $type Can be "project" or "task".
     * @param int $typeId ID of the project/task.
     *
     * @return array|bool Array with all comments (can be empty), or false on failure.
     */
    public function getAllComments($type, $typeId)
    {
        $type = mb_strtolower($type, 'UTF-8');
        if (($type !== 'project' && $type !== 'task') || (!filter_var($typeId,
                    FILTER_VALIDATE_INT) || $typeId <= 0)) {
            return false;
        }

        parse_str($this->tokenQuery, $query);
        $query[$type . '_id'] = $typeId;
        $localQuery = http_build_query($query, null, '&', PHP_QUERY_RFC3986);

        $result = $this->client->get('comments?' . $localQuery);

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
     * Alias for getAllComments('project', $projectId).
     *
     * @param int $projectId ID of the project.
     *
     * @return array|bool Array with all comments (can be empty), or false on failure.
     */
    public function getAllCommentsByProject($projectId)
    {
        return $this->getAllComments('project', $projectId);
    }

    /**
     * Alias for createComment('task', $projectId, $comment).
     *
     * @param int $taskId ID of the task.
     * @param string $comment Comment to be added.
     *
     * @return array|bool Array with values of the new comment, or false on failure.
     */
    public function createCommentForTask($taskId, $comment)
    {
        return $this->createComment('task', $taskId, $comment);
    }

    /**
     * Create a new comment.
     *
     * @param string $type Can be "project" or "task".
     * @param int $typeId ID of the project/task.
     * @param string $comment Comment to be added.
     *
     * @return array|bool Array with values of the new comment, or false on failure.
     */
    public function createComment($type, $typeId, $comment)
    {
        $type = mb_strtolower($type, 'UTF-8');
        if (($type !== 'project' && $type !== 'task') ||
            (!filter_var($typeId,
                    FILTER_VALIDATE_INT) || $typeId <= 0) ||
            !mb_strlen($comment,
                'utf8')) {
            return false;
        }

        $result = $this->client->post('comments?' . $this->tokenQuery,
            [
                RequestOptions::JSON => [
                    $type . '_id' => (int)$typeId,
                    'content' => trim($comment),
                ],
            ]);

        $status = $result->getStatusCode();
        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Alias for createComment('project', $projectId, $comment).
     *
     * @param int $projectId ID of the project.
     * @param string $comment Comment to be added.
     *
     * @return array|bool Array with values of the new comment, or false on failure.
     */
    public function createCommentForProject($projectId, $comment)
    {
        return $this->createComment('project', $projectId, $comment);
    }

    /**
     * Get a comment.
     *
     * @param int $commentId ID of the comment.
     *
     * @return array|bool Array with values of the comment, or false on failure.
     */
    public function getComment($commentId)
    {
        if (!filter_var($commentId, FILTER_VALIDATE_INT) || $commentId <= 0) {
            return false;
        }

        $result = $this->client->get('comments/' . $commentId . '?' . $this->tokenQuery);

        $status = $result->getStatusCode();
        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Update a comment.
     *
     * @param int $commentId ID of the comment.
     * @param string $content New content of the comment.
     *
     * @return bool True on success, false on failure.
     */
    public function updateComment($commentId, $content)
    {
        if ((!filter_var($commentId, FILTER_VALIDATE_INT) || $commentId <= 0) || !mb_strlen($content, 'utf8')) {
            return false;
        }

        $result = $this->client->post('comments/' . $commentId . '?' . $this->tokenQuery,
            [
                RequestOptions::JSON => ['content' => trim($content)],
            ]);

        $status = $result->getStatusCode();
        if ($status === 204) {
            return true;
        }

        return false;
    }

    /**
     * Delete a comment.
     *
     * @param int $commentId ID of the comment.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteComment($commentId)
    {
        if (!filter_var($commentId, FILTER_VALIDATE_INT) || $commentId <= 0) {
            return false;
        }

        $result = $this->client->delete('comments/' . $commentId . '?' . $this->tokenQuery);

        $status = $result->getStatusCode();
        if ($status === 200 || $status === 204) {
            return true;
        }

        return false;
    }
}
