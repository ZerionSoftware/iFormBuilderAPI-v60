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
     * Send total in response
     * @var boolean
     */
    private static $header = false;

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
    public function fetchCollection(RequestHandler $gateway, $url, $params = array(), &$totalCollection = array(), $offset = 0)
    {
        $request = $gateway->read($url, $params, true);
        $this->validate($request);

        $body = $this->decode($request['body'], true);
        $responseSize = count($body);
        $totalCollection = array_merge($totalCollection, $body);

        if (! static::$total) static::$total = $this->getTotalDataCount($request);
        if ($responseSize > 0) {  //stop execution if error or empty response
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
     * @param RequestHandler $gateway
     * @param                $url
     * @param array          $params
     *
     * @return string
     * @throws \Exception
     */
    public function fetchLastInCollection(RequestHandler $gateway, $url, $params = array())
    {
        $request = $gateway->read($url, $params, true);
        $this->validate($request);

        $body = $this->decode($request['body'], true);
        $responseSize = count($body);

        if (! static::$total) static::$total = $this->getTotalDataCount($request);
        if ($responseSize > 0) {  //stop execution if error or empty response
            if (static::$total > $responseSize) {
                $params['offset'] = static::$total - $params['offset'];
                $this->reset();

                return $gateway->read($url, $params);
            }
        }

        $this->reset();

        return $this->encode($body);
    }

    /**
     * @param RequestHandler $gateway
     * @param                $url
     * @param array          $params
     *
     * @return string
     * @throws \Exception
     */
    public function fetchIncrement(RequestHandler $gateway, $url, $params = array())
    {
        if (! isset($params['offset'])) {
            throw new \InvalidArgumentException("missing offset value");
        }

        $request = $gateway->read($url, $params, true);
        $this->validate($request);

        static::$total = $this->getTotalDataCount($request);
        $this->reset();

        return $request['body'];
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

    public function getCountFromHeader()
    {
        return static::$header;
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
        $error = is_array($request) ? $this->decode($request['body'], true) : $this->decode($request, true);

        if (isset($error['error_message'])) {
            throw new \Exception($error['error_message']);
        }

        return true;
    }

    /**
     * Reset collection variables
     */
    private function reset()
    {
        static::$header = static::$total;
        static::$limit = 100;
        static::$total = false;
    }
}
