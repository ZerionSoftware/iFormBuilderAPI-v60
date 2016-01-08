<?php namespace Iform\Tests\Resources;

use Iform\Resources\Profile;

class ProfileTest extends BaseResourceTest {

    function setUp()
    {
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles";

        $this->setResourceType('Iform\Resources\Profile\Profile');
        parent::setUp();
    }

    /**
     * N/A
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     * @expectedExceptionMessage Cannot delete profiles through the api
     */
    function testDeleteCommand()
    {
        $this->resource->delete(1);
    }

    function tearDown()
    {
        parent::tearDown();
    }
}