<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests\FabianBeiner\Todoist;

use FabianBeiner\Todoist\TodoistClient;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTodoistTestCase.
 */
abstract class AbstractTodoistTestCase extends TestCase
{
    /**
     * @var ?string API taken from env.
     */
    protected static ?string $apiToken;

    /**
     * @var ?string Exemplary name for projects, labels, and other.
     */
    protected static ?string $testName;

    /**
     * @var ?TodoistClient Todoist Client.
     */
    protected static ?TodoistClient $Todoist;

    /**
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public static function setUpBeforeClass(): void
    {
        self::$apiToken = getenv('TODOIST_TOKEN');
        self::$testName = 'PHPUnit-' . uniqid();
        self::$Todoist  = new TodoistClient(self::$apiToken);
    }
}
