<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests;

/**
 * Class TodoistLabelTest.
 */
class TodoistLabelTest extends AbstractTodoistTestCase
{
    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return string ID of the created label.
     */
    public function testCreateLabel(): string
    {
        $createLabel = self::$Todoist->createLabel(self::$testName);
        $this->assertArrayHasKey('name', $createLabel);
        $this->assertEquals(self::$testName, $createLabel['name']);

        return $createLabel['id'];
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAllLabels()
    {
        $allLabels = self::$Todoist->getAllLabels();
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
        $label = self::$Todoist->getLabel($labelId);
        $this->assertArrayHasKey('name', $label);
        $this->assertEquals($labelId, $label['id']);
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
        $labelNewName = self::$testName . '-Renamed';
        $label = self::$Todoist->updateLabel($labelId, ['name' => $labelNewName]);
        $this->assertArrayHasKey('name', $label);
        $this->assertEquals($labelNewName, $label['name']);
        $this->assertEquals($labelId, $label['id']);
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
        $success = self::$Todoist->deleteLabel($labelId);
        $this->assertTrue($success);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAllSharedLabels()
    {
        $allSharedLabels = self::$Todoist->getAllSharedLabels();
        $this->assertCount(0, $allSharedLabels);
    }
}
