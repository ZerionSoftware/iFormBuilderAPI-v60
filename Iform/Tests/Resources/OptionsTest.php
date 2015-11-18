<?php

require_once 'BaseResourceTest.php';

use Iform\Resources\OptionList\Options;

class OptionsTest extends BaseResourceTest {

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/optionlists\/" . static::$id . "\/options";

        $this->setIdentifier(static::$id);
        $this->setResourceType('Iform\Resources\OptionList\Options');
        parent::setUp();
    }

}