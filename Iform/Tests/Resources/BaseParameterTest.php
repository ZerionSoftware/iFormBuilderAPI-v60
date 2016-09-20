<?php  namespace Iform\Tests\Resources;

use Iform\Resources\Base\BaseParameter;

class BaseParameterTest extends \PHPUnit_Framework_TestCase {

    function testElementDoesNotIncludeLocalizationByDefault()
    {
        $params = BaseParameter::element();

        $this->assertNotContains('localizations', $params);
    }

    function testElementDoesIncludeLocalizationByRequest()
    {
        $params = BaseParameter::element(true);

        $this->assertContains('localizations', $params);
    }

    function testPageDoesNotIncludeLocalizationByDefault()
    {
        $params = BaseParameter::page();

        $this->assertNotContains('localizations', $params);
    }

    function testPageDoesIncludeLocalizationByRequest()
    {
        $params = BaseParameter::page(true);

        $this->assertContains('localizations', $params);
    }

    function testOptionDoesNotIncludeLocalizationByDefault()
    {
        $params = BaseParameter::option();

        $this->assertNotContains('localizations', $params);
    }

    function testOptionDoesIncludeLocalizationByRequest()
    {
        $params = BaseParameter::option(true);

        $this->assertContains('localizations', $params);
    }

    function testUserBaseParams()
    {
        $this->assertEquals(array(
            "id",
            "username",
            "global_id",
            "first_name",
            "last_name",
            "email",
            "created_date",
            "is_locked",
            "roles"
        ),  BaseParameter::user());
    }

    function testUserGroupBaseParams()
    {
        $this->assertEquals(array(
            'id',
            'users',
            'global_id',
            'version',
            'name',
            'created_date'
        ), BaseParameter::userGroup());
    }

    function testOptionListBaseParams()
    {
        $this->assertEquals(array(
            "id",
            "name",
            "global_id",
            "version",
            "created_date",
            "created_by",
            "modified_date",
            "modified_by",
            "reference_id",
            "option_icons"
        ), BaseParameter::optionList());
    }

    function testProfileBaseParameters()
    {
        $this->assertEquals(array(
            'id',
            'name',
            'global_id',
            'version',
            'address1',
            'address2',
            'city',
            'zip',
            'state',
            'country',
            'phone',
            'fax',
            'email',
            'max_user',
            'max_page',
            'is_active',
            'created_date',
            'type',
            'support_hours',
            'time_zone'
        ), BaseParameter::profile());
    }

}
