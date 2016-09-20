<?php namespace Iform\Resources\Page;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Base\FullCollection;

class PageGroup extends BaseResource  {

    /**
     * User wants full collection
     *
     * @var bool
     */
    private $getAll = false;

    function __construct(RequestHandler $gateway, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($this->urlComponents['profiles']);

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
        $this->setBaseUrl($this->urlComponents['profiles']);
    }

    public function assignment($groupId)
    {
        $this->activeUrl = $this->getSingleUrl($groupId) . '/assignments';

        return $this;
    }

    /**
     * Helper to set all fields for list call
     *
     * @return mixed
     */
    public function getAllFields()
    {
        $this->getAll = true;

        return array(
            'id', 'pages', 'global_id', 'version', 'name', 'created_date'
        );
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
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/page_groups";
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
