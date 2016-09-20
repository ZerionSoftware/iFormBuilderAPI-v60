<?php

use Iform\Resources\Base\BatchValidator;

class BatchValidatorTest extends \PHPUnit_Framework_TestCase {

    private $testFields = array();

    public function setUp()
    {
        $this->testFields = array(
            "id",
            "name",
            "permissions",
            "global_id",
            "label",
            "description",
            "version",
            "created_date",
            "created_by",
            "modified_date",
            "modified_by",
            "is_disabled",
            "reference_id_1",
            "reference_id_2",
            "reference_id_3",
            "reference_id_4",
            "reference_id_5",
            "icon",
            "sort_order",
            "page_javascript",
            "label_icons",
            "localizations"
        );
    }

    public function testCombineUsingSortGrammar()
    {
        $testGrammar = "created_date:>";
        BatchValidator::combineWithGrammarFields($this->testFields, $testGrammar);
        $this->assertContains($testGrammar, $this->testFields);
    }

    public function testCombineUsingFilterGrammar()
    {
        $testGrammar = "created_by(='ssalinas')";

        BatchValidator::combineWithGrammarFields($this->testFields, $testGrammar);
        $this->assertContains($testGrammar, $this->testFields);
    }

    public function testCombineUsingMultipleGrammars()
    {
        $testGrammar = "created_date:>,created_by(='ssalinas')";
        BatchValidator::combineWithGrammarFields($this->testFields, $testGrammar);

        $check = explode(",", $testGrammar);
        $this->assertContains($check[0], $this->testFields);
        $this->assertContains($check[1], $this->testFields);
    }

    public function testCombineUsingMultipleGrammarsToSameFieldName()
    {
        $testGrammar = 'name(~"%' . 'test' . '%")'.',name:<';
        BatchValidator::combineWithGrammarFields($this->testFields, $testGrammar);

        $this->assertEquals($this->testFields[1], $testGrammar);
    }

    public function testFormatBatchWrapsSingleParameterInArray()
    {
        $test = array('id' => 121542, 'optionlists_id' => 10);
        $params = BatchValidator::formatBatch($test);

        $this->assertArrayHasKey(0, $params);
        $this->assertEquals($params[0], $test);
    }

    public function testSearchMultipleColumns()
    {
        $query = 'id-label-name(~"%seths%"),name:<';

        BatchValidator::combineWithGrammarFields($this->testFields, $query);
//
//        var_dump($this->testFields);
    }

    public function testFormatBatchDoesNotWrapsMultipleParametersInArray()
    {
        $params = array();
        $params[] = array('id' => 121542, 'optionlists_id' => 10);
        $params[] = array('id'=>50, 'optionlists_id'=> 10);

        $this->assertEquals(BatchValidator::formatBatch($params), $params);
    }

    public function testJSONParametersWrappedCorrectly()
    {
        $json = '[{"id": "20607982","optionlist_id": "405328"}]';
        $params = json_decode($json, true);

        $this->assertEquals($params, BatchValidator::formatBatch($params));
    }

    public function testFormatUrlWithHttpEncodes()
    {
        $test = ['fileds' => 'id(>="2")'];

        $this->assertContains(http_build_query($test), BatchValidator::formatUrlWithHttpParams("test", $test));
    }

    public function tearDown()
    {
        $this->testFields = array();
    }

}
