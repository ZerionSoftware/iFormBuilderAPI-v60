<?php namespace Iform\Parser;


class ZerionApiParameterToken {

    private $lexer;
    public $type;
    public $text;

    public function __construct(ZerionApiParameterLexer $lexer, $type, $text)
    {
        $this->lexer = $lexer;
        $this->type = $type;
        $this->text = $text;
    }

    public function __toString()
    {
        return "<'" . $this->text . "'," . $this->lexer->getTokenName($this->type) . ">";
    }
}


abstract class ZerionApiParameterLexer {

    const EOF      = - 1;
    const EOF_TYPE = 1;
    public $index = 0;
    protected $input;
    protected $character;
    public $isValid = true;

    public function __construct($input)
    {
        $this->input = $input;
        $this->character = substr($input, $this->index, 1);
    }

    protected function consume()
    {
        $this->index ++;
        if ($this->index >= strlen($this->input)) {
            $this->character = ZerionApiParameterLexer::EOF;
        } else {
            $this->character = substr($this->input, $this->index, 1);
        }
    }

    public abstract function nextToken();

    public abstract function getTokenName($tokenType);
}


class ZerionApiFieldParameterLexer extends ZerionApiParameterLexer {

    const COLUMN_NAME          = 2;
    const COMMA                = 3;
    const LEFT_SQUARE_BRACKET  = 4;
    const RIGHT_SQUARE_BRACKET = 5;
    const LEFT_CURLY_BRACKET   = 6;
    const RIGHT_CURLY_BRACKET  = 7;
    const LEFT_BRACKET         = 8;
    const RIGHT_BRACKET        = 9;
    const COLON                = 10;
    const CONDITION            = 11;
    const CONDITION_LOGIC      = 12;
    const STRING_LITERAL       = 13;
    const DASH                 = 14;
    static $tokenNames = array("n/a", "<EOF>", "COLUMN_NAME", "COMMA", "LEFT_SQUARE_BRACKET", "RIGHT_SQUARE_BRACKET", "LEFT_CURLY_BRACKET", "RIGHT_CURLY_BRACKET", "LEFT_BRACKET", "RIGHT_BRACKET", "COLON", "CONDITION", "CONDITION_LOGIC", "STRING_LITERAL");

    public function getTokenName($tokenType)
    {
        return self::$tokenNames[$tokenType];
    }

    public function __construct($input)
    {
        parent::__construct($input);
    }

    public function nextToken()
    {
        if ($this->character == self::EOF) {
            return new ZerionApiParameterToken($this, self::EOF_TYPE, "");
        } else if ($this->character == ",") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::COMMA, ",");
        } else if ($this->character == "-") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::DASH , "-");
        } else if ($this->character == "[") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::LEFT_SQUARE_BRACKET, "[");
        } else if ($this->character == "]") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::RIGHT_SQUARE_BRACKET, "]");
        } else if ($this->character == "{") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::LEFT_CURLY_BRACKET, "{");
        } else if ($this->character == "}") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::RIGHT_CURLY_BRACKET, "}");
        } else if ($this->character == "(") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::LEFT_BRACKET, "(");
        } else if ($this->character == ")") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::RIGHT_BRACKET, ")");
        } else if ($this->character == ":") {
            $this->consume();

            return new ZerionApiParameterToken($this, self::COLON, ":");
        } else if ($this->character == "\"") {
            return new ZerionApiParameterToken($this, self::STRING_LITERAL, $this->getStringLiteral());
        } else if ($this->isColumnNameCharacter()) {
            return new ZerionApiParameterToken($this, self::COLUMN_NAME, $this->getColumnName());
        } else if ($this->isConditionCharacter()) {
            return new ZerionApiParameterToken($this, self::CONDITION, $this->getCondition());
        } else if ($this->isConditionLogicCharacter()) {
            $character = $this->character;
            $this->consume();

            return new ZerionApiParameterToken($this, self::CONDITION_LOGIC, $character);
        } else if (preg_match("/\s/", $this->character)) {
            $this->consume();

            return $this->nextToken();
        } else {
            $this->isValid = false;

            return new ZerionApiParameterToken($this, self::EOF_TYPE, "");
        }
    }

    private function isColumnNameCharacter()
    {
        return preg_match("/^[a-zA-Z0-9_]/", $this->character);
    }

    private function getColumnName()
    {
        $buffer = "";
        do {
            $buffer .= $this->character;
            $this->consume();
        } while ($this->isColumnNameCharacter());

        return strtolower($buffer);
    }

    private function isConditionCharacter($first = true)
    {
        return preg_match($first ? "/[><=!~]/" : "/[=]/", $this->character);
    }

    private function getCondition()
    {
        $buffer = $this->character;
        $this->consume();
        if ($this->isConditionCharacter(false)) {
            $buffer .= $this->character;
            $this->consume();
        }
        if ($buffer == "==") $buffer = "=";

        return $buffer;
    }

    private function isConditionLogicCharacter()
    {
        return preg_match("/[&|]/", $this->character);
    }

    private function isCombineCharacter()
    {
        return $this->character === "+";
    }

    private function getStringLiteral()
    {
        $this->consume();
        $buffer = "";
        while ($this->character != "\"" && $this->character != ZerionApiParameterLexer::EOF) {
            if ($this->character == "\\") {
                $this->consume();
                if ($this->character == "\"") {
                    $buffer .= $this->character;
                } else if ($this->character == "\\") {
                    $buffer .= $this->character;
                }
            } else {
                $buffer .= $this->character;
            }
            if ($this->character != ZerionApiParameterLexer::EOF) $this->consume();
        }
        if ($this->character == "\"") $this->consume();

        return $buffer;
    }
}


abstract class ZerionApiParameterParser {

    protected $input;
    protected $nextToken;
    protected $lastIndex = 0;

    public function __construct(ZerionApiParameterLexer $input)
    {
        $this->input = $input;
        $this->consume();
    }

    protected function match($x)
    {
        //match results
        // int(2)
        //int(8)
        //int(11)
        //int(13)
        //int(9)
        //int(3)
        //int(2)
        //int(8)
        //int(11)
        //int(13)
        //int(9)
        //string(4) "name"
        //string(1) "("
        //string(1) "~"
        //string(6) "%test%"
        //string(1) ")"
        //string(1) ","
        //string(5) "label"
        //string(1) "("
        //string(1) "~"
        //string(11) "%something%"
        //string(1) ")"

        $matchedResult = "";
        if ($this->nextToken->type == $x) {
            $matchedResult = $this->nextToken->text;
            $this->consume();
        } else {
            $this->throwInvalidCharacterError();
        }

        return $matchedResult;
    }

    protected function consume()
    {
        $this->lastIndex = $this->input->index;

        /**
         * Parsing next token in string and return ParameterTokenObject
         * ZerionApiParameterToken - object
         */
        $this->nextToken = $this->input->nextToken();
    }

    protected function throwInvalidCharacterError()
    {
        throw new \Exception("Invalid character at index " . $this->lastIndex);
    }

    abstract public function parse();
}


class ZerionApiFieldParameterParser extends ZerionApiParameterParser {

    private $fieldInfo = array();
    private $targetInfo = array();
    private $fieldStack = array();
    private $currentColumnName = "";
    private $validFieldNameList = null;
    private $fieldAliasList = null;
    private $pendingConcate = array();

    public function __construct(ZerionApiFieldParameterLexer $input, $validFieldNameList = null, $fieldAliasList = null)
    {
        parent::__construct($input);

        $this->validFieldNameList = $validFieldNameList;
        $this->fieldAliasList = $fieldAliasList;
    }

    private function isValidColumnName($columnName)
    {
        if (is_null($this->validFieldNameList)) return true;

        $currentFieldNameList = $this->validFieldNameList;
        foreach ($this->fieldStack as $fieldName) {
            if (! is_array($currentFieldNameList[$fieldName])) return false;
            $currentFieldNameList = $currentFieldNameList[$fieldName];
        }

        return in_array($columnName, array_keys($currentFieldNameList));
    }

    private function isNestedColumnName($columnName)
    {
        if (is_null($this->validFieldNameList)) return false;

        $currentFieldNameList = $this->validFieldNameList;
        foreach ($this->fieldStack as $fieldName) {
            if (! is_array($currentFieldNameList[$fieldName])) return false;
            $currentFieldNameList = $currentFieldNameList[$fieldName];
        }

        return is_array($currentFieldNameList[$columnName]);
    }

    private function getRealColumnName($columnName)
    {
        if (is_null($this->fieldAliasList)) return $columnName;

        $currentFieldAliasList = $this->fieldAliasList;
        foreach ($this->fieldStack as $fieldName) {
            if (! is_array($currentFieldAliasList[$fieldName])) return $columnName;
            $currentFieldAliasList = $currentFieldAliasList[$fieldName];
        }

        $name = isset($currentFieldAliasList[$columnName]) ? (is_array($currentFieldAliasList[$columnName]) ? $columnName : $currentFieldAliasList[$columnName]) : $columnName;

        return $name;
    }

    private function concatColumns($appendName)
    {
        $columnStr = $this->coalesceColumn($appendName);
        if (count($this->pendingConcate)) {
            $columnStr .= "," . implode(",", array_map(array($this, 'coalesceColumn'), $this->pendingConcate));
            $this->pendingConcate = array();
        }
        return $columnStr;
    }

    public function coalesceColumn($column)
    {
        return "COALESCE(".$column.",'')";
    }

    public function parse()
    {
        $this->fieldInfo = array("filters" => array("sql" => "", "types" => array(), "values" => array()), "sort" => array(), "children" => array());
        $this->matchColumnInfoList();

        $formatSortInfo = function (&$fieldInfo) use (&$formatSortInfo) {
            if (! empty($fieldInfo["sort"])) {
                ksort($fieldInfo["sort"]);
                $sortList = array();
                foreach ($fieldInfo["sort"] as $sortInfo) {
                    $sortList[] = $sortInfo;
                }
                $fieldInfo["sort"] = implode(", ", $sortList);
            } else $fieldInfo["sort"] = "";

            foreach ($fieldInfo["children"] as &$childFieldInfo) {
                $formatSortInfo($childFieldInfo);
            }
        };
        $formatSortInfo($this->fieldInfo);

        return $this->fieldInfo;
    }

    private function matchColumnInfoList()
    {
        $this->matchColumnInfo();

        $this->nextTokenIsConcatType();

        $this->whileNextTokenIsComma();
    }

    private function matchColumnInfo()
    {
        $columnNameIndex = $this->lastIndex;
        $columnName = $this->match(ZerionApiFieldParameterLexer::COLUMN_NAME);

        if (! $this->isValidColumnName($columnName)) throw new Exception("Invalid field name at index " . $columnNameIndex);

        $this->targetInfo = &$this->fieldInfo;

        for ($i = 0; $i < count($this->fieldStack); $i ++) {
            $this->targetInfo = &$this->targetInfo["children"][$this->fieldStack[$i]];
        }

        $childrenInfo = array();
        $childrenInfo["filters"] = array("sql" => "", "types" => array(), "values" => array());
        $childrenInfo["sort"] = array();
        $childrenInfo["children"] = array();
        $this->targetInfo["children"][$columnName] = $childrenInfo;
        $this->currentColumnName = $columnName;

        if ($this->nextToken->type == ZerionApiFieldParameterLexer::DASH) {
            return $this->pendingConcate[] = $this->getRealColumnName($columnName);
        }

        while ($this->isNextTokenMatchColumnExtraInfo()) {
            $this->matchColumnExtraInfo();
        }
    }

    private function matchColumnExtraInfo()
    {
        if ($this->nextToken->type == ZerionApiFieldParameterLexer::COLON) {
            $this->match(ZerionApiFieldParameterLexer::COLON);
            $this->matchSortStatement();
        } else if ($this->nextToken->type == ZerionApiFieldParameterLexer::LEFT_BRACKET) {
            $this->match(ZerionApiFieldParameterLexer::LEFT_BRACKET);
            $this->matchConditionStatement();
            $this->match(ZerionApiFieldParameterLexer::RIGHT_BRACKET);
        } else if ($this->nextToken->type == ZerionApiFieldParameterLexer::LEFT_SQUARE_BRACKET) {
            $this->match(ZerionApiFieldParameterLexer::LEFT_SQUARE_BRACKET);
            $this->fieldStack[] = $this->currentColumnName;
            $this->matchColumnInfoList();
            $this->currentColumnName = array_pop($this->fieldStack);
            $this->match(ZerionApiFieldParameterLexer::RIGHT_SQUARE_BRACKET);
        } else
            $this->throwInvalidCharacterError();
    }

    private function isNextTokenMatchColumnExtraInfo()
    {
        return in_array($this->nextToken->type,
            array(ZerionApiFieldParameterLexer::COLON, ZerionApiFieldParameterLexer::LEFT_BRACKET, ZerionApiFieldParameterLexer::LEFT_CURLY_BRACKET, ZerionApiFieldParameterLexer::LEFT_SQUARE_BRACKET));
    }

    private function matchSortStatement()
    {
        $conditionIndex = $this->lastIndex;
        $condition = $this->match(ZerionApiFieldParameterLexer::CONDITION);
        if ($condition != "<" && $condition != ">") throw new Exception("Invalid sort condition at index " . $conditionIndex);

        $priority = 0;
        if ($this->nextToken->type == ZerionApiFieldParameterLexer::COLUMN_NAME) {
            $priorityIndex = $this->lastIndex;
            $priority = $this->match(ZerionApiFieldParameterLexer::COLUMN_NAME);
            if (! is_numeric($priority) || isset($this->targetInfo["sort"][$priority])) throw new Exception("Invalid sort priority at index " . $priorityIndex);
        }

        if (! $this->isNestedColumnName($this->currentColumnName)) $this->targetInfo["sort"][$priority] = $this->getRealColumnName($this->currentColumnName) . " " . ($condition == ">" ? "DESC" : "ASC");
    }

    private function matchConditionStatement()
    {
        $skipCondition = $this->isNestedColumnName($this->currentColumnName);
        $trimmedSql = trim($this->targetInfo["filters"]["sql"], "() ");

        if (! $skipCondition && $trimmedSql !== "" && ! preg_match("/((AND)|(OR)) ?$/", $trimmedSql)
        ) $this->targetInfo["filters"]["sql"] .= " AND ";
        if (! $skipCondition) $this->targetInfo["filters"]["sql"] .= "(";

        if ($this->nextToken->type == ZerionApiFieldParameterLexer::LEFT_BRACKET) {
            $this->match(ZerionApiFieldParameterLexer::LEFT_BRACKET);
            if (! $skipCondition) $this->targetInfo["filters"]["sql"] .= "(";
            $this->matchConditionStatement();
            $this->match(ZerionApiFieldParameterLexer::RIGHT_BRACKET);
            if (! $skipCondition) $this->targetInfo["filters"]["sql"] .= ")";
        } else if ($this->nextToken->type == ZerionApiFieldParameterLexer::CONDITION) {
            $condition = $this->match(ZerionApiFieldParameterLexer::CONDITION);
            if ($condition == "~") $condition = "LIKE";

            if (! $skipCondition) $this->targetInfo["filters"]["sql"] .= "(" . "CONCAT(" . $this->concatColumns($this->getRealColumnName($this->currentColumnName)) . ")" . " " . $condition . " ";

            if ($this->nextToken->type == ZerionApiFieldParameterLexer::STRING_LITERAL) {
                $value = $this->match(ZerionApiFieldParameterLexer::STRING_LITERAL);
                if (! $skipCondition) {
                    $this->targetInfo["filters"]["sql"] .= "?";
                    $this->targetInfo["filters"]["types"][] = "s";
                    $this->targetInfo["filters"]["values"][] = (strtolower($value) === "true") ? "1" : ((strtolower($value) === "false") ? "0" : $value);
//					$this->targetInfo["filters"]["values"][] = $value;

                    if ($value === "") {
                        if ($condition == "!=") $this->targetInfo["filters"]["sql"] .= " AND " . $this->getRealColumnName($this->currentColumnName) . " IS NOT NULL";
                        else if ($condition == "LIKE" || $condition == "=") $this->targetInfo["filters"]["sql"] .= " OR " . $this->getRealColumnName($this->currentColumnName) . " IS NULL";
                    }
                }
            } else {
                $columnNameIndex = $this->lastIndex;
                $columnName = $this->match(ZerionApiFieldParameterLexer::COLUMN_NAME);
                if (! $this->isValidColumnName($columnName)) throw new Exception("Invalid field name at index " . $columnNameIndex);
                if (! $skipCondition) $this->targetInfo["filters"]["sql"] .= $this->getRealColumnName($columnName);
            }
            if (! $skipCondition) $this->targetInfo["filters"]["sql"] .= ")";
        } else {
            $this->throwInvalidCharacterError();
        }

        while ($this->nextToken->type == ZerionApiFieldParameterLexer::CONDITION_LOGIC) {
            $logic = $this->match(ZerionApiFieldParameterLexer::CONDITION_LOGIC);
            if (! $skipCondition) $this->targetInfo["filters"]["sql"] .= " " . ($logic == "&" ? "AND" : "OR") . " ";
            $this->matchConditionStatement();
        }
        if (! $skipCondition) $this->targetInfo["filters"]["sql"] .= ")";
    }

    private function nextTokenIsConcatType()
    {
        while ($this->nextToken->type == ZerionApiFieldParameterLexer::DASH) {
            $this->match(ZerionApiFieldParameterLexer::DASH);
            $this->matchColumnInfo();
        }
    }

    private function whileNextTokenIsComma()
    {
        while ($this->nextToken->type == ZerionApiFieldParameterLexer::COMMA) {
            $this->match(ZerionApiFieldParameterLexer::COMMA);
            $this->matchColumnInfo();
        }
    }
}