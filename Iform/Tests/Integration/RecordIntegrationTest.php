<?php

use Iform\Resources\IformResource;
use Iform\Creds\Config;

class RecordIntegrationTest extends \PHPUnit_Framework_TestCase {


    private $pageId = 794890;
    private $params = [
        'fields' => [
            [
                'element_name'=> 'my_element1',
                'value'=> 'test1'
            ],

            [
                'element_name'=> 'my_element',
                'value'=> 'test'
            ],

            [
                'element_name'=> 'my_element2',
                'value'=> 'test2'
            ]
        ]
    ];

    public function setUp()
    {
        $api = 'https://ssalinasdemo.iformbuilder.com/';
        $profileId = '161521';
        $username = 'ssalinas';
        $password = 'letmeinNow';

        Config::getInstance();
        Config::setUser($profileId);
        Config::setUsername($username);
        Config::setPassword($password);
        Config::setServer($api);
    }

    public function testCreatesRecord()
    {
        $recordResource = IformResource::records($this->pageId);
        $id = json_decode($recordResource->create($this->params), true);

        $this->assertArrayHasKey('id', $id);
    }

    public function testDeletesAll()
    {
        $recordResource = IformResource::records($this->pageId);
        $ids = $recordResource->where('id(>="1")')->deleteAll();
    }

    public function testFetchesAll()
    {
//        $recordResource = IformResource::records($this->pageId);
//        $ids = $recordResource->where('id(>"1")')->first(100)->fetchAll();
    }

    public function tearDown()
    {
        Config::getInstance();
        Config::setUser("");
        Config::setUsername("");
        Config::setPassword("");
        Config::setServer("");
    }

}
