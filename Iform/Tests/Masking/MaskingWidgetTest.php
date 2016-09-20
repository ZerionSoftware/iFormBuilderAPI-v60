<?php

use Iform\FileSystem\Masking\MaskingWidget;

class MaskingWidgetTest extends PHPUnit_Framework_TestCase {

    private $mask = null;

    function setUp()
    {
        $this->mask = new MaskingWidget();
    }

    function testGetPatternDoesNotWrapInRegEx()
    {
        $mask = "###-###-9999";

        $this->assertEquals("^###\-###\-9999$", $this->mask->getPattern($mask));
    }

    function testCharacterAndNumericalReplacedWithCorrectRegEx()
    {
        $mask = "AAA";

        $this->assertEquals("^[A-Za-z0-9][A-Za-z0-9][A-Za-z0-9]$", $this->mask->convert($mask));
    }

    function testNumericalInputReplacedWithCorrectRegex()
    {
        $mask = "###";

        $this->assertEquals("^[0-9][0-9][0-9]$", $this->mask->convert($mask));
    }

    function testCharacterInputReplacedWithCorrectRegex()
    {
        $mask = "???";

        $this->assertEquals("^[A-Za-z][A-Za-z][A-Za-z]$", $this->mask->convert($mask));
    }

    function testNumericalOptionalReplacedWithCorrectRegex()
    {
        $mask = "999";

        $this->assertEquals("^[0-9]?[0-9]?[0-9]?$", $this->mask->convert($mask));
    }

    function testConvertAlgoReturnsCorrectRegExPattern()
    {
        $mask = "###-AA?-9999";
        $expected = "^[0-9][0-9][0-9]\-[A-Za-z0-9][A-Za-z0-9][A-Za-z]\-[0-9]?[0-9]?[0-9]?[0-9]?$";

        $this->assertEquals($expected, $this->mask->convert($mask));
    }

    function tearDown()
    {
        unset($this->mask);
    }
}

