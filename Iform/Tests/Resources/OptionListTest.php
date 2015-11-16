<?php

require_once 'BaseResourceTest.php';

use Iform\Resources\OptionList\OptionLists;

class OptionListTest extends BaseResourceTest {

    private static $pattern = "";
    private static $id = 0;

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/optionlists";

        $this->setResourceType('Iform\Resources\OptionList\OptionLists');
        parent::setUp();
    }

    function testCreateCommand()
    {
        $values = ["name" => "System Grade"];
        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $optList = new OptionLists($this->mock);
        $optList->create($values);
    }

    function testUpdateCommand()
    {
        $id = 123123;
        $pattern = static::$pattern ."\/".$id;
        $values = ["name" => "System Grade"];
        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $optList = new OptionLists($this->mock);
        $optList->update($id, $values);
    }

    function testDeleteCommand()
    {
        $id = 123123;
        $pattern = static::$pattern ."\/".$id;
        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . $pattern . "/");

        $optList = new OptionLists($this->mock);
        $optList->delete($id);
    }

}
