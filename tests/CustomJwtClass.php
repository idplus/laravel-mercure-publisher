<?php
namespace Idplus\Mercure\Tests;

use Idplus\Mercure\Jwt\JwtProvider;

class CustomJwtClass implements JwtProvider
{
    /**
     * Return custom JWT
     *
     * @return string
     */
    public function __invoke(): string
    {
        return 'the.custom.jwt';
    }
}