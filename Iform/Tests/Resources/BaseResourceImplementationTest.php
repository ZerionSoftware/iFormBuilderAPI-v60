<?php namespace Iform\Tests\Resources;

use Iform\Resources\Element\Elements;
use Iform\Tests\Resources\BaseResourceTest;

/**
 * Test base method behave for implementing resource
 * using element to test
 */
class BaseResourceImplementationTest extends BaseResourceTest {

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/pages\/" . static::$id . "\/elements";

        $this->setIdentifier(static::$id);
        $this->setResourceType('Iform\Resources\Element\Elements');
        parent::setUp();
    }

    public function testRetrievesFirstItems()
    {
        // 797449 - 200 elements
        $this->setIdentifier(797455);
        $resource = $this->instantiate($this->liveTest());
        $response = $resource->first(10)
                             ->fetchAll();

        $this->assertCount(10, json_decode($response, true));
    }

    public function testFiltersResponseByGrammar()
    {
        $test = 'name(="my_element21")';
        $this->setIdentifier(797455);

        $resource = $this->instantiate($this->liveTest());
        $response = json_decode($resource->where($test)->fetchAll(), true);

        //collection queries should be array
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertEquals($response[0]['name'], "my_element21");
    }

    public function testFiltersResponseByMultipleGrammars()
    {
        $test = 'name(="my_element21"),created_date:<';
        $this->setIdentifier(797455);

        $resource = $this->instantiate($this->liveTest());
        $response = json_decode($resource->where($test)->fetchAll(), true);

        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('created_date', $response[0]);
        $this->assertEquals($response[0]['name'], "my_element21");
    }

    function tearDown()
    {
        static::$pattern = "";
        parent::tearDown();
    }
}


