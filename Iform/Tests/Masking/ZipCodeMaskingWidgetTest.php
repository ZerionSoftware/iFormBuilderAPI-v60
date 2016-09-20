<?php

use Iform\FileSystem\Masking\ZipCodeMaskingWidget;

class ZipCodeMaskingWidgetTest extends PHPUnit_Framework_TestCase {

    private $mask = null;

    function setUp()
    {
        $this->mask = new ZipCodeMaskingWidget();
    }

    function testGetPatternReturnRegEx()
    {
        $mask = "#####-9999";

        $this->assertEquals("/^#####\-9999$/", $this->mask->getPattern($mask));
    }

    function testFindMatchBindsSecondPartOfZipCodeAndFails()
    {
        $value = "-9999";
        $mask = "#####-9999";

        $this->assertFalse($this->mask->findMatch($mask, $value));
    }

    function testFindMatchBindsFirstPartOfZipCode()
    {
        $value = "47401";
        $mask = "#####-9999";

        $this->assertEquals(1, $this->mask->findMatch($mask, $value));
    }

    function testWorksWhenSendsPhoneFormat()
    {
        $mask = "(###) ###-9999";
        $value = "(123) 144-9999";

        $this->assertTrue($this->mask->findMatch($mask, $value));
    }

    function testFailsWhenValueIsIncorrectLength()
    {
        $value = "471";
        $mask = "#####-9999";

        $this->assertFalse($this->mask->findMatch($mask, $value));
    }

    function testGetMatchValuesDoesNotConcatenatesValues()
    {
        $value = "47401";
        $mask = "#####-9999";

        $this->mask->findMatch($mask, $value);
        $this->assertEquals($value, $this->mask->getMatchedValues());
    }

    function testGetMatchValuesConcatenatesValues()
    {
        $value = "47401-9999";
        $mask = "#####-9999";

        $this->mask->findMatch($mask, $value);
        $this->assertEquals($value, $this->mask->getMatchedValues());
    }


    function tearDown()
    {
        unset($this->mask);
    }
}
