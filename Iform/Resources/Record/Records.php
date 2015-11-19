<?php namespace Iform\Resources\Record;

use Iform\Resources\Base\BaseResource;
use Iform\Exceptions\InvalidCallException;
use Iform\Resources\Contracts\BatchCommandMapper;
use Iform\Resources\Contracts\BatchQueryMapper;
use Iform\Resources\Base\FullCollection;
use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BatchValidator;
use Iform\Resources\IformResource;

class Records extends BaseResource implements BatchQueryMapper, BatchCommandMapper {

    use BatchValidator;
    /**
     * Collection Object
     *
     * @var FullCollection
     */
    private $collection;
    private static $baseRecord = array(
        'parent_record_id',
        'parent_page_id',
        'parent_element_id',
        'created_device_id',
        'javascript_state'
    );
    /**
     * Page for element
     *
     * @var int
     */
    private $pageId;

    function __construct(RequestHandler $gateway, $pageId, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($pageId);

        $this->pageId = $pageId;
        $this->collection = $collection ?: new FullCollection();
    }

    /**
     * Set base url
     *
     * @param $pageId
     *
     * @return mixed
     */
    protected function setBaseUrl($pageId)
    {
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/pages/%d/records";
        $this->baseUrl = sprintf($baseUrl, $this->urlComponents['profiles'], $pageId);
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

    public function withAllFields()
    {
        return $this->where($this->findAllElementFields());
    }

    /**
     * Fetch element and filter fields for record
     *
     * @return string
     */
    private function findAllElementFields()
    {
        $fields = array();
        $elemResource = IformResource::elements($this->pageId);
        $elements = json_decode($elemResource->withAllFields()
                                             ->fetchAll(), true);

        foreach ($elements as $element) {
            if (! $this->notCollectedType($element['data_type'])) {
                array_push($fields, $element['name']);
            }
        }

        return implode(",", array_merge(self::$baseRecord, $fields));
    }

    /**
     * @param $type
     *
     * @return bool
     */
    private function notCollectedType($type)
    {
        $doNotAdd = array(16, 17, 35, 32, 18);

        return in_array($type, $doNotAdd);
    }

    /**
     * Do not fetch a large data set in memory to avoid exhaustion
     * Recommended: fetch each collection (1000 rows) and process
     * @param array $params
     *
     * @return string
     * @throws InvalidCallException
     */
    public function fetchAll($params = [])
    {
        $this->params = $this->combine($params, $this->params);

        if (empty($this->params) || ! isset($this->params['limit'])) {
            if (! $this->inEndpoint('assignments')) {
                throw new InvalidCallException("Record collection limit must be set");
            }
            $results = $this->collection->fetchCollection($this->gateway, $this->collectionUrl(), $this->params);
        } else {
            $results = $this->gateway->read($this->collectionUrl(), $this->params);
        }

        return $results;
    }

    /**
     * Update behaves differently in record
     *
     * @param $id
     * @param $values
     *
     * @return mixed
     * @throws InvalidCallException
     * @override
     */
    public function update($id, $values)
    {
        if ($this->inEndpoint('assignments')) {
            throw new InvalidCallException("record assignments cannot be updated through api");
        }

        return $this->gateway->update($this->getSingleUrl($id), $values);
    }

    /**
     * @param array $values
     *
     * @return mixed
     * @throws InvalidCallException
     */
    public function updateAll($values = [])
    {
        if ($this->inEndpoint('assignments')) {
            throw new InvalidCallException("record assignments cannot be updated through api");
        }

        $values = $this->formatBatch($values);

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    private function inEndpoint($part)
    {
        return strpos($this->activeUrl, $part) !== false;
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

    public function assignments($recordId)
    {
        $this->activeUrl = $this->getSingleUrl($recordId) . '/assignments';

        return $this;
    }

    function __destruct()
    {
        unset($this->gateway);  //kill connection
    }
}