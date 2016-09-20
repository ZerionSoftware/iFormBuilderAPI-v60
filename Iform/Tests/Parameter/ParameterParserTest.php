<?php

use Iform\Parser\ZerionApiFieldParameterParser;
use Iform\Parser\ZerionApiFieldParameterLexer;

class ZerionApiFieldParameterParserTest extends PHPUnit_Framework_TestCase {
    private $fields = '{"id":"int","name":"str","global_id":"str","version":"int","label":"str","description":"str","data_type":"int","data_size":"int","created_date":"datetime","created_by":"str","modified_date":"datetime","modified_by":"str","widget_type":"str","sort_order":"int","optionlist_id":"int","default_value":"str","low_value":"int","high_value":"int","dynamic_value":"str","is_required":"bool","condition_value":"str","client_validation":"str","is_disabled":"bool","reference_id_1":"str","reference_id_2":"str","reference_id_3":"str","reference_id_4":"str","reference_id_5":"str","attachment_link":"str","is_readonly":"bool","validation_message":"str","is_action":"bool","smart_tbl_search":"str","smart_tbl_search_col":"str","is_encrypt":"bool","is_hide_typing":"bool","on_change":"str","keyboard_type":"int","dynamic_label":"str","weighted_score":"float","localizations":{"language_code":"ar|ca|cs|da|de|el|en|en-gb|es|fi|fr|he|hr|hu|id|it|ja|km|ko|ms|nb|nl|pl|pt|pt-pt|ro|ru|sk|sl|sr|sv|th|tr|uk|vi|zh-hans|zh-hant","label":"str"}}';
    private $params = "name(~\"%test%\"),label(~\"%something%\")";
    private $params2 = "name-label(~\"%something%\")";
    private $alias = '{"optionlist_id":"option_list_id","localizations":{"language_code":"lang_code"}}';
    private $expected = ["((name LIKE ?)) AND ((label LIKE ?))",["s","s"],["%test%","%something%"]];

    private $parser = null;

    function setUp ()
    {
        $this->parser = new ZerionApiFieldParameterParser(new ZerionApiFieldParameterLexer($this->params), json_decode($this->fields, true), json_decode($this->alias, true));
    }

    function testFilterSqlColumnsParsedAndConcatenated()
    {
        $results = $this->parser->parse();
        $expectSql = "((CONCAT(COALESCE(name,'')) LIKE ?)) AND ((CONCAT(COALESCE(label,'')) LIKE ?))";

        $this->assertEquals($expectSql, $results["filters"]["sql"]);
    }

    function testFilterSqlColumnsWithCombineCharacter()
    {
        $parser = new ZerionApiFieldParameterParser(new ZerionApiFieldParameterLexer($this->params2), json_decode($this->fields, true), json_decode($this->alias, true));

        $results = $parser->parse();
        $expectSql = "((CONCAT(COALESCE(label,''),COALESCE(name,'')) LIKE ?))";

        $this->assertEquals($expectSql, $results["filters"]["sql"]);
    }

    function testFilterSqlWithThreeColumns()
    {
        $params = "name-label-description(~\"%description%\")";
        $parser = new ZerionApiFieldParameterParser(new ZerionApiFieldParameterLexer($params), json_decode($this->fields, true), json_decode($this->alias, true));

        $results = $parser->parse();
        $expectSql = "((CONCAT(COALESCE(description,''),COALESCE(name,''),COALESCE(label,'')) LIKE ?))";

        $this->assertEquals($expectSql, $results["filters"]["sql"]);
    }

    function testFilterSql()
    {
        $params = "name-label-description(~\"%something%\")";
        $parser = new ZerionApiFieldParameterParser(new ZerionApiFieldParameterLexer($params), json_decode($this->fields, true), json_decode($this->alias, true));

        $results = $parser->parse();
    }

    function testProtectsFromNullValues()
    {
        $params = "id-label-name-reference_id_2-reference_id_1-reference_id_3-reference_id_4-reference_id_5(~\"%something%\")";
        $parser = new ZerionApiFieldParameterParser(new ZerionApiFieldParameterLexer($params), json_decode($this->fields, true), json_decode($this->alias, true));
//
        $results = $parser->parse();
        $expected = "((CONCAT(COALESCE(reference_id_5,''),COALESCE(id,''),COALESCE(label,''),COALESCE(name,''),COALESCE(reference_id_2,''),COALESCE(reference_id_1,''),COALESCE(reference_id_3,''),COALESCE(reference_id_4,'')) LIKE ?))";

        $this->assertEquals($expected, $results["filters"]["sql"]);
    }

    function tearDown()
    {
        unset($this->parser);
    }
}
