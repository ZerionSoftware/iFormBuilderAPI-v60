<?php namespace Iform\Controllers;

use Iform\Resources\IformResource;
use Iform\Controllers\BaseResourceController;

class PagesController extends BaseResourceController {

    function __construct($body = null)
    {
        parent::__construct($body);
        $this->model = IformResource::pages();
    }

    public function get($id = null)
    {
        $this->response = $this->model->fetch($id);

        return $this;
    }

    public function all()
    {
        if (isset($this->request['fields']) && ! empty($this->request['fields'])) {
            $this->response = $this->model->where($this->request['fields'])
                                          ->fetchAll();
        } else {
            $this->response = $this->model->withAllFields()
                                          ->fetchAll();
        }

        return $this;
    }

    public function post()
    {
        $values = json_decode($this->request, true);
        $this->removeAngularVars($values);

        if (empty($values)) {
            $this->outputError("request is empty");
        }

        $this->response = $this->model->create($values);

        return $this;
    }

    public function put($id)
    {
        $update = json_decode($this->model->update($id, $this->request));

        if (isset($update->id)) {
            //send updated resource
            $this->response = $this->model->fetch($update->id);
        } else {
            $this->response = json_encode($update);
        }

        return $this;
    }

    public function delete($id)
    {
        $this->response = $this->model->delete($id);

        return $this;
    }
}