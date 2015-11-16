<?php namespace Iform\Tests;

use Iform\Creds\Auth;
use Iform\Resolvers\RequestHandler;
use Iform\Resolvers\TokenResolver;

class TokenResolverTest extends \PHPUnit_Framework_TestCase {

    /**
     * Token Resolver
     * @var Object
     */
    private $resolver;
    /**
     * Request Handler
     * @var Object
     */
    private $handler;

    function setup()
    {
        $this->resolver = new TokenResolver();
        $this->handler = new RequestHandler($this->resolver);
    }

    function testTokenWillFailWithInvalidUrl()
    {
        $this->resolver->setCredentials(Auth::CLIENT, Auth::SECRET, "test.com");
        $result = trim($this->resolver->getToken($this->handler));

        $this->assertStringStartsWith('Invalid', $result);
    }

    function testTokenFailsWithInvalidClient()
    {
        $this->resolver->setCredentials("fail", Auth::SECRET, Auth::OAUTH);
        $result = $this->resolver->getToken($this->handler);

        $this->assertEquals("invalid_client", $result);
    }

    function testTokenFailsWithInvalidSecret()
    {
        $this->resolver->setCredentials(Auth::CLIENT, "fail", Auth::OAUTH);
        $result = $this->resolver->getToken($this->handler);

        $this->assertEquals("invalid_grant", $result);
    }

    function testGetTokenReturnsValidToken()
    {
        $this->resolver->setCredentials(Auth::CLIENT, Auth::SECRET, Auth::OAUTH);
        $result = trim($this->resolver->getToken($this->handler));

        $this->assertEquals(40, strlen($result));
    }

    function tearDown()
    {
        unset($this->resolver);
        unset($this->handler);
    }
}
