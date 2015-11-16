<?php namespace Iform\Resources\Page;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseResource;
use Iform\Resources\Base\FullCollection;
use Iform\Exceptions\InvalidCallException;
use Iform\Resources\Base\BatchValidator;
use Iform\Resources\Contracts\BatchCommandMapper;
use Iform\Resources\Contracts\BatchQueryMapper;

class Pages extends BaseResource implements BatchQueryMapper, BatchCommandMapper {

    use BatchValidator;
    /**
     * Collection Object
     *
     * @var FullCollection
     */
    private $collection;
    private $getAll = false;
    private static $baseElements = array("id", "name", "permissions", "global_id", "label", "description", "version", "created_date",
        "created_by", "modified_date", "modified_by", "is_disabled", "reference_id_1", "reference_id_2", "reference_id_3",
        "reference_id_4", "reference_id_5", "icon", "sort_order", "page_javascript", "label_icons", "localizations");

    function __construct(RequestHandler $gateway, FullCollection $collection = null)
    {
        $this->setUser();
        $this->setGateway($gateway);
        $this->setBaseUrl($this->urlComponents['profiles']);

        $this->collection = $collection ?: new FullCollection();
    }

    protected function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Setup Page HTTP Callback
     *
     * @param $pageId
     *
     * @return $this
     */
    public function http($pageId)
    {
        $this->activeUrl = $this->getSingleUrl($pageId) . '/http_callbacks';

        return $this;
    }

    /**
     * Setup Page Email Alert
     *
     * @param $pageId
     *
     * @return $this
     */
    public function alerts($pageId)
    {
        $this->activeUrl = $this->getSingleUrl($pageId) . '/email_alerts';

        return $this;
    }

    /**
     * Setup Page Localizations
     *
     * @param $pageId
     *
     * @return $this
     * @throws InvalidCallException
     */
    public function localizations($pageId)
    {
        $this->activeUrl = $this->getSingleUrl($pageId) . '/localizations';

        return $this;
    }

    /**
     * Set assignment builder
     *
     * @param $pageId
     *
     * @return $this
     * @throws InvalidCallException
     */
    public function assignments($pageId)
    {
        $this->activeUrl = $this->getSingleUrl($pageId) . '/assignments';

        return $this;
    }

    /**
     * Query api for all resource fields
     *
     * @return $this
     */
    public function withAllFields()
    {
        $this->getAll = true;

        return $this->where(implode(",", static::$baseElements));
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

        return empty($this->params) || $this->getAll
            ? $this->collection->fetchCollection($this->gateway, $this->collectionUrl(), $this->params)
            : $this->gateway->read($this->collectionUrl(), $this->params);
    }

    public function deleteAll($values = [])
    {
        if (! $resource = $this->isCollectionResource()) {
            throw new InvalidCallException("Can only delete certain types of page resource collections. Please review <a href='http://docs.iformbuilder.apiary.io/#reference/page-resource/page-collection/retrieve-a-list-of-pages'>Page Collection Reference</a>");
        }

        return $this->gateway->delete($this->collectionUrl(), $values);
    }

    public function updateAll($values = [])
    {
        $resource = $this->isCollectionResource();
        if (! $resource || $resource === 'http_callbacks' ) {
            throw new InvalidCallException("Cannot update this type of collections. Please review <a href='http://docs.iformbuilder.apiary.io/#reference/page-resource/page-collection/retrieve-a-list-of-pages'>Page Collection Reference</a>");
        }

        return $this->gateway->update($this->collectionUrl(), $values);
    }

    /**
     * Limit batch methods for pages resources
     *
     * @return bool|mixed
     */
    private function isCollectionResource()
    {
        $valid = array('http_callbacks', 'localizations', 'email_alerts', 'assignments');
        $isValid = array_filter($valid, function ($resource) {
            return strpos($this->activeUrl, $resource) !== false;
        });

        return count($isValid) ? array_shift(array_values($isValid)) : false;
    }

    /**
     * Override base single url method
     *
     * @param $id
     *
     * @return string
     * @override
     * @throws InvalidCallException
     */
    protected function getSingleUrl($id = null)
    {
        if ($this->activeUrl === "") {
            $url = $this->baseUrl;
        } else {
            if (strpos($this->activeUrl, 'email_alerts') !== false) {
                throw new InvalidCallException("Email alerts are a collection only resource");
            }
            $url = $this->activeUrl;
        }

        return is_null($id) ? $url : $url . '/' . $id;
    }

    protected function setBaseUrl($profile)
    {
        $baseUrl = $this->urlComponents['server'] . $this->urlComponents['6.0'] . "profiles/%d/pages";
        $this->baseUrl = sprintf($baseUrl, $profile);
    }

    function __destruct()
    {
        unset($this->gateway);  //kill connection
    }
}