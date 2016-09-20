<?php namespace Iform\Tests\Resources;

require_once 'BaseResourceTest.php';

use Iform\Resources\OptionList\OptionLists;

class OptionListTest extends BaseResourceTest {

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/optionlists";

        $this->setResourceType('Iform\Resources\OptionList\OptionLists');
        parent::setUp();
    }

    public function testFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testFetchAllWithGrammar()
    {
//        $resource = $this->instantiate($this->liveTest());
//        $response = $resource->withAllFields()->fetchAll();
//        $decoded = json_decode($response, true);
//
//        //collection queries should be array
//        $this->assertInternalType('array', $decoded);
//        $this->assertCount(intval($resource->getResponseBodyCount()), $decoded);
    }

    function tearDown()
    {
        parent::tearDown();
    }

}
