<?php

use Iform\FileSystem\FileProcess;
use Iform\FileSystem\Resources\OptionFileUpload;
use Iform\FileSystem\CSV;

class FileProcessTest extends \PHPUnit_Framework_TestCase {

    private $processed;

    function setUp()
    {
        $file = __DIR__ . '/../Fixtures/options_csv_valid.csv';
        $this->processed = new FileProcess(new CSV($file), new OptionFileUpload());
    }

    public function testReturnsProcessedData()
    {
        $data = $this->processed->getDataFromFile();

        $this->assertInternalType('array', $data);
        $this->assertCount(11,$data);
    }

    public function testParseFormatsCorrectly()
    {
        $title = $this->processed->getDataFromFile();

        $this->assertArrayHasKey('label', $title[0]);
        $this->assertArrayHasKey('key_value', $title[0]);
        $this->assertArrayHasKey('condition_value', $title[0]);
        $this->assertArrayHasKey('sort_order', $title[0]);
    }

    /**
     * Invalid file
     * @expectedException \Exception
     */
    public function testThrowsExceptionForInvalidFormat()
    {
        $file = __DIR__ . '/../Fixtures/invalid_format_test.csv';
//        $file = __DIR__ . '/../Fixtures/sample.csv';
        $fileProcess = new FileProcess(new CSV($file), new OptionFileUpload());

        $fileProcess->getDataFromFile();
    }

    function tearDown()
    {
        unset($this->processed);
    }

}
