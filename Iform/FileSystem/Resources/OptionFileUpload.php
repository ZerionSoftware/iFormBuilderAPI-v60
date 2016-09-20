<?php namespace Iform\FileSystem\Resources;

use Iform\FileSystem\Processor;
use Iform\FileSystem\Resources\FileParameter;

class OptionFileUpload implements Processor {

    /**
     * Field headers
     * @var array
     */
    protected $headers = array();
    /**
     * Parsed File Data for upload
     * @var array
     */
    protected $data = array();
    /**
     * Is First line ?
     * @var bool
     */
    protected $firstLine = true;

    private $fileResource = null;

    function __construct(FileParameter $fileResource = null) {
        $this->fileResource =  $this->fileResource ?: new FileParameter();
    }

    public function process($variables)
    {
        if (! empty($this->headers)) {
            $this->data[] = $this->fileResource->getParameters($variables, $this->headers);
        } else {
            if ($this->firstLine) {
                $this->headers = $this->fileResource->getParameters($variables);
                $this->firstLine = false;
            }
        }
    }

    public function getProcessedData()
    {
        return $this->data;
    }

    public function validate()
    {
        $max = 4;
        $validFields = array('label', 'key_value', 'condition_value', 'sort_order');
        $valid = false;

        if (count($this->headers) < $max) {
            foreach($this->headers as $label) {
                if (! in_array($label, $validFields)) {
                    break;
                }
            }

            $valid = true;
        }

        return $valid;
    }
}