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
     *
     * @var array
     */
    private static $pool = array();
    /**
     * Singleton instance
     * @var null
     */
    private static $instance = null;

    /**
     * share gateway
     */
    public static function getHandler()
    {
        if (is_null(static::$handler)) {
            static::$handler = new RequestHandler(new TokenResolver());
        }

        return static::$handler;
    }

    private static function acquireObject($key, $identifier = null)
    {
        if (! array_key_exists($key, static::$pool)) {
            $className = ucfirst($key);
            if (! is_null($identifier)) {
                static::$pool[$key] = new $className(static::getHandler(), $identifier);
//                $clone = clone(static::$pool[$key]);
//                static::$pool[$key][$identifier] = $clone;
//                if (method_exists($clone, 'reset')) $clone->reset(array(), $identifier);
            } else {
                static::$pool[$key] = new $className(static::getHandler());
            }
        } else {
            if (method_exists(static::$pool[$key], 'reset')) {
                if (! is_null($identifier)) {
                    static::$pool[$key]->reset(array(), $identifier);
                } else {
                    static::$pool[$key]->reset();
                }
            }
        }

        return static::$pool[$key];
    }

    private static function releaseObject($key)
    {
        unset(static::$pool[$key]);
    }

    /**
     * Page instance
     *
     * @return Pages
     */
    public static function pages()
    {
        static::getInstance();

        return static::acquireObject('Iform\Resources\Page\Pages');
    }

    public static function records($pageId)
    {
        static::getInstance();

        return static::acquireObject('Iform\Resources\Record\Records', $pageId);
    }

    public static function optionLists()
    {
        static::getInstance();

        return static::acquireObject('Iform\Resources\OptionList\OptionLists');
    }

    public static function options($optId)
    {
        static::getInstance();

        return static::acquireObject('Iform\Resources\OptionList\Options', $optId);
    }

    public static function users()
    {
        static::getInstance();

        return static::acquireObject('Iform\Resources\User\Users');
    }

    public static function profile()
    {
        static::getInstance();

        return static::acquireObject('Iform\Resources\Profile\Profile');
    }

    public static function elements($pageId)
    {
        static::getInstance();

        return static::acquireObject('Iform\Resources\Element\Elements', $pageId);
    }

    /**
     * *Singleton* instance of this class.
     *
     * @return void
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}

