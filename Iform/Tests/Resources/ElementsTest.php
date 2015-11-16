<?php
require_once 'BaseResourceTest.php';

use Iform\Resources\Element\Elements;

class ElementsTest extends BaseResourceTest {

    private static $pattern = "";
    private static $id = 0;

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/pages\/" . static::$id . "\/elements";

        $this->setIdentifier(static::$id);
        $this->setResourceType('Iform\Resources\Element\Elements');
        parent::setUp();
    }

    public function testCreateCommand()
    {
        $values = ['name' => 'test'];
        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $element = new Elements($this->mock, static::$id);
        $element->create($values);
    }

    public function testUpdateAll()
    {
        $values = [
            [
                "id"            => 20483233,
                "dynamic_value" => "new Date()"
            ],
            [
                "id"            => 20483236,
                "dynamic_value" => "iformbuilder.username"
            ]
        ];

        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $element = new Elements($this->mock, static::$id);
        $element->updateAll($values);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testForIllegalBatchFormat()
    {
        $values = ["should fail because this is a string"];
        $element = new Elements($this->mock, static::$id);
        $element->updateAll($values);
    }

    public function testFetchAll()
    {
        $response = json_decode($this->resource->fetchAll(), true);
        //collection queries should be array
        $this->assertEquals(3, count($response));
    }

    public function testLocalizationsFetch()
    {
        $response = $this->resource->localizations(static::$id)
                                   ->fetch('es');

        $this->assertArrayHasKey('language_code', json_decode($response, true));
    }

    public function testLocalizationsUpdate()
    {
        $code = "es";
        $elementId = 1234567;
        $pattern = static::$pattern . '\/' . $elementId . '\/localizations\/';
        $values = ["label" => "nombre del Inspector"];

        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $element = new Elements($this->mock, static::$id);
        $element->localizations($elementId)
                ->update($code, $values);
    }

    public function testLocalizationsDelete()
    {
        $code = "es";
        $elementId = 1234567;
        $pattern = static::$pattern . '\/' . $elementId . '\/localizations\/';
        $values = ["label" => "nombre del Inspector"];

        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . $pattern . "/");

        $element = new Elements($this->mock, static::$id);
        $element->localizations($elementId)
                ->delete($code);
    }

    public function testLocalizationsCreate()
    {
        $elementId = 1234567;
        $pattern = static::$pattern . '\/' . $elementId . '\/localizations';
        $values = ["language_code" => "es", "label" => "nombre del Inspector"];

        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $element = new Elements($this->mock, static::$id);
        $element->localizations($elementId)
                ->create($values);
    }

    public function testLocalizationsUpdateAll()
    {
        $elementId = 1234567;
        $pattern = static::$pattern . '\/' . $elementId . '\/localizations';
        $values = [
            [
                "id"            => 20483233,
                "dynamic_value" => "new Date()"
            ],
            [
                "id"            => 20483236,
                "dynamic_value" => "iformbuilder.username"
            ]
        ];

        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $element = new Elements($this->mock, static::$id);
        $element->localizations($elementId)
                ->updateAll($values);
    }

    function tearDown()
    {
        static::$pattern = "";
        parent::tearDown();
    }
}