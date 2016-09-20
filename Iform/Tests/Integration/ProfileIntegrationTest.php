<?php

use Iform\Resources\IformResource;
use Iform\Creds\Config;

class ProfileIntegrationTest extends \PHPUnit_Framework_TestCase {

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

    public function testFetchesAll()
    {
        $profile = IformResource::profile();
        $results = $profile->withAllFields()->first(10)->fetchAll();
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
