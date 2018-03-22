<?php
/**
 * Todoist PHP API Library
 * An unofficial PHP client library for accessing the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @author  Balazs Csaba <balazscsaba2006@gmail.com>
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

/**
 * Trait TodoistCommentsTrait.
 */
trait TodoistCommentsTrait
{
    /**
     * Prepare Guzzle request data.
     *
     * @param array $data
     *
     * @return array
     */
    abstract protected function prepareRequestData(array $data = []): array;

    /**
     * Validates an ID to be a positive integer.
     *
     * @param mixed $id
     *
     * @return bool
     */
    abstract protected function validateId($id): bool;

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
        $type = strtolower($type);

        if (('project' !== $type && 'task' !== $type) || ! $this->validateId($typeId)) {
            return false;
        }

        $query  = http_build_query([$type . '_id' => $typeId], null, '&', PHP_QUERY_RFC3986);
        $result = $this->get('comments?' . $query);

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
        $type = strtolower($type);

        if (('project' !== $type && 'task' !== $type) || '' === $comment || ! $this->validateId($typeId)) {
            return false;
        }

        $data   = $this->prepareRequestData([
                                                $type . '_id' => $typeId,
                                                'content'     => $comment,
                                            ]);
        $result = $this->post('comments', $data);

        if (200 === $result->getStatusCode()) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
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
        if ( ! $this->validateId($commentId)) {
            return false;
        }

        $result = $this->get('comments/' . $commentId);

        if (200 === $result->getStatusCode()) {
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
    public function updateComment(int $commentId, string $content): bool
    {
        if ('' === $content || ! $this->validateId($commentId)) {
            return false;
        }

        $data   = $this->prepareRequestData(['content' => $content]);
        $result = $this->post('comments/' . $commentId, $data);

        return 204 === $result->getStatusCode();
    }

    /**
     * Delete a comment.
     *
     * @param int $commentId ID of the comment.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteComment(int $commentId): bool
    {
        if ( ! $this->validateId($commentId)) {
            return false;
        }

        $result = $this->delete('comments/' . $commentId);

        return 204 === $result->getStatusCode();
    }
}
