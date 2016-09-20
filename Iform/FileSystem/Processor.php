<?php namespace Iform\FileSystem;

interface Processor {

    public function process($data);

    public function getProcessedData();

}