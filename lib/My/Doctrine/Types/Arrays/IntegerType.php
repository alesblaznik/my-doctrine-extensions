<?php

namespace My\Doctrine\Types\Arrays;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use My\Doctrine\Types\TypesException;

class IntegerType extends Type
{
    const TYPENAME = 'array_int';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        if ($platform->getName() !== 'postgresql') {
            throw TypesException::platformNotSupported($this->getName(), $platform);
        }

        return 'INT[]';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return 'NULL';
        }

        if (!is_array($value)) {
            $value = array($value);
        }

        $arrayConstructorParameters =
            json_encode($value, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE, 1);

        if (false === $arrayConstructorParameters) {
            throw TypesException::invalidValue($this->getName(), 'one dimensional array of integers');
        }

        return '{' . substr($arrayConstructorParameters, 1, -1) . '}';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        // Convert {123, 456, 789} to array of integers
        $value = explode(',', substr($value, 1, -1));
        array_walk($value, function(&$item, $key) {
            $item = (int) $item;
        });

        return $value;
    }

    public function getName()
    {
        return self::TYPENAME;
    }
}
