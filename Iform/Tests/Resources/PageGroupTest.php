<?php namespace Iform\Tests\Resources;

class PageGroupTest extends BaseResourceTest {

    public function setUp()
    {
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/page_groups";

        $this->setResourceType('Iform\Resources\Page\PageGroup');
        parent::setUp();
    }

    public function testFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->fetchAll();
        //collection queries should be array
        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testAssignmentFetch()
    {
        $resource = $this->instantiate($this->stub);
        $response = json_decode($resource->assignment(1)->fetch(999999), true);

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

    public function testReturnsCorrectLabel() {
        $resource = $this->instantiate($this->stub);

        $this->assertEquals(array('id', 'pages', 'global_id', 'version', 'name', 'created_date'), $resource->getAllFields());
    }

    public function tearDown()
    {
        parent::tearDown();
    }
}
