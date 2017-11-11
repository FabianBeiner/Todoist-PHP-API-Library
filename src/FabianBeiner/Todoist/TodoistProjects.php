<?php
/**
 * @author  Fabian Beiner (fb@fabianbeiner.de)
 * @link    https://fabianbeiner.de
 * @license MIT License
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\Uuid;

trait TodoistProjects
{
    /**
     * @var string The current URL of the Todoist REST API.
     */
    protected $restApiUrl = 'https://beta.todoist.com/API/v8/';

    /**
     * @var string|null The API token to access the Todoist API, or null if unset.
     */
    private $apiToken = null;

    /**
     * @var \GuzzleHttp\Client|null Guzzle client, or null if unset.
     */
    private $client = null;

    /**
     * @var string|null Default URL query.
     */
    private $tokenQuery = null;

    /**
     * Get all projects.
     *
     * @return array|bool Array with all projects (can be empty), or false on failure.
     */
    public function getAllProjects()
    {
        $result = $this->client->get('projects?' . $this->tokenQuery);
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
        if ( ! mb_strlen($name, 'utf8')) {
            return false;
        }

        $result = $this->client->post('projects?' . $this->tokenQuery, [
            RequestOptions::JSON => ['name' => trim($name)],
            'X-Request-Id'       => Uuid::uuid4()
        ]);
        $status = $result->getStatusCode();

        if ($status === 200) {
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
        if ( ! ctype_digit($projectId)) {
            return false;
        }

        $result = $this->client->get('projects/' . $projectId . '?' . $this->tokenQuery);
        $status = $result->getStatusCode();

        if ($status === 200) {
            return json_decode($result->getBody()->getContents());
        }

        return false;
    }

    /**
     * Alias for updateProject.
     *
     * @param int    $projectId ID of the project.
     * @param string $name      New name of the project.
     */
    public function renameProject($projectId, $name)
    {
        $this->updateProject($projectId, $name);
    }

    /**
     * Update (actually rename…) a project.
     *
     * @param int    $projectId ID of the project.
     * @param string $name      New name of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function updateProject($projectId, $name)
    {
        if ( ! ctype_digit($projectId) || ! mb_strlen($name, 'utf8')) {
            return false;
        }

        $result = $this->client->post('projects/' . $projectId . '?' . $this->tokenQuery, [
            RequestOptions::JSON => ['name' => trim($name)],
            'X-Request-Id'       => Uuid::uuid4()
        ]);
        $status = $result->getStatusCode();

        if ($status === 200 || $status === 204) {
            return true;
        }

        return false;
    }

    /**
     * Delete a project.
     *
     * @param int $projectId ID of the project.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteProject($projectId)
    {
        if ( ! ctype_digit($projectId)) {
            return false;
        }

        $result = $this->client->delete('projects/' . $projectId . '?' . $this->tokenQuery);
        $status = $result->getStatusCode();

        if ($status === 200 || $status === 204) {
            return true;
        }

        return false;
    }
}
