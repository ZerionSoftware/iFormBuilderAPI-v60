<?php namespace Iform\Tests\Resources;

use Iform\Resolvers\RequestHandler;
use Iform\Resolvers\TokenResolver;

class TokenResolverStub extends TokenResolver {

    function __constructor()
    {
        return false;
    }

    function getToken()
    {
        return false;
    }
}

class RequestHandlerStub extends RequestHandler {

    public function read($url, $params = [], $header = null)
    {
        if (! is_null($header)) {
            //simulate a collection response
            return array('header' => '', 'body' => json_encode([1,2,3]));
        }

        if (preg_match('/\/localizations\//', $url)) {
            return json_encode(array(
                "language_code"=> "es",
                "label"=> "inspección de la construcción"
            ));
        }

        return json_encode(array('id' => 790783, "name" => "who_bsl2_quick_feedback"));
    }

    public function create($url, $params = [])
    {
        return json_encode(array('id' => 1232123));
    }

    public function update($url, $params = [])
    {
        return json_encode(array('id' => 1232123));
    }

    public function delete($id)
    {
        return json_encode(array());
    }
}