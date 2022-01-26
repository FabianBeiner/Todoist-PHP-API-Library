<?php
/**
 * Todoist-PHP-API-Library
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 * @version 1.2.0 <2022-01-26>
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class TodoistClient.
 *
 * @package FabianBeiner\Todoist
 */
class TodoistClient extends GuzzleClient
{
    /*
     * Use Traits.
     */
    use TodoistCommentsTrait;
    use TodoistHelpers;
    use TodoistLabelsTrait;
    use TodoistProjectsTrait;
    use TodoistSectionsTrait;
    use TodoistTasksTrait;

    /**
     * @var string URL of the Todoist REST API.
     */
    protected $restApiUrl = 'https://api.todoist.com/rest/v1/';

    /**
     * @var string 2-letter code that specifies the language for due_string parameters.
     */
    protected $defaultInputLanguage = 'en';

    /**
     * @var array All valid languages.
     */
    protected $validLanguages = ['en', 'da', 'pl', 'zh', 'ko', 'de', 'pt', 'ja', 'it', 'fr', 'sv', 'ru', 'es', 'nl'];

    /**
     * TodoistClient constructor.
     *
     * @param string $apiToken     API token to access the Todoist API.
     * @param string $languageCode 2-letter code that specifies the language for due_string parameters.
     * @param array  $guzzleConf   Configuration to be passed to Guzzle client.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     */
    public function __construct(string $apiToken, string $languageCode = 'en', array $guzzleConf = [])
    {
        $apiToken = trim($apiToken);
        if (40 !== strlen($apiToken)) {
            throw new TodoistException('The provided API token is invalid.');
        }

        $languageCode = strtolower(trim($languageCode));
        if (in_array($languageCode, $this->validLanguages)) {
            $this->defaultInputLanguage = $languageCode;
        }

        $defaults = [
            'headers'     => [
                'Accept-Encoding' => 'gzip',
            ],
            'http_errors' => false,
            'timeout'     => 10,
        ];

        $config                             = array_replace_recursive($defaults, $guzzleConf);
        $config['base_uri']                 = $this->restApiUrl;
        $config['headers']['Authorization'] = sprintf('Bearer %s', $apiToken);

        parent::__construct($config);
    }
}
