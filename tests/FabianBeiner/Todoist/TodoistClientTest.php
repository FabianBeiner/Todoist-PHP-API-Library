<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests;

use FabianBeiner\Todoist\TodoistClient;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;

/**
 * Class TodoistClientTest.
 *
 * @package FabianBeiner\Todoist\Tests
 */
class TodoistClientTest extends TestCase
{
    /**
     * @var array|false|string API taken from env.
     */
    private $apiToken = null;

    /**
     * @var string Exemplary name for projects, labels, and other.
     */
    private $testName = null;

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

    public function testGetAllProjects()
    {
        $allProjects = $this->Todoist->getAllProjects();
        $this->assertArrayHasKey('id', $allProjects[0]);
    }

    /**
     * @return int ID of the created project.
     */
    public function testCreateProject()
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
     */
    public function testGetProject($projectId)
    {
        $project = $this->Todoist->getProject($projectId);
        $this->assertArrayHasKey('name', $project);
    }

    /**
     * @depends testCreateProject
     *
     * @param $projectId
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
     */
    public function testDeleteProject($projectId)
    {
        $success = $this->Todoist->deleteProject($projectId);
        $this->assertTrue($success);
    }

    public function testGetAllLabels()
    {
        $allLabels = $this->Todoist->getAllLabels();
        $this->assertArrayHasKey('id', $allLabels[0]);
    }

    /**
     * @return int ID of the created label.
     */
    public function testCreateLabel()
    {
        $createLabel = $this->Todoist->createLabel($this->testName);
        $this->assertArrayHasKey('name', $createLabel);
        $this->assertEquals($this->testName, $createLabel['name']);

        return $createLabel['id'];
    }

    /**
     * @depends testCreateLabel
     *
     * @param $labelId
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
     */
    public function testDeleteLabel($labelId)
    {
        $success = $this->Todoist->deleteLabel($labelId);
        $this->assertTrue($success);
    }

    /**
     * @depends testCreateSection
     */
    public function testGetAllSections()
    {
        $allSections = $this->Todoist->getAllSections();
        $this->assertArrayHasKey('id', $allSections[0]);
    }

    /**
     * @return int ID of the created section.
     */
    public function testCreateSection()
    {
        $allProjects   = $this->Todoist->getAllProjects();
        $createSection = $this->Todoist->createSection($this->testName, $allProjects[0]['id']);
        $this->assertArrayHasKey('name', $createSection);
        $this->assertEquals($this->testName, $createSection['name']);

        return $createSection['id'];
    }

    /**
     * @depends testCreateSection
     *
     * @param $sectionId
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
     */
    public function testDeleteSection($sectionId)
    {
        $success = $this->Todoist->deleteSection($sectionId);
        $this->assertTrue($success);
    }
}
