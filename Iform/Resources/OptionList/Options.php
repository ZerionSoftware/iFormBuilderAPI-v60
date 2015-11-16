<?php namespace Iform\Resources\OptionList;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Base\FullCollection;
use Iform\Resources\Contracts\BatchCommandMapper;
use Iform\Resources\Contracts\BatchQueryMapper;

class Options extends BaseResource implements BatchQueryMapper, BatchCommandMapper {

    private $collection;

    private static $baseElements = array("id", "key_value", "global_id", "label", "sort_order", "condition_value", "score", "localizations");

    function __construct(RequestHandler $gateway, FullCollection $collection = null, $optId)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($optId);

        $this->collection = $collection ?: new FullCollection();
        $this->collection->setLimit(1000);
    }

    public function updateAll($values = [])
    {
        $values = $this->formatBatch($values);

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    public function deleteAll($values = [])
    {
        $values = $this->formatBatch($values);

        return $this->gateway->delete($this->collectionUrl(), $values);
    }

    public function withAllFields()
    {
        return $this->where(implode(",", static::$baseElements));
    }

    /**
     * Fetch a collection of pages : default is to return all
     *
     * @param array $params
     *
     * @return string
     */
    public function fetchAll($params = [])
    {
        if (! empty($params)) $this->setParameters($params);

        //NOTE::parameters could still be set if helper method was used before call
        return empty($this->params)
            ? $this->collection->fetchCollection($this->gateway, $this->collectionUrl())
            : $this->gateway->read($this->collectionUrl(), $this->params);
    }

    /**
     * Set parameters : passed params will always take precedence
     *
     * @param $passed
     */
    private function setParameters($passed)
    {
        if (empty($this->params)) {
            $this->params = $passed;
        } else {
            $this->params = array_replace_recursive($this->params, $passed);
        }
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

    /**
     * @param array $values
     *
     * @return array
     * @throws \Exception
     */
    private function formatBatch(array $values)
    {
        if (isset($values[0])) {
            if (! is_array($values)) throw new \Exception("invalid batch format");
        } else {
            $values = array($values); //new to wrap single call in array
        }

        return $values;
    }

    function __destruct()
    {
        unset($this->gateway);  //kill connection
    }
}