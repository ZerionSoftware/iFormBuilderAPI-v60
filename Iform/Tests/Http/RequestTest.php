<?php

use \Mockery as m;

class AppRequestTest extends \PHPUnit_Framework_TestCase {

    private $app;

    public function testCleanseAngularVarsFromRequest()
    {
        $angular = array(
            'toJSON' => "",
            '$get' => "",
            '$save' => "",
            '$query' => "",
            '$remove' => "",
            '$delete' => "",
            '$update' => "",
            '$resolved' => "",
            '$promise' => "",
            'id' => 14,
            'name' => 'test'
        );

    }

}
