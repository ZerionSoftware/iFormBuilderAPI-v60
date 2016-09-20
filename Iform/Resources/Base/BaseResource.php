<?php namespace Iform\Resources\Base;

use Iform\Creds\Profile;
use Iform\Resources\Contracts\Mapping;
use Iform\Creds\Config;
use Iform\Resources\Base\BatchValidator;

/**
 * Class BaseResource | Template for resources
 *
 * @package Iform\Resources
 */
abstract class BaseResource implements Mapping {
    /**
     * Api gateway
     *
     * @var Object
     */
    protected $gateway;
    /**
     * Collection objects
     *
     * @var object
     */
    protected $collection;
    /**
     * Base Url for api
     *
     * @var string
     */
    protected $baseUrl = "";
    /**
     * Current under construction
     *
     * @var string
     */
    protected $activeUrl = "";
    /**
     * Components for url writing
     *
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
     *
     * @var array
     */
    protected $params = array();
    /**
     * Fetch last resource in collection
     * @var bool
     */
    protected $getLast = false;
    /**
     * Fetch an offset
     * @var bool
     */
    protected $getIncrement = false;

    public function first($limit)
    {
        $this->params["limit"] = $limit;

        return $this;
    }

    public function last($limit)
    {
        $this->getLast = true;
        $this->params["offset"] = $limit;

        return $this;
    }

    public function next($limit)
    {
        $this->getIncrement = true;
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

    public function copy($id)
    {
        return $this->gateway->copy($this->getSingleUrl($id), $this->params);
    }

    public function fetchAll($params = array())
    {
        $this->params = BatchValidator::combine($params, $this->params);

        if ($this->isFiltered()) {
            $results = $this->gateway->read($this->collectionUrl(), $this->params);
        } elseif ($this->getLast) {
            $results = $this->collection->fetchLastInCollection($this->gateway, $this->collectionUrl(), $this->params);
            $this->getLast = false;
        } elseif ($this->getIncrement) {
            $results = $this->collection->fetchIncrement($this->gateway, $this->collectionUrl(), $this->params);
            $this->getIncrement = false;
        } else {
            $results = $this->collection->fetchCollection($this->gateway, $this->collectionUrl(), $this->params);
        }

        return $results;
    }

    protected function hasFilterGrammar()
    {
        if (isset($this->params['fields'])) {
            if (strpos($this->params['fields'],":>") !== false || strpos($this->params['fields'],":<") !== false) {
                return false;
            }
            return preg_match("/[><=!~]/", $this->params['fields']);
        }
    }

    protected function isFiltered()
    {
        return (! $this->getIncrement && isset($this->params['limit'])) || $this->hasFilterGrammar();
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
        Config::getInstance();
        $this->urlComponents["profiles"] = Config::getUser();
        $this->urlComponents["server"] = Config::getServer();
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
     * wrapper for count
     *
     * @return bool
     */
    public function getResponseBodyCount()
    {
        return $this->collection->getCountFromHeader();
    }

    public function withAllFields()
    {
        $allFields = $this->getAllFields();

        if (isset($this->params['fields'])) {
            BatchValidator::combineWithGrammarFields($allFields, $this->params['fields']);
        }

        return $this->where(implode(",", $allFields));
    }

    protected function getFormattedBatchParams($values)
    {
        $values = (! empty($values)) ? BatchValidator::formatBatch($values) : $values;
        $url = ! empty($this->params) ? BatchValidator::formatUrlWithHttpParams($this->collectionUrl(), $this->params): $this->collectionUrl();

        return array($url, $values);
    }

    /**
     * Find part of the url
     *
     * @param $part
     *
     * @return bool
     */
    protected function inEndpoint($part)
    {
        return strpos($this->activeUrl, $part) !== false;
    }

    public function reset($dependencies = array(), $identifier = null){}

    /**
     * Set gateway to data source
     *
     * @param $gateway
     *
     * @return mixed
     */
    abstract protected function setGateway($gateway);

    /**
     * Set base url
     *
     * @param $identifier
     *
     * @return mixed
     */
    abstract protected function setBaseUrl($identifier);

    /**
     * Get all available parameter fields for resource
     *
     * @return mixed
     */
    abstract protected function getAllFields();
}
