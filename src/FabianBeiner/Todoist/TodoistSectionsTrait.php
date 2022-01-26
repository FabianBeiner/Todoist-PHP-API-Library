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
 * Trait TodoistSectionsTrait.
 *
 * @package FabianBeiner\Todoist
 */
trait TodoistSectionsTrait
{
    /**
     * Get all the sections.
     *
     * @return array|bool An array containing all user sections, or false on failure.
     */
    public function getAllSections()
    {
        /** @var object $result Result of the GET request. */
        $result = $this->get('sections');

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Create a new section.
     *
     * @param string $sectionName        The name of the section.
     * @param int    $projectId          The project ID this section should belong to.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v1/#create-a-new-section.
     *
     * @return array|bool An array containing the values of the new section, or false on failure.
     */
    public function createSection(string $sectionName, int $projectId, array $optionalParameters = [])
    {
        $sectionName = filter_var($sectionName, FILTER_SANITIZE_STRING);
        if ( ! strlen($sectionName) || ! $this->validateId($projectId)) {
            return false;
        }

        $postData = $this->preparePostData(
            array_merge(['name' => $sectionName, 'project_id' => $projectId], $optionalParameters)
        );
        /** @var object $result Result of the POST request. */
        $result = $this->post('sections', $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Get a single section.
     *
     * @param int $sectionId The ID of the section.
     *
     * @return array|bool An array containing the section data related to the given id, or false on failure.
     */
    public function getSection(int $sectionId)
    {
        if ( ! $this->validateId($sectionId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('sections/' . $sectionId);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Update a section.
     *
     * @param int    $sectionId      The ID of the section.
     * @param string $newSectionName The new name of the section.
     *
     * @return bool True on success, false on failure.
     */
    public function updateSection(int $sectionId, string $newSectionName): bool
    {
        $newSectionName = filter_var($newSectionName, FILTER_SANITIZE_STRING);
        if ( ! strlen($newSectionName) || ! $this->validateId($sectionId)) {
            return false;
        }

        $postData = $this->preparePostData(['name' => $newSectionName]);
        /** @var object $result Result of the POST request. */
        $result = $this->post('sections/' . $sectionId, $postData);

        return 204 === $result->getStatusCode();
    }

    /**
     * Delete a section.
     *
     * @param int $sectionId The ID of the section.
     *
     * @return bool True on success, false on failure.
     */
    public function deleteSection(int $sectionId): bool
    {
        if ( ! $this->validateId($sectionId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('sections/' . $sectionId);

        return 204 === $result->getStatusCode();
    }
}
