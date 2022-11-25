<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests\FabianBeiner\Todoist;

use Exception;
use FabianBeiner\Todoist\TodoistException;

/**
 * Class TodoistExceptionTest.
 */
class TodoistExceptionTest extends AbstractTodoistTestCase
{
    public function testInstantiation()
    {
        $exception = new TodoistException();
        $this->assertInstanceOf(TodoistException::class, $exception);
        $this->assertInstanceOf(Exception::class, $exception);
    }

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function testTodoistExceptionThrowable()
    {
        $this->expectException('\FabianBeiner\Todoist\TodoistException');
        $this->expectExceptionMessage('Some message.');

        throw new TodoistException('Some message.');
    }
}
