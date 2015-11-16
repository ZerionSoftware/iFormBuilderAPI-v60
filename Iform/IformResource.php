<?php

use Iform\Resolvers\RequestHandler;
use Iform\Resolvers\TokenResolver;
use Iform\Resources\Page\Pages;

/**
 * Class IformResource
 *
 * Resource Container
 * @package IformResourceFramework
 */
class IformResource {

    /**
     * Page instance
     * @return Pages
     */
    public static function page()
    {
        $handler = new RequestHandler(new TokenResolver());
        $pageResource = new Pages($handler);

        return $pageResource;
    }

    public static function record()
    {

    }

    public static function optionList()
    {

    }

    public static function options()
    {

    }

    public static function user()
    {

    }

    public static function profile()
    {

    }


}