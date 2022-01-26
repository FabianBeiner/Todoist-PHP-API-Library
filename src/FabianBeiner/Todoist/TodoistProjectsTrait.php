<?php
/**
 * Todoist-PHP-API-Library
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

/**
 * Trait TodoistProjectsTrait.
 *
 * @package FabianBeiner\Todoist
 */
trait TodoistProjectsTrait
{
    /**
     * Get all the projects.
     *
     * @return array|bool An array containing all active user projects, or false on failure.
     */
    public function getAllProjects()
    {
        /** @var object $result Result of the GET request. */
        $result = $this->get('projects');

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Create a new project.
     *
     * @param string $projectName        The name of the new project.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v1/#create-a-new-project.
     *
     * @return array|bool An array containing the values of the new project, or false on failure.
     */
    public function createProject(string $projectName, array $optionalParameters = [])
    {
        $projectName = filter_var($projectName, FILTER_SANITIZE_STRING);
        if ( ! strlen($projectName)) {
            return false;
        }

        $postData = $this->preparePostData(array_merge(['name' => $projectName], $optionalParameters));
        /** @var object $result Result of the POST request. */
        $result = $this->post('projects', $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Get a project.
     *
     * @param int $projectId The ID of the project.
     *
     * @return array|bool An array containing the project data related to the given id, or false on failure.
     */
    public function getProject(int $projectId)
    {
        if ( ! $this->validateId($projectId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('projects/' . $projectId);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Update a project.
     *
     * @param int    $projectId      The ID of the project.
     * @param string $newProjectName The new name of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function updateProject(int $projectId, string $newProjectName): bool
    {
        $newProjectName = filter_var($newProjectName, FILTER_SANITIZE_STRING);
        if ( ! strlen($newProjectName) || ! $this->validateId($projectId)) {
            return false;
        }

        $postData = $this->preparePostData(['name' => $newProjectName]);
        /** @var object $result Result of the POST request. */
        $result = $this->post('projects/' . $projectId, $postData);

        return 204 === $result->getStatusCode();
    }

    /**
     * Delete a project.
     *
     * @param int $projectId The ID of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteProject(int $projectId): bool
    {
        if ( ! $this->validateId($projectId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('projects/' . $projectId);

        return 204 === $result->getStatusCode();
    }
}
