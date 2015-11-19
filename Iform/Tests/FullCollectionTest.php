<?php namespace Iform\Tests;

require_once 'Resources/RequestsStub.php';

use Iform\Resources\Base\FullCollection;
use Iform\Tests\Resources\RequestHandlerStub;
use Iform\Tests\Resources\TokenResolverStub;

class FullCollectionTest extends \PHPUnit_Framework_TestCase {

    private $stub;
    private $instance;

    function setUp()
    {
        $this->stub = new RequestHandlerStub(new TokenResolverStub());
        $this->instance = new FullCollection();
    }

    public function testFetchCollection()
    {
        $response = $this->instance->fetchCollection($this->stub, "iformbuilder.com");
        $this->assertJson($response);
    }

    /**
     * @expectedException \Exception
     */
    public function testFetchCollectionValidates()
    {
        $this->instance->fetchCollection($this->stub, "");
    }

    function tearDown()
    {
        unset($this->stub);
        unset($this->instance);
    }
}