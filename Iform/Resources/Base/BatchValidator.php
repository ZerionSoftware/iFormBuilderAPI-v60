<?php namespace Iform\Resources\Base;

trait BatchValidator {

    /**
     * Combine parameters for batch calls
     *
     * @param $passed
     * @param $current
     *
     * @return array
     */
    private function combine($passed, $current)
    {
        if (empty($current)) return $passed;

        return array_replace_recursive($current, $passed);
    }

    /**
     * Format batch commands
     * @param array $values
     *
     * @return array
     * @throws \Exception
     */
    private function format(array $values)
    {
        if (isset($values[0])) {
            if (! is_array($values)) throw new \Exception("invalid batch format");
        } else {
            $values = array($values); //new to wrap single call in array
        }

        return $values;
    }
}