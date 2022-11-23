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
     * @param string $taskId The ID of the task.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all comments, or false on failure.
     */
    public function getAllCommentsByTask(string $taskId)
    {
        return $this->getAllComments('task', $taskId);
    }

    /**
     * Alias for getAllComments('project', $projectId).
     *
     * @param string $projectId The ID of the project.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all comments, or false on failure.
     */
    public function getAllCommentsByProject(string $projectId)
    {
        return $this->getAllComments('project', $projectId);
    }

    /**
     * Returns an array of all comments for a given task or project.
     *
     * @param string $commentType Type can be "project" or "task."
     * @param string    $typeId      The ID of the project/task.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all comments, or false on failure.
     */
    public function getAllComments(string $commentType, string $typeId)
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
     * Alias for createComment('task', $taskId, $comment).
     *
     * @param string $taskId  The ID of the task.
     * @param string $comment The comment.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the values of the new comment, or false on failure.
     */
    public function createCommentForTask(string $taskId, string $comment)
    {
        return $this->createComment('task', $taskId, $comment);
    }

    /**
     * Alias for createComment('project', $projectId, $comment).
     *
     * @param string $projectId The ID of the project.
     * @param string $comment   The comment.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the values of the new comment, or false on failure.
     */
    public function createCommentForProject(string $projectId, string $comment)
    {
        return $this->createComment('project', $projectId, $comment);
    }

    /**
     * Creates a new comment on a project or task and returns it as an array.
     *
     * @param string $commentType Type can be "project" or "task."
     * @param string $typeId      The ID of the project/task.
     * @param string $comment     The comment.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool An array containing the values of the new comment, or false on failure.
     */
    public function createComment(string $commentType, string $typeId, string $comment)
    {
        $commentType = strtolower($commentType);
        if (('project' !== $commentType && 'task' !== $commentType) || ! $this->validateId($typeId) || ! strlen($comment)) {
            return false;
        }

        $data = $this->preparePostData([
            $commentType . '_id' => $typeId,
            'content'            => $comment,
        ]);

        /** @var object $result Result of the POST request. */
        $result = $this->post('comments', $data);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Returns a single comment as an array.
     *
     * @param string $commentId The ID of the comment.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the values of the comment, or false on failure.
     */
    public function getComment(string $commentId)
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
     * @param string $commentId The ID of the comment.
     * @param string $content   The new content of the comment.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException*@throws \Exception*@throws \Exception
     * @throws \Exception
     *
     * @return array|bool True on success, false on failure.
     */
    public function updateComment(string $commentId, string $content)
    {
        if ( ! strlen($content) || ! $this->validateId($commentId)) {
            return false;
        }

        $data = $this->preparePostData(['content' => $content]);

        /** @var object $result Result of the POST request. */
        $result = $this->post('comments/' . $commentId, $data);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Deletes a comment.
     *
     * @param string $commentId The ID of the comment.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool True on success, false on failure.
     */
    public function deleteComment(string $commentId): bool
    {
        if ( ! $this->validateId($commentId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('comments/' . $commentId);

        return 204 === $result->getStatusCode();
    }
}
