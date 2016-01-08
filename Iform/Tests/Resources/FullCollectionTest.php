<?php namespace Iform\Tests\Resources;

use Iform\Tests\Mocks\RequestHandlerStub;
use Iform\Tests\Mocks\TokenResolverStub;
use Iform\Resources\Base\FullCollection;

class FullCollectionTest extends \PHPUnit_Framework_TestCase {

    private $instance;
    private $stub;

    function setUp()
    {
        $this->instance = new FullCollection();
        $this->stub = new RequestHandlerStub(new TokenResolverStub());
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
