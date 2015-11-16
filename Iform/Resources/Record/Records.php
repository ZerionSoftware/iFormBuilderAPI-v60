<?php namespace Iform\Resources\Record;

use Iform\Resources\Base\BaseResource;
use Iform\Exceptions\InvalidCallException;
use Iform\Resources\Contracts\BatchCommandMapper;
use Iform\Resources\Contracts\BatchQueryMapper;
use Iform\Resources\Base\FullCollection;
use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BatchValidator;

class Records extends BaseResource implements BatchQueryMapper, BatchCommandMapper {

    use BatchValidator;
    /**
     * Collection Object
     *
     * @var FullCollection
     */
    private $collection;
    private $getAll = false;
    private static $baseRecord = array(
        'parent_record_id',
        'parent_page_id',
        'parent_element_id',
        'created_device_id',
        'javascript_state'
    );

    function __construct(RequestHandler $gateway, $pageId, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($pageId);

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

    /**
     * @param $id
     *
     * @throws InvalidCallException
     * @override
     */
    public function delete($id)
    {
        throw new InvalidCallException("Cannot delete profiles through the api");
    }

    public function withAllFields()
    {
        $this->getAll = true;

        return $this->where(implode(",", static::$baseRecord));
    }

    public function fetchAll($params = [])
    {
        $this->params = $this->combine($params, $this->params);

        return empty($this->params) || $this->getAll
            ? $this->collection->fetchCollection($this->gateway, $this->collectionUrl(), $this->params)
            : $this->gateway->read($this->collectionUrl(), $this->params);
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

    public function assignments($recordId)
    {
        $this->activeUrl = $this->getSingleUrl($recordId). '/assignments';

        return $this;
    }
}