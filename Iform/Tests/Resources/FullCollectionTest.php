<?php namespace Iform\Tests\Resources;

use Iform\Tests\Mocks\RequestHandlerStub;
use Iform\Tests\Mocks\TokenResolverStub;
use Iform\Resources\Base\FullCollection;
use Iform\Resources\Element\Elements;
use Iform\Resolvers\RequestHandler;
use Iform\Resolvers\TokenResolver;


class FullCollectionTest extends \PHPUnit_Framework_TestCase {

    private $instance;
    private $stub;

    function setUp()
    {
        $this->instance = new FullCollection();
        $this->stub = new RequestHandlerStub(new TokenResolverStub());
    }

    public function testFetchFullCollection()
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

    public function testRetrievesLastItems()
    {
        $url = SERVER . PROFILE . '/pages/' . PAGE . '/elements';
        $response = $this->instance->fetchLastInCollection($this->stub, $url, array('offset' => 10));

        $test = array_diff(range(990, 1000), $response);
        $this->assertEmpty($test);
    }

    public function testRetrievesFirstItems()
    {
        $url = SERVER . PROFILE . '/pages/' . PAGE . '/elements';
        $response = $this->instance->fetchIncrement($this->stub, $url, array('offset' => 900));

        $this->assertEquals(json_decode($response, true), range(900, 1000));
    }

    public function testGeneratesCountInHeader()
    {
        $url = SERVER . PROFILE . '/pages/' . PAGE . '/elements';
        $this->instance->fetchIncrement($this->stub, $url, array('offset' => 900));

        $this->assertEquals(1000, $this->instance->getCountFromHeader());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFetchIncrementRequestOffsetInParamsen()
    {
        $url = SERVER . PROFILE . '/pages/' . PAGE . '/elements';
        $this->instance->fetchIncrement($this->stub, $url, array());
    }
    function tearDown()
    {
        unset($this->stub);
        unset($this->instance);
    }
}
