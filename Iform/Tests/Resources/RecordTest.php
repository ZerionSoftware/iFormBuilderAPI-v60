<?php

require_once "BaseResourceTest.php";

use Iform\Resources\Record;

class RecordTest extends BaseResourceTest {

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/pages\/" . static::$id . "\/records";

        $this->setIdentifier(static::$id);
        $this->setResourceType('Iform\Resources\Record\Records');

        parent::setUp();
    }

    /**
     * Fetch all should fail
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testFetchAllShouldFail()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testFetchAllWithLimitSetPasses()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->first(1000)->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testUpdateAll()
    {
        $values = [
            [
                'id'     => '161259',
                'fields' => [
                    'element_name' => "test"
                ]
            ]
        ];

        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $this->resource->updateAll($values);
    }

    public function testDeleteAll()
    {
        $values = [
            [
                'id' => '161259'
            ],
            [
                'id' => '161256'
            ]
        ];

        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $this->resource->deleteAll($values);
    }

    /***************************************************************
     * Assignments
     ***************************************************************/

    public function testFetchAllForAssignment()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->assignments(static::$id)->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    /**
     * Updating assignments
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testUpdateFailsForAssignment()
    {
        $resource = $this->instantiate($this->stub);
        $resource->assignments(static::$id)
                 ->update(123123, []);
    }

    /**
     * Updating assignments
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testUpdateAllFailsForAssignment()
    {
        $resource = $this->instantiate($this->stub);
        $resource->assignments(static::$id)
                 ->updateAll([]);
    }

    function tearDown()
    {
        parent::tearDown();
    }
}