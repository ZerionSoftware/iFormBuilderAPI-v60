<?php namespace Iform\FileSystem\Resources;

trait parameterParser
{
    public function getParameters($variables, $headers = null)
    {
        $params = array();
        foreach ($variables as $key => $variable) {
            $this->parseAlgorithm($key, $variable, $params, $headers);
        }

        return $params;
    }

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