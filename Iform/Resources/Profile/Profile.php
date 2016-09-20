<?php namespace Iform\Resources\Profile;

use Iform\Resources\Base\BaseParameter;
use Iform\Resources\Base\BaseResource;
use Iform\Exceptions\InvalidCallException;
use Iform\Resources\Base\FullCollection;
use Iform\Resolvers\RequestHandler;

class Profile extends BaseResource {

    private $getAll = false;

    function __construct(RequestHandler $gateway, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl("");

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
        $this->setBaseUrl("");
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

    protected function getAllFields()
    {
        $this->getAll = true;

        return BaseParameter::profile();
    }

    function __destruct()
    {
        unset($this->gateway);  //kill connection
    }
}