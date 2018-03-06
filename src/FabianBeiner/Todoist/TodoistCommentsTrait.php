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
     * @param string $type   Can be "project" or "task".
     * @param int    $typeId ID of the project/task.
     *
     * @return array|bool Array with all comments (can be empty), or false on failure.
     */
    public function getAllComments(string $type, int $typeId)
    {
        $type = mb_strtolower($type, 'UTF-8');
        if (($type !== 'project' && $type !== 'task') || ( ! $typeId || ! filter_var($typeId,
                                                                                     FILTER_VALIDATE_INT))) {
            return false;
        }

        $query = http_build_query([$type . '_id' => $typeId], null, '&', PHP_QUERY_RFC3986);

        $result = $this->client->get('comments?' . $query);

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
    public function getAllCommentsByProject(int $projectId)
    {
        return $this->getAllComments('project', $projectId);
    }

    /**
     * Alias for createComment('task', $projectId, $comment).
     *
     * @param int    $taskId  ID of the task.
     * @param string $comment Comment to be added.
     *
     * @return array|bool Array with values of the new comment, or false on
     *                    failure.
     */
    public function createCommentForTask(int $taskId, string $comment)
    {
        return $this->createComment('task', $taskId, $comment);
    }

    /**
     * Create a new comment.
     *
     * @param string $type    Can be "project" or "task".
     * @param int    $typeId  ID of the project/task.
     * @param string $comment Comment to be added.
     *
     * @return array|bool Array with values of the new comment, or false on failure.
     */
    public function createComment(string $type, int $typeId, string $comment)
    {
        $type = mb_strtolower($type, 'UTF-8');
        if (($type !== 'project' && $type !== 'task') ||
            ( ! $typeId || ! filter_var($typeId,
                                        FILTER_VALIDATE_INT)) ||
            ! mb_strlen($comment,
                        'utf8')) {
            return false;
        }

        $result = $this->client->post('comments',
                                      [
                                          RequestOptions::JSON => [
                                              $type . '_id' => (int)$typeId,
                                              'content'     => trim($comment)
                                          ]
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
     * @param int    $projectId ID of the project.
     * @param string $comment   Comment to be added.
     *
     * @return array|bool Array with values of the new comment, or false on failure.
     */
    public function createCommentForProject(int $projectId, string $comment)
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
    public function getComment(int $commentId)
    {
        if ( ! $commentId || ! filter_var($commentId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->get('comments/' . $commentId);

        $status = $result->getStatusCode();
        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Update a comment.
     *
     * @param int    $commentId ID of the comment.
     * @param string $content   New content of the comment.
     *
     * @return bool True on success, false on failure.
     */
    public function updateComment(int $commentId, string $content)
    {
        if ( ! $commentId || ! mb_strlen($content, 'utf8') || ! filter_var($commentId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->post('comments/' . $commentId,
                                      [
                                          RequestOptions::JSON => ['content' => trim($content)]
                                      ]);

        $status = $result->getStatusCode();

        return ($status === 200 || $status === 204);
    }

    /**
     * Delete a comment.
     *
     * @param int $commentId ID of the comment.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteComment(int $commentId)
    {
        if ( ! $commentId || ! filter_var($commentId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->delete('comments/' . $commentId);

        $status = $result->getStatusCode();

        return ($status === 200 || $status === 204);
    }
}
