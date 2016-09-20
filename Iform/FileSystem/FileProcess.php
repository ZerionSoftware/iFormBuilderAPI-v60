<?php namespace Iform\FileSystem;

use Iform\FileSystem\Resources\ZerionFileResource;
use Iform\FileSystem\Processor;

class FileProcess implements ZerionFileResource {

    private $reader;
    private $processor;
    private $force = false;

    function __construct(\Iterator $reader, Processor $processor)
    {
        $this->reader = $reader;
        $this->processor = $processor;
    }

    public function mountReader(\Iterator $reader)
    {
        $this->reader = $reader;
    }

    public function mountProcessor(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function force()
    {
        $this->force = !$this->force;
    }

    public function getDataFromFile()
    {
        $this->reader->next();
        while ($this->reader->current() !== false) {

            if (! $this->reader->valid() && ! $this->force) {
                throw new \Exception("Invalid File");
            }

            $this->processor->process($this->reader->current());
            $this->reader->next();
        }

        return $this->processor->getProcessedData();
    }

    public function uploadLineByLine()
    {
       //TODO::for single resource
    }
}