<?php namespace Iform\FileSystem\Masking;

abstract class BaseMask {

    protected $pattern = null;
    protected $lookBehind = "/(?<!0\-)9/";

    protected function CharacterAndNumerical()
    {
        $this->pattern = str_replace("A", "[A-Za-z0-9]", $this->pattern);
    }

    protected function CharacterInputOnly()
    {
        $this->pattern = str_replace("?", "[A-Za-z]", $this->pattern);
    }

    protected function NumericalInput()
    {
        $this->pattern = str_replace("#", "[0-9]", $this->pattern);
    }

    protected function NumericalOptional()
    {
        $this->pattern = preg_replace($this->lookBehind, "[0-9]?", $this->pattern);
    }

    public function convert($mask)
    {
        $this->pattern = $this->getPattern($mask);
        $this->bindValues();

        return $this->pattern;
    }

    protected function bindValues()
    {
        $this->CharacterAndNumerical();
        $this->CharacterInputOnly();
        $this->NumericalOptional();
        $this->NumericalInput();
    }

    public abstract function getPattern($mask);
}