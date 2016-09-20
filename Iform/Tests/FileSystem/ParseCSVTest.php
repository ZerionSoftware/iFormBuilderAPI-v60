<?php

use Iform\FileSystem\ParseCSV;

class ParseCSVTest extends PHPUnit_Framework_TestCase {

    private $processed;
    private $result;

    function setUp()
    {
        $file = __DIR__ . '/../Fixtures/sample.csv';
        $this->processed = new ParseCSV();

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
        $processed = new ParseCSV();

        $result = $processed->parse($file);
        $this->assertTrue($result);
    }

    function testDataProcessedFromMacintoshFile(){
        $file = __DIR__ . '/../Fixtures/options_csv_valid.csv';

        $processed = new ParseCSV();
        $processed->parse($file);

        $this->assertCount(11, $processed->data);
    }


    function tearDown()
    {
        unset($this->processed);
        unset($this->result);
    }
}
