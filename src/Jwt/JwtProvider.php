<?php
namespace Idplus\Mercure\Jwt;

interface JwtProvider
{
    public function __invoke(): string;
}