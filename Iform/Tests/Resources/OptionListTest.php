<?php

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

}
