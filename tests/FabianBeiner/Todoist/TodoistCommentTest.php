<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests;

/**
 * Class TodoistCommentTest.
 */
class TodoistCommentTest extends AbstractTodoistTestCase
{
    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return string ID of the created comment for task.
     */
    public function testCreateCommentForTask(): string
    {
        $createTask = self::$Todoist->createTask(self::$testName);
        $this->assertArrayHasKey('id', $createTask);
        $this->assertEquals(self::$testName, $createTask['content']);

        $createComment = self::$Todoist->createCommentForTask($createTask['id'], self::$testName);
        $this->assertArrayHasKey('id', $createComment);
        $this->assertArrayHasKey('content', $createComment);
        $this->assertEquals(self::$testName, $createComment['content']);

        return $createComment['id'];
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAllCommentsByTask()
    {
        $createTask = self::$Todoist->createTask(self::$testName);
        $this->assertArrayHasKey('id', $createTask);
        $this->assertEquals(self::$testName, $createTask['content']);

        $createComment = self::$Todoist->createCommentForTask($createTask['id'], self::$testName);
        $this->assertArrayHasKey('id', $createComment);
        $this->assertArrayHasKey('content', $createComment);
        $this->assertEquals(self::$testName, $createComment['content']);

        $allComments = self::$Todoist->getAllCommentsByTask($createTask['id']);
        $this->assertArrayHasKey('id', $allComments[0]);
        $this->assertCount(1, $allComments);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAllCommentsByProject()
    {
        $createProject = self::$Todoist->createProject(self::$testName);
        $this->assertArrayHasKey('id', $createProject);
        $this->assertEquals(self::$testName, $createProject['name']);

        $createComment = self::$Todoist->createCommentForProject($createProject['id'], self::$testName);
        $this->assertArrayHasKey('id', $createComment);
        $this->assertArrayHasKey('content', $createComment);
        $this->assertEquals(self::$testName, $createComment['content']);

        $allComments = self::$Todoist->getAllCommentsByProject($createProject['id']);
        $this->assertArrayHasKey('id', $allComments[0]);
        $this->assertCount(1, $allComments);
    }

    /**
     * @depends testCreateCommentForTask
     *
     * @param $commentId
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetComment($commentId)
    {
        $comment = self::$Todoist->getComment($commentId);
        $this->assertArrayHasKey('content', $comment);
        $this->assertEquals($commentId, $comment['id']);
    }

    /**
     * @depends testCreateCommentForTask
     *
     * @param $commentId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateComment($commentId)
    {
        $commentNewContent = self::$testName . '-Renamed';
        $comment = self::$Todoist->updateComment($commentId, $commentNewContent);
        $this->assertArrayHasKey('content', $comment);
        $this->assertEquals($commentNewContent, $comment['content']);
        $this->assertEquals($commentId, $comment['id']);
    }

    /**
     * @depends testCreateCommentForTask
     *
     * @param $commentId
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteComment($commentId)
    {
        $success = self::$Todoist->deleteComment($commentId);
        $this->assertTrue($success);
    }
}
