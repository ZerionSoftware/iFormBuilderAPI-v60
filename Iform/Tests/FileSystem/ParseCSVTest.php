<?php

use Iform\FileSystem\ParseCSV;
use Iform\FileSystem\ParseCsvOptionBridge;

class ParseCSVTest extends PHPUnit_Framework_TestCase {

    private $processed;
    private $result;

    function setUp()
    {
        $file = __DIR__ . '/../Fixtures/sample.csv';
        $this->processed = new ParseCsvOptionBridge();
        $this->processed->encoding('UTF-8', 'UTF-8//IGNORE');


        $this->result = $this->processed->parse($file);
    }

    function testParsesWindowsExcelCsvWithNoErrors(){
        $this->assertTrue($this->result);
    }

    function testDataProcessedInProperFormat(){
        $this->assertArrayHasKey('label', $this->processed->data[0]);
        $this->assertArrayHasKey('key_value', $this->processed->data[0]);
        $this->assertArrayHasKey('condition_value', $this->processed->data[0]);
        $this->assertArrayHasKey('sort_order', $this->processed->data[0]);
    }

    function testDataProcessedFromWindowsExcelFile(){
        $this->assertCount(10, $this->processed->data);
    }

    function testParsesMacintoshCsvWithNoErrors(){
        $file = __DIR__ . '/../Fixtures/options_csv_valid.csv';
        $processed = new ParseCsvOptionBridge();
        $processed->encoding('UTF-8', 'UTF-8//IGNORE');

        $result = $processed->parse($file);
        $this->assertTrue($result);
    }

    function testDataProcessedFromMacintoshFile(){
        $file = __DIR__ . '/../Fixtures/options_csv_valid.csv';

        $processed = new ParseCsvOptionBridge();
        $processed->encoding('UTF-8', 'UTF-8//IGNORE');
        $processed->parse($file);

        $this->assertCount(11, $processed->data);
    }
//
    function testsProcessRemovesEnclosedCharacterFromFirstRowValue(){
        $file = __DIR__ . '/../Fixtures/extraReturnTests.csv';

        $processed = new ParseCsvOptionBridge();
        $processed->encoding('UTF-8', 'UTF-8//IGNORE');
        $processed->parse($file);

        $this->assertFalse(strpos($processed->data[0]['label'], "\n"));
    }

    function testsProcessFailsOnInvalidCharacters(){
        $file = __DIR__ . '/../Fixtures/Workbook2.csv';

        $processed = new ParseCsvOptionBridge();
        $processed->encoding('UTF-8', 'UTF-8//IGNORE');

        $processed->parse($file);

        var_dump($processed->data[59]);
    }

    public function testThrowsExceptionForInvalidFormat()
    {
        $file = __DIR__ . '/../Fixtures/invalid_format_test.csv';

        $processed = new ParseCsvOptionBridge();
        $processed->encoding('UTF-8', 'UTF-8//IGNORE');
        $processed->parse($file);

        $this->assertEmpty($processed->data);
    }

    function tearDown()
    {
        unset($this->processed);
        unset($this->result);
    }
}
