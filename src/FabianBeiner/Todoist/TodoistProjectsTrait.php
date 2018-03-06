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
 * Trait TodoistProjectsTrait.
 *
 * @package FabianBeiner\Todoist
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
        $result = $this->client->get('projects');

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
     * Create a new project.
     *
     * @param string $name Name of the project.
     *
     * @return array|bool Array with values of the new project, or false on failure.
     */
    public function createProject($name)
    {
        if ('' === $name) {
            return false;
        }

        $result = $this->client->post('projects', [
            RequestOptions::JSON => ['name' => trim($name)],
        ]);

        if ($result->getStatusCode() === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Get a project.
     *
     * @param int $projectId ID of the project.
     *
     * @return array|bool Array with values of the project, or false on failure.
     */
    public function getProject($projectId)
    {
        if (!filter_var($projectId, FILTER_VALIDATE_INT) || $projectId <= 0) {
            return false;
        }

        $result = $this->client->get('projects/' . $projectId . '?' . $this->tokenQuery);

        if ($result->getStatusCode() === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Alias for updateProject().
     *
     * @param int    $projectId ID of the project.
     * @param string $name      New name of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function renameProject($projectId, $name): bool
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
    public function updateProject($projectId, $name): bool
    {
        if ($projectId <= 0 || '' === $name || !filter_var($projectId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->post('projects/' . $projectId, [
            RequestOptions::JSON => ['name' => trim($name)],
        ]);

        return ($result->getStatusCode() === 204);
    }

    /**
     * Delete a project.
     *
     * @param int $projectId ID of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteProject($projectId): bool
    {
        if ($projectId <= 0 || !filter_var($projectId, FILTER_VALIDATE_INT)) {
            return false;
        }

        $result = $this->client->delete('projects/' . $projectId);

        $status = $result->getStatusCode();

        return ($status === 200 || $status === 204);
    }
}
