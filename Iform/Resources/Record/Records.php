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
     * @override
     *
     * @param array $dependencies
     * @param null  $identifier
     */
    public function reset($dependencies = array(), $identifier = null)
    {
        if (isset($dependencies['gateway'])) {
            $this->setGateway($dependencies['gateway']);
        }

        $this->pageId = $identifier;
        $this->params = array();

        $this->setUser();
        $this->setBaseUrl($identifier);
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

    /**
     * Fetch element and filter fields for record
     *
     * @return string
     */
    protected function getAllFields()
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
     *
     * @param array $params
     *
     * @return string
     * @throws InvalidCallException
     */
    public function fetchAll($params = array())
    {
        $this->validateFetchRequest();

        return parent::fetchAll($params);
    }

    private function validateFetchRequest()
    {
        if (! isset($this->params['limit']) && ! $this->inEndpoint('assignments')) {
            throw new InvalidCallException("Record collection limit must be set");
        }
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
    public function updateAll($values = array())
    {
        if ($this->inEndpoint('assignments')) {
            throw new InvalidCallException("record assignments cannot be updated through api");
        }

        list($url, $values) = $this->getFormattedBatchParams($values);

        return $this->gateway->update($url, $values);
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