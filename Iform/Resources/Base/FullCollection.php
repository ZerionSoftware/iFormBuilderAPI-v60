<?php namespace Iform\Resources\Base;

use Iform\Resolvers\RequestHandler;
use Iform\Resources\Base\BaseCollection;

class FullCollection extends BaseCollection {

    /**
     * API response limit - Page Resource has 100 limit
     *
     * @var int
     */
    private static $limit = 100;
    /**
     * Collection total
     *
     * @var bool
     */
    private static $total = false;

    /**
     * @param RequestHandler $gateway
     * @param                $url
     * @param array          $params
     * @param array          $totalCollection
     * @param int            $offset
     *
     * @return string
     * @throws \Exception
     */
    public function fetchCollection(RequestHandler $gateway, $url, $params = [], &$totalCollection = [], $offset = 0)
    {
        $request = $gateway->read($url, $params, true);
        $this->validate($request);

        $body = $this->decode($request['body'], true);
        $responseSize = count($body);
        $totalCollection = array_merge($body, $totalCollection);

        if ($responseSize > 0) {  //stop execution if error or empty response
            if (! static::$total) static::$total = $this->getTotalDataCount($request);
            if (static::$total > $responseSize) {
                $offset += static::$limit;
                $params['offset'] = $offset;

                return $this->fetchCollection($gateway, $url, $params, $totalCollection, $offset);
            }
        }

        $this->reset();

        return $this->encode($totalCollection);
    }

    /**
     * Set limit - example: record limit can reach 1000
     *
     * @param $limit
     */
    public function setLimit($limit)
    {
        static::$limit = $limit;
    }

    /**
     * Successful collection request should return header/body
     * @param $request
     *
     * @return bool
     * @throws \Exception
     */
    private function validate($request)
    {
        if(! is_array($request)) {
            $error = $this->decode($request, true);
            if (isset($error['error_message'])) {
                throw new \Exception($error['error_message']);
            }
        }

        return true;
    }

    /**
     * Reset collection variables
     */
    private function reset()
    {
        static::$total = false;
    }
}