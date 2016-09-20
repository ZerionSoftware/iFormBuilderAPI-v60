<?php namespace Iform\FileSystem\Resources;

use Iform\FileSystem\Processor;

interface ZerionFileResource{

    function getDataFromFile();

    function uploadLineByLine();

    function mountReader(\Iterator $iterator);

    function mountProcessor(Processor $processor);
}