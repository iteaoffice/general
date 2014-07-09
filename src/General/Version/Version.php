<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     Version
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Version;

use Zend\Http;
use Zend\Json\Json;

/**
 * Class to store and retrieve the version of General module
 */
final class Version
{
    /**
     * Zend Framework version identification - see compareVersion()
     */
    const VERSION = '1.1.1';
    /**
     * Github Service Identifier for version information is retrieved from
     */
    const VERSION_SERVICE_GITHUB = 'GITHUB';
    /**
     * The latest stable version Zend Framework available
     *
     * @var string
     */
    protected static $latestVersion;

    /**
     * Returns true if the running version of Zend Framework is
     * the latest (or newer??) than the latest tag on GitHub,
     * which is returned by self::getLatest().
     *
     * @return bool
     */
    public static function isLatest()
    {
        return self::compareVersion(self::getLatest()) < 1;
    }

    /**
     * Compare the specified Zend Framework version string $version
     * with the current Zend\Version\Version::VERSION of General module
     *
     * @param string $version A version string (e.g. "0.7.1").
     *
     * @return int -1 if the $version is older,
     *             0 if they are the same,
     *             and +1 if $version is newer.
     *
     */
    public static function compareVersion($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);

        return version_compare($version, strtolower(self::VERSION));
    }

    /**
     * Fetches the version of the latest stable release.
     *
     * By default, this uses the API provided by framework.zend.com for version
     * retrieval.
     *
     * If $service is set to VERSION_SERVICE_GITHUB, this will use the GitHub
     * API (v3) and only returns refs that begin with * 'tags/release-'.
     * Because GitHub returns the refs in alphabetical order, we need to reduce
     * the array to a single value, comparing the version numbers with
     * version_compare().
     *
     * @see    http://developer.github.com/v3/git/refs/#get-all-references
     * @link   https://api.github.com/repos/zendframework/zf2/git/refs/tags/release-
     * @link   http://framework.zend.com/api/zf-version?v=2
     *
     * @param string $service Version service with which to retrieve the version
     * @param Http\Client $httpClient HTTP client with which to retrieve the version
     *
     * @return string
     */
    public static function getLatest($service = self::VERSION_SERVICE_GITHUB, Http\Client $httpClient = null)
    {
        if (null !== self::$latestVersion) {
            return self::$latestVersion;
        }
        self::$latestVersion = 'not available';
        if (null === $httpClient && !ini_get('allow_url_fopen')) {
            trigger_error(
                sprintf(
                    'allow_url_fopen is not set, and no Zend\Http\Client ' .
                    'was passed. You must either set allow_url_fopen in ' .
                    'your PHP configuration or pass a configured ' .
                    'Zend\Http\Client as the second argument to %s.',
                    __METHOD__
                ),
                E_USER_WARNING
            );

            return self::$latestVersion;
        }
        $response = false;
        if ($service === self::VERSION_SERVICE_GITHUB) {
            $response = self::getLatestFromGithub($httpClient);
        } else {
            trigger_error(
                sprintf(
                    'Unknown version service: %s',
                    $service
                ),
                E_USER_WARNING
            );
        }
        if ($response) {
            self::$latestVersion = $response;
        }

        return self::$latestVersion;
    }

    /**
     * Get the latest version from Github
     *
     * @param Http\Client $httpClient Configured HTTP client
     *
     * @return string|null API response or false on error
     */
    protected static function getLatestFromGithub(Http\Client $httpClient = null)
    {
        $url = 'https://api.github.com/repos/debranova/general/git/refs/tags/release-';
        $url .= '?client_id=2b1088587b9820f33583&amp;client_secret=1738809f67b3fbf4198f2bc36ef54c52d6a3bb6c';
        if ($httpClient === null) {
            $context = stream_context_create(
                array(
                    'http' => array(
                        'user_agent' => sprintf('debranova-version/%s', self::VERSION),
                    ),
                )
            );
            $apiResponse = file_get_contents($url, false, $context);
        } else {
            $request = new Http\Request();
            $request->setUri($url);
            $httpClient->setRequest($request);
            $apiResponse = self::getApiResponse($httpClient);
        }
        if (!$apiResponse) {
            return false;
        }
        $decodedResponse = Json::decode($apiResponse, Json::TYPE_ARRAY);
        // Simplify the API response into a simple array of version numbers
        $tags = array_map(
            function ($tag) {
                return substr($tag['ref'], 18); // Reliable because we're
                // filtering on 'refs/tags/release-'
            },
            $decodedResponse
        );

        // Fetch the latest version number from the array
        return array_reduce(
            $tags,
            function ($a, $b) {
                return version_compare($a, $b, '>') ? $a : $b;
            }
        );
    }

    /**
     * Get the API response to a call from a configured HTTP client
     *
     * @param Http\Client $httpClient Configured HTTP client
     *
     * @return string|false API response or false on error
     */
    protected static function getApiResponse(Http\Client $httpClient)
    {
        try {
            $response = $httpClient->send();
        } catch (Http\Exception\RuntimeException $e) {
            return false;
        }
        if (!$response->isSuccess()) {
            return false;
        }

        return $response->getBody();
    }
}
