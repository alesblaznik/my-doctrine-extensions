<?php

namespace My\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class TypesException extends \Exception
{
    public static function platformNotSupported($typeName, AbstractPlatform $platform)
    {
        return new self("Platform '" . $platform->getName() . "' is not supported for type '$typeName'");
    }

    public static function conversionToDatabaseFailed($typeName, $expectedFormat)
    {
        return new self("Invalid type value for $typeName. Expected $expectedFormat");
    }
}
