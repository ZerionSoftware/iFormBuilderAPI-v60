<?php namespace Iform\Tests\Mocks;

use Iform\Resolvers\TokenResolver;
use Iform\Resolvers\RequestHandler;

class TokenResolverStub extends TokenResolver {

    function __constructor()
    {
        return false;
    }

    function getToken(RequestHandler $iForm)
    {
        return false;
    }
}
