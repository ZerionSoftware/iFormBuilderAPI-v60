<?php namespace Iform\Resources\OptionList;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseParameter;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Base\BatchValidator;
use Iform\Resources\Base\FullCollection;
use Iform\Resources\Contracts\BatchCommandMapper;

class Options extends BaseResource implements BatchCommandMapper {

    function __construct(RequestHandler $gateway,  $optId, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($optId);

        $this->collection = $collection ?: new FullCollection();
        $this->collection->setLimit(1000);
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
        $this->setBaseUrl($identifier);
    }

    public function updateAll($values = array())
    {
        $values = BatchValidator::formatBatch($values);

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    public function deleteAll($values = array())
    {
        list($url, $values) = $this->getFormattedBatchParams($values);

        return $this->gateway->delete($url, $values);
    }

    protected function getAllFields()
    {
        return BaseParameter::option();
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
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/optionlists/%d/options";
        $this->baseUrl = sprintf($baseUrl, $this->urlComponents['profiles'], $identifier);
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