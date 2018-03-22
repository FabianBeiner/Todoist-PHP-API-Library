<?php

namespace FabianBeiner\Todoist\Tests;

use FabianBeiner\Todoist\TodoistClient as Client;
use FabianBeiner\Todoist\TodoistException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class TodoistClientTest extends TestCase
{
    private $apiToken;

    public function setUp()
    {
        $this->apiToken = 'mmZTXgqtBpWRVqIBqviqLcoyvs0PWHKCXtfr53gZ';

        parent::setUp();
    }

    /**
     * @dataProvider clientOptions
     */
    public function testConfiguration(array $options = [])
    {
        $client = new Client($this->apiToken, $options);
        $config = $client->getConfig();

        $baseUri = $config['base_uri'];
        $headers = $config['headers'];

        $this->assertInstanceOf(Uri::class, $baseUri);
        $this->assertEquals('beta.todoist.com', $baseUri->getHost());
        $this->assertEquals('/API/v8/', $baseUri->getPath());
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertEquals(sprintf('Bearer %s', $this->apiToken), $headers['Authorization']);

        if (isset($options['headers']['X-CUSTOM-HEADER'])) {
            $this->assertArrayHasKey('X-CUSTOM-HEADER', $headers);
        }
    }

    /**
     * @dataProvider clientEmptyOptions
     * @expectedException \FabianBeiner\Todoist\TodoistException
     */
    public function testEmptyConfiguration($authToken, array $options = [])
    {
        $client = new Client($authToken, $options);
        $config = $client->getConfig();

        $this->assertInstanceOf(Uri::class, $config['base_uri']);
    }

    public function testRequestGet()
    {
        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->willReturn(new Response());

        $this->assertInstanceOf(ResponseInterface::class, $client->get('uri'));
    }

    public function testGetException()
    {
        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $client
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new TodoistException('some error'));

        $this->expectException(TodoistException::class);
        $this->expectExceptionMessage('some error');
        $client->get('uri');
    }

//    /**
//     * @dataProvider getProxyMethods
//     */
//    public function testGetProxies($methodName)
//    {
//        $client = $this->getMockBuilder(Client::class)
//            ->setMethods(['get'])
//            ->disableOriginalConstructor()
//            ->getMock();
//        $client
//            ->expects($this->once())
//            ->method('get')
//            ->willReturn(new Response());
//
//        $this->assertInstanceOf(ResponseInterface::class, $client->{$methodName}());
//    }
//
//
//    public function getProxyMethods()
//    {
//        return [
//            ['getAllProjects'],
//            ['getAllLabels'],
//            ['getAllTasks'],
//        ];
//    }

    public function clientOptions()
    {
        return [
            [],
            [
                [
                    'base_uri' => 'https://google.com',
                    'headers'  => [
                        'X-CUSTOM-HEADER' => 'test',
                        'Authorization'   => 'Basic someuser',
                    ],
                ],
            ],
        ];
    }

    public function clientEmptyOptions()
    {
        return [
            [''],
            ['authtoken'],
        ];
    }
}
