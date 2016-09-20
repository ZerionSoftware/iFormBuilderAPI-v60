<?php

use Iform\FileSystem\CSV;

class CSVTest extends \PHPUnit_Framework_TestCase {

    private $csv;

    public function setUp()
    {
        $file = __DIR__ . '/../Fixtures/record_id_test.csv';

        $this->csv = new CSV($file);
    }

    public function testNextWillSetCurrent()
    {
        $this->csv->next();
        $firstLine = $this->csv->current();

        $this->assertTrue(in_array('iform_record_id', $firstLine));
        $this->assertTrue(in_array('ddm_client_id', $firstLine));
    }

    public function testNextWillAdjustKeys()
    {
        $this->assertEquals(0, $this->csv->key());
        $this->csv->next();
        $this->assertEquals(1, $this->csv->key());
    }


    public function testNextParsesLineAtCurrentKey()
    {
        $this->csv->next();
        $this->csv->next();
        $ids = $this->csv->current();

        $this->assertTrue(in_array('3074', $ids));
    }

    public function testRewinds()
    {
        $this->csv->next();
        $this->csv->rewind();

        $this->assertEquals(0, $this->csv->key());
    }

    public function testValidReturnsError()
    {
        $file = __DIR__ . '/../Fixtures/invalid_format_test.csv';
        $csv = new CSV($file);

        $csv->next();
        $this->assertFalse($csv->valid());
    }

    public function tearDown()
    {
        unset($this->csv);
    }

}
