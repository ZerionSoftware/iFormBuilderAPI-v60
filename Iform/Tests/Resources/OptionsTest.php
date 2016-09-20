<?php namespace Iform\Tests\Resources;

class OptionsTest extends BaseResourceTest {

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/optionlists\/" . static::$id . "\/options";

        $this->setIdentifier(static::$id);
        $this->setResourceType('Iform\Resources\OptionList\Options');
        parent::setUp();
    }

    public function testUpdateAll()
    {
        $values = [
            [
                "id"            => 20483233,
                "kay_value" => "new Date()"
            ],
            [
                "id"            => 20483236,
                "kay_value" => "iformbuilder.username"
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

        $resource = $this->instantiate($this->stub);
        $this->assertEquals($values, $resource->deleteAll($values));
    }

    public function testDeleteAllWithGrammar()
    {
        $resource = $this->instantiate($this->stub);
        $resource->where('id(>"1")');

        $this->arrayHasKey('id', json_decode($resource->deleteAll(array()), true));
    }

    function tearDown()
    {
        parent::tearDown();
    }
}
