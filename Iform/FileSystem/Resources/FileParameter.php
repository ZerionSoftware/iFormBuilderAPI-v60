<?php namespace Iform\FileSystem\Resources;

/**
 * Class FileParameter
 * NOTE:: Could be a trait --- When PHP is updated
 * @package Iform\FileSystem\Resources
 */
class FileParameter {

    /**
     * @param      $variables
     * @param null $headers
     *
     * @return array
     */
    public function getParameters($variables, $headers = null)
    {
        $params = array();
        foreach ($variables as $key => $variable) {
            $this->parseAlgorithm($key, $variable, $params, $headers);
        }

        return $params;
    }

    /**
     * @param      $key
     * @param      $variable
     * @param      $params
     * @param null $headers
     */
    private function parseAlgorithm($key, $variable, &$params, $headers = null)
    {
        if ((is_numeric($variable) && (integer) $variable === 0) || ! empty($variable)) {
            $trimmed = trim($variable);
            if (is_null($headers)) {
                $params[$key] = $trimmed;
            } else {
                $params[$headers[$key]] = $trimmed;
            }
        }
    }
}