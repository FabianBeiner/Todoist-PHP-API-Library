<?php

namespace FabianBeiner\Todoist\Tests;

use FabianBeiner\Todoist\TodoistClient;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

class TodoistClientTest extends TestCase
{
    private $apiToken;

    private $projectName;

    private $projectId;

    /**
     * Set up method.
     */
    public function setUp()
    {
        $this->apiToken    = getenv('TODOIST_TOKEN');
        $this->projectName = uniqid();

        parent::setUp();
    }

    /**
     * Test the configuration.
     *
     * @param array $options
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function testConfiguration(array $options = [])
    {
        $Todoist = new TodoistClient($this->apiToken, $options);
        $config  = $Todoist->getConfig();

        $baseUri = $config['base_uri'];
        $headers = $config['headers'];

        $this->assertInstanceOf(Uri::class, $baseUri);
        $this->assertEquals('beta.todoist.com', $baseUri->getHost());
        $this->assertEquals('/API/v8/', $baseUri->getPath());
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertEquals(sprintf('Bearer %s', $this->apiToken), $headers['Authorization']);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function testGetAllProjects()
    {
        $Todoist     = new TodoistClient($this->apiToken);
        $allProjects = $Todoist->getAllProjects();
        $this->assertObjectHasAttribute('id', $allProjects[0]);
    }

    /**
     * @return int ID of the created project.
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function testCreateProject()
    {
        $Todoist       = new TodoistClient($this->apiToken);
        $createProject = $Todoist->createProject($this->projectName);
        $this->assertObjectHasAttribute('name', $createProject);
        $this->assertEquals($this->projectName, $createProject->name);

        return $createProject->id;
    }

    /**
     * @depends testCreateProject
     *
     * @param $id
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function testGetProject($id)
    {
        $Todoist = new TodoistClient($this->apiToken);
        $project = $Todoist->getProject($id);
        $this->assertObjectHasAttribute('name', $project);
    }

    /**
     * @depends testCreateProject
     *
     * @param $id
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function testUpdateProject($id)
    {
        $Todoist = new TodoistClient($this->apiToken);
        $success = $Todoist->updateProject($id, $this->projectName . '-Renamed');
        $this->assertTrue($success);
    }

    /**
     * @depends testCreateProject
     *
     * @param $id
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function testDeleteProject($id)
    {
        $Todoist = new TodoistClient($this->apiToken);
        $success = $Todoist->deleteProject($id);
        $this->assertTrue($success);
    }
}
