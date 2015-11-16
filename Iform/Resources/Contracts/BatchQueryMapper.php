<?php namespace Iform\Resources\Contracts;


interface BatchQueryMapper {
    /**
     * Fetch a list of resources
     *
     * @return mixed
     */
    public function fetchAll();

    /**
     * Helper to set all fields for list call
     *
     * @return mixed
     */
    public function withAllFields();
}