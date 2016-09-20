<?php namespace Iform\Resources\Element;

use Iform\Resources\Base\BaseResource;
use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\FullCollection;
use Iform\Resources\Base\BatchValidator;
use Iform\Resources\Contracts\BatchCommandMapper;
use Iform\Resources\Contracts\BatchQueryMapper;
use Iform\Resources\Base\BaseParameter;

class Elements extends BaseResource implements BatchQueryMapper, BatchCommandMapper {

    function __construct(RequestHandler $gateway, $pageId, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($pageId);

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
        $this->setBaseUrl($identifier);
    }

    public function localizations($elementId)
    {
        $this->activeUrl = $this->getSingleUrl($elementId) . '/localizations';

        return $this;
    }

    protected function getAllFields()
    {
        return BaseParameter::element();
    }

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function updateAll($values = array())
    {
        $values = BatchValidator::formatBatch($values);

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function deleteAll($values = array())
    {
        list($url, $values) = $this->getFormattedBatchParams($values);

        return $this->gateway->delete($url, $values);
    }

    protected function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    protected function setBaseUrl($pageId)
    {
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/pages/%d/elements";
        $this->baseUrl = sprintf($baseUrl, $this->urlComponents['profiles'], $pageId);
    }

    function __destruct()
    {
        unset($this->gateway);  //kill connection
    }
}
