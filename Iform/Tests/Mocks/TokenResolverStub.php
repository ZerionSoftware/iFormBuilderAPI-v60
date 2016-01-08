<?php namespace Iform\Tests\Mocks;

Use Iform\Resolvers\TokenResolver;

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
