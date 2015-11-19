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
     * @param array $values
     *
     * @return array
     * @throws \Exception
     */
    private function formatBatch($values)
    {
        if (isset($values[0])) {
            if (! is_array($values[0])) throw new \InvalidArgumentException("invalid batch format");
        } else {
            $values = array($values); //new to wrap single call in array
        }

        return $values;
    }
}