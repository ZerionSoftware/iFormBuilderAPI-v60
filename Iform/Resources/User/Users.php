<?php namespace Iform\Resources\User;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Contracts\BatchCommandMapper;
use Iform\Resources\Contracts\BatchQueryMapper;
use Iform\Resources\Base\BatchValidator;
use Iform\Resources\Base\FullCollection;

class Users extends BaseResource implements BatchQueryMapper, BatchCommandMapper {

    use BatchValidator;
    /**
     * Collection Object
     *
     * @var FullCollection
     */
    private $collection;
    /**
     * User wants full collection
     * @var bool
     */
    private $getAll = false;
    /**
     * All available fields
     * @var array
     */
    private static $baseLabel = array(
        "id", "username", "global_id", "first_name", "last_name",
        "email", "created_date", "is_locked"
    );
    /**
     * Page assignment fields
     * @var array
     */
    private static $basePage = array('can_collect', 'can_view');

    function __construct(RequestHandler $gateway, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($this->urlComponents['profiles']);

        $this->collection = $collection ?: new FullCollection();
    }

    public function pageAssignment($userId)
    {
        $this->activeUrl = $this->getSingleUrl($userId) . '/page_assignments';

        return $this;
    }

    public function recordAssignment($userId)
    {
        $this->activeUrl = $this->getSingleUrl($userId) . '/record_assignments';

        return $this;
    }

    /**
     * Helper to set all fields for list call
     *
     * @return mixed
     */
    public function withAllFields()
    {
        $this->getAll = true;
        $labels = "";
        if ($this->activeUrl !== "") {
            if (strpos($this->activeUrl, 'page_assignment')) {
                $labels = static::$basePage;
            } elseif(strpos($this->activeUrl, 'record_assignments')){
//                $labels = static::$baseRecord;
            }
        } else {
            $labels = static::$baseLabel;
        }

        return $this->where(implode(",", $labels));
    }

    /**
     * Update collection
     *
     * @param $values
     *
     * @return mixed
     */
    public function updateAll($values)
    {
        $values = $this->formatBatch($values);

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    /**
     * Fetch list
     *
     * @param array $params
     *
     * @return string
     */
    public function fetchAll($params = [])
    {
        $this->params = $this->combine($params, $this->params);

        return empty($this->params) || $this->getAll
            ? $this->collection->fetchCollection($this->gateway, $this->collectionUrl(), $this->params)
            : $this->gateway->read($this->collectionUrl(), $this->params);
    }

    /**
     * Delete collection
     *
     * @param $values
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
//      NOTE::need make abstract or mixin to remove duplication
        if (isset($values[0])) {
            if (! is_array($values[0])) throw new \InvalidArgumentException("invalid batch format");
        } else {
            $values = array($values); //new to wrap single call in array
        }

        return $values;
    }

    /**
     * Set base url
     *
     * @param $profile
     *
     * @return mixed
     */
    protected function setBaseUrl($profile)
    {
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/users";
        $this->baseUrl = sprintf($baseUrl, $profile);
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