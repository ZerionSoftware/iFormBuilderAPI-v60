<?php namespace Iform\FileSystem\Masking;

use Iform\FileSystem\Masking\BaseMask;

class ZipCodeMaskingWidget extends BaseMask {

    /**
     * First part of zip code
     * @var
     */
    private $val1;
    /**
     * Second part of zip code
     * @var
     */
    private $val2;

    public function getMatchedValues()
    {
        return (! is_null($this->val2) && $this->val2 !== "") ? $this->val1 .= "-" . $this->val2 : $this->val1;
    }

    public function getPattern($mask)
    {
        return "/^" . str_replace("\?", "?", preg_quote($mask)) . '$/';
    }

    private function matchValues($fieldValue, $pattern1, $pattern2)
    {
        if (strpos($fieldValue, "-") !== false) {
            $this->setValues(explode("-", $fieldValue, 2));

            return ! (! preg_match($pattern1, $this->val1) || ! preg_match($pattern2, $this->val2));
        }

        $this->setValues(array($fieldValue, null));

        return ! (! preg_match($pattern1, $fieldValue));
    }

    public function findMatch($mask, $fieldValue)
    {
        list($mask1, $mask2) = explode("-", $mask, 2);

        return $this->matchValues($fieldValue, $this->convert($mask1), $this->convert($mask2));
    }

    /**
     * @param $fieldValue
     */
    private function setValues(array $fieldValue)
    {
        list ($this->val1, $this->val2) = $fieldValue;
    }
}