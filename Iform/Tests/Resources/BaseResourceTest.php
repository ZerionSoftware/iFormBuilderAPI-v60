<?php

use \Mockery as m;
use Iform\Tests\Resources\RequestHandlerStub;
use Iform\Tests\Resources\TokenResolverStub;

require_once 'RequestsStub.php';


/**
 * Class BaseResourceTest
 *
 * Testing base features for all implemented resources
 */
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
    /**
     * Double obj
     * @var object
     */
    public $stub;
    /**
     * Iform resource
     * @var String
     */
    private $resourceType;
    /**
     * URL regex
     * @var string
     */
    protected static $pattern = "";
    /**
     * identifier
     * @var int
     */
    protected static $id = 0;

    function setUp()
    {
        $this->mock = m::mock('Iform\Resolvers\RequestHandler');
        $this->stub = new RequestHandlerStub(new TokenResolverStub());

        //most resources wll be testing commands - setup mock
        $this->resource = $this->instantiate($this->mock);
    }

    function setResourceType($resource)
    {
        $this->resourceType = $resource;
    }

    function setIdentifier($id)
    {
        $this->identifier = $id;
    }

    public function testFetch()
    {
        $resource = $this->instantiate($this->stub);
        $response = json_decode($resource->fetch(999999), true);

        $this->assertArrayHasKey('id', ($response));
    }

    function testCreateCommand()
    {
        $values = ["name" => "System Grade"];
        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $this->resource->create($values);
    }

    function testUpdateCommand()
    {
        $id = 123123;
        $pattern = static::$pattern . "\/" . $id;
        $values = ["name" => "System Grade"];
        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . $pattern . "/", $values);


        $this->resource->update($id, $values);
    }

    function testDeleteCommand()
    {
        $id = 123123;
        $pattern = static::$pattern . "\/" . $id;
        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . $pattern . "/");

        $this->resource->delete($id);
    }

    protected function instantiate($dependencies)
    {
        $instance = ucfirst($this->resourceType);

        return ! empty($this->identifier) ? new $instance($dependencies, $this->identifier) : new $instance($dependencies);
    }

    function tearDown()
    {
        m::close();
        static::$id = "";
        static::$pattern = "";

        unset($this->stub);
        unset($this->resourceType);
        unset($this->resource);
        unset($this->identifier);
    }
}
