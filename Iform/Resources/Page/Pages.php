<?php namespace Iform\Resources\Page;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Base\BaseParameter;
use Iform\Resources\Base\FullCollection;
use Iform\Exceptions\InvalidCallException;
use Iform\Resources\Contracts\BatchCommandMapper;

class Pages extends BaseResource implements BatchCommandMapper {

    private $getAll = false;

    function __construct(RequestHandler $gateway, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($this->urlComponents['profiles']);

        $this->collection = $collection ?: new FullCollection();
    }

    /**
     * @override
     * @param array $dependencies
     * @param null  $identifier
     */
    public function reset($dependencies = array(), $identifier = null)
    {
        if (isset($dependencies['gateway'])) {
            $this->setGateway($dependencies['gateway']);
        }

        $this->params = array();
        $this->setUser();
        $this->setBaseUrl($this->urlComponents['profiles']);
    }

    protected function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Setup Page HTTP Callback
     *
     * @param $pageId
     *
     * @return $this
     */
    public function http($pageId)
    {
        $this->activeUrl = $this->getSingleUrl($pageId) . '/http_callbacks';

        return $this;
    }

    /**
     * Setup Page Email Alert
     *
     * @param $pageId
     *
     * @return $this
     */
    public function alerts($pageId)
    {
        $this->activeUrl = $this->getSingleUrl($pageId) . '/email_alerts';

        return $this;
    }

    /**
     * Setup Page Localizations
     *
     * @param $pageId
     *
     * @return $this
     * @throws InvalidCallException
     */
    public function localizations($pageId)
    {
        $this->activeUrl = $this->getSingleUrl($pageId) . '/localizations';

        return $this;
    }

    /**
     * Set assignment builder
     *
     * @param $pageId
     *
     * @return $this
     * @throws InvalidCallException
     */
    public function assignments($pageId)
    {
        $this->activeUrl = $this->getSingleUrl($pageId) . '/assignments';

        return $this;
    }

    /**
     * Return base parameters
     *
     * @return array
     */
    public function getAllFields()
    {
        $this->getAll = true;

        return BaseParameter::page();
    }

    public function deleteAll($values = array())
    {
        if (! $resource = $this->isCollectionResource()) {
            throw new InvalidCallException("Can only delete certain types of page resource collections.");
        }

        return $this->gateway->delete($this->collectionUrl(), $values);
    }

    public function updateAll($values = array())
    {
        $resource = $this->isCollectionResource();
        if (! $resource || $resource === 'http_callbacks' ) {
            throw new InvalidCallException("Cannot update this type of collections.");
        }

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    /**
     * Limit batch methods for pages resources
     *
     * @return bool|mixed
     */
    private function isCollectionResource()
    {
        $valid = array('http_callbacks', 'localizations', 'email_alerts', 'assignments');
        $isValid = array_values(
            array_filter($valid, function ($resource) {
                return strpos($this->activeUrl, $resource) !== false;
            })
        );

        return count($isValid) ? array_shift($isValid) : false;
    }

    /**
     * Override base single url method
     *
     * @param $id
     *
     * @return string
     * @override
     * @throws InvalidCallException
     */
    protected function getSingleUrl($id = null)
    {
        if ($this->activeUrl === "") {
            $url = $this->baseUrl;
        } else {
            if (strpos($this->activeUrl, 'email_alerts') !== false && ! is_null($id)) {
                throw new InvalidCallException("Email alerts are a collection only resource");
            }
            $url = $this->activeUrl;
        }

        return is_null($id) ? $url : $url . '/' . $id;
    }

    protected function setBaseUrl($profile)
    {
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/pages";
        $this->baseUrl = sprintf($baseUrl, $profile);
    }

    function __destruct()
    {
        unset($this->gateway);  //kill connection
    }
}