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
     * object pool
     * @var array
     */
    private static $pool = [];
    /**
     * share gateway
     */
    public static function setup()
    {
        static::$handler = static::$handler ?: new RequestHandler(new TokenResolver());
    }

    private static function getObject($key, $identifier = null)
    {
        if (! array_key_exists($key, static::$pool)) {
            $className = ucfirst($key);
            static::setup();
            static::$pool[$key] = new $className(static::$handler);
        }

        if (! is_null($identifier)) {
            $clone = clone(static::$pool[$key]);
            //TODO:: clear object with existing identifier
            if (method_exists($clone, 'setIdentifier')) {
                $clone->setIdentifier($identifier);
            }
            static::$pool[$key] = $clone;
        }

        return static::$pool[$key];
    }

    /**
     * Page instance
     *
     * @return Pages
     */
    public static function pages()
    {
        static::setup();

        return new Pages(static::$handler);
    }

    public static function records($pageId)
    {
        static::setup();

        return new Records(static::$handler, $pageId);
    }

    public static function optionLists()
    {
        static::setup();

        return new OptionLists(static::$handler);
    }

    public static function options($optId)
    {
        static::setup();

        return new Options(static::$handler, $optId);
    }

    public static function users()
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