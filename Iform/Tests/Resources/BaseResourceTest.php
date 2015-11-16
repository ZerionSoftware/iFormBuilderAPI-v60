<?php

use \Mockery as m;
use Iform\Tests\Resources\RequestHandlerStub;
use Iform\Tests\Resources\TokenResolverStub;

require_once 'RequestsStub.php';


class BaseResourceTest extends \PHPUnit_Framework_TestCase {

    /**
     * Resource under test
     *
     * @var object
     */
    public $resource;
    /**
     * Some resource need ids
     *
     * @var
     */
    public $identifier;
    /**
     * Mockery obj
     * @var object
     */
    public $mock;

    function setUp()
    {
        $instance = ucfirst($this->resource);
        $this->mock = m::mock('Iform\Resolvers\RequestHandler');

        if (! empty($this->identifier)) {
            $this->resource = new $instance(new RequestHandlerStub(new TokenResolverStub()), $this->identifier);
        } else {
            $this->resource = new $instance(new RequestHandlerStub(new TokenResolverStub()));
        }
    }

    function setResourceType($resource)
    {
        $this->resource = $resource;
    }

    function setIdentifier($id)
    {
        $this->identifier = $id;
    }

    public function testFetch()
    {
        $response = json_decode($this->resource->fetch(999999), true);
        $this->assertArrayHasKey('id', ($response));
    }

    function tearDown()
    {
        m::close();
        unset($this->resource);
    }
}
