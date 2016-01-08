<?php namespace Iform\Resources\User;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Contracts\BatchQueryMapper;
use Iform\Resources\Base\BatchValidator;
use Iform\Resources\Base\FullCollection;

class UserGroup extends BaseResource implements BatchQueryMapper {

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
        'id','users','global_id','version','name', 'created_date'
    );

    function __construct(RequestHandler $gateway, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($this->urlComponents['profiles']);

        $this->collection = $collection ?: new FullCollection();
    }

    /**
     * Helper to set all fields for list call
     *
     * @return mixed
     */
    public function withAllFields()
    {
        $this->getAll = true;

        return $this->where(implode(",", self::$baseLabel));
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

        if (empty($this->params) || ! isset($this->params['limit'])) {
            $results = $this->collection->fetchCollection($this->gateway, $this->collectionUrl(), $this->params);
        } else {
            $results = $this->gateway->read($this->collectionUrl(), $this->params);
        }

        return $results;
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
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/user_groups";
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