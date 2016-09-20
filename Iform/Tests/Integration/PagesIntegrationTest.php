<?php

use Iform\Resources\IformResource;
use Iform\Creds\Config;

class PagesIntegrationTest extends \PHPUnit_Framework_TestCase {

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
//        $recordResource = IformResource::records($this->pageId);
//        $id = json_decode($recordResource->create($this->params), true);
//
//        $this->assertArrayHasKey('id', $id);
    }

    public function testDeletesAll()
    {
//        $recordResource = IformResource::records($this->pageId);
//        $ids = $recordResource->where('id(>="1")')->first(100)->deleteAll();
//
//        var_dump($ids);
    }

    public function testFetchesAllWithMultipleGrammar()
    {
        $pages = IformResource::pages();
        $grammar = 'name(~"%' . 'test' . '%")'.',name:<';
        $pages = json_decode($pages->where($grammar)->withAllFields()->fetchAll(), true);

        $this->assertContains('a', $pages[0]['name']);
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
