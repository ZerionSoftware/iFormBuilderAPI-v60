<?php namespace Iform\Resources\Contracts;

interface BatchCommandMapper {

    /**
     * Update collection
     * @param $values
     *
     * @return mixed
     */
    public function updateAll($values);

    /**
     * Delete collection
     * @param $values
     *
     * @return mixed
     */
    public function deleteAll($values);
}