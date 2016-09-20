<?php namespace Iform\Tests\Resources;

use Iform\Resources\User;

class UserTest extends BaseResourceTest {

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/users";

        $this->setResourceType('Iform\Resources\User\Users');
        parent::setUp();
    }

    public function testFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testUpdateAll()
    {
        $values = [
            [
                'id'         => '161259',
                'first_name' => 'Seth'
            ],
            [
                'id'         => '161256',
                'first_name' => 'Anthony'
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
     * Page Assignments
     ***************************************************************/

    public function testPageAssignmentsFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->pageAssignment(static::$id)
                             ->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testPageAssignmentsUpdateAll()
    {
        $values = [
            [
                'id'         => '161259',
                'first_name' => 'Seth'
            ],
            [
                'id'         => '161256',
                'first_name' => 'Anthony'
            ]
        ];

        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $this->resource->updateAll($values);
    }

    public function testPageAssignmentsDeleteAll()
    {
        $values = [
            [
                'page_id' => '161259'
            ],
            [
                'page_id' => '161256'
            ]
        ];

        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $this->resource->deleteAll($values);
    }

    /***************************************************************
     * RecordAssignments
     ***************************************************************/

    public function testCreateRecordAssignment()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->recordAssignment(static::$id)
                             ->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testRecordAssignmentsFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->recordAssignment(static::$id)
                             ->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testRecordAssignmentsDeleteAll()
    {
        $values = [
            "page_id"   => 790777,
            "record_id" => 4
        ];

        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $this->resource->recordAssignment(static::$id)
                       ->create($values);
    }


    public function testGetAllFieldReturnPageAssignmentLabels()
    {
        $resource = $this->instantiate($this->stub);
        $resource->pageAssignment(static::$id);

        $this->assertEquals(array('can_collect', 'can_view'), $resource->getAllFields());
    }

    public function testGetAllFieldReturnRecordAssignmentLabels()
    {
        $resource = $this->instantiate($this->stub);
        $resource->recordAssignment(static::$id);

        $this->assertEquals(array("id","page_id","record_id"), $resource->getAllFields());
    }

    public function testGetAllFieldReturnBaseUserLabels()
    {
        $resource = $this->instantiate($this->stub);

        $this->assertEquals(array(
            "id", "username", "global_id", "first_name", "last_name",
            "email", "created_date", "is_locked", "roles"
        ), $resource->getAllFields());
    }

    function tearDown()
    {
        parent::tearDown();
    }
}