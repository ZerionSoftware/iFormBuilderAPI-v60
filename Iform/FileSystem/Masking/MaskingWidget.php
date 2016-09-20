<?php namespace Iform\FileSystem\Masking;

use Iform\FileSystem\Masking\BaseMask;

class MaskingWidget extends BaseMask {

    public function getPattern($mask)
    {
        return '^' . str_replace("\?", "?", preg_quote($mask)) . '$';
    }
}