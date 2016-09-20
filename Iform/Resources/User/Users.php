<?php namespace Iform\Resources\User;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Contracts\BatchCommandMapper;
use Iform\Resources\Base\BatchValidator;
use Iform\Resources\Base\FullCollection;

class Users extends BaseResource implements BatchCommandMapper {

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
        "email", "created_date", "is_locked", "roles"
    );
    /**
     * Page assignment fields
     * @var array
     */
    private static $basePage = array('can_collect', 'can_view');
    /**
     * Base Record
     *
     * @var array
     */
    private static $baseRecord = array("id","page_id","record_id");

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

    public function getAllFields()
    {
        $this->getAll = true;
        $labels = "";
        if ($this->activeUrl !== "") {
            if (strpos($this->activeUrl, 'page_assignment')) {
                $labels = static::$basePage;
            } elseif(strpos($this->activeUrl, 'record_assignments')){
                $labels = static::$baseRecord;
            }
        } else {
            $labels = static::$baseLabel;
        }

        return $labels;
    }

    /**
     * Update collection
     *
     * @param $values
     *
     * @return mixed
     */
    public function updateAll($values = array())
    {
        $values = BatchValidator::formatBatch($values);

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    /**
     * Delete collection
     *
     * @param $values
     *
     * @return mixed
     */
    public function deleteAll($values = array())
    {
        $values = BatchValidator::formatBatch($values);

        return $this->gateway->delete($this->collectionUrl(), $values);
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