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
 * Trait TodoistCommentsTrait.
 */
trait TodoistCommentsTrait
{
    /**
     * Alias for getAllComments('task', $taskId).
     *
     * @param int $taskId The ID of the task.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all comments, or false on failure.
     */
    public function getAllCommentsByTask(int $taskId)
    {
        return $this->getAllComments('task', $taskId);
    }

    /**
     * Returns an array of all comments for a given task or project.
     *
     * @param string $commentType Type can be "project" or "task."
     * @param int    $typeId      The ID of the project/task.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all comments, or false on failure.
     */
    public function getAllComments(string $commentType, int $typeId)
    {
        $commentType = strtolower($commentType);
        if (('project' !== $commentType && 'task' !== $commentType) || ! $this->validateId($typeId)) {
            return false;
        }

        $query = http_build_query([$commentType . '_id' => $typeId], null, '&', PHP_QUERY_RFC3986);
        /** @var object $result Result of the GET request. */
        $result = $this->get('comments?' . $query);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Alias for getAllComments('project', $projectId).
     *
     * @param int $projectId The ID of the project.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all comments, or false on failure.
     */
    public function getAllCommentsByProject(int $projectId)
    {
        return $this->getAllComments('project', $projectId);
    }

    /**
     * Alias for createComment('task', $taskId, $comment).
     *
     * @param int    $taskId  The ID of the task.
     * @param string $comment The comment.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the values of the new comment, or false on failure.
     */
    public function createCommentForTask(int $taskId, string $comment)
    {
        return $this->createComment('task', $taskId, $comment);
    }

    /**
     * Creates a new comment on a project or task and returns it as an array.
     *
     * @param string $commentType Type can be "project" or "task."
     * @param int    $typeId      The ID of the project/task.
     * @param string $comment     The comment.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool An array containing the values of the new comment, or false on failure.
     */
    public function createComment(string $commentType, int $typeId, string $comment)
    {
        $commentType = strtolower($commentType);
        if (('project' !== $commentType && 'task' !== $commentType) || ! $this->validateId($typeId)) {
            return false;
        }

        $data = $this->preparePostData([
            $commentType . '_id' => $typeId,
            'content'            => $comment,
        ]);å
        /** @var object $result Result of the POST request. */
        $result = $this->post('comments', $data);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Alias for createComment('project', $projectId, $comment).
     *
     * @param int    $projectId The ID of the project.
     * @param string $comment   The comment.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the values of the new comment, or false on failure.
     */
    public function createCommentForProject(int $projectId, string $comment)
    {
        return $this->createComment('project', $projectId, $comment);
    }

    /**
     * Returns a single comment as an array.
     *
     * @param int $commentId The ID of the comment.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the values of the comment, or false on failure.
     */
    public function getComment(int $commentId)
    {
        if ( ! $this->validateId($commentId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('comments/' . $commentId);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Updates a comment.
     *
     * @param int    $commentId The ID of the comment.
     * @param string $content   The new content of the comment.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException*@throws \Exception*@throws \Exception
     * @throws \Exception
     *
     * @return bool True on success, false on failure.
     */
    public function updateComment(int $commentId, string $content): bool
    {
        if ( ! strlen($content) || ! $this->validateId($commentId)) {
            return false;
        }

        $data = $this->preparePostData(['content' => $content]);
        /** @var object $result Result of the POST request. */
        $result = $this->post('comments/' . $commentId, $data);

        return 204 === $result->getStatusCode();
    }

    /**
     * Deletes a comment.
     *
     * @param int $commentId The ID of the comment.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool True on success, false on failure.
     */
    public function deleteComment(int $commentId): bool
    {
        if ( ! $this->validateId($commentId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('comments/' . $commentId);

        return 204 === $result->getStatusCode();
    }
}
