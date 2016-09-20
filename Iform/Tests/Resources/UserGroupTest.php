<?php namespace Iform\Tests\Resources;

use Iform\Resources\User\UserGroup;

class UserGroupTest extends BaseResourceTest {

    public function setUp()
    {
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/user_groups";

        $this->setResourceType('Iform\Resources\User\UserGroup');
        parent::setUp();
    }

    public function testFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->fetchAll();
        //collection queries should be array
        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testGetAllFieldReturnsCorrectLabels()
    {
        $resource = $this->instantiate($this->stub);
        $str = 'id,users,global_id,version,name,created_date';
//        var_dump($resource->withAllFields());
//        $this->assertEquals($str, $resource->withAllFields());
    }

    public function tearDown()
    {
        parent::tearDown();
    }

}