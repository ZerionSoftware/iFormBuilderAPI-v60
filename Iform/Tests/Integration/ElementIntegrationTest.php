<?php

use Iform\Resources\IformResource;
use Iform\Creds\Config;

class ElementIntegrationTest extends \PHPUnit_Framework_TestCase {

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

    public function testJSONParameters()
    {
        $elements = IformResource::elements(801697);

        $json = '[{"id": "20607982","optionlist_id": "405328"}]';
        $params = json_decode($json, true);

        $update = json_decode($elements->updateAll($params));
        $this->assertEquals(20607982, $update[0]->id);
    }

    public function testFetchAll()
    {
        $elements = IformResource::elements(797782);
        $params = [
            ['id' => 20614129],
            ['id' => 20614792]
        ];

        $results = $elements->deleteAll($params);
    }

    public function testDelete()
    {
        $elements = IformResource::elements(794890);
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
