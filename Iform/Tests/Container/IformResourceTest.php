<?php

use Iform\Resources\IformResource;

class IformResourceTest extends \PHPUnit_Framework_TestCase {

    public function testLoadsPageResource()
    {
        $page = IformResource::pages();
        $this->assertInstanceOf('Iform\Resources\Page\Pages', $page);
    }

    public function testLoadsProfileResource()
    {
        $profile = IformResource::profile();
        $this->assertInstanceOf('Iform\Resources\Profile\Profile', $profile);
    }

    public function testLoadsUserResource()
    {
        $users = IformResource::users();
        $this->assertInstanceOf('Iform\Resources\User\Users', $users);
    }

    public function testLoadsOptionListResource()
    {
        $optionList = IformResource::options(88883);
        $this->assertInstanceOf('Iform\Resources\OptionList\Options', $optionList);
    }

    public function testLoadsRecordsResource()
    {
        $records = IformResource::records(88883);
        $this->assertInstanceOf('Iform\Resources\Record\Records', $records);
    }

    public function testLoadsOptionResource()
    {
        $optionList = IformResource::optionLists();
        $this->assertInstanceOf('Iform\Resources\OptionList\OptionLists', $optionList);
    }

    public function testLoadsElementResource()
    {
        $page = IformResource::elements(8989898);
        $this->assertInstanceOf('Iform\Resources\Element\Elements', $page);
    }

    public function testSharesInstanceOfRequestHandler()
    {
        $page = IformResource::pages();
        $this->assertInstanceOf('Iform\Resources\Page\Pages', $page);

        $element = IformResource::elements(8989898);
        $this->assertInstanceOf('Iform\Resources\Element\Elements', $element);
    }

    public function loadsResourceAfterUserChange()
    {
        $users = IformResource::users();
        $this->assertInstanceOf('Iform\Resources\User\Users', $users);

        $element = IformResource::elements(8989898);
        $this->assertInstanceOf('Iform\Resources\Element\Elements', $element);
    }

}
