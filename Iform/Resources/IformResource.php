<?php namespace Iform\Resources;

use Iform\Resolvers\RequestHandler;
use Iform\Resolvers\TokenResolver;
use Iform\Resources\Page\Pages;
use Iform\Resources\Record\Records;
use Iform\Resources\OptionList\OptionLists;
use Iform\Resources\OptionList\Options;
use Iform\Resources\Element\Elements;
use Iform\Resources\User\Users;
use Iform\Resources\Profile\Profile;

/**
 * Class IformResource
 * Resource Container
 *
 * @package ResourceFramework
 */
class IformResource {

    /**
     * Requests
     *
     * @var RequestHandler
     */
    private static $handler = null;

    /**
     * share gateway
     */
    public static function setup()
    {
        static::$handler = static::$handler ?: new RequestHandler(new TokenResolver());
    }

    /**
     * Page instance
     *
     * @return Pages
     */
    public static function page()
    {
        static::setup();

        return new Pages(static::$handler);
    }

    public static function record($pageId)
    {
        static::setup();

        return new Records(static::$handler, $pageId);
    }

    public static function optionList()
    {
        static::setup();

        return new OptionLists(static::$handler);
    }

    public static function options($optId)
    {
        static::setup();

        return new Options(static::$handler, $optId);
    }

    public static function user()
    {
        static::setup();

        return new Users(static::$handler);
    }

    public static function profile()
    {
        static::setup();

        return new Profile(static::$handler);
    }

    public static function elements($pageId)
    {
        static::setup();

        return new Elements(static::$handler, $pageId);
    }
}