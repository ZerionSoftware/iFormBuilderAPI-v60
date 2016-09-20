<?php namespace Iform\Tests\Resources;

use Iform\Resources\Record;
use Iform\Resources\Base\BatchValidator;

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
    public function testFetchAllShouldFailWithNoParams()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testFetchAllWithLimitSetPasses()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->first(100)->fetchAll();

        $this->assertCount(100, $response);
    }

    public function testFetchesIncrementOfCollection()
    {
        $resource = $this->instantiate($this->stub);
        $response =  json_decode($resource->first(100)->next(900)->fetchAll());

        $this->assertCount(101, $response);
        $this->assertEquals(1000, array_pop($response));
    }

    public function testFetchsLastInCollection()
    {
        $resource = $this->instantiate($this->stub);
        $response =  $resource->first(100)->last(10)->fetchAll();

//       $this->assertCount(101, $response);
//       $this->assertEquals(1000, array_pop($response));
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

    public function testDeletesWithGrammar()
    {
//        $values = [
//            [
//                'id' => '161259'
//            ],
//            [
//                'id' => '161256'
//            ]
//        ];
//
//        $grammar = ['fields' => 'id > 1'];
//        $test = BatchValidator::combine($values, $grammar);
//
//        $this->mock->shouldReceive('delete')
//                   ->once()
//                   ->with("/" . static::$pattern . "/", $test);
//
//        $this->resource->where('id > 1')->deleteAll($values);
    }

    public function testDeletesWithByGrammarWithNoParams()
    {
        $test = "https://ssalinasdemo.iformbuilder.com/exzact/api/v60/profiles/161521/pages/7777777/records?fields=id+%3E+1";

        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with($test, []);

        $this->resource->where('id > 1')->deleteAll([]);
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