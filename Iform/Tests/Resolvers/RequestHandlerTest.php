<?php

use Iform\Resolvers\RequestHandler;
use Iform\Tests\Mocks\TokenResolverStub;
use \Mockery as m;


class RequestHandlerTest extends \PHPUnit_Framework_TestCase {

    private $mockResolver;

    function setUp()
    {
        $this->mockResolver = m::mock('Iform\Resolvers\TokenResolver');
    }

    public function testGetReturnsValidHTTPCode()
    {
//        $handler = new RequestHandler(new TokenResolverStub());
//        $request = $handler->read("https://www.iformbuilder.com/", array(), true);
//        var_dump($request);
//
//        $this->assertContains("200", $request['header']);
    }

    public function testFetchesToken()
    {
        $this->mockResolver->shouldReceive('getToken')->once();

        //should use cached token
        $resolver = new RequestHandler($this->mockResolver);
        $resolver->read("test.com");
    }

    public function testFetchesNewTokenIfExpired()
    {
        $this->mockResolver->shouldReceive('getToken')->once();

        //should use cached token
        $resolver = new RequestHandler($this->mockResolver);

        $resolver->read("test.com");
    }

    public function testGetRequestHaveHTTPEncodedParams()
    {
        $handler = m::mock('Iform\Resolvers\RequestHandler');
        $url = "https://www.iformbuilder.com/?test=seth+salinas";

        $handler->shouldReceive('read')->once();
        $handler->shouldReceive('baseCurl')->with($url);

        $handler->read("https://www.iformbuilder.com/", array('test'=>'seth salinas'), true);

    }

    function tearDown()
    {
        m::close();
        unset($this->mockResolver);
    }
}
