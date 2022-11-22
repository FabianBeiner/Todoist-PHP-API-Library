<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests;

/**
 * Class TodoistProjectTest.
 */
class TodoistProjectTest extends AbstractTodoistTestCase
{
    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return string ID of the created project.
     */
    public function testCreateProject(): string
    {
        $createProject = self::$Todoist->createProject(self::$testName);

        $this->assertArrayHasKey('name', $createProject);
        $this->assertEquals(self::$testName, $createProject['name']);

        return $createProject['id'];
    }

    /**
     * @depends testCreateProject
     *
     * @param $projectId
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetProject($projectId)
    {
        $project = self::$Todoist->getProject($projectId);
        $this->assertArrayHasKey('name', $project);
        $this->assertEquals($projectId, $project['id']);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return void
     */
    public function testGetAllProjects()
    {
        $allProjects = self::$Todoist->getAllProjects();
        $this->assertArrayHasKey('id', $allProjects[0]);
    }

    /**
     * @depends testCreateProject
     *
     * @param $projectId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateProject($projectId)
    {
        $projectNewName = self::$testName . '-Renamed';
        $projectUpdated = self::$Todoist->updateProject($projectId, ['name' => $projectNewName]);
        $this->assertArrayHasKey('name', $projectUpdated);
        $this->assertEquals($projectNewName, $projectUpdated['name']);
        $this->assertEquals($projectId, $projectUpdated['id']);
    }

    /**
     * @depends testCreateProject
     *
     * @param $projectId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function testGetAllCollaborators($projectId)
    {
        $allCollaborators = self::$Todoist->getAllCollaborators($projectId);
        $this->assertCount(0, $allCollaborators);
    }

    /**
     * @depends testCreateProject
     *
     * @param $projectId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteProject($projectId)
    {
        $success = self::$Todoist->deleteProject($projectId);
        $this->assertTrue($success);
    }
}
