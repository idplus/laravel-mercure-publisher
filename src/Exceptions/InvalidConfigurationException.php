<?php
namespace Idplus\Mercure\Exceptions;
use Exception;

class InvalidConfigurationException extends Exception
{
    public static function hubUrlNotSpecified()
    {
        return new static('The Mercure Hub url is mandatory.');
    }
    public static function ambiguousJwtClass()
    {
        return new static('"jwt" and "jwt_provider" cannot be used together.');
    }
    public static function jwtProviderNotSpecified()
    {
        return new static('At least a "jwt" or a custom "jwd_provider" class must be specified.');
    }
}