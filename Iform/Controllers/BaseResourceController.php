<?php namespace Iform\Controllers;

class BaseResourceController {
    /**
     * iForm resource
     * @var object
     */
    protected $model;
    /**
     * Server response
     * @var
     */
    protected $response;
    /**
     * Request variables
     * @var null
     */
    protected $request;

    function __construct($body = null) {
        if (! is_null($body) && $body !== "") {
            $this->request = json_decode($body);
        }
    }
    /**
     * Remove Angular Resource Variables
     *
     * @param array $values
     *
     * @return mixed
     */
    function removeAngularVars(array &$values)
    {
        if (isset($values['$resolved'])) unset($values['$resolved']);
        if (isset($values['$promise'])) unset($values['$promise']);

        return $values;
    }

    /**
     * Clean key names
     * @param $name
     *
     * @return mixed|string
     */
    function cleanTableName($name){
        $name = strtolower($name);

        $replace = array('([\40])','([^a-zA-Z0-9_])','(-{2,})');
        $with = array('_','','_');
        $name = preg_replace($replace,$with,$name);

        return $name;
    }

    function formatPostGrammar($values) {
        if (is_array($values)) {
            return join(",", array_values($values));
        }

        return false;
    }

    public function output() {
        //TODO:: validate response and log if error
        echo $this->response;
    }

    /**
     * Attach request with failed response
     * @param $msg
     *
     * @return string
     */
    public function outputError($msg)
    {
        echo json_encode(array('error_message' => $msg));
    }

}