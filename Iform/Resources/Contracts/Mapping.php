<?php namespace Iform\Resources\Contracts;

interface Mapping {
    /**
     * @param $limit
     *
     * @return mixed
     */
    public function first($limit);

    /**
     * @param $limit
     *
     * @return mixed
     */
    public function last($limit);

    /**
     * @param $grammar
     *
     * @return mixed
     */
    public function where($grammar);

    /**
     * Create a resource
     *
     * @param $values
     *
     * @return mixed
     */
    public function create($values);

    /**
     * Update a resource
     *
     * @param $id
     * @param $values
     *
     * @return mixed
     */
    public function update($id, $values);

    /**
     * Delete a resource
     *
     * @param $id
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * Fetch a resource
     *
     * @param $id
     *
     * @return mixed
     */
    public function fetch($id);
}