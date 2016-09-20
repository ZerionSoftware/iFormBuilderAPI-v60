<?php

require_once 'Auth/Auth.php';
require_once 'Auth/RequestHandler.php';
require_once 'Auth/Fetch.php';
require_once 'Auth/Build.php';

use iForm\Auth\iFormAuth;

class iForm extends iFormAuth implements Fetch, Build {

    /**
     * Make calls against
     *
     * @var object
     */
    protected $api;
    /**
     * Token Resolver
     *
     * @var object
     */
    protected $auth;
    /**
     * token for child classes
     *
     * @var JWT token
     */
    protected $token;
    /**
     * Base parameters for child classes
     *
     * @var array
     */
    protected $base_params = array();
    /**
     * URL components to build from
     *
     * @var array
     */
    protected $url_components = array(
        'server'   => "",
        'profiles' => "",
        'api'      => "exzact/api/",
        'oauth'    => "exzact/api/oauth/token",
        '6.0'      => 'exzact/api/v60/',
        'token'    => '/token',
    );

    /**
     * Pass a restful handler
     *
     * @param Rest $handler
     */
    function __construct(Rest $handler)
    {
        $this->api = $handler;
    }

    /**
     * Authenticate with JWT
     */
    public function authenticate()
    {
        $token = $this->doAuth();
        $this->base_params = array(
            "ACCESS_TOKEN" => $token,
        );
    }

    /**
     * Set profile
     *
     * @param $profile
     */
    public function setProfile($profile)
    {
        $this->url_components["profiles"] = $profile;
    }

    public function setServer($server)
    {
        $this->url_components["server"] = $server;
    }

    public function getPage($page_id)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN']);

        return $this->api->read($url);
    }

    public function getPageCollection($params = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages";
        $url = sprintf($base_url, $this->url_components['profiles']);
        $call_params = is_null($params) ? array('limit'  => '100',
                                                'offset' => 0) : $params;

        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $call_params);

        return $this->api->read($url, true);
    }

    public function getElementList($page_id, $params = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/elements";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id);
        $call_params = is_null($params) ? array('limit'  => '100',
                                                'offset' => 0) : $params;

        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $call_params);
        return $this->api->read($url, true);
    }

    public function getElement($page_id, $eid)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/elements/%d";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id, $eid);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN']);

        return $this->api->read($url);
    }

    public function updateElement($page_id, $eid, $params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/elements/%d";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id, $eid);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->update($url);
    }

    public function getElementByField($page_id, $field)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/elements";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $field);

        return $this->api->read($url);
    }

    public function getPageWithChildren($page_id)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/elements";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], array('fields' => 'data_type(="18"),data_size,widget_type',
                                                                           'limit'  => '100',
                                                                           'offset' => 0));

        return $this->api->read($url);
    }

    public function getPageByField($field)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages";
        $url = sprintf($base_url, $this->url_components['profiles']);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $field );

        return $this->api->read($url);
    }

    public function getOptionListByField($field)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages";
        $url = sprintf($base_url, $this->url_components['profiles']);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $field );

        return $this->api->read($url);
    }

    public function getRecordCollection($page_id, $params = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/records";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id);
        $call_params = is_null($params) ? array('limit' => '1000', 'offset' => '0') : $params;
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $call_params);

        //pass true to include header information in response
        return $this->api->read($url, true);
    }

    /**
     * @param $page_id
     * @param $record_id
     *
     * @return mixed
     */
    public function getRecord($page_id, $record_id)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/records/%d";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id, $record_id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN']);

        return $this->api->read($url);
    }

    /**
     * @param $params
     *
     * @return mixed
     */
    public function createPage($params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages";
        $url = sprintf($base_url, $this->url_components['profiles']);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }

    public function createElement($page_id, $params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/elements";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }

    public function createRecord($page_id, $params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/records";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }

    public function assignPage($page_id, $params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/assignments";
        $url = sprintf($base_url, $this->url_components['profiles'], $page_id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }

    public function createOptionList($params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists";
        $url = sprintf($base_url, $this->url_components['profiles']);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }

    public function createOption($optId, $options)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists/%d/options";
        $url = sprintf($base_url, $this->url_components['profiles'], $optId);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $options);

        return $this->api->create($url);
    }

    public function createOptionForList($name, $options = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists/%d/options";
        $url = sprintf($base_url, $this->url_components['profiles']);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $options);

        return $this->api->create($url);
    }

    public function getOptionList($id)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists/%d";
        $url = sprintf($base_url, $this->url_components['profiles'], $id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN']);

        return $this->api->read($url);
    }

    public function getOptionsForList($id, $params = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists/%d/options";
        $url = sprintf($base_url, $this->url_components['profiles'], $id);
        $call_params = is_null($params) ? array('limit'  => '1000', 'offset' => 0) : $params;
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $call_params);

        return $this->api->read($url, true);
    }

    public function getOptionListCollection()
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists";
        $url = sprintf($base_url, $this->url_components['profiles']);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], array('limit'  => '1000',
                                                                           'offset' => 0));

        return $this->api->read($url);
    }

    public function deleteOptionList($id)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists/%d";
        $url = sprintf($base_url, $this->url_components['profiles'], $id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN']);

        return $this->api->delete($url);
    }

    public function deletePage($id)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d";
        $url = sprintf($base_url, $this->url_components['profiles'], $id);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN']);

        return $this->api->delete($url);
    }

    public function deleteRecords($id, $params = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/records";
        $url = sprintf($base_url, $this->url_components['profiles'], $id);
        $call_params = is_null($params) ? array('limit' => '1000', 'offset' => '0') : $params;
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $call_params);

        return $this->api->delete($url);
    }

    public function getOption($listId, $optId)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists/%d/options/%d";
        $url = sprintf($base_url, $this->url_components['profiles'], $listId, $optId);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN']);

        return $this->api->read($url);
    }

    /***************
     * Localization
     **************/

    /**
     * @param      $pageId
     * @param      $elementId
     * @param null $params
     *
     * @return mixed
     */
    public function getElementLocalizationCollection($pageId, $elementId, $params = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/elements/%d/localizations";
        $url = sprintf($base_url, $this->url_components['profiles'], $pageId, $elementId);
        $call_params = is_null($params) ? array('limit' => '100', 'offset' => '0') : $params;
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $call_params);

        return $this->api->read($url);
    }

    public function createPageLocalization($pageId, $params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/localizations";
        $url = sprintf($base_url, $this->url_components['profiles'], $pageId);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }

    public function createElementLocalization($pageId, $elementId, $params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/elements/%d/localizations";
        $url = sprintf($base_url, $this->url_components['profiles'], $pageId, $elementId);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }

    public function createOptionLocalization($optListId, $optId, $params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/optionlists/%d/options/%d/localizations";
        $url = sprintf($base_url, $this->url_components['profiles'], $optListId, $optId);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }

    /***************
     * HTTP Callbacks
     **************/

    /**
     * @param      $pageId
     * @param null $params
     *
     * @return mixed
     */
    public function getHttpCallbackCollection($pageId, $params = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/http_callbacks";
        $url = sprintf($base_url, $this->url_components['profiles'], $pageId);
        $call_params = is_null($params) ? array('limit' => '100', 'offset' => '0') : $params;
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $call_params);

        return $this->api->read($url, true);
    }

    public function createHttpCallback($pageId, $params)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/http_callbacks";
        $url = sprintf($base_url, $this->url_components['profiles'], $pageId);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $params);

        return $this->api->create($url);
    }
    /***************
     * Email
     **************/
    /**
     * @param      $pageId
     * @param null $params
     *
     * @return mixed
     */
    public function getEmailAlertCollection($pageId, $params = null)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/email_alerts";
        $url = sprintf($base_url, $this->url_components['profiles'], $pageId);
        $call_params = is_null($params) ? array('limit' => '100', 'offset' => '0') : $params;
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $call_params);

        return $this->api->read($url, true);
    }

    /**
     * @param $pageId
     * @param $emails
     *
     * @return mixed
     */
    public function createEmailAlerts($pageId, $emails)
    {
        $base_url = $this->url_components['server'] . $this->url_components['6.0'] . "profiles/%d/pages/%d/email_alerts";
        $url = sprintf($base_url, $this->url_components['profiles'], $pageId);
        $this->api->setupRequest($this->base_params['ACCESS_TOKEN'], $emails);

        return $this->api->create($url);
    }
}
