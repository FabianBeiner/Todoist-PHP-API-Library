<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests;

use FabianBeiner\Todoist\TodoistClient;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

/**
 * Class TodoistClientTest.
 */
class TodoistClientTest extends TestCase
{
    /**
     * @var array|false|string API taken from env.
     */
    private $apiToken = null;

    /**
     * @var string|null Exemplary name for projects, labels, and other.
     */
    private ?string $testName = null;

    /**
     * @var object Todoist Client.
     */
    private $Todoist = null;

    /**
     * Set up method.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function setUp(): void
    {
        $this->apiToken = getenv('TODOIST_TOKEN');
        $this->testName = 'PHPUnit-' . uniqid();
        $this->Todoist  = new TodoistClient($this->apiToken);

        parent::setUp();
    }

    /**
     * Test the configuration.
     */
    public function testConfiguration()
    {
        $config = $this->Todoist->getConfig();

        $baseUri = $config['base_uri'];
        $headers = $config['headers'];

        $this->assertInstanceOf(Uri::class, $baseUri);
        $this->assertEquals('api.todoist.com', $baseUri->getHost());
        $this->assertEquals('/rest/v1/', $baseUri->getPath());
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertEquals(sprintf('Bearer %s', $this->apiToken), $headers['Authorization']);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return int ID of the created project.
     */
    public function testCreateProject(): int
    {
        $createProject = $this->Todoist->createProject($this->testName);
        $this->assertArrayHasKey('name', $createProject);
        $this->assertEquals($this->testName, $createProject['name']);

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
        $project = $this->Todoist->getProject($projectId);
        $this->assertArrayHasKey('name', $project);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return void
     */
    public function testGetAllProjects()
    {
        $allProjects = $this->Todoist->getAllProjects();
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
        $success = $this->Todoist->updateProject($projectId, $this->testName . '-Renamed');
        $this->assertTrue($success);
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
        $allCollaborators = $this->Todoist->getAllCollaborators($projectId);
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
        $success = $this->Todoist->deleteProject($projectId);
        $this->assertTrue($success);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return int ID of the created label.
     */
    public function testCreateLabel(): int
    {
        $createLabel = $this->Todoist->createLabel($this->testName);
        $this->assertArrayHasKey('name', $createLabel);
        $this->assertEquals($this->testName, $createLabel['name']);

        return $createLabel['id'];
    }

    public function testGetAllLabels()
    {
        $allLabels = $this->Todoist->getAllLabels();
        $this->assertArrayHasKey('id', $allLabels[0]);
    }

    /**
     * @depends testCreateLabel
     *
     * @param $labelId
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetLabel($labelId)
    {
        $label = $this->Todoist->getLabel($labelId);
        $this->assertArrayHasKey('name', $label);
    }

    /**
     * @depends testCreateLabel
     *
     * @param $labelId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateLabel($labelId)
    {
        $success = $this->Todoist->updateLabel($labelId, $this->testName . '-Renamed');
        $this->assertTrue($success);
    }

    /**
     * @depends testCreateLabel
     *
     * @param $labelId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteLabel($labelId)
    {
        $success = $this->Todoist->deleteLabel($labelId);
        $this->assertTrue($success);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return int ID of the created section.
     */
    public function testCreateSection(): int
    {
        $allProjects   = $this->Todoist->getAllProjects();
        $createSection = $this->Todoist->createSection($this->testName, $allProjects[0]['id']);
        $this->assertArrayHasKey('name', $createSection);
        $this->assertEquals($this->testName, $createSection['name']);

        return $createSection['id'];
    }

    /**
     * @depends testCreateSection
     */
    public function testGetAllSections($sectionId)
    {
        $allSections = $this->Todoist->getAllSections($sectionId);
        $this->assertArrayHasKey('id', $allSections[0]);
    }

    /**
     * @depends testCreateSection
     *
     * @param $sectionId
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetSection($sectionId)
    {
        $section = $this->Todoist->getSection($sectionId);
        $this->assertArrayHasKey('name', $section);
    }

    /**
     * @depends testCreateSection
     *
     * @param $sectionId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateSection($sectionId)
    {
        $success = $this->Todoist->updateSection($sectionId, $this->testName . '-Renamed');
        $this->assertTrue($success);
    }

    /**
     * @depends testCreateSection
     *
     * @param $sectionId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteSection($sectionId)
    {
        $success = $this->Todoist->deleteSection($sectionId);
        $this->assertTrue($success);
    }
}
