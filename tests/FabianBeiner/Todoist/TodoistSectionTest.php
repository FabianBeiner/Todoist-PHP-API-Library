<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests\FabianBeiner\Todoist;

/**
 * Class TodoistSectionTest.
 */
class TodoistSectionTest extends AbstractTodoistTestCase
{
    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return string ID of the created section.
     */
    public function testCreateSection(): string
    {
        $allProjects   = self::$Todoist->getAllProjects();
        $createSection = self::$Todoist->createSection(self::$testName, $allProjects[0]['id']);
        $this->assertArrayHasKey('name', $createSection);
        $this->assertEquals(self::$testName, $createSection['name']);

        return $createSection['id'];
    }

    /**
     * @depends testCreateSection
     *
     * @param $sectionId
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAllSections($sectionId)
    {
        $allSections = self::$Todoist->getAllSections($sectionId);
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
        $section = self::$Todoist->getSection($sectionId);
        $this->assertEquals($sectionId, $section['id']);
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
        $sectionNewName = self::$testName . '-Renamed';
        $sectionUpdated = self::$Todoist->updateSection($sectionId, $sectionNewName);
        $this->assertArrayHasKey('name', $sectionUpdated);
        $this->assertEquals($sectionNewName, $sectionUpdated['name']);
        $this->assertEquals($sectionId, $sectionUpdated['id']);
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
        $success = self::$Todoist->deleteSection($sectionId);
        $this->assertTrue($success);
    }
}
