<?php namespace Iform\Resources\OptionList;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Base\FullCollection;
use Iform\Resources\Contracts\BatchQueryMapper;
use Iform\Resources\Base\BatchValidator;

class OptionLists extends BaseResource implements BatchQueryMapper {

    use BatchValidator;
    /**
     * Full collection object
     *
     * @var FullCollection
     */
    private $collection;
    /**
     * Base
     *
     * @var array
     */
    private static $baseElements = array("id", "name", "global_id", "version", "created_date", "created_by", "modified_date", "modified_by", "reference_id", "option_icons");

    function __construct(RequestHandler $gateway, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($this->urlComponents['profiles']);

        $this->collection = $collection ?: new FullCollection();
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
        $this->params = $this->combine($params, $this->params);

        //NOTE::parameters could still be set if helper method was used before call
        return empty($this->params)
            ? $this->collection->fetchCollection($this->gateway, $this->collectionUrl())
            : $this->gateway->read($this->collectionUrl(), $this->params);
    }

    public function withAllFields()
    {
        return $this->where(implode(",", static::$baseElements));
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
}