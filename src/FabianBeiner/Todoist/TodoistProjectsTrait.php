<?php
/**
 * PHP Client for Todoist
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

/**
 * Trait TodoistProjectsTrait.
 */
trait TodoistProjectsTrait
{
    /**
     * Get all projects.
     *
     * @return array|bool Array with all projects (can be empty), or false on failure.
     */
    public function getAllProjects()
    {
        /** @var object $result Result of the GET request. */
        $result = $this->get('projects');

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
     * Create a new project.
     *
     * @param string $name Name of the project.
     *
     * @return object|bool Object with values of the new project, or false on failure.
     */
    public function createProject(string $name)
    {
        if ('' === $name) {
            return false;
        }

        $data   = $this->prepareRequestData(['name' => $name]);
        /** @var object $result Result of the POST request. */
        $result = $this->post('projects', $data);

        if (200 === $result->getStatusCode()) {
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
     * Get a project.
     *
     * @param int $projectId ID of the project.
     *
     * @return object|bool Object with values of the project, or false on failure.
     */
    public function getProject(int $projectId)
    {
        if ( ! $this->validateId($projectId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('projects/' . $projectId);

        if (200 === $result->getStatusCode()) {
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
     * Alias for updateProject().
     *
     * @param int    $projectId ID of the project.
     * @param string $name      New name of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function renameProject(int $projectId, string $name): bool
    {
        return $this->updateProject($projectId, $name);
    }

    /**
     * Update (actually rename...) a project.
     *
     * @param int    $projectId ID of the project.
     * @param string $name      New name of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function updateProject(int $projectId, string $name): bool
    {
        if ('' === $name || ! $this->validateId($projectId)) {
            return false;
        }

        $data   = $this->prepareRequestData(['name' => $name]);
        /** @var object $result Result of the POST request. */
        $result = $this->post('projects/' . $projectId, $data);

        return 204 === $result->getStatusCode();
    }

    /**
     * Delete a project.
     *
     * @param int $projectId ID of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteProject(int $projectId): bool
    {
        if ($projectId <= 0 || ! $projectId || ! filter_var($projectId, FILTER_VALIDATE_INT)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('projects/' . $projectId);

        return 204 === $result->getStatusCode();
    }
}
