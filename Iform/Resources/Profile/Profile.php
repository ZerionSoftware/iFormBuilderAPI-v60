<?php namespace Iform\Resources\Profile;

use Iform\Resources\Base\BaseResource;
use Iform\Exceptions\InvalidCallException;
use Iform\Resources\Contracts\BatchQueryMapper;
use Iform\Resources\Base\FullCollection;
use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BatchValidator;

class Profile extends BaseResource implements BatchQueryMapper {

    use BatchValidator;
    /**
     * Collection Object
     *
     * @var FullCollection
     */
    private $collection;
    private $getAll = false;

    private static $baseElements = array(
        'id','name','global_id','version', 'address1', 'address2','city',
        'zip', 'state', 'country','phone','fax', 'email', 'max_user',
        'max_page','is_active','created_date', 'type',
        'support_hours','time_zone'
    );

    function __construct(RequestHandler $gateway, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl("");

        $this->collection = $collection ?: new FullCollection();
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
        $this->baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles";
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

        return $this->where(implode(",", static::$baseElements));
    }

    public function fetchAll($params = [])
    {
        $this->params = $this->combine($params, $this->params);

        return empty($this->params) || $this->getAll
            ? $this->collection->fetchCollection($this->gateway, $this->collectionUrl(), $this->params)
            : $this->gateway->read($this->collectionUrl(), $this->params);
    }
}