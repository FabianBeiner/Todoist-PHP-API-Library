<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests\FabianBeiner\Todoist;

/**
 * Class TodoistTaskTest.
 */
class TodoistTaskTest extends AbstractTodoistTestCase
{
    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return string ID of the created task.
     */
    public function testCreateTask(): string
    {
        $createTask = self::$Todoist->createTask(self::$testName);

        $this->assertArrayHasKey('id', $createTask);
        $this->assertEquals(self::$testName, $createTask['content']);

        return $createTask['id'];
    }

    /**
     * @depends testCreateTask
     *
     * @param $taskId
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetTask($taskId)
    {
        $task = self::$Todoist->getTask($taskId);
        $this->assertArrayHasKey('id', $task);
        $this->assertArrayHasKey('content', $task);
        $this->assertEquals($taskId, $task['id']);
        $this->assertEquals(self::$testName, $task['content']);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return void
     */
    public function testGetAllTasks(): void
    {
        $allTasks = self::$Todoist->getAllTasks();
        $this->assertArrayHasKey('id', $allTasks[0]);
    }

    /**
     * @depends testCreateTask
     *
     * @param $taskId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateTask($taskId)
    {
        $taskNewContent = self::$testName . '-Renamed';
        $taskUpdated    = self::$Todoist->updateTask($taskId, ['content' => $taskNewContent]);
        $this->assertArrayHasKey('content', $taskUpdated);
        $this->assertEquals($taskNewContent, $taskUpdated['content']);
        $this->assertEquals($taskId, $taskUpdated['id']);
    }

    /**
     * @depends testCreateTask
     *
     * @param $taskId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCloseTask($taskId)
    {
        $success = self::$Todoist->closeTask($taskId);
        $this->assertTrue($success);
    }

    /**
     * @depends testCreateTask
     *
     * @param $taskId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testReopenTask($taskId)
    {
        $success = self::$Todoist->reopenTask($taskId);
        $this->assertTrue($success);
    }

    /**
     * @depends testCreateTask
     *
     * @param $taskId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteTask($taskId)
    {
        $success = self::$Todoist->deleteTask($taskId);
        $this->assertTrue($success);
    }
}
