<?php

declare(strict_types=1);

namespace FabianBeiner\Todoist\Tests\FabianBeiner\Todoist;

use GuzzleHttp\Psr7\Uri;

/**
 * Class TodoistClientTest.
 */
class TodoistClientTest extends AbstractTodoistTestCase
{
    /**
     * Test the configuration.
     */
    public function testConfiguration()
    {
        $config = self::$Todoist->getConfig();

        $baseUri = $config['base_uri'];
        $headers = $config['headers'];

        $this->assertInstanceOf(Uri::class, $baseUri);
        $this->assertEquals('api.todoist.com', $baseUri->getHost());
        $this->assertEquals('/rest/v2/', $baseUri->getPath());
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertEquals(sprintf('Bearer %s', self::$apiToken), $headers['Authorization']);
    }
}
