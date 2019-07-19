<?php

namespace FabianBeiner\Todoist\Tests;

use Exception;
use FabianBeiner\Todoist\TodoistException;
use PHPUnit\Framework\TestCase;

/**
 * Class TodoistExceptionTest.
 */
class TodoistExceptionTest extends TestCase
{
    public function testInstantiation()
    {
        $exception = new TodoistException();
        $this->assertInstanceOf(TodoistException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }

    /**
     * @expectedException \FabianBeiner\Todoist\TodoistException
     * @expectedExceptionMessage Some message.
     */
    public function testTodoistExceptionThrowable()
    {
        throw new TodoistException('Some message.');
    }
}
