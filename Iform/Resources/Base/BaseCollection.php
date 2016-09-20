<?php namespace Iform\Resources\Base;

abstract class BaseCollection {

    /**
     * Find total in response header
     *
     * @param $check
     *
     * @return int
     */
    protected function getTotalDataCount($check)
    {
        preg_match("/(?<=total-count:).+/", strtolower($check['header']), $total);
        $total_count = intval($total[0]);

        return $total_count;
    }

    /**
     * @param      $data
     * @param bool $asso
     *
     * @return mixed
     */
    public function decode($data, $asso = false)
    {
        return json_decode($data, $asso);
    }

    /**
     * @param $data
     *
     * @return string
     */
    public function encode($data)
    {
        return json_encode($data, JSON_HEX_APOS);
    }

    /**
     * Find limit for next call
     *
     * @param     $total
     * @param     $increment
     * @param int $maxLimit
     *
     * @return int
     */
    public function figureLimit($total, $increment, $maxLimit = 1000)
    {
        $mod = $increment % $total;

        return $mod !== $increment ? $maxLimit - $mod : $increment;
    }

    /**
     * Better checking for values
     *
     * @param $value
     *
     * @return bool
     */
    protected function notEmpty($value)
    {
        return (ctype_digit($value) && $value === "0") || $value === 0 || ! empty($value);
    }
}