<?php
/**
 * Todoist SDK for PHP – An unofficial Todoist PHP API library
 *
 * An open source PHP SDK that allows you to access the Todoist API from your
 * PHP application.
 *
 * @author  Fabian Beiner (fb@fabianbeiner.de)
 * @link    https://fabianbeiner.de
 * @license MIT License
 * @version 0.1.0 (2014-12-21)
 */

namespace FabianBeiner\Todoist;

use GuzzleHttp\Client;
use GuzzleHttp\Exception;

class Todoist {
    /**
     * The URL of the Todoist API.
     *
     * @var string
     */
    protected static $sApiUrl = 'https://todoist.com/API/';

    /**
     * The token to access the Todoist API.
     *
     * @var null|string
     */
    private $sToken = null;

    /**
     * The email of the user.
     *
     * @var null|string
     */
    private $sEmail = null;

    /**
     * The password of the user.
     *
     * @var null|string
     */
    private $sPassword = null;

    /**
     * All the valid languages (except 'en') Todoist supports.
     * (See http://todoist.com/API/help/#users, under /API/register)
     *
     * @var array
     */
    protected static $aValidLanguages = [
        'de',
        'fr',
        'ja',
        'pl',
        'pt_BR',
        'zh_CN',
        'es',
        'hi',
        'ko',
        'pt',
        'ru',
        'zh_TW'
    ];

    /**
     * The constructor.
     *
     * @param $sEmail
     * @param $sPassword
     *
     * @throws \Exception
     */
    public function __construct($sEmail, $sPassword) {
        $this->sEmail    = $sEmail;
        $this->sPassword = $sPassword;

        /**
         * Call the API to get a valid token.
         */
        $oCall = $this->loginUser();
        if (isset($oCall['api_token']) OR isset($oCall['token'])) {
            $this->sToken = $oCall['api_token'] ? : $oCall['token'];
        }
    }

    /**
     * Logins a user and returns a JSON object with all the user details.
     *
     * @return json
     * @throws \Exception
     */
    public function loginUser() {
        try {
            $oCall = self::callApi('login', [
                'email'    => $this->sEmail,
                'password' => $this->sPassword
            ]);
        } catch (\Exception $oException) {
            throw new \Exception('The API call failed: “' . $oException->getMessage() . '”');
        }

        if ($oCall->status !== 200) {
            throw new \Exception('The returned HTTP status code indicates an error');
        }
        elseif ($oCall->content === 'LOGIN_ERROR') {
            throw new \Exception('The provided login credentials aren’t valid');
        }

        return $oCall->content;
    }

    /**
     * See loginUser();
     */
    public function getUserDetails() {
        return $this->loginUser();
    }

    /**
     * Check, if the internally set login token is valid.
     *
     * @return bool
     * @throws \Exception
     */
    public function testToken() {
        if ($this->sToken === null) {
            return false;
        }

        try {
            $oCall = self::callApi('ping', ['token' => $this->sToken]);
        } catch (\Exception $oException) {
            throw new \Exception('The API call failed: “' . $oException->getMessage() . '”');
        }

        if ($oCall->status !== 200 OR $oCall->content !== 'ok') {
            return false;
        }

        return true;
    }

    /**
     * Returns all the different time zones Todoist supports.
     *
     * @return bool|json
     * @throws \Exception
     */
    public static function getTimezones() {
        try {
            $oCall = self::callApi('getTimezones');
        } catch (\Exception $oException) {
            throw new \Exception('The API call failed: “' . $oException->getMessage() . '”');
        }

        if ($oCall->status !== 200) {
            return false;
        }

        return $oCall->content;
    }

    /**
     * Register a user.
     *
     * @param $aOptions
     *        Required keys: email, full_name, password
     *        Optional keys: lang, timezone
     *
     * @return bool|string|json
     * @throws \Exception
     */
    public static function registerUser($aOptions) {
        $aDefault = [
            'email'     => null,
            'full_name' => null,
            'password'  => null,
            'lang'      => null,
            'timezone'  => null
        ];
        $aOptions = array_merge($aDefault, $aOptions);

        /**
         * Check if the provided options actually make sense…
         */
        if ($aOptions['email'] === null OR !filter_var($aOptions['email'], FILTER_VALIDATE_EMAIL)) {
            return '“email” is invalid.';
        }
        elseif ($aOptions['full_name'] === null) {
            return '“full_name” is invalid.';
        }
        elseif ($aOptions['password'] === null OR (strlen($aOptions['password']) < 5)) {
            return '“password” is invalid.';
        }
        elseif ($aOptions['lang'] === null OR !in_array($aOptions['lang'], self::$aValidLanguages)) {
            $aOptions['lang'] = 'en';
        }
        $aOptions = array_filter($aOptions);

        try {
            $oCall = self::callApi('register', $aOptions);
        } catch (\Exception $oException) {
            throw new \Exception('The API call failed: “' . $oException->getMessage() . '”');
        }

        if ($oCall->status !== 200) {
            return false;
        }

        /**
         * Most cases here should never happen, because I checked the values before…
         */
        switch ($oCall->content) {
            case 'ALREADY_REGISTRED':
                return 'This email is already registered.';
            case 'TOO_SHORT_PASSWORD':
                return 'The selected password is too short (min. 5 chars).';
            case 'INVALID_EMAIL':
                return 'The provided email is invalid.';
            case 'INVALID_TIMEZONE':
                return 'The provided time zone is invalid.';
            case 'INVALID_FULL_NAME':
                return 'The provided name is invalid.';
            case 'UNKNOWN_ERROR':
                return 'An unknown error occurred.';
            default:
                return $oCall->content;
        }
    }

    /**
     * Delete a user.
     *
     * @param        $sToken
     * @param        $sPassword
     * @param int    $bDeleteImmediately
     * @param string $sReason
     *
     * @return bool
     * @throws \Exception
     */
    public static function deleteUser($sToken, $sPassword, $bDeleteImmediately = 0, $sReason = 'Deleted via Todoist SDK for PHP') {
        $aOptions = [
            'token'             => $sToken,
            'current_password'  => $sPassword,
            'in_background'     => ($bDeleteImmediately ? 0 : 1),
            'reason_for_delete' => $sReason
        ];
        try {
            $oCall = self::callApi('deleteUser', $aOptions);
        } catch (\Exception $oException) {
            throw new \Exception('The API call failed: “' . $oException->getMessage() . '”');
        }

        if ($oCall->status !== 200 OR $oCall->content !== 'ok') {
            return false;
        }

        return true;
    }

    /**
     * Calls the API.
     *
     * @param       $sResource
     * @param array $aBody
     *
     * @return object
     */
    private static function callApi($sResource, $aBody = []) {
        $oClient   = new Client(['base_url' => self::$sApiUrl]);
        $oResponse = $oClient->post($sResource, [
            'body' => $aBody
        ]);

        /**
         * Which type of content did we receive?
         */
        if (explode(';', $oResponse->getHeader('Content-Type'))[0] === 'application/json') {
            $sContent = $oResponse->json();
        }
        else {
            $sContent = (string)$oResponse->getBody();
        }

        return (object)[
            'status'  => $oResponse->getStatusCode(),
            'content' => $sContent
        ];
    }
}