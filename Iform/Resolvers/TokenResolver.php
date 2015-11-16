<?php namespace Iform\Resolvers;

use Iform\Resolvers\RequestHandler;
use Iform\Creds\Auth;
use Iform\Resolvers\Jwt;

/**
 * @category Authentication
 * @package  iForm\Authentication
 * @author   Seth Salinas <ssalinas@zerionsoftware.com>
 * @license  http://opensource.org/licenses/MIT
 */
class TokenResolver implements Auth {

    /**
     * This value has a maximum of 10 minutes
     *
     * @var int
     */
    private static $exp = 600;
    /**
     * Credentials - secret.  See instructions for acquiring credentials
     *
     * @var string
     */
    private static $secret;
    /**
     * Credentials - client key.  See instructions for acquiring credentials
     *
     * @var string
     */
    private static $client;
    /**
     * oAuth - https://ServerName.iformbuilder.com/exzact/api/oauth/token
     *
     * @var string
     */
    private static $endpoint;

    /**
     *
     */
    function __construct()
    {
        $this->setCredentials(Auth::CLIENT, Auth::SECRET, Auth::OAUTH);
    }

    public function setCredentials($client, $secret, $endpoint)
    {
        self::$client = $client;
        self::$secret = $secret;
        self::$endpoint = $endpoint;
    }
    /**
     * @param string $client_key
     * @param string $client_secret
     *
     * @return string
     */
    private function encodeAssertion($client_key, $client_secret)
    {
        $iat = time();
        $payload = array(
            "iss" => $client_key,
            "aud" => self::$endpoint,
            "exp" => $iat + self::$exp,
            "iat" => $iat
        );

        return $this->encoder($payload, $client_secret);
    }

    private function encoder($payload, $client_secret)
    {
        return JWT::encode($payload, $client_secret);
    }
    /**
     * api OAuth endpoint
     *
     * @param string $url
     *
     * @return Boolean
     */
    private function isValid($url)
    {
        return strpos($url, "exzact/api/oauth/token") !== false;
    }

    /**
     * Validate Endpoint
     *
     * @throws \Exception
     */
    private function validateEndpoint()
    {
        if (empty(self::$endpoint) || ! $this->isValid(self::$endpoint)) {
            throw new \Exception('Invalid url: Valid format https://SERVER_NAME.iformbuilder.com/exzact/api/oauth/token');
        }
    }

    /**
     * Format Params
     *
     * @return string
     */
    private function getParams()
    {
        return array("grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
                     "assertion"  => $this->encodeAssertion(self::$client, self::$secret));
    }

    /**
     * @param RequestHandler $iForm
     *
     * @return string
     * @throws \Exception
     */
    public function getToken(RequestHandler $iForm)
    {
        try {
            $this->validateEndpoint();
            $params = $this->getParams();
            $result = $this->check($iForm->create(self::$endpoint)
                                         ->with(http_build_query($params)));
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        return $result;
    }

    /**
     * Check results
     *
     * @param $results
     *
     * @return string token || error msg
     */
    private function check($results)
    {
        $token = json_decode($results, true);

        return isset($token['access_token']) ? $token['access_token'] : $token['error'];
    }
}