<?php namespace Iform\Resources\Base;

use Iform\Creds\Profile;
use Iform\Resources\Contracts\Mapping;

/**
 * Class BaseResource | Template for resources
 *
 * @package Iform\Resources
 */
abstract class BaseResource implements Profile, Mapping {
    /**
     * Api gateway
     * @var Object
     */
    protected $gateway;
    /**
     * Base Url for api
     * @var string
     */
    protected $baseUrl = "";
    /**
     * Current under construction
     * @var string
     */
    protected $activeUrl = "";
    /**
     * Components for url writing
     * @var array
     */
    protected $urlComponents = array(
        'server'   => "",
        'profiles' => "",
        'api'      => "exzact/api/",
        '6.0'      => 'exzact/api/v60/'
    );
    /**
     * params needed for calls
     * @var array
     */
    protected $params = array();

    public function first($limit)
    {
        $this->params["limit"] = $limit;

        return $this;
    }

    public function last($limit)
    {
        $this->params["offset"] = $limit;

        return $this;
    }

    public function where($grammar)
    {
        $this->params["fields"] = $grammar;

        return $this;
    }

    public function create($values)
    {
        return $this->gateway->create($this->getSingleUrl(), $values);
    }

    public function update($id, $values)
    {
        return $this->gateway->update($this->getSingleUrl($id), $values);
    }

    public function delete($id)
    {
        return $this->gateway->delete($this->getSingleUrl($id));
    }

    public function fetch($id)
    {
        return $this->gateway->read($this->getSingleUrl($id), $this->params);
    }

    /**
     * Set the iFormBuilder user
     *
     * @param null $profile
     * @param null $server
     *
     * @throws \Exception
     */
    protected function setUser($profile = null, $server = null)
    {
        $profile = $profile ?: Profile::ID;
        $server = $server ?: Profile::SERVER;

        if (Profile::ID === "" || Profile::SERVER === "") {
            throw new \Exception("iFormBuilder user credentials not set.  Please provide a valid profile id and server location.");
        }

        $this->urlComponents["profiles"] = $profile;
        $this->urlComponents["server"] = $server;
    }

    /**
     * Grab a collection url
     *
     * @return mixed
     */
    protected function collectionUrl()
    {
        return ($this->activeUrl === "") ? $this->baseUrl : $this->activeUrl;
    }

    /**
     * Compose url for single resource
     *
     * @param $id
     *
     * @return string
     */
    protected function getSingleUrl($id = null)
    {
        if ($this->activeUrl === "") {
            $url = $this->baseUrl;
        } else {
            $url = $this->activeUrl;
        }
        return is_null($id) ? $url : $url . '/' . $id;
    }

    /**
     * Set gateway to data source
     * @param $gateway
     *
     * @return mixed
     */
    abstract protected function setGateway($gateway);

    /**
     * Set base url
     * @param $identifier
     *
     * @return mixed
     */
    abstract protected function setBaseUrl($identifier);

}