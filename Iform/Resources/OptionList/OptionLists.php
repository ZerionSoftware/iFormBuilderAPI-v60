<?php namespace Iform\Resources\OptionList;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseParameter;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Base\FullCollection;
use Iform\Resources\Base\BatchValidator;

class OptionLists extends BaseResource {

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
        $this->setUser();
        $this->setBaseUrl($this->urlComponents['profiles']);
    }

    protected function getAllFields()
    {
        return BaseParameter::optionList();
    }
    /**
     * Set base url
     *
     * @param $identifier
     *
     * @return mixed
     */
    protected function setBaseUrl($identifier)
    {
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/optionlists";
        $this->baseUrl = sprintf($baseUrl, $identifier);
    }

    /**
     * Set gateway to data source
     *
     * @param $gateway
     *
     * @return mixed
     */
    protected function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    function __destruct()
    {
        unset($this->gateway);  //kill connection
    }
}
