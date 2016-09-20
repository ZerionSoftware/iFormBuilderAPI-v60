<?php namespace Iform\Resources\Base;

class BatchValidator {

    /**
     * Combine parameters for batch calls
     *
     * @param $passed
     * @param $current
     *
     * @return array
     */
    public static function combine($passed, $current)
    {
        if (empty($current)) return $passed;

        return array_replace_recursive($current, $passed);
    }

    /**
     * @param array $values
     *
     * @return array
     * @throws \Exception
     */
    public static function formatBatch($values)
    {
        if (isset($values[0])) {
            if (! is_array($values[0])) throw new \InvalidArgumentException("invalid batch format");
        } else {
            $values = array($values); //new to wrap single call in array
        }

        return $values;
    }

    public static function combineWithGrammarFields(&$baseFields, $grammar)
    {
        if (strpos($grammar, ",") !== false) {
            $results = explode(",", $grammar);

            if (! empty($results)) {
                $i = 0;
                $append = false;
                $hash = array();
                while ($results) {
                    if ($fieldName = static::findFieldName($results[$i])) {
                        if(! array_key_exists($fieldName, $hash)) {
                            if (static::isSearchCondition($fieldName)) {

                                array_unshift($baseFields, $results[$i]);
                                unset($results[$i]);
                                $i ++;
                                continue;
                            }

                            if (! $key = static::getFieldNameIndex($fieldName, $baseFields) ) continue;
                            $hash[$fieldName] = $key;

                        } else {
                            $append = true;
                        }



                        static::replaceFieldWithGrammar($hash[$fieldName], $baseFields, $results[$i], $append);
                        $append = false;
                    }
                    unset($results[$i]);
                    $i ++;
                }
            }
        } else {
            $fieldName = static::findFieldName($grammar);
            if ($key = static::getFieldNameIndex($fieldName, $baseFields) ) {
                static::replaceFieldWithGrammar($key, $baseFields, $grammar);
            }
        }
    }

    private static function isSearchCondition($search)
    {
        return (strpos($search, "-") !== false);
    }

    private static function findFieldName ($search)
    {
        $regex = ".[><=!~]|[:>:<].$";
        $results = preg_split("/" . $regex . "/", $search);

        return ! empty($results) ? $results[0] : false;
    }

    private static function getFieldNameIndex($fieldName, $baseFields)
    {
        return array_search($fieldName, $baseFields);
    }

    private static function replaceFieldWithGrammar($fieldName, &$lookup, $filterGrammar, $append = false)
    {
        if ($append) {
            return $lookup[$fieldName] .= "," .$filterGrammar;
        }

        $lookup[$fieldName] = $filterGrammar;
    }

    public static function formatUrlWithHttpParams($url, array $params)
    {
        return empty($params) ? $url : $url . "?" . http_build_query($params);
    }

}