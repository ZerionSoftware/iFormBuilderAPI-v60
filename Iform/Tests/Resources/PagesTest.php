<?php namespace Iform\Tests\Resources;

use Iform\Resources\Base\BaseParameter;
use Iform\Resources\Page\Pages;
use Iform\Tests\Resources\BaseResourceTest;

class PagesTest extends BaseResourceTest {

    function setUp()
    {
        static::$id = 7777777;
        static::$pattern = "iformbuilder\.com\/exzact\/api\/v60\/profiles\/[0-9]+\/pages";

        $this->setResourceType('Iform\Resources\Page\Pages');
        parent::setUp();
    }

    public function testFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testReturnsCorrectLabel() {
        $resource = $this->instantiate($this->stub);

        $this->assertEquals(BaseParameter::page(), $resource->getAllFields());
    }

    /***************************************************************
     * Alerts
     ***************************************************************/

    public function testAlertsFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->alerts(static::$id)
                             ->fetchAll(['email']);

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testAlertsDeleteAllCommand()
    {
        $pattern = static::$pattern . "\/" . static::$id . '\/email_alerts';
        $values = [
            [
                "email" => "inspector_1@iformbuilder.com"
            ],
            [
                "email" => "inspector_2@iformbuilder.com"
            ]
        ];

        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $this->resource->alerts(static::$id)
                       ->deleteAll($values);
    }

    /**
     * Deleting alerts
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testAlertsDeleteShouldFail()
    {
        $resource = $this->instantiate($this->stub);
        $resource->alerts(static::$id)
                 ->delete(123123);
    }

    /***************************************************************
     * HTTP Callbacks
     ***************************************************************/

    public function testHttpFetch()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->http(static::$id)
                             ->fetch(787878);

        $this->assertArrayHasKey('id', json_decode($response, true));
    }

    public function testHttpFetchAll()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->http(static::$id)
                             ->fetchAll();

        $this->assertInternalType('array', json_decode($response, true));
    }

    public function testHttpUpdateCommand()
    {
        $httpId = 15420;
        $pattern = static::$pattern . "\/" . static::$id . '\/http_callbacks\/' . $httpId;
        $values = ['url' => 'test.com'];

        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $this->resource->http(static::$id)
                       ->update($httpId, $values);
    }

    public function testHttpDeleteCommand()
    {
        $httpId = 15420;
        $pattern = static::$pattern . "\/" . static::$id . '\/http_callbacks\/' . $httpId;

        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . $pattern . "/");

        $this->resource->http(static::$id)
                       ->delete($httpId);
    }

    public function testHttpCreateCommand()
    {
        $pattern = static::$pattern . "\/" . static::$id . '\/http_callbacks';
        $values = [
            "name"                   => "New Record Endpoint",
            "url"                    => "https=>//www.iformbuilder.com/post_http_endpoint.php",
            "feed_format"            => "json",
            "content_type"           => "header",
            "is_gauranteed_delivery" => true
        ];

        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $this->resource->http(static::$id)
                       ->create($values);
    }

    /****************************************`***********************
     * Assignments
     ***************************************************************/

    public function testAssignmentCreateCommand()
    {
        $pattern = static::$pattern . "\/" . static::$id . '\/assignments';
        $values = [
            "user_id" => 161236,
            "can_collect" => true
        ];

        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $this->resource->assignments(static::$id)
                       ->create($values);
    }

    /***************************************************************
     * Localizations
     ***************************************************************/

    public function testLocalizationsFetch()
    {
        $resource = $this->instantiate($this->stub);
        $response = $resource->localizations(static::$id)
                             ->fetch('es');

        $this->assertArrayHasKey('language_code', json_decode($response, true));
    }

    public function testLocalizationsUpdateCommand()
    {
        $language_code = "es";
        $pattern = static::$pattern . "\/" . static::$id . '\/localizations\/' . $language_code;
        $values = ['url' => 'test.com'];

        $this->mock->shouldReceive('update')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $this->resource->localizations(static::$id)
                       ->update($language_code, $values);
    }

    public function testLocalizationsDeleteCommand()
    {
        $language_code = "es";
        $pattern = static::$pattern . "\/" . static::$id . '\/localizations\/' . $language_code;

        $this->mock->shouldReceive('delete')
                   ->once()
                   ->with("/" . $pattern . "/");

        $this->resource->localizations(static::$id)
                       ->delete($language_code);
    }

    public function testLocalizationsCreateCommand()
    {
        $pattern = static::$pattern . "\/" . static::$id . '\/localizations';
        $values = ['language_code' => 'es'];

        $this->mock->shouldReceive('create')
                   ->once()
                   ->with("/" . $pattern . "/", $values);

        $this->resource->localizations(static::$id)
                       ->create($values);
    }

    /**
     * Alerts are collection ONLY resources
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testAlertsDeleteCommandFails()
    {
        $alertId = 15420;
        $this->resource->alerts(static::$id)
                       ->delete($alertId);
    }

    /**
     * Updating http collections not allowed
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testAlertsUpdateAllCommandFails()
    {
        $this->resource->http(static::$id)
                       ->updateAll(array('id' => '123123'));
    }

    /**
     * Updating http collections not allowed
     *
     * @expectedException \Iform\Exceptions\InvalidCallException
     */
    public function testUpdateAllCommand()
    {
        $resource = $this->instantiate($this->stub);
        $result = json_decode($resource->updateAll(array('id' => '123123')));
    }


    function tearDown()
    {
        parent::tearDown();
    }
}



