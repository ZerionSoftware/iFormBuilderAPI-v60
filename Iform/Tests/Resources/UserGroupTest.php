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

    public function tearDown()
    {
        parent::tearDown();
    }

}