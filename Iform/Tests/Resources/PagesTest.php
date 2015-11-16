<?php

require_once 'BaseResourceTest.php';

use Iform\Resources\Page\Pages;

class PagesTest extends BaseResourceTest {

    private static $pattern = "";
    private static $basePattern = "";
    private static $id;

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/pages\/" . static::$id;
        static::$basePattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/pages";

        $this->setResourceType('Iform\Resources\Page\Pages');
        parent::setUp();
    }

    public function testFetchAll()
    {
        $response = $this->resource->fetchAll();
        //collection queries should be array
        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testUpdateCommand()
    {
        $values = ['name' => 'test'];
        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . static::$pattern . "/", $values);

        $page = new Pages($this->mock);
        $page->update(static::$id, $values);
    }

    public function testDeleteCommand()
    {
        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . static::$pattern . "/");

        $page = new Pages($this->mock);
        $page->delete(static::$id);
    }

    public function testCreateCommand()
    {
        $values = ['name' => 'test'];
        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . static::$basePattern . "/", $values);

        $page = new Pages($this->mock);
        $page->create($values);
    }

    public function testAlertsFetchAll()
    {
        $response = $this->resource->alerts(static::$id)
                                   ->fetchAll(['email']);

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testHttpFetch()
    {
        $response = $this->resource->http(static::$id)
                                   ->fetch(787878);

        $this->assertArrayHasKey('id', json_decode($response, true));
    }

    public function testHttpFetchAll()
    {
        $response = $this->resource->http(static::$id)
                                   ->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testHttpUpdateCommand()
    {
        $httpId = 15420;
        $pattern = static::$pattern . '\/http_callbacks\/' . $httpId;
        $values = ['url' => 'test.com'];

        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $page = new Pages($this->mock);
        $page->http(static::$id)
             ->update($httpId, $values);
    }

    public function testHttpDeleteCommand()
    {
        $httpId = 15420;
        $pattern = static::$pattern . '\/http_callbacks\/' . $httpId;

        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . $pattern . "/");

        $page = new Pages($this->mock);
        $page->http(static::$id)
             ->delete($httpId);
    }

    /**
     * Alerts are collection ONLY resources
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testAlertsDeleteCommandFails()
    {
        $alertId = 15420;
        $page = new Pages($this->mock);

        $page->alerts(static::$id)
             ->delete($alertId);
    }

    /**
     * Updating http collections not allowed
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testAlertsUpdateAllCommandFails()
    {
        $page = new Pages($this->mock);

        $page->http(static::$id)
             ->updateAll(array('id' => '123123'));
    }

    public function testLocalizationsFetch()
    {
        $response = $this->resource->localizations(static::$id)
                                   ->fetch('es');

        $this->assertArrayHasKey('language_code', json_decode($response, true));
    }

    function tearDown()
    {
        static::$id = "";
        static::$pattern = "";
        static::$basePattern = "";
        parent::tearDown();
    }
}



