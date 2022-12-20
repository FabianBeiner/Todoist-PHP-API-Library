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
 * Trait TodoistSectionsTrait.
 */
trait TodoistSectionsTrait
{
    /**
     * Returns an array of all sections.
     *
     * @param string|null $projectId Filter sections by project ID.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all user sections, or false on failure.
     */
    public function getAllSections(?string $projectId = null): bool|array
    {
        if ( ! $this->validateId($projectId)) {
            /** @var object $result Result of the GET request. */
            $result = $this->get('sections');
        } else {
            /** @var object $result Result of the GET request. */
            $result = $this->get('sections?project_id=' . $projectId);
        }

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Creates a new section and returns it as an array.
     *
     * @param string $sectionName        The name of the section.
     * @param string $projectId          The project ID this section should belong to.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v2#create-a-new-section
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool An array containing the values of the new section, or false on failure.
     */
    public function createSection(string $sectionName, string $projectId, array $optionalParameters = []): bool|array
    {
        if ( ! strlen($sectionName) || ! $this->validateId($projectId)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters    = [
            'order',
        ];
        $filteredParameters = array_intersect_key($optionalParameters, array_flip($validParameters));

        $postData = $this->preparePostData(
            array_merge(['name' => $sectionName, 'project_id' => $projectId], $filteredParameters)
        );

        /** @var object $result Result of the POST request. */
        $result = $this->post('sections', $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Returns a single section as an array.
     *
     * @param string $sectionId The ID of the section.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the section data related to the given id, or false on failure.
     */
    public function getSection(string $sectionId): bool|array
    {
        if ( ! $this->validateId($sectionId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('sections/' . $sectionId);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Updates the section for the given ID.
     *
     * @param string $sectionId      The ID of the section.
     * @param string $newSectionName The new name of the section.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool True on success, false on failure.
     */
    public function updateSection(string $sectionId, string $newSectionName): bool|array
    {
        if ( ! strlen($newSectionName) || ! $this->validateId($sectionId)) {
            return false;
        }

        $postData = $this->preparePostData(['name' => $newSectionName]);

        /** @var object $result Result of the POST request. */
        $result = $this->post('sections/' . $sectionId, $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Deletes a section.
     *
     * @param string $sectionId The ID of the section.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool True on success, false on failure.
     */
    public function deleteSection(string $sectionId): bool
    {
        if ( ! $this->validateId($sectionId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('sections/' . $sectionId);

        return 204 === $result->getStatusCode();
    }
}
