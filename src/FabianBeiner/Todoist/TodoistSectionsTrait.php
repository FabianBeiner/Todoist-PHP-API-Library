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
     * @param int|null $projectId Filter sections by project ID.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all user sections, or false on failure.
     */
    public function getAllSections(int $projectId = null)
    {
        if ( ! $this->validateId($projectId)) {
            /** @var object $result Result of the GET request. */
            $result = $this->get('sections?project_id=' . $projectId);
        } else {
            /** @var object $result Result of the GET request. */
            $result = $this->get('sections');
        }

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Creates a new section and returns it as an array.
     *
     * @param string $sectionName        The name of the section.
     * @param int    $projectId          The project ID this section should belong to.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v1/#create-a-new-section.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool An array containing the values of the new section, or false on failure.
     */
    public function createSection(string $sectionName, int $projectId, array $optionalParameters = [])
    {
        $sectionName = filter_var($sectionName, FILTER_SANITIZE_STRING);
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
     * @param int $sectionId The ID of the section.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * Updates the section for the given ID.
     *
     * @param int    $sectionId      The ID of the section.
     * @param string $newSectionName The new name of the section.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
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
     * Deletes a section.
     *
     * @param int $sectionId The ID of the section.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
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
