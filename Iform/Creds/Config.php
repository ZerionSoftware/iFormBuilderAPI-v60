<?php namespace Iform\Creds;
/**
 * Class Config | Singleton
 *
 * @package Iform\Creds
 */
class Config {

    /**
     * Instance var
     * @var null
     */
    private static $instance = null;
    /**
     * client id : profile id assigned in api apps
     */
    private static $id;
    /**
     * server :  "https://YOURCOMPANYSERVER.iformbuilder.com/"
     */
    private static $server;
    /**
     * Username
     * @var
     */
    private static $username;
    /**
     * pw
     * @var
     */
    private static $pw;
    /**
     * optional workflow for jwt
     * @var
     */
    private static $client;
    /**
     * optional workflow for jwt
     * @var
     */
    private static $secret;

    /**
     * Setter
     *
     * @param $server
     */
    public static function setServer($server)
    {
        self::$server = $server;
    }

    /**
     * Setter
     *
     * @param $id
     */
    public static function setUser($id)
    {
        self::$id = $id;
    }

    /**
     * Setter
     *
     * @param $username
     */
    public static function setUsername($username)
    {
        self::$username = $username;
    }


    /**
     * Setter
     *
     * @param $pw
     */
    public static function setPassword($pw)
    {
        self::$pw = $pw;
    }

    /**
     * @return mixed
     */
    public static function getUsername()
    {
        return self::$username;
    }

    /**
     * @return mixed
     */
    public static function getPassword()
    {
        return self::$pw;
    }


    /**
     * Getter
     *
     * @return mixed
     */
    public static function getUser()
    {
        return self::$id;
    }

    /**
     * Getter
     *
     * @return mixed
     */
    public static function getServer()
    {
        return self::$server;
    }

    /**
     * Oauth Getter
     *
     * @return string
     */
    public static function getOauth()
    {
        return self::$server . "exzact/api/oauth/token";
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
     * api setup wrapper
     * @param $config
     */
    public static function api($config)
    {
        static::getInstance();

        if (isset($config['profile'])) static::setUser($config['profile']);
        if (isset($config['server'])) static::setServer( static::zerionEndpoint($config['server']));
        if (isset($config['client'])) static::$client = $config['client'];
        if (isset($config['server'])) static::$secret  = $config['server'];
    }

    /**
     * Builder zerion endpoint
     * @param $serverName
     *
     * @return string
     */
    protected static function zerionEndpoint($serverName)
    {
        return "https://" .$serverName .".iformbuilder.com/";
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