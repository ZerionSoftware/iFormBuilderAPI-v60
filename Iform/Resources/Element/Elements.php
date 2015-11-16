<?php namespace Iform\Resources\Element;

use Iform\Resources\Base\BaseResource;
use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\FullCollection;
use Iform\Resources\Base\BatchValidator;
use Iform\Resources\Contracts\BatchCommandMapper;
use Iform\Resources\Contracts\BatchQueryMapper;

class Elements extends BaseResource implements BatchQueryMapper, BatchCommandMapper {

    use BatchValidator;
    /**
     * Full Collection Object
     *
     * @var FullCollection
     */
    private $collection;
    /**
     * Base
     *
     * @var array
     */
    private static $baseElements = array('id', 'name', 'global_id', 'version', 'label', 'description', 'data_type', 'data_size', 'created_date', 'created_by', 'modified_date', 'modified_by', 'widget_type', 'sort_order', 'optionlist_id',
        'default_value', 'low_value', 'high_value', 'dynamic_value', 'condition_value', 'client_validation', 'is_disabled', 'reference_id_1', 'reference_id_2',
        'reference_id_3', 'reference_id_4', 'reference_id_5', 'attachment_link', 'is_readonly', 'is_required', 'validation_message', 'is_action', 'smart_tbl_search',
        'smart_tbl_search_col', 'is_encrypt', 'is_hide_typing', 'on_change', 'keyboard_type', 'dynamic_label', 'weighted_score', 'localizations');

    function __construct(RequestHandler $gateway, $pageId, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($pageId);

        $this->collection = $collection ?: new FullCollection();
    }

    public function localizations($elementId)
    {
        $this->activeUrl = $this->getSingleUrl($elementId) . '/localizations';

        return $this;
    }

    public function withAllFields()
    {
        return $this->where(implode(",", static::$baseElements));
    }

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function updateAll($values = [])
    {
        $values = $this->formatBatch($values);

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    /**
     * @param array $values
     *
     * @return mixed
     */
    public function deleteAll($values = [])
    {
        $values = $this->formatBatch($values);

        return $this->gateway->delete($this->collectionUrl(), $values);
    }

    /**
     * @param array $values
     *
     * @return array
     * @throws \Exception
     */
    private function formatBatch($values)
    {
        if (isset($values[0])) {
            if (! is_array($values[0])) throw new \InvalidArgumentException("invalid batch format");
        } else {
            $values = array($values); //new to wrap single call in array
        }

        return $values;
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